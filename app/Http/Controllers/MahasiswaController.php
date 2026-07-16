<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if (session('user_role') === 'mahasiswa') {
            $mhs = Mahasiswa::where('nim', session('user_nim'))->first();
            if ($mhs) {
                return redirect()->route('mahasiswa.show', $mhs->id);
            }
            return redirect()->route('dashboard')->with('error', 'Profil Anda tidak ditemukan.');
        }

        $query = Mahasiswa::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nim', 'like', "%$s%")
                ->orWhere('nama', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        $mahasiswas = $query->orderBy('nama')->paginate(10)->withQueryString();
        $jurusans   = Mahasiswa::distinct()->pluck('jurusan')->sort()->values();
        $angkatans  = Mahasiswa::distinct()->pluck('angkatan')->sortDesc()->values();

        return view('mahasiswa.index', compact('mahasiswas', 'jurusans', 'angkatans'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'           => 'required|string|max:20|unique:mahasiswas',
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan'       => 'required|string|max:100',
            'program_studi' => 'required|string|max:100',
            'angkatan'      => 'required|integer|min:2000|max:' . date('Y'),
            'email'         => 'nullable|email|max:100|unique:mahasiswas',
            'no_telp'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'status'        => 'required|in:Aktif,Cuti,Lulus,DO',
        ], [
            'nim.unique'   => 'NIM sudah terdaftar.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        // Default password to hash of 'mhs123'
        $validated['password'] = \Illuminate\Support\Facades\Hash::make('mhs123');

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        if (session('user_role') === 'mahasiswa') {
            $mhs = Mahasiswa::where('nim', session('user_nim'))->first();
            if (!$mhs || $mhs->id !== $mahasiswa->id) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke profil mahasiswa lain.');
            }
        }

        $mahasiswa->load(['nilais.mataKuliah']);
        $ipk = $mahasiswa->nilais->avg('bobot') ?? 0;
        $totalSks = $mahasiswa->nilais->sum(fn($n) => $n->mataKuliah->sks ?? 0);
        return view('mahasiswa.show', compact('mahasiswa', 'ipk', 'totalSks'));
    }

    public function profile()
    {
        $mhs = Mahasiswa::where('nim', session('user_nim'))->first();
        if ($mhs) {
            return $this->show($mhs);
        }
        return redirect()->route('dashboard')->with('error', 'Profil tidak ditemukan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim'           => 'required|string|max:20|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan'       => 'required|string|max:100',
            'program_studi' => 'required|string|max:100',
            'angkatan'      => 'required|integer|min:2000|max:' . date('Y'),
            'email'         => 'nullable|email|max:100|unique:mahasiswas,email,' . $mahasiswa->id,
            'no_telp'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'status'        => 'required|in:Aktif,Cuti,Lulus,DO',
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
