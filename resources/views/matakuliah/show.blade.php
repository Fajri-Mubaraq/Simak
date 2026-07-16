@extends('layouts.app')
@section('title','Detail Mata Kuliah')
@section('page-title') Detail <span>Mata Kuliah</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('matakuliah.index') }}">Mata Kuliah</a><span class="sep">/</span>
    <span class="current">{{ $matakuliah->kode_mk }}</span>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card-c">
            <div style="background:linear-gradient(135deg,var(--secondary),#0284c7);padding:24px 20px;text-align:center;">
                <div style="width:64px;height:64px;background:rgba(255,255,255,.2);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;color:#fff;">
                    <i class="fas fa-book-open"></i>
                </div>
                <h4 style="color:#fff;font-size:18px;font-weight:700;margin:0;">{{ $matakuliah->nama_mk }}</h4>
                <p style="color:rgba(255,255,255,.7);font-size:13px;margin:6px 0 12px;">{{ $matakuliah->kode_mk }}</p>
                <span class="badge-c {{ $matakuliah->status=='Aktif'?'badge-mk-aktif':'badge-mk-off' }}">{{ $matakuliah->status }}</span>
            </div>
            <div class="card-body-c">
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-layer-group"></i></div>
                    <div><div class="detail-lbl">SKS</div><div class="detail-val">{{ $matakuliah->sks }} SKS</div></div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-calendar-days"></i></div>
                    <div><div class="detail-lbl">Semester</div><div class="detail-val">Semester {{ $matakuliah->semester }}</div></div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-building-columns"></i></div>
                    <div><div class="detail-lbl">Jurusan</div><div class="detail-val">{{ $matakuliah->jurusan }}</div></div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-chalkboard-user"></i></div>
                    <div><div class="detail-lbl">Dosen</div><div class="detail-val">{{ $matakuliah->dosen ?? '-' }}</div></div>
                </div>
                @if($matakuliah->deskripsi)
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-align-left"></i></div>
                    <div><div class="detail-lbl">Deskripsi</div><div class="detail-val" style="font-size:13px;font-weight:400;">{{ $matakuliah->deskripsi }}</div></div>
                </div>
                @endif
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('matakuliah.edit', $matakuliah) }}" class="btn-primary-c" style="flex:1;justify-content:center;">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('matakuliah.destroy', $matakuliah) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus mata kuliah ini?')" style="flex:1;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-secondary-c" style="width:100%;justify-content:center;color:#ef4444;">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon si-purple"><i class="fas fa-users"></i></div>
                    <div>
                        <p class="stat-num">{{ $matakuliah->nilais->count() }}</p>
                        <p class="stat-lbl">Mahasiswa Mengambil</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon si-green"><i class="fas fa-chart-bar"></i></div>
                    <div>
                        <p class="stat-num">{{ number_format($rataRata, 1) }}</p>
                        <p class="stat-lbl">Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tbl-wrapper">
            <div class="tbl-header">
                <h5><i class="fas fa-list me-2" style="color:var(--primary);"></i>Mahasiswa yang Mengambil MK Ini</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Mahasiswa</th><th>NIM</th><th>Semester Ambil</th><th>Nilai</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($matakuliah->nilais as $n)
                        <tr>
                            <td style="font-weight:600;">{{ $n->mahasiswa->nama ?? '-' }}</td>
                            <td><code style="background:#f1f5f9;padding:3px 7px;border-radius:6px;font-size:12px;">{{ $n->mahasiswa->nim ?? '-' }}</code></td>
                            <td style="font-size:13px;">{{ $n->semester_ambil }}</td>
                            <td>
                                <span style="font-size:18px;font-weight:800;">{{ $n->nilai_huruf }}</span>
                                <span style="font-size:12px;color:#94a3b8;margin-left:4px;">({{ $n->nilai_angka }})</span>
                            </td>
                            <td>
                                @php $sc=['Lulus'=>'lulus-n','Tidak Lulus'=>'gagal','Mengulang'=>'ulang']; @endphp
                                <span class="badge-c badge-{{ $sc[$n->status]??'lulus-n' }}">{{ $n->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada mahasiswa yang mengambil MK ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
