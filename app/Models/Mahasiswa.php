<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Hidden(['password'])]
class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim', 'nama', 'jenis_kelamin', 'jurusan',
        'program_studi', 'angkatan', 'email',
        'password', 'no_telp', 'alamat', 'status', 'foto_profil',
    ];

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->nama);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }

    public function getAvatarColorAttribute(): string
    {
        $colors = ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
        return $colors[crc32($this->nim) % count($colors)];
    }
}
