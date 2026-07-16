@extends('layouts.app')

@section('title', 'Detail Mahasiswa')
@section('page-title') Detail <span>Mahasiswa</span> @endsection

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a>
    <span class="sep">/</span>
    <span class="current">{{ $mahasiswa->nama }}</span>
@endsection

@section('content')

<div class="row g-3">
    {{-- PROFILE CARD --}}
    <div class="col-md-4">
        <div class="card-c">
            <div style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));padding:28px 20px;text-align:center;">
                @if($mahasiswa->foto_profil)
                    <img src="{{ asset($mahasiswa->foto_profil) }}" class="mhs-avatar mx-auto mb-3" style="width:72px; height:72px; object-fit: cover; border-radius: 50%; border: 3px solid rgba(255,255,255,0.2);">
                @else
                    <div class="mhs-avatar mx-auto mb-3" style="width:72px;height:72px;font-size:26px;background:{{ $mahasiswa->avatar_color }};">
                        {{ $mahasiswa->initials }}
                    </div>
                @endif
                <h4 style="color:#fff;font-size:18px;font-weight:700;margin:0;">{{ $mahasiswa->nama }}</h4>
                <p style="color:rgba(255,255,255,.7);font-size:13px;margin:4px 0 12px;">{{ $mahasiswa->nim }}</p>
                @php $sc=['Aktif'=>'aktif','Cuti'=>'cuti','Lulus'=>'lulus','DO'=>'do']; @endphp
                <span class="badge-c badge-{{ $sc[$mahasiswa->status] ?? 'aktif' }}">{{ $mahasiswa->status }}</span>
            </div>
            <div class="card-body-c">
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-building-columns"></i></div>
                    <div>
                        <div class="detail-lbl">Jurusan</div>
                        <div class="detail-val">{{ $mahasiswa->jurusan }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-scroll"></i></div>
                    <div>
                        <div class="detail-lbl">Program Studi</div>
                        <div class="detail-val">{{ $mahasiswa->program_studi }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="detail-lbl">Angkatan</div>
                        <div class="detail-val">{{ $mahasiswa->angkatan }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-venus-mars"></i></div>
                    <div>
                        <div class="detail-lbl">Jenis Kelamin</div>
                        <div class="detail-val">{{ $mahasiswa->jenis_kelamin=='L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                </div>
                @if($mahasiswa->email)
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="detail-lbl">Email</div>
                        <div class="detail-val" style="font-size:13px;word-break:break-all;">{{ $mahasiswa->email }}</div>
                    </div>
                </div>
                @endif
                @if($mahasiswa->no_telp)
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="detail-lbl">No. Telepon</div>
                        <div class="detail-val">{{ $mahasiswa->no_telp }}</div>
                    </div>
                </div>
                @endif
                @if($mahasiswa->alamat)
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-location-dot"></i></div>
                    <div>
                        <div class="detail-lbl">Alamat</div>
                        <div class="detail-val" style="font-size:13px;">{{ $mahasiswa->alamat }}</div>
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn-primary-c" style="flex:1;justify-content:center;">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('mahasiswa.destroy', $mahasiswa) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus data ini? Semua nilai terkait juga akan dihapus.')" style="flex:1;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-secondary-c" style="width:100%;justify-content:center;color:#ef4444;">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- NILAI / TRANSKRIP --}}
    <div class="col-md-8">
        {{-- SUMMARY IPK --}}
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon si-purple"><i class="fas fa-star"></i></div>
                    <div>
                        <p class="stat-num">{{ number_format($ipk, 2) }}</p>
                        <p class="stat-lbl">IPK (rata-rata bobot)</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon si-blue"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <p class="stat-num">{{ $totalSks }}</p>
                        <p class="stat-lbl">Total SKS Ditempuh</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TRANSKRIP --}}
        <div class="tbl-wrapper">
            <div class="tbl-header">
                <h5><i class="fas fa-list-check me-2" style="color:var(--primary);"></i>Transkrip Nilai</h5>
                <a href="{{ route('nilai.create') }}" class="btn-primary-c" style="font-size:13px;padding:7px 14px;">
                    <i class="fas fa-plus"></i> Input Nilai
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Semester</th>
                            <th>Nilai</th>
                            <th>Bobot</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswa->nilais as $nilai)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $nilai->mataKuliah->nama_mk ?? '-' }}</div>
                                <div style="font-size:12px;color:#94a3b8;">{{ $nilai->mataKuliah->kode_mk ?? '' }}</div>
                            </td>
                            <td>{{ $nilai->mataKuliah->sks ?? '-' }} SKS</td>
                            <td style="font-size:13px;">{{ $nilai->semester_ambil }}</td>
                            <td>
                                <div style="font-size:18px;font-weight:800;color:#0f172a;">{{ $nilai->nilai_huruf }}</div>
                                <div style="font-size:12px;color:#94a3b8;">{{ $nilai->nilai_angka }}</div>
                            </td>
                            <td style="font-weight:700;">{{ $nilai->bobot }}</td>
                            <td>
                                @php $sc=['Lulus'=>'lulus-n','Tidak Lulus'=>'gagal','Mengulang'=>'ulang']; @endphp
                                <span class="badge-c badge-{{ $sc[$nilai->status] ?? 'lulus-n' }}">{{ $nilai->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data nilai</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
