<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        if (session('user_role') === 'mahasiswa') {
            $mahasiswa = Mahasiswa::where('nim', session('user_nim'))->with(['nilais.mataKuliah', 'absensis'])->first();
            if (!$mahasiswa) {
                session()->flush();
                return redirect()->route('login')->with('error', 'Sesi tidak valid.');
            }

            $ipk = $mahasiswa->nilais->avg('bobot') ?? 0;
            $totalSks = $mahasiswa->nilais->sum(fn($n) => $n->mataKuliah->sks ?? 0);
            
            // Attendance stats
            $totalAbsen = $mahasiswa->absensis->count();
            $hadirAbsen = $mahasiswa->absensis->where('status', 'Hadir')->count();
            $persenKehadiran = $totalAbsen > 0 ? round(($hadirAbsen / $totalAbsen) * 100) : 100;
            
            // Recent grades
            $recentNilais = $mahasiswa->nilais()->with('mataKuliah')->latest()->take(5)->get();

            return view('dashboard.mahasiswa', compact('mahasiswa', 'ipk', 'totalSks', 'persenKehadiran', 'recentNilais'));
        }

        $stats = [
            'total_mahasiswa'  => Mahasiswa::count(),
            'total_mk'         => MataKuliah::count(),
            'total_nilai'      => Nilai::count(),
            'mahasiswa_aktif'  => Mahasiswa::where('status', 'Aktif')->count(),
            'mahasiswa_lulus'  => Mahasiswa::where('status', 'Lulus')->count(),
            'mahasiswa_cuti'   => Mahasiswa::where('status', 'Cuti')->count(),
            'mk_aktif'         => MataKuliah::where('status', 'Aktif')->count(),
            'rata_nilai'       => Nilai::avg('nilai_angka') ?? 0,
        ];

        // Data jurusan untuk chart
        $perJurusan = Mahasiswa::selectRaw('jurusan, COUNT(*) as total')
            ->groupBy('jurusan')
            ->orderByDesc('total')
            ->get();

        // Data angkatan
        $perAngkatan = Mahasiswa::selectRaw('angkatan, COUNT(*) as total')
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->get();

        // Mahasiswa terbaru
        $mahasiswaTerbaru = Mahasiswa::latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'perJurusan', 'perAngkatan', 'mahasiswaTerbaru'));
    }
}
