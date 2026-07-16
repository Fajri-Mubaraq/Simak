<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'tanggal',
        'pertemuan_ke',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Get status badge CSS class
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'Hadir' => 'badge-hadir',
            'Izin'  => 'badge-izin',
            'Sakit' => 'badge-sakit',
            'Alpha' => 'badge-alpha',
            default => 'badge-hadir',
        };
    }
}
