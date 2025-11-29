@extends(App\Helpers\LayoutHelper::getUserLayout())

@section('title', 'Profil Saya')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

{{-- Penting: Hapus padding dari parent content di layout.blade.php --}}
<style>
    .content {
        padding: 100px 40px 30px 40px !important; /* Sesuaikan dengan navbar */
    }
</style>

<div class="profile-wrapper">
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- CARD PROFIL (FULL WIDTH LAYOUT) --}}
    <div class="profile-card">
        {{-- Avatar with Edit Icon --}}
        <div class="avatar-wrapper">
            <img id="profileAvatar" class="profile-avatar"
                src="{{ auth()->user()->foto 
                    ? asset('uploads/pengguna/' . auth()->user()->foto) 
                    : 'https://placehold.co/200x200/7b0000/ffffff?text=' . strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}"
                alt="Foto Profil">

            {{-- Edit Icon Button --}}
            <button class="avatar-edit-btn" id="btnShowMenu" title="Edit Foto">
                <i class="fa fa-pencil"></i>
            </button>

            {{-- Popup Menu --}}
            <div class="avatar-menu" id="avatarMenu">
                <label class="avatar-menu-item" id="btnUploadFoto">
                    <i class="fa fa-upload"></i>
                    <span>Upload picture</span>
                    <input type="file" id="inputFoto" accept="image/*" style="display:none;">
                </label>

                @if(auth()->user()->foto)
                    <button class="avatar-menu-item delete" id="btnDeleteFoto">
                        <i class="fa fa-trash"></i>
                        <span>Remove picture</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- User Info --}}
        <h2 class="profile-name">{{ $user->nama_lengkap }}</h2>
        <span class="profile-role-badge">{{ strtoupper($user->role) }}</span>

        {{-- Form Detail --}}
        <div class="profile-form">
            <div class="row g-3">
                {{-- Nama Lengkap --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" value="{{ $user->nama_lengkap }}" readonly>
                </div>

                {{-- Role Pengguna --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role Pengguna</label>
                    <input type="text" class="form-control text-capitalize" value="{{ $user->role }}" readonly>
                </div>

                {{-- Username --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control" value="{{ $user->username_email }}" readonly>
                </div>

                {{-- Bidang --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Bidang</label>
                    <input type="text" class="form-control" value="{{ $user->bidang?->nama ?? '-' }}" readonly>
                </div>

                {{-- No Telepon --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No Telepon</label>
                    <input type="text" class="form-control" value="{{ $user->no_telepon ?? '-' }}" readonly>
                </div>

                {{-- Terdaftar Sejak --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Terdaftar Sejak</label>
                    <input type="text" class="form-control" value="{{ $user->created_at?->format('d F Y') ?? '-' }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cropper --}}
<div class="modal fade" id="modalCropper" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="max-height:60vh; display:flex; justify-content:center; align-items:center; overflow:auto;">
                    <img id="cropperImage" style="max-width:100%; display:block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelCrop" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="doCropUpload" class="btn btn-primary">Crop & Upload</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Confirm Hapus --}}
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <p class="mb-3">Yakin ingin menghapus foto profil?</p>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form id="formDeleteFoto" action="{{ route('profil.hapus.foto') }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Wrapper - Remove all padding */
.profile-wrapper {
    margin: 0;
    padding: 0;
}

/* Alerts inside wrapper */
.profile-wrapper .alert {
    margin-bottom: 15px;
}

/* Profile Card Styling - FULL WIDTH */
.profile-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    padding: 40px 30px;
    width: 100%;
    margin: 0;
}

/* Avatar Section - Center layout */
.avatar-wrapper {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 25px;
}

.profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f5f5f5;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    background: #f3f4f6;
}

/* Edit Button - Overlay on avatar */
.avatar-edit-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background-color: #7b0000;
    border: 3px solid white;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.avatar-edit-btn:hover {
    background-color: #5a0000;
    transform: scale(1.05);
}

.avatar-edit-btn i {
    font-size: 18px;
}

/* Popup Menu */
.avatar-menu {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.25);
    padding: 8px;
    min-width: 200px;
    z-index: 1000;
    display: none;
}

.avatar-menu.show {
    display: block;
    animation: menuFadeIn 0.2s ease;
}

@keyframes menuFadeIn {
    from {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

.avatar-menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: transparent;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.2s ease;
    font-size: 14px;
    color: #333;
}

.avatar-menu-item:hover {
    background: #f5f5f5;
}

.avatar-menu-item i {
    font-size: 16px;
    width: 20px;
    color: #666;
}

.avatar-menu-item.delete {
    color: #dc3545;
}

.avatar-menu-item.delete i {
    color: #dc3545;
}

.avatar-menu-item.delete:hover {
    background: #fff5f5;
}

/* User Info */
.profile-name {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    text-align: center;
    margin-bottom: 12px;
}

.profile-role-badge {
    display: inline-block;
    padding: 6px 20px;
    background-color: #7b0000;
    color: white;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin: 0 auto 40px;
    display: block;
    width: fit-content;
}

/* Profile Form */
.profile-form {
    margin-top: 40px;
    padding-top: 40px;
    border-top: 2px solid #f1f5f9;
}

/* Form Styling */
.form-label {
    color: #475569;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 15px;
    font-size: 14px;
    background-color: #f8fafc;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #7b0000;
    box-shadow: 0 0 0 3px rgba(123, 0, 0, 0.1);
    background-color: white;
    outline: none;
}

.form-control:read-only {
    background-color: #f1f5f9;
    cursor: not-allowed;
}

/* Responsive Design */
@media (max-width: 991px) {
    .profile-card {
        padding: 30px 20px;
    }
    
    .avatar-wrapper {
        width: 150px;
        height: 150px;
    }
    
    .avatar-edit-btn {
        width: 40px;
        height: 40px;
    }
    
    .avatar-edit-btn i {
        font-size: 16px;
    }
    
    .profile-form {
        margin-top: 30px;
        padding-top: 30px;
    }
}

@media (max-width: 576px) {
    .profile-card {
        padding: 25px 15px;
    }
    
    .avatar-wrapper {
        width: 130px;
        height: 130px;
    }
    
    .avatar-edit-btn {
        width: 36px;
        height: 36px;
    }
    
    .avatar-edit-btn i {
        font-size: 14px;
    }
    
    .profile-name {
        font-size: 20px;
    }
    
    .profile-role-badge {
        font-size: 12px;
        padding: 5px 15px;
    }
    
    .avatar-menu {
        min-width: 180px;
    }
    
    .avatar-menu-item {
        padding: 10px 14px;
        font-size: 13px;
    }
    
    .profile-form {
        margin-top: 25px;
        padding-top: 25px;
    }
}
</style>

{{-- Script Cropper & AJAX Upload --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let cropper;
    const inputFoto = document.getElementById('inputFoto');
    const cropperImage = document.getElementById('cropperImage');
    const modalCropper = new bootstrap.Modal(document.getElementById('modalCropper'));
    const modalConfirmDelete = new bootstrap.Modal(document.getElementById('modalConfirmDelete'));
    const avatarMenu = document.getElementById('avatarMenu');
    const btnShowMenu = document.getElementById('btnShowMenu');

    // Toggle popup menu saat klik icon pensil
    btnShowMenu.addEventListener('click', function (e) {
        e.stopPropagation();
        avatarMenu.classList.toggle('show');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
        if (!avatarMenu.contains(e.target) && e.target !== btnShowMenu) {
            avatarMenu.classList.remove('show');
        }
    });

    // Klik delete -> buka modal konfirmasi
    const btnDelete = document.getElementById('btnDeleteFoto');
    if (btnDelete) {
        btnDelete.addEventListener('click', function () {
            avatarMenu.classList.remove('show');
            modalConfirmDelete.show();
        });
    }

    // File dipilih -> tampilkan modal cropper
    inputFoto.addEventListener('change', function (e) {
        if (e.target.files && e.target.files.length > 0) {
            const file = e.target.files[0];

            // Validate basic client-side
            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar.');
                return;
            }

            // Hide menu
            avatarMenu.classList.remove('show');

            const reader = new FileReader();
            reader.onload = function (evt) {
                cropperImage.src = evt.target.result;
                // Show modal then init cropper after modal shown
                modalCropper.show();

                // Destroy existing cropper (if ada)
                document.getElementById('modalCropper').addEventListener('shown.bs.modal', function () {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 2,
                        background: false,
                        minContainerWidth: 300,
                        minContainerHeight: 300,
                    });
                }, {once: true});
            };
            reader.readAsDataURL(file);
        }
    });

    // Tombol Crop & Upload
    document.getElementById('doCropUpload').addEventListener('click', function () {
        if (!cropper) return;

        // Disable button saat upload
        const btnUpload = document.getElementById('doCropUpload');
        btnUpload.disabled = true;
        btnUpload.textContent = 'Mengupload...';

        cropper.getCroppedCanvas({
            width: 600,
            height: 600,
            imageSmoothingQuality: 'high'
        }).toBlob(function (blob) {
            // Create FormData
            const fd = new FormData();
            // Nama file: profile_timestamp.png
            const filename = 'profile_' + Date.now() + '.png';
            fd.append('foto', blob, filename);

            // CSRF token dari meta or blade
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch("{{ route('profil.upload.foto') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: fd
            })
            .then(async res => {
                const contentType = res.headers.get('content-type');
                console.log('Response Status:', res.status);
                console.log('Content-Type:', contentType);
                
                // Cek apakah response JSON
                if (contentType && contentType.includes('application/json')) {
                    return res.json();
                } else {
                    const text = await res.text();
                    console.error('Non-JSON Response:', text);
                    throw new Error('Server tidak mengembalikan JSON. Cek console untuk detail.');
                }
            })
            .then(data => {
                console.log('Response Data:', data); // Debug
                
                if (data.status === 'success') {
                    modalCropper.hide();
                    
                    // Update avatar tanpa reload
                    const avatar = document.getElementById('profileAvatar');
                    avatar.src = data.foto_url + '?t=' + new Date().getTime();
                    
                    // Show success alert (Bootstrap alert)
                    const alertBox = document.createElement('div');
                    alertBox.className = 'alert alert-success alert-dismissible fade show';
                    alertBox.setAttribute('role', 'alert');
                    alertBox.innerHTML = `
                        ${data.message || 'Foto profil berhasil diperbarui.'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.profile-wrapper').prepend(alertBox);
                    
                    // Reload halaman setelah 1 detik untuk update tombol hapus
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // Enable button kembali
                    btnUpload.disabled = false;
                    btnUpload.textContent = 'Crop & Upload';
                    alert(data.message || 'Upload gagal: ' + JSON.stringify(data));
                }
            })
            .catch(err => {
                console.error('Upload Error:', err);
                // Enable button kembali
                btnUpload.disabled = false;
                btnUpload.textContent = 'Crop & Upload';
                alert('Terjadi kesalahan saat upload. Cek console browser (F12) untuk detail error.');
            });
        }, 'image/jpeg', 0.9); // Ubah ke JPEG untuk ukuran lebih kecil
    });

    // Optional: jika modal cropper ditutup -> destroy cropper & clear src
    document.getElementById('modalCropper').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        cropperImage.src = '';
        inputFoto.value = ''; // Reset input
    });
});
</script>
@endsection