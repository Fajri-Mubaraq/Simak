@extends('layouts.app')

@section('title', 'Edit Mahasiswa')
@section('page-title') Edit <span>Mahasiswa</span> @endsection

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a>
    <span class="sep">/</span>
    <span class="current">Edit: {{ $mahasiswa->nama }}</span>
@endsection

@section('content')

<div class="card-c" style="max-width:760px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-user-pen me-2"></i>Edit Data Mahasiswa</h5>
        <p>NIM: {{ $mahasiswa->nim }} | {{ $mahasiswa->nama }}</p>
    </div>
    <div class="card-body-c">
        <form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-c">NIM <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                           class="form-control-c {{ $errors->has('nim')?'is-invalid':'' }}" maxlength="20">
                    @error('nim')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}"
                           class="form-control-c {{ $errors->has('nama')?'is-invalid':'' }}" maxlength="100">
                    @error('nama')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Jenis Kelamin <span style="color:#ef4444;">*</span></label>
                    <select name="jenis_kelamin" class="form-select-c {{ $errors->has('jenis_kelamin')?'is-invalid':'' }}">
                        <option value="L" {{ old('jenis_kelamin',$mahasiswa->jenis_kelamin)=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin',$mahasiswa->jenis_kelamin)=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Angkatan <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                           class="form-control-c {{ $errors->has('angkatan')?'is-invalid':'' }}"
                           min="2000" max="{{ date('Y') }}">
                    @error('angkatan')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Jurusan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="jurusan" value="{{ old('jurusan', $mahasiswa->jurusan) }}"
                           class="form-control-c {{ $errors->has('jurusan')?'is-invalid':'' }}" list="jurusanList">
                    <datalist id="jurusanList">
                        <option value="Teknik Informatika">
                        <option value="Sistem Informasi">
                        <option value="Manajemen Informatika">
                    </datalist>
                    @error('jurusan')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Program Studi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="program_studi" value="{{ old('program_studi', $mahasiswa->program_studi) }}"
                           class="form-control-c {{ $errors->has('program_studi')?'is-invalid':'' }}">
                    @error('program_studi')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Email</label>
                    <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}"
                           class="form-control-c {{ $errors->has('email')?'is-invalid':'' }}">
                    @error('email')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $mahasiswa->no_telp) }}"
                           class="form-control-c {{ $errors->has('no_telp')?'is-invalid':'' }}" maxlength="20">
                    @error('no_telp')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label-c">Alamat</label>
                    <textarea name="alamat" class="form-control-c" rows="2">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" class="form-select-c {{ $errors->has('status')?'is-invalid':'' }}">
                        @foreach(['Aktif','Cuti','Lulus','DO'] as $st)
                        <option value="{{ $st }}" {{ old('status',$mahasiswa->status)==$st?'selected':'' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-c">
                    <i class="fas fa-floppy-disk"></i> Update Data
                </button>
                <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn-secondary-c">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
