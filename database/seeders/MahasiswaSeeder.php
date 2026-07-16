<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('mhs123');

        $data = [
            ['nim'=>'2101000001','nama'=>'Ahmad Fauzi','jenis_kelamin'=>'L','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2021,'email'=>'ahmad.fauzi@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567001','status'=>'Aktif'],
            ['nim'=>'2101000002','nama'=>'Siti Nurhaliza','jenis_kelamin'=>'P','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2021,'email'=>'siti.nurhaliza@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567002','status'=>'Aktif'],
            ['nim'=>'2101000003','nama'=>'Budi Santoso','jenis_kelamin'=>'L','jurusan'=>'Sistem Informasi','program_studi'=>'S1 Sistem Informasi','angkatan'=>2021,'email'=>'budi.santoso@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567003','status'=>'Aktif'],
            ['nim'=>'2101000004','nama'=>'Dewi Rahayu','jenis_kelamin'=>'P','jurusan'=>'Sistem Informasi','program_studi'=>'S1 Sistem Informasi','angkatan'=>2021,'email'=>'dewi.rahayu@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567004','status'=>'Aktif'],
            ['nim'=>'2101000005','nama'=>'Eko Prasetyo','jenis_kelamin'=>'L','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2021,'email'=>'eko.prasetyo@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567005','status'=>'Cuti'],
            ['nim'=>'2201000001','nama'=>'Fitri Handayani','jenis_kelamin'=>'P','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2022,'email'=>'fitri.handayani@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567006','status'=>'Aktif'],
            ['nim'=>'2201000002','nama'=>'Galih Permana','jenis_kelamin'=>'L','jurusan'=>'Manajemen Informatika','program_studi'=>'D3 Manajemen Informatika','angkatan'=>2022,'email'=>'galih.permana@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567007','status'=>'Aktif'],
            ['nim'=>'2201000003','nama'=>'Hana Putri','jenis_kelamin'=>'P','jurusan'=>'Sistem Informasi','program_studi'=>'S1 Sistem Informasi','angkatan'=>2022,'email'=>'hana.putri@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567008','status'=>'Aktif'],
            ['nim'=>'2201000004','nama'=>'Irwan Setiawan','jenis_kelamin'=>'L','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2022,'email'=>'irwan.setiawan@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567009','status'=>'Aktif'],
            ['nim'=>'2301000001','nama'=>'Julia Sari','jenis_kelamin'=>'P','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2023,'email'=>'julia.sari@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567010','status'=>'Aktif'],
            ['nim'=>'2301000002','nama'=>'Kevin Ardianto','jenis_kelamin'=>'L','jurusan'=>'Sistem Informasi','program_studi'=>'S1 Sistem Informasi','angkatan'=>2023,'email'=>'kevin.ardianto@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567011','status'=>'Aktif'],
            ['nim'=>'2001000001','nama'=>'Lina Marlina','jenis_kelamin'=>'P','jurusan'=>'Teknik Informatika','program_studi'=>'S1 Teknik Informatika','angkatan'=>2020,'email'=>'lina.marlina@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567012','status'=>'Lulus'],
            ['nim'=>'2001000002','nama'=>'Muhammad Rizky','jenis_kelamin'=>'L','jurusan'=>'Manajemen Informatika','program_studi'=>'D3 Manajemen Informatika','angkatan'=>2020,'email'=>'m.rizky@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567013','status'=>'Lulus'],
            ['nim'=>'2301000003','nama'=>'Nanda Agustina','jenis_kelamin'=>'P','jurusan'=>'Manajemen Informatika','program_studi'=>'D3 Manajemen Informatika','angkatan'=>2023,'email'=>'nanda.agustina@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567014','status'=>'Aktif'],
            ['nim'=>'2201000005','nama'=>'Oscar Hidayat','jenis_kelamin'=>'L','jurusan'=>'Sistem Informasi','program_studi'=>'S1 Sistem Informasi','angkatan'=>2022,'email'=>'oscar.hidayat@student.ac.id','password'=>$defaultPassword,'no_telp'=>'081234567015','status'=>'DO'],
        ];

        foreach ($data as $row) {
            Mahasiswa::create($row);
        }
    }
}
