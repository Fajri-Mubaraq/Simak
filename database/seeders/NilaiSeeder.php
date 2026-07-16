<?php

namespace Database\Seeders;

use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas  = Mahasiswa::all()->keyBy('nim');
        $mataKuliahs = MataKuliah::all()->keyBy('kode_mk');

        $data = [
            ['nim'=>'2101000001','kode_mk'=>'IF101','semester_ambil'=>'2021/2022 Ganjil','nilai_angka'=>88],
            ['nim'=>'2101000001','kode_mk'=>'IF102','semester_ambil'=>'2021/2022 Ganjil','nilai_angka'=>75],
            ['nim'=>'2101000001','kode_mk'=>'IF201','semester_ambil'=>'2021/2022 Genap','nilai_angka'=>82],
            ['nim'=>'2101000001','kode_mk'=>'IF202','semester_ambil'=>'2021/2022 Genap','nilai_angka'=>91],
            ['nim'=>'2101000002','kode_mk'=>'IF101','semester_ambil'=>'2021/2022 Ganjil','nilai_angka'=>93],
            ['nim'=>'2101000002','kode_mk'=>'IF102','semester_ambil'=>'2021/2022 Ganjil','nilai_angka'=>87],
            ['nim'=>'2101000002','kode_mk'=>'IF201','semester_ambil'=>'2021/2022 Genap','nilai_angka'=>79],
            ['nim'=>'2101000003','kode_mk'=>'SI101','semester_ambil'=>'2021/2022 Ganjil','nilai_angka'=>85],
            ['nim'=>'2101000003','kode_mk'=>'SI201','semester_ambil'=>'2022/2023 Ganjil','nilai_angka'=>78],
            ['nim'=>'2201000001','kode_mk'=>'IF101','semester_ambil'=>'2022/2023 Ganjil','nilai_angka'=>90],
            ['nim'=>'2201000001','kode_mk'=>'IF102','semester_ambil'=>'2022/2023 Ganjil','nilai_angka'=>84],
            ['nim'=>'2201000002','kode_mk'=>'MI101','semester_ambil'=>'2022/2023 Ganjil','nilai_angka'=>72],
            ['nim'=>'2201000002','kode_mk'=>'MI201','semester_ambil'=>'2023/2024 Ganjil','nilai_angka'=>68],
            ['nim'=>'2301000001','kode_mk'=>'IF101','semester_ambil'=>'2023/2024 Ganjil','nilai_angka'=>95],
            ['nim'=>'2301000001','kode_mk'=>'IF102','semester_ambil'=>'2023/2024 Ganjil','nilai_angka'=>88],
        ];

        foreach ($data as $row) {
            $m  = $mahasiswas[$row['nim']] ?? null;
            $mk = $mataKuliahs[$row['kode_mk']] ?? null;
            if (!$m || !$mk) continue;

            $grade = Nilai::hitungNilaiHuruf((float) $row['nilai_angka']);
            $status = $grade['bobot'] >= 1.00 ? 'Lulus' : 'Tidak Lulus';

            Nilai::create([
                'mahasiswa_id'   => $m->id,
                'mata_kuliah_id' => $mk->id,
                'semester_ambil' => $row['semester_ambil'],
                'nilai_angka'    => $row['nilai_angka'],
                'nilai_huruf'    => $grade['huruf'],
                'bobot'          => $grade['bobot'],
                'status'         => $status,
            ]);
        }
    }
}
