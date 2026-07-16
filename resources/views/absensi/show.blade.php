@extends('layouts.app')
@section('title','Detail Absensi')
@section('page-title') Detail <span>Absensi</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('absensi.index') }}">Absensi</a><span class="sep">/</span>
    <span class="current">Detail</span>
@endsection
@section('content')

<div class="row g-4">
    {{-- INFO CARD --}}
    <div class="col-lg-5">
        <div class="card-c">
            <div class="card-header-c">
                <h5><i class="fas fa-user-graduate me-2"></i>Informasi Mahasiswa</h5>
            </div>
            <div class="card-body-c">
                <div class="text-center mb-4">
                    <div class="mhs-avatar mx-auto mb-3" style="width:64px;height:64px;font-size:24px;background:{{ $absensi->mahasiswa->avatar_color ?? '#6366f1' }};">
                        {{ $absensi->mahasiswa->initials ?? '?' }}
                    </div>
                    <h5 style="font-weight:700;color:#0f172a;margin:0;">{{ $absensi->mahasiswa->nama ?? '-' }}</h5>
                    <p style="font-size:13px;color:#64748b;margin:4px 0 0;">NIM: {{ $absensi->mahasiswa->nim ?? '-' }}</p>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-book-open"></i></div>
                    <div>
                        <div class="detail-lbl">Mata Kuliah</div>
                        <div class="detail-val">{{ $absensi->mataKuliah->nama_mk ?? '-' }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $absensi->mataKuliah->kode_mk ?? '' }}</div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="detail-lbl">Tanggal</div>
                        <div class="detail-val">{{ $absensi->tanggal->format('d F Y') }}</div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-hashtag"></i></div>
                    <div>
                        <div class="detail-lbl">Pertemuan</div>
                        <div class="detail-val">Ke-{{ $absensi->pertemuan_ke }}</div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <div class="detail-lbl">Status</div>
                        <div><span class="badge-c {{ $absensi->status_class }}" style="font-size:14px;padding:5px 14px;">{{ $absensi->status }}</span></div>
                    </div>
                </div>

                @if($absensi->keterangan)
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-comment"></i></div>
                    <div>
                        <div class="detail-lbl">Keterangan</div>
                        <div class="detail-val" style="font-size:14px;font-weight:500;">{{ $absensi->keterangan }}</div>
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2 mt-4">
                    @if($canManage)
                    <a href="{{ route('absensi.edit', $absensi) }}" class="btn-primary-c">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    @endif
                    <a href="{{ route('absensi.index') }}" class="btn-secondary-c">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- REKAP ABSENSI CARD --}}
    <div class="col-lg-7">
        <div class="card-c">
            <div class="card-header-c">
                <h5><i class="fas fa-chart-bar me-2"></i>Rekap Absensi — {{ $absensi->mataKuliah->nama_mk ?? '' }}</h5>
                <p>Total {{ $rekapStats['total'] }} pertemuan tercatat</p>
            </div>
            <div class="card-body-c">
                {{-- Mini Stats --}}
                <div class="d-flex gap-3 mb-4 flex-wrap">
                    <div style="flex:1;min-width:80px;text-align:center;padding:12px;background:rgba(16,185,129,.08);border-radius:10px;">
                        <div style="font-size:24px;font-weight:800;color:#065f46;">{{ $rekapStats['hadir'] }}</div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;">Hadir</div>
                    </div>
                    <div style="flex:1;min-width:80px;text-align:center;padding:12px;background:rgba(14,165,233,.08);border-radius:10px;">
                        <div style="font-size:24px;font-weight:800;color:#0c4a6e;">{{ $rekapStats['izin'] }}</div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;">Izin</div>
                    </div>
                    <div style="flex:1;min-width:80px;text-align:center;padding:12px;background:rgba(245,158,11,.08);border-radius:10px;">
                        <div style="font-size:24px;font-weight:800;color:#92400e;">{{ $rekapStats['sakit'] }}</div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;">Sakit</div>
                    </div>
                    <div style="flex:1;min-width:80px;text-align:center;padding:12px;background:rgba(239,68,68,.08);border-radius:10px;">
                        <div style="font-size:24px;font-weight:800;color:#7f1d1d;">{{ $rekapStats['alpha'] }}</div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;">Alpha</div>
                    </div>
                </div>

                {{-- Persentase Kehadiran --}}
                @php
                    $persen = $rekapStats['total'] > 0 ? round(($rekapStats['hadir'] / $rekapStats['total']) * 100, 1) : 0;
                @endphp
                <div style="margin-bottom:20px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:13px;font-weight:600;color:#475569;">Persentase Kehadiran</span>
                        <span style="font-size:13px;font-weight:700;color:{{ $persen >= 75 ? '#065f46' : '#7f1d1d' }};">{{ $persen }}%</span>
                    </div>
                    <div style="height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $persen }}%;background:{{ $persen >= 75 ? 'linear-gradient(135deg,#34d399,#10b981)' : 'linear-gradient(135deg,#f87171,#ef4444)' }};border-radius:4px;transition:width .5s;"></div>
                    </div>
                    @if($persen < 75)
                    <div style="font-size:12px;color:#ef4444;margin-top:4px;"><i class="fas fa-triangle-exclamation me-1"></i>Kehadiran di bawah 75%!</div>
                    @endif
                </div>

                {{-- Table rekap per pertemuan --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pertemuan</th><th>Tanggal</th><th>Status</th><th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekapAbsensi as $r)
                            <tr style="{{ $r->id == $absensi->id ? 'background:#f0f0ff;' : '' }}">
                                <td style="font-weight:600;">Ke-{{ $r->pertemuan_ke }}</td>
                                <td style="font-size:13px;">{{ $r->tanggal->format('d M Y') }}</td>
                                <td><span class="badge-c {{ $r->status_class }}">{{ $r->status }}</span></td>
                                <td style="font-size:13px;color:#64748b;">{{ $r->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
