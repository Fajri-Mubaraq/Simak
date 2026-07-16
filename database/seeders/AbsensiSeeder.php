<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas  = Mahasiswa::where('status', 'Aktif')->take(8)->get();
        $mataKuliahs = MataKuliah::where('status', 'Aktif')->take(3)->get();

        if ($mahasiswas->isEmpty() || $mataKuliahs->isEmpty()) {
            return;
        }

        $statusOptions = ['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit', 'Alpha'];

        foreach ($mataKuliahs as $mk) {
            $baseDate = Carbon::create(2026, 2, 10); // Awal semester

            for ($pertemuan = 1; $pertemuan <= 8; $pertemuan++) {
                $tanggal = $baseDate->copy()->addWeeks($pertemuan - 1);

                foreach ($mahasiswas as $mhs) {
                    $status = $statusOptions[array_rand($statusOptions)];
                    $keterangan = null;

                    if ($status === 'Izin') {
                        $keterangan = 'Izin keperluan keluarga';
                    } elseif ($status === 'Sakit') {
                        $keterangan = 'Sakit, ada surat dokter';
                    }

                    Absensi::create([
                        'mahasiswa_id'   => $mhs->id,
                        'mata_kuliah_id' => $mk->id,
                        'tanggal'        => $tanggal->format('Y-m-d'),
                        'pertemuan_ke'   => $pertemuan,
                        'status'         => $status,
                        'keterangan'     => $keterangan,
                    ]);
                }
            }
        }
    }
}
