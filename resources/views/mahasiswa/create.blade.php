@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')
@section('page-title') Tambah <span>Mahasiswa</span> @endsection

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a>
    <span class="sep">/</span>
    <span class="current">Tambah</span>
@endsection

@section('content')

<div class="card-c" style="max-width:760px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-user-plus me-2"></i>Form Tambah Mahasiswa</h5>
        <p>Isi semua field yang bertanda * (wajib)</p>
    </div>
    <div class="card-body-c">
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-c">NIM <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim') }}"
                           class="form-control-c {{ $errors->has('nim')?'is-invalid':'' }}"
                           placeholder="Contoh: 2024001" maxlength="20">
                    @error('nim')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           class="form-control-c {{ $errors->has('nama')?'is-invalid':'' }}"
                           placeholder="Nama lengkap mahasiswa" maxlength="100">
                    @error('nama')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Jenis Kelamin <span style="color:#ef4444;">*</span></label>
                    <select name="jenis_kelamin" class="form-select-c {{ $errors->has('jenis_kelamin')?'is-invalid':'' }}">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Angkatan <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="angkatan" value="{{ old('angkatan', date('Y')) }}"
                           class="form-control-c {{ $errors->has('angkatan')?'is-invalid':'' }}"
                           min="2000" max="{{ date('Y') }}">
                    @error('angkatan')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Jurusan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="jurusan" value="{{ old('jurusan') }}"
                           class="form-control-c {{ $errors->has('jurusan')?'is-invalid':'' }}"
                           placeholder="Contoh: Teknik Informatika" list="jurusanList">
                    <datalist id="jurusanList">
                        <option value="Teknik Informatika">
                        <option value="Sistem Informasi">
                        <option value="Manajemen Informatika">
                        <option value="Teknik Elektro">
                        <option value="Teknik Sipil">
                    </datalist>
                    @error('jurusan')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Program Studi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="program_studi" value="{{ old('program_studi') }}"
                           class="form-control-c {{ $errors->has('program_studi')?'is-invalid':'' }}"
                           placeholder="Contoh: S1 Teknik Informatika">
                    @error('program_studi')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control-c {{ $errors->has('email')?'is-invalid':'' }}"
                           placeholder="mahasiswa@student.ac.id">
                    @error('email')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                           class="form-control-c {{ $errors->has('no_telp')?'is-invalid':'' }}"
                           placeholder="08xxxxxxxxxx" maxlength="20">
                    @error('no_telp')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label-c">Alamat</label>
                    <textarea name="alamat" class="form-control-c {{ $errors->has('alamat')?'is-invalid':'' }}"
                              placeholder="Alamat lengkap mahasiswa" rows="2">{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" class="form-select-c {{ $errors->has('status')?'is-invalid':'' }}">
                        @foreach(['Aktif','Cuti','Lulus','DO'] as $st)
                        <option value="{{ $st }}" {{ old('status','Aktif')==$st?'selected':'' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-c">
                    <i class="fas fa-floppy-disk"></i> Simpan Data
                </button>
                <a href="{{ route('mahasiswa.index') }}" class="btn-secondary-c">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
