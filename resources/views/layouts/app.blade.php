<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Asset Management')</title>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS (via CDN to avoid Node.js dependency) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }" class="bg-[#f8fafc] text-slate-800 antialiased selection:bg-indigo-500 selection:text-white flex min-h-screen overflow-x-hidden">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/80 z-30 md:hidden" @click="sidebarOpen = false" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-slate-900 shadow-2xl flex-shrink-0 fixed h-full z-40 transition-transform duration-300 md:translate-x-0 flex flex-col">
        <div class="h-20 flex items-center justify-between md:justify-center border-b border-slate-800/50 px-6">
            <span class="text-2xl font-bold font-outfit tracking-wide flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-layer-group text-white text-sm"></i>
                </div>
                <span class="text-white">Smart<span class="text-indigo-400">Asset</span></span>
            </span>
            <button @click="sidebarOpen = false" class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-4 ml-3">Modul Utama</div>
            
            <a href="{{ route('items.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('items.*') ? 'bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-boxes w-6 {{ request()->routeIs('items.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Dasbor Inventaris</span>
            </a>
            
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-8 ml-3">Siklus Aset</div>
            
            <a href="{{ route('borrowings.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('borrowings.*') ? 'bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-handshake w-6 {{ request()->routeIs('borrowings.*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Peminjaman</span>
            </a>
            <a href="{{ route('maintenance-tickets.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('maintenance-tickets.*') ? 'bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-tools w-6 {{ request()->routeIs('maintenance-tickets.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Perbaikan</span>
            </a>
            <a href="{{ route('relocations.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('relocations.*') ? 'bg-blue-500/10 text-blue-400 rounded-xl border border-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-truck-moving w-6 {{ request()->routeIs('relocations.*') ? 'text-blue-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Pemindahan</span>
            </a>
            <a href="{{ route('disposals.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('disposals.*') ? 'bg-rose-500/10 text-rose-400 rounded-xl border border-rose-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-trash-alt w-6 {{ request()->routeIs('disposals.*') ? 'text-rose-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Penghapusan</span>
            </a>

            @if(Auth::user()->role == 'admin')
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-8 ml-3">Pengaturan Sistem</div>
            
            <a href="{{ route('locations.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('locations.*') ? 'bg-slate-800 text-white rounded-xl' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-map-marker-alt w-6 {{ request()->routeIs('locations.*') ? 'text-slate-300' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Lokasi</span>
            </a>
            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 mb-1 {{ request()->routeIs('users.*') ? 'bg-slate-800 text-white rounded-xl' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl' }} transition-all group">
                <i class="fas fa-users w-6 {{ request()->routeIs('users.*') ? 'text-slate-300' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                <span class="font-medium text-sm">Pengguna</span>
            </a>
            @endif
        </nav>
        

    </aside>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 w-full min-h-screen flex flex-col bg-[#f8fafc] relative transition-all duration-300">
        <!-- Top Header -->
        <header class="h-20 glass-panel sticky top-0 z-20 flex items-center justify-between px-4 md:px-10 border-b border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-xl md:text-2xl font-bold text-slate-800 font-outfit tracking-tight truncate max-w-[150px] sm:max-w-xs md:max-w-none">@yield('header_title', 'Dasbor')</h1>
            </div>
            
            <div class="flex items-center gap-5">
                @php
                    $pending_reports = \App\Models\Report::where('status', 'pending')->count();
                @endphp
                <a href="{{ route('reports.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm relative">
                    <i class="fas fa-bell"></i>
                    @if($pending_reports > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 rounded-full border-2 border-white text-[8px] font-bold text-white flex items-center justify-center">
                        {{ $pending_reports > 9 ? '9+' : $pending_reports }}
                    </span>
                    @endif
                </a>
                
                <div class="h-8 w-px bg-slate-200 mx-1"></div>
                
                <div class="flex items-center gap-3 cursor-pointer group p-1.5 pr-3 rounded-xl hover:bg-white hover:shadow-sm hover:border hover:border-slate-100 transition-all border border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 shadow-inner">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="hidden md:block">
                        <div class="text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</div>
                        <div class="text-xs text-slate-500 font-medium">{{ Auth::user()->email ?? 'admin@sistem.com' }}</div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="ml-3 border-l border-slate-200 pl-4">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors" title="Keluar">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-4 md:p-10 flex-1 w-full max-w-7xl mx-auto">
            @if(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start shadow-sm animate-fade-in-down">
                <i class="fas fa-check-circle mt-1 mr-3 text-emerald-500 text-lg"></i>
                <div>
                    <h4 class="font-bold text-emerald-900">Success!</h4>
                    <p class="text-sm mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-8 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start shadow-sm animate-fade-in-down">
                <i class="fas fa-exclamation-circle mt-1 mr-3 text-rose-500 text-lg"></i>
                <div>
                    <h4 class="font-bold text-rose-900">Action Required</h4>
                    <p class="text-sm mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <div class="animate-fade-in">
                @yield('content')
            </div>
        </div>
    </main>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        .animate-fade-in-down { animation: fadeInDown 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #475569; }
    </style>

    @stack('scripts')
    
    <!-- TomSelect Library -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all searchable selects
            document.querySelectorAll('.searchable-select').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    // ServiceWorker registration successful
                }, function(err) {
                    // ServiceWorker registration failed
                });
            });
        }
    </script>
</body>
</html>
