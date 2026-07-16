@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title') <span>Dashboard</span> SIMAK @endsection

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-user-graduate"></i></div>
            <div>
                <p class="stat-num">{{ $stats['total_mahasiswa'] }}</p>
                <p class="stat-lbl">Total Mahasiswa</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-book-open"></i></div>
            <div>
                <p class="stat-num">{{ $stats['total_mk'] }}</p>
                <p class="stat-lbl">Mata Kuliah</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-star-half-stroke"></i></div>
            <div>
                <p class="stat-num">{{ $stats['total_nilai'] }}</p>
                <p class="stat-lbl">Data Nilai</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="fas fa-chart-line"></i></div>
            <div>
                <p class="stat-num">{{ number_format($stats['rata_nilai'], 1) }}</p>
                <p class="stat-lbl">Rata-rata Nilai</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- STATUS MAHASISWA --}}
    <div class="col-md-5">
        <div class="card-c h-100">
            <div class="card-header-c">
                <h5><i class="fas fa-users me-2"></i>Status Mahasiswa</h5>
                <p>Distribusi status mahasiswa aktif</p>
            </div>
            <div class="card-body-c">
                @php
                    $total = max($stats['total_mahasiswa'], 1);
                    $statuses = [
                        ['label'=>'Aktif',  'val'=>$stats['mahasiswa_aktif'], 'color'=>'#10b981'],
                        ['label'=>'Cuti',   'val'=>$stats['mahasiswa_cuti'],  'color'=>'#f59e0b'],
                        ['label'=>'Lulus',  'val'=>$stats['mahasiswa_lulus'], 'color'=>'#6366f1'],
                        ['label'=>'DO',     'val'=>$stats['total_mahasiswa']-$stats['mahasiswa_aktif']-$stats['mahasiswa_cuti']-$stats['mahasiswa_lulus'], 'color'=>'#ef4444'],
                    ];
                @endphp
                <div class="d-flex flex-column gap-3">
                    @foreach($statuses as $s)
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;font-weight:600;color:#334155;">{{ $s['label'] }}</span>
                            <span style="font-size:13px;color:#64748b;">{{ $s['val'] }} mahasiswa</span>
                        </div>
                        <div style="height:8px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
                            <div style="height:100%;width:{{ round(($s['val']/$total)*100) }}%;background:{{ $s['color'] }};border-radius:10px;transition:width .6s ease;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row g-2 mt-3">
                    <div class="col-6">
                        <div style="background:rgba(16,185,129,.08);border-radius:10px;padding:12px;text-align:center;">
                            <p style="font-size:22px;font-weight:800;color:#10b981;margin:0;">{{ $stats['mahasiswa_aktif'] }}</p>
                            <p style="font-size:11px;color:#065f46;margin:0;">Aktif</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:rgba(99,102,241,.08);border-radius:10px;padding:12px;text-align:center;">
                            <p style="font-size:22px;font-weight:800;color:#6366f1;margin:0;">{{ $stats['mahasiswa_lulus'] }}</p>
                            <p style="font-size:11px;color:#3730a3;margin:0;">Lulus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PER JURUSAN --}}
    <div class="col-md-7">
        <div class="card-c h-100">
            <div class="card-header-c">
                <h5><i class="fas fa-building-columns me-2"></i>Mahasiswa per Jurusan</h5>
                <p>Distribusi berdasarkan program studi</p>
            </div>
            <div class="card-body-c">
                @php $colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6']; @endphp
                <div class="d-flex flex-column gap-3">
                    @forelse($perJurusan as $i => $j)
                    @php $pct = round(($j->total / max($stats['total_mahasiswa'],1))*100); @endphp
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;font-weight:600;color:#334155;">{{ $j->jurusan }}</span>
                            <span style="font-size:13px;color:#64748b;">{{ $j->total }} ({{ $pct }}%)</span>
                        </div>
                        <div style="height:8px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $colors[$i%count($colors)] }};border-radius:10px;"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAHASISWA TERBARU --}}
<div class="tbl-wrapper">
    <div class="tbl-header">
        <h5><i class="fas fa-clock-rotate-left me-2" style="color:var(--primary);"></i>Mahasiswa Terbaru</h5>
        <a href="{{ route('mahasiswa.index') }}" class="btn-primary-c" style="font-size:13px;padding:7px 14px;">
            <i class="fas fa-arrow-right"></i> Lihat Semua
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswaTerbaru as $mhs)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="mhs-avatar" style="background:{{ $mhs->avatar_color }};">
                                {{ $mhs->initials }}
                            </div>
                            <span style="font-weight:600;">{{ $mhs->nama }}</span>
                        </div>
                    </td>
                    <td><code style="background:#f1f5f9;padding:3px 8px;border-radius:6px;font-size:13px;">{{ $mhs->nim }}</code></td>
                    <td><span class="badge-c badge-jurusan">{{ $mhs->jurusan }}</span></td>
                    <td>{{ $mhs->angkatan }}</td>
                    <td>
                        <span class="badge-c badge-{{ strtolower($mhs->status) == 'aktif' ? 'aktif' : (strtolower($mhs->status)=='cuti'?'cuti':(strtolower($mhs->status)=='lulus'?'lulus':'do')) }}">
                            {{ $mhs->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('mahasiswa.show', $mhs) }}" class="btn-action btn-view" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data mahasiswa</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
