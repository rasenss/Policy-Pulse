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

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        /* Animasi Masuk Halaman */
        .page-animate {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Efek Hover Kartu */
        .hover-scale {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .hover-scale:hover {
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-slate-600 antialiased flex flex-col min-h-screen">

    <nav x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-sm border-b border-slate-200/50' : 'bg-transparent border-b border-transparent'"
         class="fixed top-0 w-full z-50 transition-all duration-500 ease-out">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:rotate-6 transition-transform duration-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-xl font-bold text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">PolicyPulse</span>
                        <span class="block text-[10px] font-bold tracking-widest text-slate-400 uppercase">Riset Politik 2025</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-1 bg-white/50 backdrop-blur-sm p-1.5 rounded-full border border-white/50 shadow-sm ring-1 ring-slate-200/50">
                    <a href="/" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->is('/') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900 hover:bg-white/80' }}">
                        Dashboard
                    </a>
                    <a href="/education" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->is('education') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900 hover:bg-white/80' }}">
                        Modul Edukasi
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-32 page-animate">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-100 mt-auto py-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-900 font-bold text-sm">Rasendriya Khansa Jolankarfyan</p>
            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-50 border border-slate-100">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs text-slate-500 font-mono">Total Data: <span class="font-bold text-slate-800">{{ $totalTweets ?? '...' }}</span> Tweets</span>
            </div>
        </div>
    </footer>
</body>
</html>