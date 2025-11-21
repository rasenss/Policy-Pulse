<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolicyPulse | Analisis Sentimen 2025</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script type="module">
        import hotwiredTurbo from 'https://cdn.skypack.dev/@hotwired/turbo';
    </script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        .page-animate { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .turbo-progress-bar { height: 3px; background-color: #2563eb; box-shadow: 0 0 10px rgba(37, 99, 235, 0.5); }
        
        .hover-scale { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .hover-scale:hover { transform: scale(1.02) translateY(-2px); box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1); }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-slate-600 antialiased flex flex-col min-h-screen selection:bg-blue-100 selection:text-blue-700">
    
    <!-- NAVBAR (Updated dengan Mobile Menu) -->
    <nav x-data="{ scrolled: false, mobileOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-white/90 backdrop-blur-xl shadow-sm border-b border-slate-200/50' : 'bg-transparent border-b border-transparent'"
         class="fixed top-0 w-full z-50 transition-all duration-500 ease-in-out"
         data-turbo-permanent id="navbar">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo -->
                <a href="/2.png" class="flex items-center gap-3 group cursor-pointer relative z-50">
                    <img src="{{ asset('/1.png') }}" alt="Logo PolicyPulse" class="w-10 h-10 object-contain group-hover:scale-110 transition-transform duration-300">
                    <div>
                        <span class="block text-xl font-bold text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">PolicyPulse</span>
                        <span class="block text-[10px] font-bold tracking-widest text-slate-400 uppercase">Riset Politik 2025</span>
                    </div>
                </a>

                <!-- MENU DESKTOP (Hidden on Mobile) -->
                <div class="hidden md:flex items-center space-x-1 bg-white/60 backdrop-blur-md p-1.5 rounded-full border border-white/50 shadow-sm ring-1 ring-slate-200/50">
                    <a href="/" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->is('/') ? 'bg-slate-900 text-white shadow-md transform scale-105' : 'text-slate-500 hover:text-slate-900 hover:bg-white/80' }}">
                        Dashboard
                    </a>
                    <a href="/education" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->is('education') ? 'bg-slate-900 text-white shadow-md transform scale-105' : 'text-slate-500 hover:text-slate-900 hover:bg-white/80' }}">
                        Modul Edukasi
                    </a>
                </div>

                <!-- TOMBOL HAMBURGER (Mobile Only) -->
                <div class="md:hidden flex items-center relative z-50">
                    <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <svg x-show="!mobileOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU DROPDOWN -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-5"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-5"
             class="absolute top-20 left-0 w-full bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-lg md:hidden z-40 pb-6 pt-2">
            
            <div class="flex flex-col px-4 space-y-2">
                <a href="/" class="px-4 py-3 rounded-xl font-bold text-base {{ request()->is('/') ? 'bg-slate-100 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    📊 Dashboard Data
                </a>
                <a href="/education" class="px-4 py-3 rounded-xl font-bold text-base {{ request()->is('education') ? 'bg-slate-100 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    🎓 Modul Edukasi
                </a>
            </div>
            
            <div class="mt-4 px-4">
                <div class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 border border-emerald-100">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide">System Live</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-32 page-animate">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-100 mt-auto py-8 relative z-10" data-turbo-permanent id="footer">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-900 font-bold text-sm">Rasendriya Khansa Jolankarfyan</p>
            <div class="mt-3 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-50 border border-slate-100 shadow-sm">
                <span class="text-xs text-slate-500 font-mono">Total Data: <span class="font-bold text-blue-600">{{ $totalTweets ?? '...' }}</span> Tweets</span>
            </div>
        </div>
    </footer>
</body>
</html>