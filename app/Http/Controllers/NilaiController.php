<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::with(['mahasiswa', 'mataKuliah']);

        if (session('user_role') === 'mahasiswa') {
            $query->whereHas('mahasiswa', fn($q) => $q->where('nim', session('user_nim')));
        } elseif (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            $query->whereHas('mahasiswa', fn($q) => $q->where('jurusan', $jurusan));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('mahasiswa', fn($sub) => $sub->where('nim', 'like', "%$s%")->orWhere('nama', 'like', "%$s%"))
                  ->orWhereHas('mataKuliah', fn($sub) => $sub->where('kode_mk', 'like', "%$s%")->orWhere('nama_mk', 'like', "%$s%"));
            });
        }

        if ($request->filled('semester_ambil')) {
            $query->where('semester_ambil', $request->semester_ambil);
        }

        if ($request->filled('nilai_huruf')) {
            $query->where('nilai_huruf', $request->nilai_huruf);
        }

        $nilais   = $query->latest()->paginate(10)->withQueryString();
        $semesters = Nilai::distinct()->pluck('semester_ambil')->sort()->values();

        return view('nilai.index', compact('nilais', 'semesters'));
    }

    public function create()
    {
        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            $mahasiswas = Mahasiswa::where('jurusan', $jurusan)->orderBy('nama')->get();
            $mataKuliahs = MataKuliah::where('status', 'Aktif')->where('jurusan', $jurusan)->orderBy('kode_mk')->get();
        } else {
            $mahasiswas  = Mahasiswa::orderBy('nama')->get();
            $mataKuliahs = MataKuliah::where('status', 'Aktif')->orderBy('kode_mk')->get();
        }
        return view('nilai.create', compact('mahasiswas', 'mataKuliahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id'    => 'required|exists:mahasiswas,id',
            'mata_kuliah_id'  => 'required|exists:mata_kuliahs,id',
            'semester_ambil'  => 'required|string|max:20',
            'nilai_angka'     => 'required|numeric|min:0|max:100',
            'status'          => 'required|in:Lulus,Tidak Lulus,Mengulang',
        ], [
            'mahasiswa_id.exists'   => 'Mahasiswa tidak ditemukan.',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak ditemukan.',
        ]);

        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            
            $mhs = Mahasiswa::find($validated['mahasiswa_id']);
            $mk = MataKuliah::find($validated['mata_kuliah_id']);
            
            if (!$mhs || $mhs->jurusan !== $jurusan) {
                return back()->withErrors(['mahasiswa_id' => 'Anda hanya dapat memberikan nilai untuk mahasiswa jurusan ' . $jurusan])->withInput();
            }
            if (!$mk || $mk->jurusan !== $jurusan) {
                return back()->withErrors(['mata_kuliah_id' => 'Anda hanya dapat memberikan nilai untuk mata kuliah jurusan ' . $jurusan])->withInput();
            }
        }

        // Hitung nilai huruf & bobot otomatis
        $grade = Nilai::hitungNilaiHuruf((float) $validated['nilai_angka']);
        $validated['nilai_huruf'] = $grade['huruf'];
        $validated['bobot']       = $grade['bobot'];

        // Cek duplikat
        $exists = Nilai::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->where('semester_ambil', $validated['semester_ambil'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['mata_kuliah_id' => 'Nilai untuk mahasiswa, mata kuliah, dan semester ini sudah ada.'])->withInput();
        }

        Nilai::create($validated);

        return redirect()->route('nilai.index')
            ->with('success', 'Data nilai berhasil ditambahkan.');
    }

    public function show(Nilai $nilai)
    {
        if (session('user_role') === 'mahasiswa') {
            $mhs = Mahasiswa::where('nim', session('user_nim'))->first();
            if (!$mhs || $nilai->mahasiswa_id !== $mhs->id) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke nilai mahasiswa lain.');
            }
        }

        $nilai->load(['mahasiswa', 'mataKuliah']);
        return view('nilai.show', compact('nilai'));
    }

    public function edit(Nilai $nilai)
    {
        $nilai->load(['mahasiswa', 'mataKuliah']);

        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            if ($nilai->mahasiswa->jurusan !== $jurusan) {
                return redirect()->route('nilai.index')->with('error', 'Anda tidak memiliki akses untuk mengubah nilai mahasiswa jurusan lain.');
            }
            
            $mahasiswas  = Mahasiswa::where('jurusan', $jurusan)->orderBy('nama')->get();
            $mataKuliahs = MataKuliah::where('jurusan', $jurusan)->orderBy('kode_mk')->get();
        } else {
            $mahasiswas  = Mahasiswa::orderBy('nama')->get();
            $mataKuliahs = MataKuliah::orderBy('kode_mk')->get();
        }

        return view('nilai.edit', compact('nilai', 'mahasiswas', 'mataKuliahs'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'semester_ambil' => 'required|string|max:20',
            'nilai_angka'    => 'required|numeric|min:0|max:100',
            'status'         => 'required|in:Lulus,Tidak Lulus,Mengulang',
        ]);

        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            if ($nilai->mahasiswa->jurusan !== $jurusan) {
                return redirect()->route('nilai.index')->with('error', 'Anda tidak memiliki akses untuk mengubah nilai mahasiswa jurusan lain.');
            }
            
            $mhs = Mahasiswa::find($validated['mahasiswa_id']);
            $mk = MataKuliah::find($validated['mata_kuliah_id']);
            
            if (!$mhs || $mhs->jurusan !== $jurusan) {
                return back()->withErrors(['mahasiswa_id' => 'Anda hanya dapat memberikan nilai untuk mahasiswa jurusan ' . $jurusan])->withInput();
            }
            if (!$mk || $mk->jurusan !== $jurusan) {
                return back()->withErrors(['mata_kuliah_id' => 'Anda hanya dapat memberikan nilai untuk mata kuliah jurusan ' . $jurusan])->withInput();
            }
        }

        $grade = Nilai::hitungNilaiHuruf((float) $validated['nilai_angka']);
        $validated['nilai_huruf'] = $grade['huruf'];
        $validated['bobot']       = $grade['bobot'];

        $nilai->update($validated);

        return redirect()->route('nilai.index')
            ->with('success', 'Data nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai)
    {
        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            if ($nilai->mahasiswa->jurusan !== $jurusan) {
                return redirect()->route('nilai.index')->with('error', 'Anda tidak memiliki akses untuk menghapus nilai mahasiswa jurusan lain.');
            }
        }

        $nilai->delete();
        return redirect()->route('nilai.index')
            ->with('success', 'Data nilai berhasil dihapus.');
    }
}
