@extends(App\Helpers\LayoutHelper::getUserLayout())

@section('title', 'Profil Saya')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

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
        {{-- Avatar with Camera Icon (Display Only) --}}
        <div class="avatar-wrapper">
            <img class="profile-avatar" 
                 src="https://placehold.co/200x200/7b0000/ffffff?text={{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}" 
                 alt="Foto Profil">
            <div class="avatar-camera-btn">
                <i class="fa-solid fa-camera"></i>
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

/* Avatar Section */
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
}

.avatar-camera-btn {
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
    cursor: default;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.avatar-camera-btn i {
    font-size: 18px;
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
    
    .avatar-camera-btn {
        width: 40px;
        height: 40px;
    }
    
    .avatar-camera-btn i {
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
        width: 120px;
        height: 120px;
    }
    
    .profile-name {
        font-size: 20px;
    }
    
    .profile-role-badge {
        font-size: 12px;
        padding: 5px 15px;
    }
    
    .profile-form {
        margin-top: 25px;
        padding-top: 25px;
    }
}
</style>
@endsection