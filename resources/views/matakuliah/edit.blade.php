@extends('layouts.app')
@section('title','Edit Mata Kuliah')
@section('page-title') Edit <span>Mata Kuliah</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('matakuliah.index') }}">Mata Kuliah</a><span class="sep">/</span>
    <span class="current">Edit: {{ $matakuliah->kode_mk }}</span>
@endsection
@section('content')
<div class="card-c" style="max-width:760px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-book-open me-2"></i>Edit Mata Kuliah</h5>
        <p>{{ $matakuliah->kode_mk }} | {{ $matakuliah->nama_mk }}</p>
    </div>
    <div class="card-body-c">
        <form action="{{ route('matakuliah.update', $matakuliah) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label-c">Kode MK <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_mk" value="{{ old('kode_mk',$matakuliah->kode_mk) }}"
                           class="form-control-c {{ $errors->has('kode_mk')?'is-invalid':'' }}" maxlength="20">
                    @error('kode_mk')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label-c">Nama Mata Kuliah <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_mk" value="{{ old('nama_mk',$matakuliah->nama_mk) }}"
                           class="form-control-c {{ $errors->has('nama_mk')?'is-invalid':'' }}" maxlength="150">
                    @error('nama_mk')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label-c">SKS <span style="color:#ef4444;">*</span></label>
                    <select name="sks" class="form-select-c">
                        @for($i=1;$i<=6;$i++)
                        <option value="{{ $i }}" {{ old('sks',$matakuliah->sks)==$i?'selected':'' }}>{{ $i }} SKS</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-c">Semester <span style="color:#ef4444;">*</span></label>
                    <select name="semester" class="form-select-c">
                        @for($i=1;$i<=8;$i++)
                        <option value="{{ $i }}" {{ old('semester',$matakuliah->semester)==$i?'selected':'' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-c">Status</label>
                    <select name="status" class="form-select-c">
                        <option value="Aktif" {{ old('status',$matakuliah->status)=='Aktif'?'selected':'' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status',$matakuliah->status)=='Nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Jurusan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="jurusan" value="{{ old('jurusan',$matakuliah->jurusan) }}"
                           class="form-control-c" list="jurusanList">
                    <datalist id="jurusanList">
                        <option value="Teknik Informatika"><option value="Sistem Informasi"><option value="Manajemen Informatika">
                    </datalist>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Dosen Pengampu</label>
                    <input type="text" name="dosen" value="{{ old('dosen',$matakuliah->dosen) }}"
                           class="form-control-c" maxlength="100">
                </div>
                <div class="col-12">
                    <label class="form-label-c">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control-c" rows="3">{{ old('deskripsi',$matakuliah->deskripsi) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-c"><i class="fas fa-floppy-disk"></i> Update</button>
                <a href="{{ route('matakuliah.show', $matakuliah) }}" class="btn-secondary-c"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
