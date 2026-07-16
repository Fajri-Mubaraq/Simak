<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('kode_mk', 'like', "%$s%")
                ->orWhere('nama_mk', 'like', "%$s%")
                ->orWhere('dosen', 'like', "%$s%"));
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $mataKuliahs = $query->orderBy('kode_mk')->paginate(10)->withQueryString();
        $jurusans    = MataKuliah::distinct()->pluck('jurusan')->sort()->values();

        return view('matakuliah.index', compact('mataKuliahs', 'jurusans'));
    }

    public function create()
    {
        return view('matakuliah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk'   => 'required|string|max:20|unique:mata_kuliahs',
            'nama_mk'   => 'required|string|max:150',
            'sks'       => 'required|integer|min:1|max:6',
            'semester'  => 'required|integer|min:1|max:8',
            'jurusan'   => 'required|string|max:100',
            'dosen'     => 'nullable|string|max:100',
            'status'    => 'required|in:Aktif,Nonaktif',
            'deskripsi' => 'nullable|string',
        ], [
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
        ]);

        MataKuliah::create($validated);

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $matakuliah)
    {
        $matakuliah->load('nilais.mahasiswa');
        $rataRata = $matakuliah->nilais->avg('nilai_angka') ?? 0;
        return view('matakuliah.show', compact('matakuliah', 'rataRata'));
    }

    public function edit(MataKuliah $matakuliah)
    {
        return view('matakuliah.edit', compact('matakuliah'));
    }

    public function update(Request $request, MataKuliah $matakuliah)
    {
        $validated = $request->validate([
            'kode_mk'   => 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $matakuliah->id,
            'nama_mk'   => 'required|string|max:150',
            'sks'       => 'required|integer|min:1|max:6',
            'semester'  => 'required|integer|min:1|max:8',
            'jurusan'   => 'required|string|max:100',
            'dosen'     => 'nullable|string|max:100',
            'status'    => 'required|in:Aktif,Nonaktif',
            'deskripsi' => 'nullable|string',
        ]);

        $matakuliah->update($validated);

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function getNextKodeMk(Request $request)
    {
        $jurusan = $request->query('jurusan');
        
        $prefixMap = [
            'Teknik Informatika' => 'IF',
            'Sistem Informasi' => 'SI',
            'Manajemen Informatika' => 'MI',
        ];
        
        $prefix = $prefixMap[$jurusan] ?? '';
        if (!$prefix && $jurusan) {
            // Fallback: take first letters of each word
            $words = explode(' ', $jurusan);
            foreach ($words as $w) {
                if ($w) {
                    $prefix .= strtoupper(substr($w, 0, 1));
                }
            }
        }
        
        if (!$prefix) {
            return response()->json(['next_kode' => '']);
        }
        
        // Find the highest existing code with this prefix
        $lastMk = MataKuliah::where('kode_mk', 'like', $prefix . '%')
            ->orderBy('kode_mk', 'desc')
            ->first();
            
        if ($lastMk) {
            $numPart = substr($lastMk->kode_mk, strlen($prefix));
            if (is_numeric($numPart)) {
                $nextNum = (int)$numPart + 1;
                $nextNumStr = str_pad($nextNum, strlen($numPart), '0', STR_PAD_LEFT);
                return response()->json(['next_kode' => $prefix . $nextNumStr]);
            }
        }
        
        // Default starting code
        return response()->json(['next_kode' => $prefix . '101']);
    }

    public function destroy(MataKuliah $matakuliah)
    {
        $matakuliah->delete();
        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus.');
    }
}
