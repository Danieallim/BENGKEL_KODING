<x-layout.guest title="Login">
    {{-- Card Utama - Dipaksa Putih Solid --}}
    <div class="card shadow-2xl rounded-2xl w-full max-w-[420px]" style="background-color: white !important; color: #1e2d6b;">
        <div class="card-body p-[40px]">

            {{-- Logo & Title --}}
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo-bengkot.png') }}"
                    class="w-[70px] h-[70px] rounded-[18px] object-cover mx-auto mb-[16px] block shadow-md">

                <h1 class="text-[1.6rem] font-extrabold text-[#1e2d6b] m-0 mb-[4px]">
                    Poliklinik
                </h1>

                <p class="text-[0.85rem] text-slate-500">
                    Masuk ke akun Anda
                </p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div class="form-control mb-4">
                    <label class="label pb-1">
                        <span class="text-[0.82rem] font-bold text-gray-700">Email</span>
                    </label>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-[12px] border border-slate-200 bg-slate-50">
                        <i class="fas fa-envelope text-slate-400"></i>
                        <input type="email" name="email" placeholder="Masukkan email..."
                            class="grow bg-transparent text-slate-800 text-[0.9rem] outline-none" required>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-control mb-6">
                    <label class="label pb-1">
                        <span class="text-[0.82rem] font-bold text-gray-700">Password</span>
                    </label>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-[12px] border border-slate-200 bg-slate-50">
                        <i class="fas fa-lock text-slate-400"></i>
                        <input type="password" name="password" id="password_login" placeholder="Masukkan password..."
                            class="grow bg-transparent text-slate-800 text-[0.9rem] outline-none" required>
                        <i class="fas fa-eye text-slate-400 cursor-pointer" id="toggle_login" onclick="togglePassword('password_login', 'toggle_login')"></i>
                    </div>
                </div>

                {{-- Button --}}
                <button type="submit" 
                    class="w-full py-3 text-white font-bold rounded-xl shadow-lg transition-all hover:opacity-90"
                    style="background: linear-gradient(135deg, #2d4499 0%, #1e2d6b 100%);">
                    <i class="fas fa-right-to-bracket mr-2"></i> Login
                </button>
            </form>

            <p class="text-center mt-6 text-[0.85rem] text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-[#2d4499] font-bold hover:underline">Register</a>
            </p>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
    @endpush
</x-layout.guest>