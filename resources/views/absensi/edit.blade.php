@extends('layouts.app')
@section('title','Edit Absensi')
@section('page-title') Edit <span>Absensi</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('absensi.index') }}">Absensi</a><span class="sep">/</span>
    <span class="current">Edit</span>
@endsection
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-c">
            <div class="card-header-c">
                <h5><i class="fas fa-pen me-2"></i>Edit Data Absensi</h5>
                <p>{{ $absensi->mahasiswa->nama ?? '' }} — {{ $absensi->mataKuliah->nama_mk ?? '' }}</p>
            </div>
            <div class="card-body-c">
                <form action="{{ route('absensi.update', $absensi) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        {{-- Mahasiswa --}}
                        <div class="col-md-6">
                            <label class="form-label-c">Mahasiswa <span style="color:var(--danger);">*</span></label>
                            <select name="mahasiswa_id" class="form-select-c {{ $errors->has('mahasiswa_id')?'is-invalid':'' }}">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $absensi->mahasiswa_id)==$mhs->id?'selected':'' }}>
                                    {{ $mhs->nim }} - {{ $mhs->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('mahasiswa_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Mata Kuliah --}}
                        <div class="col-md-6">
                            <label class="form-label-c">Mata Kuliah <span style="color:var(--danger);">*</span></label>
                            <select name="mata_kuliah_id" class="form-select-c {{ $errors->has('mata_kuliah_id')?'is-invalid':'' }}">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $absensi->mata_kuliah_id)==$mk->id?'selected':'' }}>
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
                                   value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}">
                            @error('tanggal')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Pertemuan Ke --}}
                        <div class="col-md-4">
                            <label class="form-label-c">Pertemuan Ke <span style="color:var(--danger);">*</span></label>
                            <select name="pertemuan_ke" class="form-select-c {{ $errors->has('pertemuan_ke')?'is-invalid':'' }}">
                                @for($p = 1; $p <= 16; $p++)
                                <option value="{{ $p }}" {{ old('pertemuan_ke', $absensi->pertemuan_ke)==$p?'selected':'' }}>Pertemuan {{ $p }}</option>
                                @endfor
                            </select>
                            @error('pertemuan_ke')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label class="form-label-c">Status Kehadiran <span style="color:var(--danger);">*</span></label>
                            <select name="status" id="status_select" class="form-select-c {{ $errors->has('status')?'is-invalid':'' }}">
                                @foreach(['Hadir','Izin','Sakit','Alpha'] as $st)
                                <option value="{{ $st }}" {{ old('status', $absensi->status)==$st?'selected':'' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label-c">Keterangan</label>
                            <textarea name="keterangan" id="keterangan_textarea" class="form-control-c" rows="3"
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                            @error('keterangan')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-primary-c">
                            <i class="fas fa-save"></i> Perbarui
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
        const statusSelect = document.getElementById('status_select');
        const keteranganTextarea = document.getElementById('keterangan_textarea');

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
