<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id', 'mata_kuliah_id', 'semester_ambil',
        'nilai_angka', 'nilai_huruf', 'bobot', 'status',
    ];

    protected $casts = [
        'nilai_angka' => 'decimal:2',
        'bobot'       => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Otomatis tentukan nilai huruf & bobot dari nilai angka.
     */
    public static function hitungNilaiHuruf(float $angka): array
    {
        return match (true) {
            $angka >= 85 => ['huruf' => 'A',  'bobot' => 4.00],
            $angka >= 80 => ['huruf' => 'A-', 'bobot' => 3.75],
            $angka >= 75 => ['huruf' => 'B+', 'bobot' => 3.50],
            $angka >= 70 => ['huruf' => 'B',  'bobot' => 3.00],
            $angka >= 65 => ['huruf' => 'B-', 'bobot' => 2.75],
            $angka >= 60 => ['huruf' => 'C+', 'bobot' => 2.50],
            $angka >= 55 => ['huruf' => 'C',  'bobot' => 2.00],
            $angka >= 50 => ['huruf' => 'D',  'bobot' => 1.00],
            default      => ['huruf' => 'E',  'bobot' => 0.00],
        };
    }
}
