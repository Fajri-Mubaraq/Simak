<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode_mk', 'nama_mk', 'sks', 'semester',
        'jurusan', 'dosen', 'status', 'deskripsi',
    ];

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}
