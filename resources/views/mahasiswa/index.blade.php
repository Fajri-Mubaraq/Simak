@extends('layouts.app')

@section('title', 'Data Mahasiswa')
@section('page-title') Data <span>Mahasiswa</span> @endsection

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <span class="current">Data Mahasiswa</span>
@endsection

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Daftar Mahasiswa</h2>
        <p style="font-size:14px;color:#64748b;margin:4px 0 0;">Total {{ $mahasiswas->total() }} mahasiswa terdaftar</p>
    </div>
    @if(session('user_role') === 'admin')
    <a href="{{ route('mahasiswa.create') }}" class="btn-primary-c">
        <i class="fas fa-plus"></i> Tambah Mahasiswa
    </a>
    @endif
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('mahasiswa.index') }}" class="tbl-wrapper mb-3">
    <div style="padding:16px 20px;">
        <div class="filter-bar">
            <div class="search-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari NIM, nama, email...">
                <i class="fas fa-magnifying-glass search-icon"></i>
            </div>
            <select name="jurusan" class="filter-select">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                <option value="{{ $j }}" {{ request('jurusan')==$j?'selected':'' }}>{{ $j }}</option>
                @endforeach
            </select>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                @foreach(['Aktif','Cuti','Lulus','DO'] as $st)
                <option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>{{ $st }}</option>
                @endforeach
            </select>
            <select name="angkatan" class="filter-select">
                <option value="">Semua Angkatan</option>
                @foreach($angkatans as $a)
                <option value="{{ $a }}" {{ request('angkatan')==$a?'selected':'' }}>{{ $a }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary-c" style="padding:9px 16px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search','jurusan','status','angkatan']))
            <a href="{{ route('mahasiswa.index') }}" class="btn-secondary-c" style="padding:9px 16px;">
                <i class="fas fa-xmark"></i> Reset
            </a>
            @endif
        </div>
    </div>
</form>

{{-- TABLE --}}
<div class="tbl-wrapper">
    <div class="tbl-header">
        <h5><i class="fas fa-user-graduate me-2" style="color:var(--primary);"></i>Data Mahasiswa</h5>
        <span style="font-size:13px;color:#64748b;">
            {{ $mahasiswas->firstItem() ?? 0 }}-{{ $mahasiswas->lastItem() ?? 0 }} dari {{ $mahasiswas->total() }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th>Status</th>
                    <th>Kontak</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $i => $mhs)
                <tr>
                    <td style="color:#94a3b8;font-size:13px;">{{ $mahasiswas->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($mhs->foto_profil)
                                <img src="{{ asset($mhs->foto_profil) }}" class="mhs-avatar" style="object-fit: cover; border-radius: 50%;">
                            @else
                                <div class="mhs-avatar" style="background:{{ $mhs->avatar_color }};">
                                    {{ $mhs->initials }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:#0f172a;">{{ $mhs->nama }}</div>
                                <div style="font-size:12px;color:#94a3b8;">
                                    {{ $mhs->jenis_kelamin == 'L' ? '♂ Laki-laki' : '♀ Perempuan' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><code style="background:#f1f5f9;padding:3px 8px;border-radius:6px;font-size:13px;">{{ $mhs->nim }}</code></td>
                    <td>
                        <div style="font-weight:500;font-size:13px;">{{ $mhs->jurusan }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $mhs->program_studi }}</div>
                    </td>
                    <td style="font-weight:600;">{{ $mhs->angkatan }}</td>
                    <td>
                        @php
                            $statusClass = ['Aktif'=>'aktif','Cuti'=>'cuti','Lulus'=>'lulus','DO'=>'do'];
                        @endphp
                        <span class="badge-c badge-{{ $statusClass[$mhs->status] ?? 'aktif' }}">{{ $mhs->status }}</span>
                    </td>
                    <td>
                        @if($mhs->email)
                        <div style="font-size:12px;color:#64748b;">
                            <i class="fas fa-envelope me-1" style="color:#94a3b8;"></i>{{ $mhs->email }}
                        </div>
                        @endif
                        @if($mhs->no_telp)
                        <div style="font-size:12px;color:#64748b;">
                            <i class="fas fa-phone me-1" style="color:#94a3b8;"></i>{{ $mhs->no_telp }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('mahasiswa.show', $mhs) }}" class="btn-action btn-view" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(session('user_role') === 'admin')
                            <a href="{{ route('mahasiswa.edit', $mhs) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('mahasiswa.destroy', $mhs) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus data {{ $mhs->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-user-graduate"></i></div>
                            <p style="font-size:16px;font-weight:600;color:#334155;margin:0 0 6px;">Belum ada data</p>
                            <p style="font-size:14px;color:#94a3b8;margin:0 0 16px;">
                                {{ request()->hasAny(['search','jurusan','status','angkatan']) ? 'Tidak ada hasil yang sesuai filter.' : 'Mulai tambahkan data mahasiswa.' }}
                            </p>
                            @if(session('user_role') === 'admin')
                            <a href="{{ route('mahasiswa.create') }}" class="btn-primary-c">
                                <i class="fas fa-plus"></i> Tambah Sekarang
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mahasiswas->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
        {{ $mahasiswas->links() }}
    </div>
    @endif
</div>

@endsection
