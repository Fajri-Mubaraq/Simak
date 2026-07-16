@extends('layouts.app')
@section('title', 'Mata Kuliah')
@section('page-title') Mata <span>Kuliah</span> @endsection

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <span class="current">Mata Kuliah</span>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Daftar Mata Kuliah</h2>
        <p style="font-size:14px;color:#64748b;margin:4px 0 0;">Total {{ $mataKuliahs->total() }} mata kuliah</p>
    </div>
    <a href="{{ route('matakuliah.create') }}" class="btn-primary-c">
        <i class="fas fa-plus"></i> Tambah Mata Kuliah
    </a>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('matakuliah.index') }}" class="tbl-wrapper mb-3">
    <div style="padding:16px 20px;">
        <div class="filter-bar">
            <div class="search-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode MK, nama, dosen...">
                <i class="fas fa-magnifying-glass search-icon"></i>
            </div>
            <select name="jurusan" class="filter-select">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                <option value="{{ $j }}" {{ request('jurusan')==$j?'selected':'' }}>{{ $j }}</option>
                @endforeach
            </select>
            <select name="semester" class="filter-select">
                <option value="">Semua Semester</option>
                @for($s=1;$s<=8;$s++)
                <option value="{{ $s }}" {{ request('semester')==$s?'selected':'' }}>Semester {{ $s }}</option>
                @endfor
            </select>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('status')=='Aktif'?'selected':'' }}>Aktif</option>
                <option value="Nonaktif" {{ request('status')=='Nonaktif'?'selected':'' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn-primary-c" style="padding:9px 16px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search','jurusan','semester','status']))
            <a href="{{ route('matakuliah.index') }}" class="btn-secondary-c" style="padding:9px 16px;">
                <i class="fas fa-xmark"></i> Reset
            </a>
            @endif
        </div>
    </div>
</form>

<div class="tbl-wrapper">
    <div class="tbl-header">
        <h5><i class="fas fa-book-open me-2" style="color:var(--primary);"></i>Daftar Mata Kuliah</h5>
        <span style="font-size:13px;color:#64748b;">{{ $mataKuliahs->firstItem()??0 }}-{{ $mataKuliahs->lastItem()??0 }} dari {{ $mataKuliahs->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th><th>Kode MK</th><th>Nama Mata Kuliah</th>
                    <th>SKS</th><th>Semester</th><th>Jurusan</th>
                    <th>Dosen</th><th>Status</th><th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mataKuliahs as $i => $mk)
                <tr>
                    <td style="color:#94a3b8;font-size:13px;">{{ $mataKuliahs->firstItem()+$i }}</td>
                    <td><code style="background:#f1f5f9;padding:3px 8px;border-radius:6px;font-size:13px;">{{ $mk->kode_mk }}</code></td>
                    <td>
                        <div style="font-weight:600;">{{ $mk->nama_mk }}</div>
                        @if($mk->deskripsi)
                        <div style="font-size:12px;color:#94a3b8;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $mk->deskripsi }}</div>
                        @endif
                    </td>
                    <td><span class="badge-c badge-sks">{{ $mk->sks }} SKS</span></td>
                    <td style="font-weight:600;">Sem. {{ $mk->semester }}</td>
                    <td><span class="badge-c badge-jurusan" style="font-size:11px;">{{ $mk->jurusan }}</span></td>
                    <td style="font-size:13px;color:#64748b;">{{ $mk->dosen ?? '-' }}</td>
                    <td>
                        <span class="badge-c {{ $mk->status=='Aktif'?'badge-mk-aktif':'badge-mk-off' }}">{{ $mk->status }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('matakuliah.show', $mk) }}" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('matakuliah.edit', $mk) }}" class="btn-action btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('matakuliah.destroy', $mk) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus {{ $mk->nama_mk }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-book-open"></i></div>
                            <p style="font-size:16px;font-weight:600;color:#334155;margin:0 0 6px;">Belum ada mata kuliah</p>
                            <a href="{{ route('matakuliah.create') }}" class="btn-primary-c">
                                <i class="fas fa-plus"></i> Tambah Sekarang
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mataKuliahs->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
        {{ $mataKuliahs->links() }}
    </div>
    @endif
</div>

@endsection
