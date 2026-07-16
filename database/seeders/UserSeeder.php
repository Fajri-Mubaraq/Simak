<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'nidn'     => '2401000001',
            'email'    => 'admin@simak.ac.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'jurusan'  => null,
        ]);

        User::create([
            'name'     => 'Dr. Siti Rahayu',
            'nidn'     => '2401000002',
            'email'    => 'dosen@simak.ac.id',
            'password' => Hash::make('dosen123'),
            'role'     => 'dosen',
            'jurusan'  => 'Teknik Informatika',
        ]);

        User::create([
            'name'     => 'Staff Akademik',
            'nidn'     => '2401000003',
            'email'    => 'staff@simak.ac.id',
            'password' => Hash::make('staff123'),
            'role'     => 'staff',
            'jurusan'  => null,
        ]);
    }
}
