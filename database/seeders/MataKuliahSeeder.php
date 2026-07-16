<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_mk'=>'IF101','nama_mk'=>'Pemrograman Dasar','sks'=>3,'semester'=>1,'jurusan'=>'Teknik Informatika','dosen'=>'Dr. Ahmad Hakim, M.Kom','status'=>'Aktif','deskripsi'=>'Pengantar pemrograman menggunakan bahasa C dan Python.'],
            ['kode_mk'=>'IF102','nama_mk'=>'Matematika Diskrit','sks'=>3,'semester'=>1,'jurusan'=>'Teknik Informatika','dosen'=>'Dr. Budi Wicaksono, M.Si','status'=>'Aktif','deskripsi'=>'Logika, himpunan, relasi, fungsi, dan graf.'],
            ['kode_mk'=>'IF201','nama_mk'=>'Struktur Data','sks'=>3,'semester'=>2,'jurusan'=>'Teknik Informatika','dosen'=>'Dr. Cahya Nugraha, M.T','status'=>'Aktif','deskripsi'=>'Stack, queue, tree, graph dan implementasinya.'],
            ['kode_mk'=>'IF202','nama_mk'=>'Basis Data','sks'=>3,'semester'=>2,'jurusan'=>'Teknik Informatika','dosen'=>'Ir. Dian Kusuma, M.Kom','status'=>'Aktif','deskripsi'=>'Perancangan database, SQL, dan normalisasi.'],
            ['kode_mk'=>'IF301','nama_mk'=>'Pemrograman Web','sks'=>3,'semester'=>3,'jurusan'=>'Teknik Informatika','dosen'=>'Eko Putra, S.Kom, M.T','status'=>'Aktif','deskripsi'=>'HTML, CSS, JavaScript, PHP dan framework web modern.'],
            ['kode_mk'=>'IF302','nama_mk'=>'Pemrograman Web 2','sks'=>3,'semester'=>4,'jurusan'=>'Teknik Informatika','dosen'=>'Fitria Dewi, M.Kom','status'=>'Aktif','deskripsi'=>'Framework laravel, React, dan deployment aplikasi web.'],
            ['kode_mk'=>'IF401','nama_mk'=>'Kecerdasan Buatan','sks'=>3,'semester'=>5,'jurusan'=>'Teknik Informatika','dosen'=>'Dr. Gunawan, M.Cs','status'=>'Aktif','deskripsi'=>'Machine learning, deep learning, dan computer vision.'],
            ['kode_mk'=>'IF402','nama_mk'=>'Keamanan Jaringan','sks'=>3,'semester'=>5,'jurusan'=>'Teknik Informatika','dosen'=>'Hendra Wijaya, M.T','status'=>'Aktif','deskripsi'=>'Kriptografi, firewall, dan ethical hacking dasar.'],
            ['kode_mk'=>'SI101','nama_mk'=>'Sistem Informasi Manajemen','sks'=>3,'semester'=>1,'jurusan'=>'Sistem Informasi','dosen'=>'Dr. Indra Saputra, M.M','status'=>'Aktif','deskripsi'=>'Konsep dasar sistem informasi dalam organisasi.'],
            ['kode_mk'=>'SI201','nama_mk'=>'Analisis & Perancangan Sistem','sks'=>3,'semester'=>3,'jurusan'=>'Sistem Informasi','dosen'=>'Joko Susilo, M.Kom','status'=>'Aktif','deskripsi'=>'SDLC, UML, dan desain sistem berorientasi objek.'],
            ['kode_mk'=>'MI101','nama_mk'=>'Komputer & Masyarakat','sks'=>2,'semester'=>1,'jurusan'=>'Manajemen Informatika','dosen'=>'Kartini Sari, M.Pd','status'=>'Aktif','deskripsi'=>'Peran teknologi dalam kehidupan bermasyarakat.'],
            ['kode_mk'=>'MI201','nama_mk'=>'Pemrograman Aplikasi','sks'=>3,'semester'=>3,'jurusan'=>'Manajemen Informatika','dosen'=>'Lukman Hakim, M.T','status'=>'Aktif','deskripsi'=>'Pengembangan aplikasi berbasis desktop dan mobile.'],
        ];

        foreach ($data as $row) {
            MataKuliah::create($row);
        }
    }
}
