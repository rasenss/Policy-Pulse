<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolicyPulse | Analisis Sentimen 2025</title>
    
    <!-- SETTING FAVICON (Icon Tab Browser) -->
    <link rel="icon" type="image/png" href="{{ asset('img/2.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/2.png') }}">

    <!-- Tailwind CSS & Plugins -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Turbo Drive -->
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
        
        .page-animate {
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hover-scale {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .hover-scale:hover {
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        
        /* Turbo Progress Bar */
        .turbo-progress-bar { height: 3px; background-color: #2563eb; box-shadow: 0 0 10px rgba(37, 99, 235, 0.5); }
    </style>
</head>
<body class="text-slate-600 antialiased flex flex-col min-h-screen selection:bg-blue-100 selection:text-blue-700">

    <!-- NAVBAR -->
    <nav x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-white/80 backdrop-blur-xl shadow-sm border-b border-slate-200/50' : 'bg-transparent border-b border-transparent'"
         class="fixed top-0 w-full z-50 transition-all duration-500 ease-in-out"
         data-turbo-permanent id="navbar">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- BAGIAN LOGO -->
                <a href="/" class="flex items-center gap-4 group cursor-pointer relative z-50">
                    
                    <!-- LOGO UTAMA (1.png) -->
                    <!-- Pastikan file ada di public/img/1.png -->
                    <div class="relative">
                        <img src="{{ asset('img/1.png') }}" 
                             alt="Logo Utama" 
                             class="w-12 h-12 rounded-xl shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-500 object-contain bg-white p-1">
                    </div>
                    
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">PolicyPulse</span>
                            
                            <!-- LOGO KECIL/PENDAMPING (2.png) -->
                            <!-- Pastikan file ada di public/img/2.png -->
                            <img src="{{ asset('img/2.png') }}" 
                                 alt="Laravel Logo" 
                                 class="w-5 h-5 object-contain opacity-80 hover:opacity-100 transition-opacity" 
                                 title="Powered by Laravel">
                        </div>
                        <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Riset Politik 2025</span>
                    </div>
                </a>

                <!-- MENU TENGAH -->
                <div class="hidden md:flex items-center space-x-1 bg-white/60 backdrop-blur-md p-1.5 rounded-full border border-white/50 shadow-sm ring-1 ring-slate-200/50">
                    <a href="/" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->is('/') ? 'bg-slate-900 text-white shadow-md transform scale-105' : 'text-slate-500 hover:text-slate-900 hover:bg-white/80' }}">
                        Dashboard
                    </a>
                    <a href="/education" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->is('education') ? 'bg-slate-900 text-white shadow-md transform scale-105' : 'text-slate-500 hover:text-slate-900 hover:bg-white/80' }}">
                        Modul Edukasi
                    </a>
                </div>

                <!-- STATUS KANAN -->
                <div class="hidden md:block">
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide">System Live</span>
                    </div>
                </div> 
            </div>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-32 page-animate">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-100 mt-auto py-8 relative z-10" data-turbo-permanent id="footer">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-900 font-bold text-sm tracking-wide">Rasendriya Khansa Jolankarfyan</p>
            <div class="mt-3 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-50 border border-slate-100 shadow-sm">
                <span class="text-xs text-slate-500 font-mono">Total Data: <span class="font-bold text-blue-600">{{ $totalTweets ?? '...' }}</span> Tweets</span>
            </div>
        </div>
    </footer>

</body>
</html>