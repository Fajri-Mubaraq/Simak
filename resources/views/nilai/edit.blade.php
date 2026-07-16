@extends('layouts.app')
@section('title','Edit Nilai')
@section('page-title') Edit <span>Nilai</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('nilai.index') }}">Data Nilai</a><span class="sep">/</span>
    <span class="current">Edit Nilai</span>
@endsection
@section('content')
<div class="card-c" style="max-width:700px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-star-half-stroke me-2"></i>Edit Data Nilai</h5>
        <p>{{ $nilai->mahasiswa->nama ?? '-' }} | {{ $nilai->mataKuliah->nama_mk ?? '-' }}</p>
    </div>
    <div class="card-body-c">
        <form action="{{ route('nilai.update', $nilai) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-c">Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-select-c">
                        @foreach($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ old('mahasiswa_id',$nilai->mahasiswa_id)==$m->id?'selected':'' }}>
                            {{ $m->nim }} - {{ $m->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label-c">Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-select-c">
                        @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id',$nilai->mata_kuliah_id)==$mk->id?'selected':'' }}>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Semester Ambil</label>
                    <input type="text" name="semester_ambil" value="{{ old('semester_ambil',$nilai->semester_ambil) }}"
                           class="form-control-c" list="semList">
                    <datalist id="semList">
                        @php for($y=2020;$y<=date('Y');$y++): @endphp
                        <option value="{{ $y }}/{{ $y+1 }} Ganjil"><option value="{{ $y }}/{{ $y+1 }} Genap">
                        @php endfor; @endphp
                    </datalist>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Nilai Angka (0-100)</label>
                    <input type="number" name="nilai_angka" value="{{ old('nilai_angka',$nilai->nilai_angka) }}"
                           class="form-control-c {{ $errors->has('nilai_angka')?'is-invalid':'' }}"
                           min="0" max="100" step="0.01"
                           oninput="previewNilai(this.value)">
                    @error('nilai_angka')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="col-12" id="nilaiPreview">
                    <div style="background:rgba(99,102,241,.06);border:2px dashed rgba(99,102,241,.2);border-radius:12px;padding:16px;text-align:center;">
                        <div style="font-size:13px;color:#64748b;margin-bottom:4px;">Nilai Huruf & Bobot (Saat Ini)</div>
                        <span id="previewHuruf" style="font-size:36px;font-weight:800;color:#6366f1;">{{ $nilai->nilai_huruf }}</span>
                        <span id="previewBobot" style="font-size:16px;color:#64748b;margin-left:12px;">(bobot: {{ $nilai->bobot }})</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Status</label>
                    <select name="status" class="form-select-c">
                        @foreach(['Lulus','Tidak Lulus','Mengulang'] as $st)
                        <option value="{{ $st }}" {{ old('status',$nilai->status)==$st?'selected':'' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-c"><i class="fas fa-floppy-disk"></i> Update</button>
                <a href="{{ route('nilai.show', $nilai) }}" class="btn-secondary-c"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
const gradeTable=[{min:85,huruf:'A',bobot:4.00},{min:80,huruf:'A-',bobot:3.75},{min:75,huruf:'B+',bobot:3.50},{min:70,huruf:'B',bobot:3.00},{min:65,huruf:'B-',bobot:2.75},{min:60,huruf:'C+',bobot:2.50},{min:55,huruf:'C',bobot:2.00},{min:50,huruf:'D',bobot:1.00},{min:0,huruf:'E',bobot:0.00}];
function previewNilai(val){const v=parseFloat(val);if(isNaN(v))return;const g=gradeTable.find(x=>v>=x.min);document.getElementById('previewHuruf').textContent=g.huruf;document.getElementById('previewBobot').textContent='(bobot: '+g.bobot.toFixed(2)+')';}
</script>
@endpush
@endsection
