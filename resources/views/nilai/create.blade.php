@extends('layouts.app')
@section('title','Input Nilai')
@section('page-title') Input <span>Nilai</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('nilai.index') }}">Data Nilai</a><span class="sep">/</span>
    <span class="current">Input Nilai</span>
@endsection
@section('content')
<div class="card-c" style="max-width:700px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-star-half-stroke me-2"></i>Form Input Nilai</h5>
        <p>Nilai huruf dan bobot dihitung otomatis dari nilai angka</p>
    </div>
    <div class="card-body-c">
        <form action="{{ route('nilai.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-c">Mahasiswa <span style="color:#ef4444;">*</span></label>
                    <select name="mahasiswa_id" class="form-select-c {{ $errors->has('mahasiswa_id')?'is-invalid':'' }}">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ old('mahasiswa_id')==$m->id?'selected':'' }}>
                            {{ $m->nim }} - {{ $m->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label-c">Mata Kuliah <span style="color:#ef4444;">*</span></label>
                    <select name="mata_kuliah_id" class="form-select-c {{ $errors->has('mata_kuliah_id')?'is-invalid':'' }}">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id')==$mk->id?'selected':'' }}>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                        </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Semester Ambil <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="semester_ambil" value="{{ old('semester_ambil') }}"
                           class="form-control-c {{ $errors->has('semester_ambil')?'is-invalid':'' }}"
                           placeholder="2024/2025 Ganjil" list="semesterList">
                    <datalist id="semesterList">
                        @php for($y=2020;$y<=date('Y');$y++): @endphp
                        <option value="{{ $y }}/{{ $y+1 }} Ganjil">
                        <option value="{{ $y }}/{{ $y+1 }} Genap">
                        @php endfor; @endphp
                    </datalist>
                    @error('semester_ambil')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Nilai Angka (0-100) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="nilai_angka" value="{{ old('nilai_angka') }}"
                           class="form-control-c {{ $errors->has('nilai_angka')?'is-invalid':'' }}"
                           placeholder="0-100" min="0" max="100" step="0.01"
                           oninput="previewNilai(this.value)">
                    @error('nilai_angka')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>

                {{-- PREVIEW NILAI HURUF --}}
                <div class="col-12" id="nilaiPreview" style="display:none;">
                    <div style="background:rgba(99,102,241,.06);border:2px dashed rgba(99,102,241,.2);border-radius:12px;padding:16px;text-align:center;">
                        <div style="font-size:13px;color:#64748b;margin-bottom:4px;">Nilai Huruf & Bobot (Otomatis)</div>
                        <span id="previewHuruf" style="font-size:36px;font-weight:800;color:#6366f1;"></span>
                        <span id="previewBobot" style="font-size:16px;color:#64748b;margin-left:12px;"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label-c">Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" class="form-select-c">
                        <option value="Lulus" {{ old('status','Lulus')=='Lulus'?'selected':'' }}>Lulus</option>
                        <option value="Tidak Lulus" {{ old('status')=='Tidak Lulus'?'selected':'' }}>Tidak Lulus</option>
                        <option value="Mengulang" {{ old('status')=='Mengulang'?'selected':'' }}>Mengulang</option>
                    </select>
                </div>
            </div>

            {{-- TABEL KONVERSI --}}
            <div style="margin-top:20px;background:#f8fafc;border-radius:10px;padding:14px;">
                <p style="font-size:12px;font-weight:700;color:#475569;margin:0 0 8px;">📊 Konversi Nilai Otomatis:</p>
                <div style="display:flex;flex-wrap:wrap;gap:6px;font-size:11px;">
                    @foreach([['≥85','A','4.00','#10b981'],['80-84','A-','3.75','#10b981'],['75-79','B+','3.50','#6366f1'],['70-74','B','3.00','#6366f1'],['65-69','B-','2.75','#6366f1'],['60-64','C+','2.50','#f59e0b'],['55-59','C','2.00','#f59e0b'],['50-54','D','1.00','#ef4444'],['<50','E','0.00','#64748b']] as $k)
                    <div style="background:#fff;border-radius:8px;padding:5px 10px;border:1px solid #e2e8f0;">
                        <span style="color:#64748b;">{{ $k[0] }}</span>
                        → <span style="font-weight:700;color:{{ $k[3] }};">{{ $k[1] }}</span>
                        <span style="color:#94a3b8;">({{ $k[2] }})</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-c"><i class="fas fa-floppy-disk"></i> Simpan Nilai</button>
                <a href="{{ route('nilai.index') }}" class="btn-secondary-c"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const gradeTable = [
    {min:85, huruf:'A',  bobot:4.00},
    {min:80, huruf:'A-', bobot:3.75},
    {min:75, huruf:'B+', bobot:3.50},
    {min:70, huruf:'B',  bobot:3.00},
    {min:65, huruf:'B-', bobot:2.75},
    {min:60, huruf:'C+', bobot:2.50},
    {min:55, huruf:'C',  bobot:2.00},
    {min:50, huruf:'D',  bobot:1.00},
    {min:0,  huruf:'E',  bobot:0.00},
];

function previewNilai(val) {
    const v = parseFloat(val);
    if (isNaN(v) || val === '') {
        document.getElementById('nilaiPreview').style.display = 'none';
        return;
    }
    const grade = gradeTable.find(g => v >= g.min);
    document.getElementById('previewHuruf').textContent = grade.huruf;
    document.getElementById('previewBobot').textContent = '(bobot: ' + grade.bobot.toFixed(2) + ')';
    document.getElementById('nilaiPreview').style.display = 'block';
}
</script>
@endpush

@endsection
