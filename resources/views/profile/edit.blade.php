@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title') Edit <span>Profil</span> @endsection
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
    <span class="current">Edit Profil</span>
@endsection

@section('content')
<!-- Cropper.js Stylesheet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<style>
    /* Custom File Input Styling */
    .file-upload-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        cursor: pointer;
    }
    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }
    .file-upload-button {
        padding: 6px 14px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
        transition: all 0.2s;
        white-space: nowrap;
    }
    .file-upload-wrapper:hover .file-upload-button {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(99, 102, 241, 0.3);
    }
    .file-upload-filename {
        font-size: 13px;
        color: #64748b;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        flex-grow: 1;
    }

    /* Cropper Modal Overlay Styling */
    .cropper-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        padding: 20px;
        animation: fadeIn 0.25s ease;
    }
    .cropper-modal-card {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .cropper-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        text-align: left;
    }
    .cropper-modal-header h5 { margin: 0; font-size: 16px; font-weight: 700; color: #0f172a; }
    .cropper-modal-header p { margin: 4px 0 0; font-size: 12px; color: #64748b; }
    .cropper-modal-body {
        padding: 20px;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .cropper-container-wrapper {
        width: 100%;
        max-height: 380px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
    }
    .cropper-modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    @keyframes fadeIn {
        from { opacity: 0; } to { opacity: 1; }
    }
    @keyframes scaleIn {
        from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-c">
            <div class="card-header-c" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h5><i class="fas fa-user-gear me-2"></i>Pengaturan Profil</h5>
                    <p>Perbarui informasi akun Anda secara berkala</p>
                </div>
                <span class="badge-c" style="background:rgba(255,255,255,0.2);color:#fff;font-size:12px;font-weight:700;">
                    {{ strtoupper($role) }}
                </span>
            </div>
            <div class="card-body-c" style="padding: 24px 30px;">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Hidden Input for Cropped Image data (Base64) --}}
                        <input type="hidden" name="cropped_image" id="cropped_image_input">

                        {{-- Avatar preview & greeting --}}
                        <div class="col-12 text-center mb-2">
                            <div id="avatar_preview_container" style="display:inline-block; position:relative;">
                                @if($profile->foto_profil)
                                    <img id="avatar_preview_img" src="{{ asset($profile->foto_profil) }}" class="mhs-avatar mx-auto mb-2" style="width:68px; height:68px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);">
                                @else
                                    <div id="avatar_preview_initials" class="mhs-avatar mx-auto mb-2" style="width:68px; height:68px; font-size:26px; background:linear-gradient(135deg, var(--primary), var(--primary-dark)); color:#fff; display:flex; align-items:center; justify-content:center; border-radius:50%;">
                                        {{ strtoupper(substr(session('user_name', 'A'), 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h6 style="margin:0;font-weight:700;color:#0f172a;">{{ session('user_name') }}</h6>
                            <p style="margin:2px 0 0;font-size:12px;color:#94a3b8;">
                                @if($role === 'mahasiswa')
                                    NIM: {{ $profile->nim }}
                                @else
                                    NIDN: {{ $profile->nidn }}
                                @endif
                            </p>
                        </div>

                        {{-- Foto Profil File Input --}}
                        <div class="col-12">
                            <label class="form-label-c">Foto Profil</label>
                            <div class="file-upload-wrapper form-control-c" style="padding: 6px 10px; height: auto;">
                                <input type="file" name="foto_profil" id="foto_profil_input" class="file-upload-input" accept="image/*">
                                <div class="file-upload-button">
                                    <i class="fas fa-cloud-arrow-up"></i> Pilih Foto
                                </div>
                                <span class="file-upload-filename" id="file_name_display">Belum ada file dipilih</span>
                            </div>
                            <small style="color:#64748b;font-size:11px;display:block;margin-top:4px;">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                            @error('foto_profil')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- NIM / NIDN (Read-Only) --}}
                        <div class="col-12">
                            <label class="form-label-c">Identifier (NIM / NIDN) <i class="fas fa-lock ms-1" style="font-size:11px;color:#94a3b8;" title="Tidak dapat diubah"></i></label>
                            <input type="text" class="form-control-c" style="background:#f8fafc;color:#64748b;" 
                                   value="{{ $role === 'mahasiswa' ? $profile->nim : $profile->nidn }}" disabled>
                        </div>

                        {{-- Nama --}}
                        <div class="col-12">
                            <label class="form-label-c">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="{{ $role === 'mahasiswa' ? 'nama' : 'name' }}" 
                                   value="{{ old($role === 'mahasiswa' ? 'nama' : 'name', $role === 'mahasiswa' ? $profile->nama : $profile->name) }}"
                                   class="form-control-c {{ $errors->has('nama') || $errors->has('name') ? 'is-invalid' : '' }}" required>
                            @error('nama')<div class="invalid-msg">{{ $message }}</div>@enderror
                            @error('name')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-12">
                            <label class="form-label-c">Alamat Email <span style="color:#ef4444;">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $profile->email) }}"
                                   class="form-control-c {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                            @error('email')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- No Telp (Mahasiswa Only) --}}
                        @if($role === 'mahasiswa')
                        <div class="col-12">
                            <label class="form-label-c">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $profile->no_telp) }}"
                                   class="form-control-c {{ $errors->has('no_telp') ? 'is-invalid' : '' }}">
                            @error('no_telp')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Alamat (Mahasiswa Only) --}}
                        <div class="col-12">
                            <label class="form-label-c">Alamat</label>
                            <textarea name="alamat" class="form-control-c {{ $errors->has('alamat') ? 'is-invalid' : '' }}" rows="2" placeholder="Alamat lengkap...">{{ old('alamat', $profile->alamat) }}</textarea>
                            @error('alamat')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                        @endif

                        <hr style="border-color:#f1f5f9;margin:16px 0 8px;">
                        <p style="font-size:12px;font-weight:600;color:#64748b;margin:0 0 4px;">UBAH PASSWORD (KOSONGKAN JIKA TIDAK INGIN DIUBAH)</p>

                        {{-- Password Baru --}}
                        <div class="col-md-6">
                            <label class="form-label-c">Password Baru</label>
                            <div class="input-group" style="position:relative;">
                                <input type="password" id="password" name="password" 
                                       class="form-control-c {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                       placeholder="Minimal 6 karakter" autocomplete="new-password">
                                <button type="button" onclick="togglePass('password', 'eye1')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;z-index:5;cursor:pointer;">
                                    <i class="fas fa-eye" id="eye1"></i>
                                </button>
                            </div>
                            @error('password')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-md-6">
                            <label class="form-label-c">Konfirmasi Password</label>
                            <div class="input-group" style="position:relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control-c" placeholder="Ulangi password baru">
                                <button type="button" onclick="togglePass('password_confirmation', 'eye2')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;z-index:5;cursor:pointer;">
                                    <i class="fas fa-eye" id="eye2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-primary-c">
                            <i class="fas fa-circle-check"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn-secondary-c">
                            <i class="fas fa-xmark"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cropper Modal Overlay -->
<div id="cropperModal" class="cropper-modal-overlay" style="display:none;">
    <div class="cropper-modal-card">
        <div class="cropper-modal-header">
            <h5><i class="fas fa-crop-simple me-2"></i>Sesuaikan Foto Profil</h5>
            <p>Geser dan atur area foto (Rasio 1:1)</p>
        </div>
        <div class="cropper-modal-body">
            <div class="cropper-container-wrapper">
                <img id="cropperImageSource" src="" style="display:block;">
            </div>
        </div>
        <div class="cropper-modal-footer">
            <button type="button" class="btn-secondary-c" id="btnCancelCrop" style="padding:9px 16px;"><i class="fas fa-xmark"></i> Batal</button>
            <button type="button" class="btn-primary-c" id="btnApplyCrop" style="padding:9px 16px;"><i class="fas fa-check"></i> Potong & Terapkan</button>
        </div>
    </div>
</div>

<!-- Cropper.js Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('foto_profil_input');
        const filenameDisplay = document.getElementById('file_name_display');
        const croppedImageInput = document.getElementById('cropped_image_input');
        const avatarPreviewContainer = document.getElementById('avatar_preview_container');

        const cropperModal = document.getElementById('cropperModal');
        const cropperImgSource = document.getElementById('cropperImageSource');
        const btnCancelCrop = document.getElementById('btnCancelCrop');
        const btnApplyCrop = document.getElementById('btnApplyCrop');

        let cropper = null;

        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                filenameDisplay.textContent = file.name;

                // Load image into FileReader
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Set src of cropper source image
                    cropperImgSource.src = e.target.result;
                    
                    // Show modal
                    cropperModal.style.display = 'flex';

                    // Initialize cropper
                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(cropperImgSource, {
                        aspectRatio: 1, // Force square crop
                        viewMode: 1,    // Restrict crop box to canvas
                        background: false,
                        autoCropArea: 0.8,
                        responsive: true
                    });
                };
                reader.readAsDataURL(file);
            } else {
                filenameDisplay.textContent = 'Belum ada file dipilih';
            }
        });

        // Cancel cropping
        btnCancelCrop.addEventListener('click', function() {
            cropperModal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            fileInput.value = ''; // Reset file input
            filenameDisplay.textContent = 'Belum ada file dipilih';
        });

        // Apply cropping
        btnApplyCrop.addEventListener('click', function() {
            if (cropper) {
                // Get cropped canvas data
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });

                if (canvas) {
                    const croppedBase64 = canvas.toDataURL('image/jpeg');
                    
                    // Store base64 in hidden input
                    croppedImageInput.value = croppedBase64;

                    // Update avatar preview on page instantly
                    avatarPreviewContainer.innerHTML = `<img id="avatar_preview_img" src="${croppedBase64}" class="mhs-avatar mx-auto mb-2" style="width:68px; height:68px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);">`;

                    // Update filename label to indicate ready to upload
                    filenameDisplay.textContent = fileInput.files[0].name + ' (Siap diunggah)';
                }

                // Close and destroy
                cropperModal.style.display = 'none';
                cropper.destroy();
                cropper = null;
            }
        });
    });

    function togglePass(id, eyeId) {
        const input = document.getElementById(id);
        const icon = document.getElementById(eyeId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
</script>
@endsection
