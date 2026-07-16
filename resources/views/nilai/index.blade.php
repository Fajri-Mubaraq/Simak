@extends('layouts.app')
@section('title','Data Nilai')
@section('page-title') Data <span>Nilai</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <span class="current">Data Nilai</span>
@endsection
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Data Nilai Mahasiswa</h2>
        <p style="font-size:14px;color:#64748b;margin:4px 0 0;">Total {{ $nilais->total() }} data nilai</p>
    </div>
    @if(session('user_role') !== 'mahasiswa')
    <a href="{{ route('nilai.create') }}" class="btn-primary-c">
        <i class="fas fa-plus"></i> Input Nilai
    </a>
    @endif
</div>

<form method="GET" action="{{ route('nilai.index') }}" class="tbl-wrapper mb-3">
    <div style="padding:16px 20px;">
        <div class="filter-bar">
            <div class="search-wrap">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM, nama, kode MK...">
                <i class="fas fa-magnifying-glass search-icon"></i>
            </div>
            <select name="semester_ambil" class="filter-select">
                <option value="">Semua Semester</option>
                @foreach($semesters as $sem)
                <option value="{{ $sem }}" {{ request('semester_ambil')==$sem?'selected':'' }}>{{ $sem }}</option>
                @endforeach
            </select>
            <select name="nilai_huruf" class="filter-select">
                <option value="">Semua Nilai</option>
                @foreach(['A','A-','B+','B','B-','C+','C','D','E'] as $nh)
                <option value="{{ $nh }}" {{ request('nilai_huruf')==$nh?'selected':'' }}>{{ $nh }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary-c" style="padding:9px 16px;"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','semester_ambil','nilai_huruf']))
            <a href="{{ route('nilai.index') }}" class="btn-secondary-c" style="padding:9px 16px;"><i class="fas fa-xmark"></i> Reset</a>
            @endif
        </div>
    </div>
</form>

<div class="tbl-wrapper">
    <div class="tbl-header">
        <h5><i class="fas fa-star-half-stroke me-2" style="color:var(--primary);"></i>Rekap Nilai</h5>
        <span style="font-size:13px;color:#64748b;">{{ $nilais->firstItem()??0 }}-{{ $nilais->lastItem()??0 }} dari {{ $nilais->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th><th>Mahasiswa</th><th>Mata Kuliah</th>
                    <th>Semester</th><th>Nilai</th><th>Bobot</th><th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilais as $i => $n)
                @php
                    $nhClass = match(true) {
                        in_array($n->nilai_huruf, ['A','A-']) => 'badge-nilai-a',
                        in_array($n->nilai_huruf, ['B+','B','B-']) => 'badge-nilai-b',
                        in_array($n->nilai_huruf, ['C+','C']) => 'badge-nilai-c',
                        $n->nilai_huruf=='D' => 'badge-nilai-d',
                        default => 'badge-nilai-e',
                    };
                @endphp
                <tr>
                    <td style="color:#94a3b8;font-size:13px;">{{ $nilais->firstItem()+$i }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $n->mahasiswa->nama ?? '-' }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $n->mahasiswa->nim ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $n->mataKuliah->nama_mk ?? '-' }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $n->mataKuliah->kode_mk ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $n->semester_ambil }}</td>
                    <td>
                        <span class="badge-c {{ $nhClass }}" style="font-size:15px;padding:5px 12px;">{{ $n->nilai_huruf }}</span>
                        <div style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $n->nilai_angka }}</div>
                    </td>
                    <td style="font-weight:700;color:#0f172a;">{{ $n->bobot }}</td>
                    <td>
                        @php $sc=['Lulus'=>'lulus-n','Tidak Lulus'=>'gagal','Mengulang'=>'ulang']; @endphp
                        <span class="badge-c badge-{{ $sc[$n->status]??'lulus-n' }}">{{ $n->status }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('nilai.show', $n) }}" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                            @if(session('user_role') !== 'mahasiswa')
                            <a href="{{ route('nilai.edit', $n) }}" class="btn-action btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('nilai.destroy', $n) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus data nilai ini?')">
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
                            <div class="empty-icon"><i class="fas fa-star-half-stroke"></i></div>
                            <p style="font-size:16px;font-weight:600;margin:0 0 6px;">Belum ada data nilai</p>
                            @if(session('user_role') !== 'mahasiswa')
                            <a href="{{ route('nilai.create') }}" class="btn-primary-c"><i class="fas fa-plus"></i> Input Nilai</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($nilais->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
        {{ $nilais->links() }}
    </div>
    @endif
</div>

@endsection
