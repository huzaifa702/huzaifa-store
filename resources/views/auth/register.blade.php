@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md animate-on-scroll" x-data="registerForm()">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-brand-500 to-accent-500 rounded-2xl flex items-center justify-center shadow-lg mb-4 animate-float">
                <span class="text-white text-3xl font-bold">H</span>
            </div>
            <h1 class="text-3xl font-bold gradient-text">Create Account</h1>
            <p class="text-gray-500 mt-2">Join us and start shopping</p>
        </div>

        <div class="glass-card rounded-3xl shadow-xl p-8 border border-white/5">
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-5" @submit="return validateAll()">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                    <div class="relative">
                        <input type="text" name="name" x-model="name" @input="validateName()" value="{{ old('name') }}" required autofocus
                               placeholder="John Doe"
                               :class="nameError ? 'ring-2 ring-red-500/50 border-red-500/30' : (name.length > 1 ? 'ring-2 ring-emerald-500/50 border-emerald-500/30' : 'border-white/[0.06]')"
                               class="w-full px-4 py-3 bg-dark-800/60 rounded-xl focus:outline-none transition-all text-sm border">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="name.length > 0">
                            <span x-show="!nameError" class="text-emerald-400 text-lg">✓</span>
                            <span x-show="nameError" class="text-red-400 text-lg">✗</span>
                        </div>
                    </div>
                    <p x-show="nameError" x-text="nameError" class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><span>⚠️</span></p>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" x-model="email" @input="validateEmail()" value="{{ old('email') }}" required
                               placeholder="you@example.com"
                               :class="emailError ? 'ring-2 ring-red-500/50 border-red-500/30' : (emailValid ? 'ring-2 ring-emerald-500/50 border-emerald-500/30' : 'border-white/[0.06]')"
                               class="w-full px-4 py-3 bg-dark-800/60 rounded-xl focus:outline-none transition-all text-sm border">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="email.length > 0">
                            <span x-show="emailValid && !emailError" class="text-emerald-400 text-lg">✓</span>
                            <span x-show="emailError" class="text-red-400 text-lg">✗</span>
                        </div>
                    </div>
                    <p x-show="emailError" x-text="emailError" class="text-red-400 text-xs mt-1.5"></p>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Phone <span class="text-gray-600">(optional)</span></label>
                    <div class="relative">
                        <input type="tel" name="phone" x-model="phone" @input="validatePhone()"
                               placeholder="+92 300 1234567"
                               :class="phoneError ? 'ring-2 ring-red-500/50 border-red-500/30' : (phone.length > 5 && !phoneError ? 'ring-2 ring-emerald-500/50 border-emerald-500/30' : 'border-white/[0.06]')"
                               class="w-full px-4 py-3 bg-dark-800/60 rounded-xl focus:outline-none transition-all text-sm border">
                    </div>
                    <p x-show="phoneError" x-text="phoneError" class="text-red-400 text-xs mt-1.5"></p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" x-model="password" @input="validatePassword()" required
                               placeholder="Min 8 characters"
                               :class="passwordError ? 'ring-2 ring-red-500/50 border-red-500/30' : (passwordStrength >= 2 ? 'ring-2 ring-emerald-500/50 border-emerald-500/30' : 'border-white/[0.06]')"
                               class="w-full px-4 py-3 bg-dark-800/60 rounded-xl focus:outline-none transition-all text-sm border pr-12">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition">
                            <span x-text="showPass ? '🙈' : '👁️'"></span>
                        </button>
                    </div>
                    <!-- Password Strength Meter -->
                    <div x-show="password.length > 0" class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <div class="h-1.5 rounded-full flex-1 transition-all duration-300" :class="passwordStrength >= 1 ? (passwordStrength === 1 ? 'bg-red-500' : passwordStrength === 2 ? 'bg-amber-500' : 'bg-emerald-500') : 'bg-dark-700'"></div>
                            <div class="h-1.5 rounded-full flex-1 transition-all duration-300" :class="passwordStrength >= 2 ? (passwordStrength === 2 ? 'bg-amber-500' : 'bg-emerald-500') : 'bg-dark-700'"></div>
                            <div class="h-1.5 rounded-full flex-1 transition-all duration-300" :class="passwordStrength >= 3 ? 'bg-emerald-500' : 'bg-dark-700'"></div>
                        </div>
                        <p class="text-xs" :class="passwordStrength === 1 ? 'text-red-400' : passwordStrength === 2 ? 'text-amber-400' : passwordStrength >= 3 ? 'text-emerald-400' : 'text-gray-600'"
                           x-text="passwordStrength === 0 ? '' : passwordStrength === 1 ? '🔓 Weak — add numbers & symbols' : passwordStrength === 2 ? '🔐 Medium — add more variety' : '🛡️ Strong password!'"></p>
                    </div>
                    <p x-show="passwordError" x-text="passwordError" class="text-red-400 text-xs mt-1"></p>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password_confirmation" x-model="confirmPassword" @input="validateConfirm()" required
                               placeholder="Re-enter your password"
                               :class="confirmError ? 'ring-2 ring-red-500/50 border-red-500/30' : (confirmPassword.length > 0 && !confirmError ? 'ring-2 ring-emerald-500/50 border-emerald-500/30' : 'border-white/[0.06]')"
                               class="w-full px-4 py-3 bg-dark-800/60 rounded-xl focus:outline-none transition-all text-sm border">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="confirmPassword.length > 0">
                            <span x-show="!confirmError && confirmPassword === password" class="text-emerald-400 text-lg">✓</span>
                            <span x-show="confirmError" class="text-red-400 text-lg">✗</span>
                        </div>
                    </div>
                    <p x-show="confirmError" x-text="confirmError" class="text-red-400 text-xs mt-1.5"></p>
                </div>

                <button type="submit"
                        :disabled="!isFormValid()"
                        :class="isFormValid() ? 'from-brand-500 to-accent-500 hover:shadow-2xl hover:shadow-brand-500/30 hover:scale-[1.02]' : 'from-gray-700 to-gray-600 cursor-not-allowed opacity-60'"
                        class="w-full py-3.5 bg-gradient-to-r text-white rounded-xl font-bold transition-all transform text-sm">
                    <span x-show="isFormValid()">Create Account 🎉</span>
                    <span x-show="!isFormValid()">Please fill all fields correctly</span>
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-gray-500">
                Already have an account? <a href="{{ route('login') }}" class="text-brand-400 font-semibold hover:underline">Sign in</a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function registerForm() {
    return {
        name: '{{ old("name", "") }}',
        email: '{{ old("email", "") }}',
        phone: '',
        password: '',
        confirmPassword: '',
        showPass: false,
        nameError: '',
        emailError: '',
        emailValid: false,
        phoneError: '',
        passwordError: '',
        passwordStrength: 0,
        confirmError: '',

        validateName() {
            this.name = this.name.trimStart();
            if (this.name.length === 0) { this.nameError = ''; return; }
            if (this.name.length < 2) { this.nameError = 'Name must be at least 2 characters'; return; }
            if (/\d/.test(this.name)) { this.nameError = 'Name should not contain numbers'; return; }
            if (!/^[a-zA-Z\s.'-]+$/.test(this.name)) { this.nameError = 'Name contains invalid characters'; return; }
            this.nameError = '';
        },

        validateEmail() {
            if (this.email.length === 0) { this.emailError = ''; this.emailValid = false; return; }
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(this.email)) {
                this.emailError = 'Please enter a valid email address';
                this.emailValid = false;
                return;
            }
            const disposable = ['mailinator.com','guerrillamail.com','tempmail.com','throwaway.email','yopmail.com'];
            const domain = this.email.split('@')[1]?.toLowerCase();
            if (disposable.includes(domain)) {
                this.emailError = 'Disposable email addresses are not allowed';
                this.emailValid = false;
                return;
            }
            this.emailError = '';
            this.emailValid = true;
        },

        validatePhone() {
            if (this.phone.length === 0) { this.phoneError = ''; return; }
            const cleaned = this.phone.replace(/[\s\-()]/g, '');
            if (!/^\+?\d{7,15}$/.test(cleaned)) {
                this.phoneError = 'Enter a valid phone number (7-15 digits)';
                return;
            }
            this.phoneError = '';
        },

        validatePassword() {
            if (this.password.length === 0) { this.passwordError = ''; this.passwordStrength = 0; return; }
            if (this.password.length < 8) { this.passwordError = 'Password must be at least 8 characters'; this.passwordStrength = 0; return; }
            this.passwordError = '';
            let strength = 0;
            if (this.password.length >= 8) strength++;
            if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) strength++;
            if (/\d/.test(this.password) && /[^a-zA-Z0-9]/.test(this.password)) strength++;
            this.passwordStrength = strength;
            if (this.confirmPassword.length > 0) this.validateConfirm();
        },

        validateConfirm() {
            if (this.confirmPassword.length === 0) { this.confirmError = ''; return; }
            if (this.confirmPassword !== this.password) {
                this.confirmError = 'Passwords do not match';
                return;
            }
            this.confirmError = '';
        },

        isFormValid() {
            return this.name.length >= 2 && !this.nameError &&
                   this.emailValid && !this.emailError &&
                   this.password.length >= 8 && !this.passwordError &&
                   this.confirmPassword === this.password && !this.confirmError &&
                   !this.phoneError;
        },

        validateAll() {
            this.validateName();
            this.validateEmail();
            this.validatePhone();
            this.validatePassword();
            this.validateConfirm();
            return this.isFormValid();
        }
    };
}
</script>
@endsection
@endsection
