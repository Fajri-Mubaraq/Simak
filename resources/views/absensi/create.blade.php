@extends('layouts.app')
@section('title','Tambah Absensi')
@section('page-title') Tambah <span>Absensi</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('absensi.index') }}">Absensi</a><span class="sep">/</span>
    <span class="current">Tambah</span>
@endsection
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-c">
            <div class="card-header-c">
                <h5><i class="fas fa-plus me-2"></i>Tambah Data Absensi</h5>
                <p>Isi form berikut untuk menambah data absensi mahasiswa</p>
            </div>
            <div class="card-body-c">
                <form action="{{ route('absensi.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        {{-- Filter Angkatan --}}
                        <div class="col-md-4">
                            <label class="form-label-c">Filter Angkatan</label>
                            <select id="filter_angkatan" class="form-select-c">
                                <option value="">-- Semua Angkatan --</option>
                                @php
                                    $distinctAngkatans = $mahasiswas->pluck('angkatan')->unique()->sortDesc();
                                @endphp
                                @foreach($distinctAngkatans as $angkatan)
                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Mahasiswa --}}
                        <div class="col-md-8">
                            <label class="form-label-c">Mahasiswa <span style="color:var(--danger);">*</span></label>
                            <select name="mahasiswa_id" id="mahasiswa_id" class="form-select-c {{ $errors->has('mahasiswa_id')?'is-invalid':'' }}">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}" data-angkatan="{{ $mhs->angkatan }}" {{ old('mahasiswa_id')==$mhs->id?'selected':'' }}>
                                    {{ $mhs->nim }} - {{ $mhs->nama }} (Angkatan {{ $mhs->angkatan }})
                                </option>
                                @endforeach
                            </select>
                            @error('mahasiswa_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Mata Kuliah --}}
                        <div class="col-md-12">
                            <label class="form-label-c">Mata Kuliah <span style="color:var(--danger);">*</span></label>
                            <select name="mata_kuliah_id" class="form-select-c {{ $errors->has('mata_kuliah_id')?'is-invalid':'' }}">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id')==$mk->id?'selected':'' }}>
                                    {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                </option>
                                @endforeach
                            </select>
                            @error('mata_kuliah_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-4">
                            <label class="form-label-c">Tanggal <span style="color:var(--danger);">*</span></label>
                            <input type="date" name="tanggal" class="form-control-c {{ $errors->has('tanggal')?'is-invalid':'' }}"
                                   value="{{ old('tanggal', date('Y-m-d')) }}">
                            @error('tanggal')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Pertemuan Ke --}}
                        <div class="col-md-4">
                            <label class="form-label-c">Pertemuan Ke <span style="color:var(--danger);">*</span></label>
                            <select name="pertemuan_ke" class="form-select-c {{ $errors->has('pertemuan_ke')?'is-invalid':'' }}">
                                @for($p = 1; $p <= 16; $p++)
                                <option value="{{ $p }}" {{ old('pertemuan_ke')==$p?'selected':'' }}>Pertemuan {{ $p }}</option>
                                @endfor
                            </select>
                            @error('pertemuan_ke')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label class="form-label-c">Status Kehadiran <span style="color:var(--danger);">*</span></label>
                            <select name="status" id="status_select" class="form-select-c {{ $errors->has('status')?'is-invalid':'' }}">
                                @foreach(['Hadir','Izin','Sakit','Alpha'] as $st)
                                <option value="{{ $st }}" {{ old('status')==$st?'selected':'' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label-c">Keterangan</label>
                            <textarea name="keterangan" id="keterangan_textarea" class="form-control-c" rows="3"
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-primary-c">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('absensi.index') }}" class="btn-secondary-c">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('filter_angkatan');
        const mahasiswaSelect = document.getElementById('mahasiswa_id');
        const statusSelect = document.getElementById('status_select');
        const keteranganTextarea = document.getElementById('keterangan_textarea');
        
        // Store all original options
        const originalOptions = Array.from(mahasiswaSelect.options).slice(1).map(opt => ({
            value: opt.value,
            text: opt.text,
            angkatan: opt.getAttribute('data-angkatan'),
            selected: opt.selected
        }));

        filterSelect.addEventListener('change', function() {
            const selectedAngkatan = this.value;
            
            // Clear current options except placeholder
            mahasiswaSelect.innerHTML = '<option value="">-- Pilih Mahasiswa --</option>';
            
            // Rebuild dropdown
            originalOptions.forEach(opt => {
                if (selectedAngkatan === "" || opt.angkatan === selectedAngkatan) {
                    const newOpt = document.createElement('option');
                    newOpt.value = opt.value;
                    newOpt.text = opt.text;
                    newOpt.setAttribute('data-angkatan', opt.angkatan);
                    if (opt.selected) {
                        newOpt.selected = true;
                    }
                    mahasiswaSelect.appendChild(newOpt);
                }
            });
        });

        function updateKeteranganState() {
            const val = statusSelect.value;
            if (val === 'Hadir' || val === 'Alpha') {
                keteranganTextarea.value = '';
                keteranganTextarea.disabled = true;
                keteranganTextarea.placeholder = 'Keterangan tidak diperlukan untuk Hadir / Alpha';
            } else {
                keteranganTextarea.disabled = false;
                keteranganTextarea.placeholder = 'Catatan tambahan (opsional)';
            }
        }

        statusSelect.addEventListener('change', updateKeteranganState);
        updateKeteranganState();
    });
</script>

@endsection
