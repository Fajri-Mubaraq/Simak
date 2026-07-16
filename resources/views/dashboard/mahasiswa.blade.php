@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title') Portal <span>Mahasiswa</span> @endsection

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-graduation-cap"></i></div>
            <div>
                <p class="stat-num">{{ number_format($ipk, 2) }}</p>
                <p class="stat-lbl">IPK Kumulatif</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-book-open"></i></div>
            <div>
                <p class="stat-num">{{ $totalSks }}</p>
                <p class="stat-lbl">Total SKS Diambil</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-clipboard-user"></i></div>
            <div>
                <p class="stat-num">{{ $persenKehadiran }}%</p>
                <p class="stat-lbl">Persentase Kehadiran</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="fas fa-circle-check"></i></div>
            <div>
                <p class="stat-num" style="font-size: 20px;">{{ $mahasiswa->status }}</p>
                <p class="stat-lbl">Status Akademik</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- DETAIL PROFIL --}}
    <div class="col-md-5">
        <div class="card-c h-100">
            <div class="card-header-c">
                <h5><i class="fas fa-address-card me-2"></i>Profil Saya</h5>
                <p>Data diri mahasiswa aktif</p>
            </div>
            <div class="card-body-c" style="padding: 15px 22px;">
                <div class="text-center mb-3">
                    @if($mahasiswa->foto_profil)
                        <img src="{{ asset($mahasiswa->foto_profil) }}" class="mhs-avatar mx-auto mb-2" style="width: 64px; height: 64px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);">
                    @else
                        <div class="mhs-avatar mx-auto mb-2" style="background:{{ $mahasiswa->avatar_color }}; width: 64px; height: 64px; font-size: 24px; display:flex; align-items:center; justify-content:center; border-radius:50%;">
                            {{ $mahasiswa->initials }}
                        </div>
                    @endif
                    <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">{{ $mahasiswa->nama }}</h5>
                    <code style="background: #f1f5f9; padding: 2px 8px; border-radius: 6px; font-size: 13px;">{{ $mahasiswa->nim }}</code>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-building-columns"></i></div>
                    <div>
                        <div class="detail-lbl">Jurusan</div>
                        <div class="detail-val" style="font-size: 14px;">{{ $mahasiswa->jurusan }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div>
                        <div class="detail-lbl">Program Studi</div>
                        <div class="detail-val" style="font-size: 14px;">{{ $mahasiswa->program_studi }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="detail-lbl">Angkatan</div>
                        <div class="detail-val" style="font-size: 14px;">{{ $mahasiswa->angkatan }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="detail-lbl">Email</div>
                        <div class="detail-val" style="font-size: 14px; word-break: break-all;">{{ $mahasiswa->email ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NILAI TERBARU --}}
    <div class="col-md-7">
        <div class="tbl-wrapper h-100">
            <div class="tbl-header">
                <h5><i class="fas fa-clock-rotate-left me-2" style="color:var(--primary);"></i>Nilai Akademik Terbaru</h5>
                <a href="{{ route('nilai.index') }}" class="btn-primary-c" style="font-size:13px;padding:7px 14px;">
                    <i class="fas fa-arrow-right"></i> Selengkapnya
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Nilai Angka</th>
                            <th>Nilai Huruf</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentNilais as $n)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $n->mataKuliah->nama_mk }}</div>
                                <div style="font-size:12px;color:#94a3b8;">{{ $n->mataKuliah->kode_mk }}</div>
                            </td>
                            <td>{{ $n->mataKuliah->sks }}</td>
                            <td><span style="font-weight:600;">{{ $n->nilai_angka }}</span></td>
                            <td>
                                @php
                                    $nClass = 'e';
                                    if(in_array($n->nilai_huruf, ['A', 'A-'])) $nClass = 'a';
                                    elseif(in_array($n->nilai_huruf, ['B+', 'B', 'B-'])) $nClass = 'b';
                                    elseif(in_array($n->nilai_huruf, ['C+', 'C'])) $nClass = 'c';
                                    elseif(in_array($n->nilai_huruf, ['D'])) $nClass = 'd';
                                @endphp
                                <span class="badge-c badge-nilai-{{ $nClass }}">{{ $n->nilai_huruf }}</span>
                            </td>
                            <td>
                                <span class="badge-c badge-{{ $n->status == 'Lulus' ? 'aktif' : ($n->status == 'Mengulang' ? 'cuti' : 'do') }}">
                                    {{ $n->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data nilai semester ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
