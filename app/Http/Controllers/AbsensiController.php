<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of absensi.
     */
    public function index(Request $request)
    {
        $query = Absensi::with(['mahasiswa', 'mataKuliah']);

        if (session('user_role') === 'mahasiswa') {
            $query->whereHas('mahasiswa', fn($q) => $q->where('nim', session('user_nim')));
        } elseif (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            $query->whereHas('mahasiswa', fn($q) => $q->where('jurusan', $jurusan));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('mahasiswa', fn($q) => $q->where('nim', 'like', "%$s%")->orWhere('nama', 'like', "%$s%"));
        }

        if ($request->filled('mata_kuliah_id')) {
            $query->where('mata_kuliah_id', $request->mata_kuliah_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pertemuan_ke')) {
            $query->where('pertemuan_ke', $request->pertemuan_ke);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $absensis   = $query->latest('tanggal')->latest('pertemuan_ke')->paginate(10)->withQueryString();
        
        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            $mataKuliahs = MataKuliah::where('status', 'Aktif')->where('jurusan', $jurusan)->orderBy('nama_mk')->get();
        } else {
            $mataKuliahs = MataKuliah::where('status', 'Aktif')->orderBy('nama_mk')->get();
        }

        // Statistik
        $statsQuery = Absensi::query();
        if (session('user_role') === 'mahasiswa') {
            $statsQuery->whereHas('mahasiswa', fn($q) => $q->where('nim', session('user_nim')));
        } elseif (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            $statsQuery->whereHas('mahasiswa', fn($q) => $q->where('jurusan', $jurusan));
        }

        if ($request->filled('mata_kuliah_id')) {
            $statsQuery->where('mata_kuliah_id', $request->mata_kuliah_id);
        }
        if ($request->filled('pertemuan_ke')) {
            $statsQuery->where('pertemuan_ke', $request->pertemuan_ke);
        }
        $stats = [
            'hadir' => (clone $statsQuery)->where('status', 'Hadir')->count(),
            'izin'  => (clone $statsQuery)->where('status', 'Izin')->count(),
            'sakit' => (clone $statsQuery)->where('status', 'Sakit')->count(),
            'alpha' => (clone $statsQuery)->where('status', 'Alpha')->count(),
            'total' => (clone $statsQuery)->count(),
        ];

        $canManage = in_array(session('user_role'), ['admin', 'dosen']);

        return view('absensi.index', compact('absensis', 'mataKuliahs', 'stats', 'canManage'));
    }

    /**
     * Show the form for creating a new absensi.
     */
    public function create()
    {
        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            $mahasiswas  = Mahasiswa::where('status', 'Aktif')->where('jurusan', $jurusan)->orderBy('nama')->get();
            $mataKuliahs = MataKuliah::where('status', 'Aktif')->where('jurusan', $jurusan)->orderBy('kode_mk')->get();
        } else {
            $mahasiswas  = Mahasiswa::where('status', 'Aktif')->orderBy('nama')->get();
            $mataKuliahs = MataKuliah::where('status', 'Aktif')->orderBy('kode_mk')->get();
        }
        return view('absensi.create', compact('mahasiswas', 'mataKuliahs'));
    }

    /**
     * Store a newly created absensi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'tanggal'        => 'required|date',
            'pertemuan_ke'   => 'required|integer|min:1|max:16',
            'status'         => 'required|in:Hadir,Izin,Sakit,Alpha',
            'keterangan'     => 'nullable|string|max:500',
        ], [
            'mahasiswa_id.exists'   => 'Mahasiswa tidak ditemukan.',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak ditemukan.',
            'pertemuan_ke.max'      => 'Pertemuan maksimal ke-16.',
        ]);

        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            
            $mhs = Mahasiswa::find($validated['mahasiswa_id']);
            $mk = MataKuliah::find($validated['mata_kuliah_id']);

            if (!$mhs || $mhs->jurusan !== $jurusan) {
                return back()->withErrors(['mahasiswa_id' => 'Anda hanya dapat mengelola absensi mahasiswa jurusan ' . $jurusan])->withInput();
            }
            if (!$mk || $mk->jurusan !== $jurusan) {
                return back()->withErrors(['mata_kuliah_id' => 'Anda hanya dapat mengelola absensi mata kuliah jurusan ' . $jurusan])->withInput();
            }
        }

        // Cek duplikat
        $exists = Absensi::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'pertemuan_ke' => 'Data absensi untuk mahasiswa, mata kuliah, dan pertemuan ini sudah ada.'
            ])->withInput();
        }

        Absensi::create($validated);

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified absensi detail.
     */
    public function show(Absensi $absensi)
    {
        if (session('user_role') === 'mahasiswa') {
            $mhs = Mahasiswa::where('nim', session('user_nim'))->first();
            if (!$mhs || $absensi->mahasiswa_id !== $mhs->id) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke data absensi mahasiswa lain.');
            }
        }

        $absensi->load(['mahasiswa', 'mataKuliah']);

        // Get all absensi for this mahasiswa + mata kuliah combo
        $rekapAbsensi = Absensi::where('mahasiswa_id', $absensi->mahasiswa_id)
            ->where('mata_kuliah_id', $absensi->mata_kuliah_id)
            ->orderBy('pertemuan_ke')
            ->get();

        $rekapStats = [
            'hadir' => $rekapAbsensi->where('status', 'Hadir')->count(),
            'izin'  => $rekapAbsensi->where('status', 'Izin')->count(),
            'sakit' => $rekapAbsensi->where('status', 'Sakit')->count(),
            'alpha' => $rekapAbsensi->where('status', 'Alpha')->count(),
            'total' => $rekapAbsensi->count(),
        ];

        $canManage = in_array(session('user_role'), ['admin', 'dosen']);

        return view('absensi.show', compact('absensi', 'rekapAbsensi', 'rekapStats', 'canManage'));
    }

    /**
     * Show the form for editing the specified absensi.
     */
    public function edit(Absensi $absensi)
    {
        $absensi->load(['mahasiswa', 'mataKuliah']);

        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            if ($absensi->mahasiswa->jurusan !== $jurusan) {
                return redirect()->route('absensi.index')->with('error', 'Anda tidak memiliki akses untuk mengubah absensi mahasiswa jurusan lain.');
            }
            $mahasiswas  = Mahasiswa::where('jurusan', $jurusan)->orderBy('nama')->get();
            $mataKuliahs = MataKuliah::where('jurusan', $jurusan)->orderBy('kode_mk')->get();
        } else {
            $mahasiswas  = Mahasiswa::orderBy('nama')->get();
            $mataKuliahs = MataKuliah::orderBy('kode_mk')->get();
        }

        return view('absensi.edit', compact('absensi', 'mahasiswas', 'mataKuliahs'));
    }

    /**
     * Update the specified absensi.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'tanggal'        => 'required|date',
            'pertemuan_ke'   => 'required|integer|min:1|max:16',
            'status'         => 'required|in:Hadir,Izin,Sakit,Alpha',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            if ($absensi->mahasiswa->jurusan !== $jurusan) {
                return redirect()->route('absensi.index')->with('error', 'Anda tidak memiliki akses untuk mengubah absensi mahasiswa jurusan lain.');
            }
            
            $mhs = Mahasiswa::find($validated['mahasiswa_id']);
            $mk = MataKuliah::find($validated['mata_kuliah_id']);

            if (!$mhs || $mhs->jurusan !== $jurusan) {
                return back()->withErrors(['mahasiswa_id' => 'Anda hanya dapat mengelola absensi mahasiswa jurusan ' . $jurusan])->withInput();
            }
            if (!$mk || $mk->jurusan !== $jurusan) {
                return back()->withErrors(['mata_kuliah_id' => 'Anda hanya dapat mengelola absensi mata kuliah jurusan ' . $jurusan])->withInput();
            }
        }

        // Cek duplikat (exclude current)
        $exists = Absensi::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->where('id', '!=', $absensi->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'pertemuan_ke' => 'Data absensi untuk mahasiswa, mata kuliah, dan pertemuan ini sudah ada.'
            ])->withInput();
        }

        $absensi->update($validated);

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified absensi.
     */
    public function destroy(Absensi $absensi)
    {
        if (session('user_role') === 'dosen') {
            $user = \App\Models\User::where('nidn', session('user_nidn'))->first();
            $jurusan = $user ? $user->jurusan : null;
            if ($absensi->mahasiswa->jurusan !== $jurusan) {
                return redirect()->route('absensi.index')->with('error', 'Anda tidak memiliki akses untuk menghapus absensi mahasiswa jurusan lain.');
            }
        }

        $absensi->delete();
        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
}
