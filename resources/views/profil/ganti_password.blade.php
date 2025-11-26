@extends(App\Helpers\LayoutHelper::getUserLayout())

@section('title', 'Ganti Password')

@section('content')

<div class="container-fluid py-3">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="password-card">
        <form action="{{ route('ganti.password.post') }}" method="POST" id="passwordForm">
            @csrf

            {{-- PASSWORD LAMA --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                <div class="password-input-wrapper">
                    <input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="Masukkan password lama" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('currentPassword', this)">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- PASSWORD BARU --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                <div class="password-input-wrapper">
                    <input type="password" name="new_password" id="newPassword" class="form-control" placeholder="Masukkan password baru" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('newPassword', this)">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('new_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="password-rules mt-3">
                    <p class="mb-2 fw-semibold">Syarat password:</p>
                    <ul class="password-requirements">
                        <li id="req-length">
                            <span class="req-icon">✗</span>
                            <span>Minimal 8 karakter</span>
                        </li>
                        <li id="req-uppercase">
                            <span class="req-icon">✗</span>
                            <span>Mengandung huruf besar (A–Z)</span>
                        </li>
                        <li id="req-lowercase">
                            <span class="req-icon">✗</span>
                            <span>Mengandung huruf kecil (a–z)</span>
                        </li>
                        <li id="req-number">
                            <span class="req-icon">✗</span>
                            <span>Mengandung angka (0–9)</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                <div class="password-input-wrapper">
                    <input type="password" name="new_password_confirmation" id="confirmPassword" class="form-control" placeholder="Masukkan kembali password baru" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword', this)">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <small id="confirmError" class="text-danger d-none">Password tidak cocok</small>
            </div>

            {{-- CATATAN --}}
            <div class="alert alert-warning small mb-4">
                ⚠️ Setelah password diubah, Anda akan otomatis logout dan harus login kembali menggunakan password baru.
            </div>

            {{-- BUTTON --}}
            <div class="text-end">
                <a href="{{ route('profil.saya') }}" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" id="submitBtn" class="btn btn-primary px-4 ms-2" style="background:#0067ea; border:none;" disabled>
                    Simpan Password Baru
                </button>
            </div>

        </form>
    </div>

</div>

<style>
/* Card styling - SAMA PERSIS DENGAN PROFIL */
.password-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    padding: 40px 30px;
    width: 100%;
    margin: 0;
}

.password-input-wrapper {
    position: relative;
}

.password-input-wrapper .form-control {
    padding-right: 45px;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
    color: #6c757d;
    transition: color 0.3s;
    z-index: 10;
}

.toggle-password:hover {
    color: #495057;
}

.toggle-password .eye-icon {
    display: block;
}

.toggle-password.active .eye-icon path:first-child {
    d: path("M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24");
}

.password-rules {
    background: #f8f9fa;
    border-left: 4px solid #7b0000;
    padding: 15px 20px;
    border-radius: 6px;
}

.password-requirements {
    list-style: none;
    padding: 0;
    margin: 0;
}

.password-requirements li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px 0;
    color: #6c757d;
    font-size: 14px;
    transition: all 0.3s ease;
}

.password-requirements li.valid {
    color: #28a745;
}

.password-requirements li.valid .req-icon {
    color: #28a745;
}

.req-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    font-weight: bold;
    font-size: 16px;
    color: #dc3545;
}

/* Form Styling - SAMA DENGAN PROFIL */
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

.form-control::placeholder {
    color: #94a3b8;
}

.btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary {
    background-color: #e2e8f0;
    border: 1px solid #cbd5e1;
    color: #475569;
}

.btn-secondary:hover {
    background-color: #cbd5e1;
    border-color: #94a3b8;
}

.btn-primary:disabled {
    background-color: #94a3b8 !important;
    cursor: not-allowed;
    opacity: 0.6;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffecb5;
    border-radius: 8px;
    color: #856404;
}

/* Responsive Design - SAMA DENGAN PROFIL */
@media (max-width: 991px) {
    .password-card {
        padding: 30px 20px;
    }
}

@media (max-width: 576px) {
    .password-card {
        padding: 25px 15px;
    }
    
    .btn {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .text-end {
        display: flex;
        flex-direction: column-reverse;
        gap: 10px;
    }
    
    .text-end .btn {
        width: 100%;
        margin: 0 !important;
    }
}
</style>

<script>
// Toggle password visibility
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const isPassword = input.type === 'password';
    
    input.type = isPassword ? 'text' : 'password';
    button.classList.toggle('active');
}

// Validasi password requirements
const newPasswordInput = document.getElementById('newPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');
const submitBtn = document.getElementById('submitBtn');

const requirements = {
    length: { regex: /.{8,}/, element: document.getElementById('req-length') },
    uppercase: { regex: /[A-Z]/, element: document.getElementById('req-uppercase') },
    lowercase: { regex: /[a-z]/, element: document.getElementById('req-lowercase') },
    number: { regex: /[0-9]/, element: document.getElementById('req-number') }
};

function validatePassword() {
    const password = newPasswordInput.value;
    let allValid = true;

    // Check each requirement
    for (let key in requirements) {
        const req = requirements[key];
        const isValid = req.regex.test(password);
        
        if (isValid) {
            req.element.classList.add('valid');
            req.element.querySelector('.req-icon').textContent = '✓';
        } else {
            req.element.classList.remove('valid');
            req.element.querySelector('.req-icon').textContent = '✗';
            allValid = false;
        }
    }

    return allValid;
}

function validateConfirmPassword() {
    const password = newPasswordInput.value;
    const confirm = confirmPasswordInput.value;
    const confirmError = document.getElementById('confirmError');
    
    if (confirm.length > 0) {
        if (password !== confirm) {
            confirmError.classList.remove('d-none');
            return false;
        } else {
            confirmError.classList.add('d-none');
            return true;
        }
    }
    
    confirmError.classList.add('d-none');
    return confirm.length === 0 ? false : true;
}

function updateSubmitButton() {
    const currentPassword = document.getElementById('currentPassword').value;
    const passwordValid = validatePassword();
    const confirmValid = validateConfirmPassword();
    
    if (currentPassword.length > 0 && passwordValid && confirmValid) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Event listeners
newPasswordInput.addEventListener('input', updateSubmitButton);
confirmPasswordInput.addEventListener('input', updateSubmitButton);
document.getElementById('currentPassword').addEventListener('input', updateSubmitButton);
</script>

@endsection