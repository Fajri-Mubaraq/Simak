@extends('layouts.app')
@section('title','Tambah Mata Kuliah')
@section('page-title') Tambah <span>Mata Kuliah</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <a href="{{ route('matakuliah.index') }}">Mata Kuliah</a><span class="sep">/</span>
    <span class="current">Tambah</span>
@endsection
@section('content')
<div class="card-c" style="max-width:760px;margin:0 auto;">
    <div class="card-header-c">
        <h5><i class="fas fa-book-open me-2"></i>Form Tambah Mata Kuliah</h5>
        <p>Isi semua field yang bertanda * (wajib)</p>
    </div>
    <div class="card-body-c">
        <form action="{{ route('matakuliah.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label-c">Kode MK <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_mk" id="kode_mk_input" value="{{ old('kode_mk') }}"
                           class="form-control-c {{ $errors->has('kode_mk')?'is-invalid':'' }}"
                           placeholder="IF301" maxlength="20">
                    @error('kode_mk')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label-c">Nama Mata Kuliah <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_mk" value="{{ old('nama_mk') }}"
                           class="form-control-c {{ $errors->has('nama_mk')?'is-invalid':'' }}"
                           placeholder="Pemrograman Web 2" maxlength="150">
                    @error('nama_mk')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label-c">SKS <span style="color:#ef4444;">*</span></label>
                    <select name="sks" class="form-select-c {{ $errors->has('sks')?'is-invalid':'' }}">
                        <option value="">-- Pilih --</option>
                        @for($i=1;$i<=6;$i++)
                        <option value="{{ $i }}" {{ old('sks')==$i?'selected':'' }}>{{ $i }} SKS</option>
                        @endfor
                    </select>
                    @error('sks')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label-c">Semester <span style="color:#ef4444;">*</span></label>
                    <select name="semester" class="form-select-c {{ $errors->has('semester')?'is-invalid':'' }}">
                        <option value="">-- Pilih --</option>
                        @for($i=1;$i<=8;$i++)
                        <option value="{{ $i }}" {{ old('semester')==$i?'selected':'' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label-c">Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" class="form-select-c">
                        <option value="Aktif" {{ old('status','Aktif')=='Aktif'?'selected':'' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status')=='Nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Jurusan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="jurusan" id="jurusan_input" value="{{ old('jurusan') }}"
                           class="form-control-c {{ $errors->has('jurusan')?'is-invalid':'' }}"
                           placeholder="Teknik Informatika" list="jurusanList">
                    <datalist id="jurusanList">
                        <option value="Teknik Informatika"><option value="Sistem Informasi"><option value="Manajemen Informatika">
                    </datalist>
                    @error('jurusan')<div class="invalid-msg"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-c">Dosen Pengampu</label>
                    <input type="text" name="dosen" value="{{ old('dosen') }}"
                           class="form-control-c" placeholder="Nama dosen" maxlength="100">
                </div>
                <div class="col-12">
                    <label class="form-label-c">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control-c" rows="3"
                              placeholder="Deskripsi singkat mata kuliah...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-c"><i class="fas fa-floppy-disk"></i> Simpan</button>
                <a href="{{ route('matakuliah.index') }}" class="btn-secondary-c"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jurusanInput = document.getElementById('jurusan_input');
        const kodeMkInput = document.getElementById('kode_mk_input');

        function fetchNextKode() {
            const jurusanVal = jurusanInput.value.trim();
            if (!jurusanVal) return;

            fetch(`{{ route('api.next-kode-mk') }}?jurusan=${encodeURIComponent(jurusanVal)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.next_kode) {
                        kodeMkInput.value = data.next_kode;
                    }
                })
                .catch(err => console.error('Error fetching next Kode MK:', err));
        }

        // Fetch when input loses focus or changes (to catch selection from datalist)
        jurusanInput.addEventListener('change', fetchNextKode);
        jurusanInput.addEventListener('blur', fetchNextKode);
        jurusanInput.addEventListener('input', function() {
            // Auto fetch if they typed or selected exact value from datalist options
            const options = Array.from(document.querySelectorAll('#jurusanList option')).map(o => o.value);
            if (options.includes(jurusanInput.value)) {
                fetchNextKode();
            }
        });

        // Initial fetch if page loaded with prefilled values but no code
        if (jurusanInput.value && !kodeMkInput.value) {
            fetchNextKode();
        }
    });
</script>
@endsection
