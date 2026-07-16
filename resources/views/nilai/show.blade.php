@extends('layouts.app')
@section('title','Detail Nilai')
@section('page-title') Detail <span>Nilai</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('nilai.index') }}">Data Nilai</a><span class="sep">/</span>
    <span class="current">Detail</span>
@endsection
@section('content')
<div class="card-c" style="max-width:600px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-star-half-stroke me-2"></i>Detail Data Nilai</h5>
        <p>{{ $nilai->mahasiswa->nama ?? '-' }} — {{ $nilai->mataKuliah->nama_mk ?? '-' }}</p>
    </div>
    <div class="card-body-c">
        {{-- NILAI BESAR --}}
        @php
            $nhClass = match(true) {
                in_array($nilai->nilai_huruf,['A','A-']) => '#10b981',
                in_array($nilai->nilai_huruf,['B+','B','B-']) => '#6366f1',
                in_array($nilai->nilai_huruf,['C+','C']) => '#f59e0b',
                $nilai->nilai_huruf=='D' => '#ef4444',
                default => '#64748b',
            };
        @endphp
        <div style="text-align:center;padding:24px;background:rgba(99,102,241,.05);border-radius:14px;margin-bottom:20px;">
            <div style="font-size:64px;font-weight:900;color:{{ $nhClass }};line-height:1;">{{ $nilai->nilai_huruf }}</div>
            <div style="font-size:20px;color:#64748b;margin-top:4px;">{{ $nilai->nilai_angka }} / 100</div>
            <div style="font-size:14px;color:#94a3b8;margin-top:4px;">Bobot: {{ $nilai->bobot }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="detail-lbl">Mahasiswa</div>
                <div class="detail-val">{{ $nilai->mahasiswa->nama ?? '-' }}</div>
                <div style="font-size:12px;color:#94a3b8;">{{ $nilai->mahasiswa->nim ?? '' }}</div>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="detail-lbl">Mata Kuliah</div>
                <div class="detail-val">{{ $nilai->mataKuliah->nama_mk ?? '-' }}</div>
                <div style="font-size:12px;color:#94a3b8;">{{ $nilai->mataKuliah->kode_mk ?? '' }} | {{ $nilai->mataKuliah->sks ?? '' }} SKS</div>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-calendar-days"></i></div>
            <div><div class="detail-lbl">Semester Ambil</div><div class="detail-val">{{ $nilai->semester_ambil }}</div></div>
        </div>
        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="detail-lbl">Status Kelulusan</div>
                @php $sc=['Lulus'=>'lulus-n','Tidak Lulus'=>'gagal','Mengulang'=>'ulang']; @endphp
                <span class="badge-c badge-{{ $sc[$nilai->status]??'lulus-n' }}" style="font-size:13px;margin-top:4px;">{{ $nilai->status }}</span>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('nilai.edit', $nilai) }}" class="btn-primary-c" style="flex:1;justify-content:center;">
                <i class="fas fa-pen"></i> Edit Nilai
            </a>
            <form action="{{ route('nilai.destroy', $nilai) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus data nilai ini?')" style="flex:1;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-secondary-c" style="width:100%;justify-content:center;color:#ef4444;">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('nilai.index') }}" style="font-size:13px;color:#94a3b8;text-decoration:none;">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke daftar nilai
            </a>
        </div>
    </div>
</div>
@endsection
