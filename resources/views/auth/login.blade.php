<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Smart Asset Management</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900"></div>
    <div class="absolute inset-0 bg-pattern"></div>
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-5xl mx-auto px-4 z-10 flex shadow-2xl rounded-3xl overflow-hidden glass-card">
        
        <!-- Left Side: Branding/Image (Hidden on mobile) -->
        <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-indigo-600 to-purple-700 p-12 flex-col justify-between relative overflow-hidden text-white">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-md border border-white/30 mb-6 shadow-lg">
                    <i class="fas fa-layer-group text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl font-bold font-outfit leading-tight mb-4">Smart<br><span class="text-indigo-200">Asset</span></h1>
                <p class="text-indigo-100 font-medium text-lg leading-relaxed">Sistem Manajemen Inventaris Modern untuk Instansi & Perusahaan.</p>
            </div>
            

        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-7/12 bg-white p-8 md:p-16 flex flex-col justify-center relative">
            <div class="max-w-md w-full mx-auto">
                
                <div class="md:hidden flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-layer-group text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold font-outfit text-slate-800">Smart<span class="text-indigo-600">Asset</span></span>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-slate-800 font-outfit mb-2">Selamat Datang 👋</h2>
                    <p class="text-slate-500">Silakan masuk menggunakan akun Anda untuk mengakses sistem.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-start text-sm">
                        <i class="fas fa-exclamation-circle mt-0.5 mr-3 text-rose-500 text-base"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-bold text-slate-700">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="contoh: admin@sistem.com" 
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-bold text-slate-700 flex justify-between">
                            <span>Kata Sandi</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" name="password" id="password" required placeholder="Masukkan kata sandi Anda" 
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none text-slate-700">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer">
                                Ingat Saya
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex justify-center items-center gap-2 group">
                        Masuk ke Sistem <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
                
                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-400 font-medium">Smart Asset Management &copy; {{ date('Y') }}. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
