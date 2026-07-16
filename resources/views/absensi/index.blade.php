@extends('layouts.app')
@section('title','Absensi')
@section('page-title') Data <span>Absensi</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <span class="current">Absensi</span>
@endsection
@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check"></i></div>
            <div>
                <p class="stat-num">{{ $stats['hadir'] }}</p>
                <p class="stat-lbl">Hadir</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-envelope"></i></div>
            <div>
                <p class="stat-num">{{ $stats['izin'] }}</p>
                <p class="stat-lbl">Izin</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="fas fa-briefcase-medical"></i></div>
            <div>
                <p class="stat-num">{{ $stats['sakit'] }}</p>
                <p class="stat-lbl">Sakit</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon si-pink"><i class="fas fa-xmark"></i></div>
            <div>
                <p class="stat-num">{{ $stats['alpha'] }}</p>
                <p class="stat-lbl">Alpha</p>
            </div>
        </div>
    </div>
</div>

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Data Absensi Mahasiswa</h2>
        <p style="font-size:14px;color:#64748b;margin:4px 0 0;">Total {{ $stats['total'] }} data absensi</p>
    </div>
    @if($canManage)
    <a href="{{ route('absensi.create') }}" class="btn-primary-c">
        <i class="fas fa-plus"></i> Tambah Absensi
    </a>
    @endif
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('absensi.index') }}" class="tbl-wrapper mb-3">
    <div style="padding:16px 20px;">
        <div class="filter-bar">
            <div class="search-wrap">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM, nama...">
                <i class="fas fa-magnifying-glass search-icon"></i>
            </div>
            <select name="mata_kuliah_id" class="filter-select">
                <option value="">Semua Mata Kuliah</option>
                @foreach($mataKuliahs as $mk)
                <option value="{{ $mk->id }}" {{ request('mata_kuliah_id')==$mk->id?'selected':'' }}>{{ $mk->nama_mk }}</option>
                @endforeach
            </select>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                @foreach(['Hadir','Izin','Sakit','Alpha'] as $st)
                <option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>{{ $st }}</option>
                @endforeach
            </select>
            <select name="pertemuan_ke" class="filter-select">
                <option value="">Semua Pertemuan</option>
                @for($p = 1; $p <= 16; $p++)
                <option value="{{ $p }}" {{ request('pertemuan_ke')==$p?'selected':'' }}>Pertemuan Ke-{{ $p }}</option>
                @endfor
            </select>
            <button type="submit" class="btn-primary-c" style="padding:9px 16px;"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','mata_kuliah_id','status','pertemuan_ke','tanggal_dari','tanggal_sampai']))
            <a href="{{ route('absensi.index') }}" class="btn-secondary-c" style="padding:9px 16px;"><i class="fas fa-xmark"></i> Reset</a>
            @endif
        </div>
    </div>
</form>

{{-- TABLE --}}
<div class="tbl-wrapper">
    <div class="tbl-header">
        <h5><i class="fas fa-clipboard-check me-2" style="color:var(--primary);"></i>Rekap Absensi</h5>
        <span style="font-size:13px;color:#64748b;">{{ $absensis->firstItem()??0 }}-{{ $absensis->lastItem()??0 }} dari {{ $absensis->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th><th>Mahasiswa</th><th>Mata Kuliah</th>
                    <th>Tanggal</th><th>Pertemuan</th><th>Status</th>
                    <th>Keterangan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensis as $i => $abs)
                <tr>
                    <td style="color:#94a3b8;font-size:13px;">{{ $absensis->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="mhs-avatar" style="background:{{ $abs->mahasiswa->avatar_color ?? '#6366f1' }};">
                                {{ $abs->mahasiswa->initials ?? '?' }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#0f172a;">{{ $abs->mahasiswa->nama ?? '-' }}</div>
                                <div style="font-size:12px;color:#94a3b8;">{{ $abs->mahasiswa->nim ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $abs->mataKuliah->nama_mk ?? '-' }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $abs->mataKuliah->kode_mk ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $abs->tanggal->format('d M Y') }}</td>
                    <td>
                        <code style="background:#f1f5f9;padding:3px 8px;border-radius:6px;font-size:13px;">Ke-{{ $abs->pertemuan_ke }}</code>
                    </td>
                    <td>
                        <span class="badge-c {{ $abs->status_class }}">{{ $abs->status }}</span>
                    </td>
                    <td style="font-size:13px;color:#64748b;max-width:150px;">{{ Str::limit($abs->keterangan, 30) ?? '-' }}</td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('absensi.show', $abs) }}" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                            @if($canManage)
                            <a href="{{ route('absensi.edit', $abs) }}" class="btn-action btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('absensi.destroy', $abs) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus data absensi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-clipboard-check"></i></div>
                            <p style="font-size:16px;font-weight:600;margin:0 0 6px;">Belum ada data absensi</p>
                            @if($canManage)
                            <a href="{{ route('absensi.create') }}" class="btn-primary-c"><i class="fas fa-plus"></i> Tambah Absensi</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($absensis->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
        {{ $absensis->links() }}
    </div>
    @endif
</div>

@endsection
