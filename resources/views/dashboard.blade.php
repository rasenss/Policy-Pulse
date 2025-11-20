@extends('layout')

@section('content')
<div class="mb-12 text-center max-w-3xl mx-auto">
    <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider text-blue-600 uppercase bg-blue-50 rounded-full border border-blue-100">Analisis Sentimen</span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4 tracking-tight">Monitor Kebijakan Publik</h1>
    <p class="text-lg text-slate-500 leading-relaxed">Memantau respon masyarakat terhadap 5 kebijakan strategis pemerintah (Januari - Juni 2025).</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
    @foreach($policies as $policy)
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full hover-lift transition-all duration-300">
        
        <!-- Info -->
        <div class="p-7 border-b border-slate-50 bg-gradient-to-b from-white to-slate-50/50">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-white text-slate-600 uppercase tracking-wider border border-slate-200 shadow-sm">{{ $policy->category }}</span>
                <span class="text-[11px] font-mono font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($policy->date_issued)->translatedFormat('d M Y') }}</span>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-3 leading-snug">{{ $policy->title }}</h2>
            <p class="text-sm text-slate-500 leading-relaxed mb-4">{{ $policy->description }}</p>
            @if($policy->source_link)
            <a href="{{ $policy->source_link }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">Baca Berita Lengkap &rarr;</a>
            @endif
        </div>

        <!-- Chart -->
        <div class="p-6 flex justify-center items-center bg-white relative">
            <div class="h-48 w-48 relative">
                <canvas id="chart-{{ $policy->id }}"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none opacity-80">
                   <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Total</span>
                   <span class="text-2xl font-black text-slate-800">{{ $policy->positive_count + $policy->negative_count + $policy->neutral_count }}</span>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-3 divide-x divide-slate-100 border-y border-slate-100 bg-slate-50/50">
            <div class="py-4 text-center"><span class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Positif</span><span class="block text-xl font-black text-emerald-500">{{ $policy->positive_count }}</span></div>
            <div class="py-4 text-center"><span class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Negatif</span><span class="block text-xl font-black text-rose-500">{{ $policy->negative_count }}</span></div>
            <div class="py-4 text-center"><span class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Netral</span><span class="block text-xl font-black text-slate-500">{{ $policy->neutral_count }}</span></div>
        </div>

        <!-- Komentar (Scroll Panjang) -->
        <div class="flex-grow bg-slate-50 p-4">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Suara Netizen</h3>
                <span class="text-[10px] text-slate-400">Scroll untuk semua</span>
            </div>
            
            <!-- Tinggi 500px agar muat banyak -->
            <div class="h-[500px] overflow-y-auto pr-2 space-y-3">
                @forelse($policy->tweets as $tweet)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-sm hover:border-blue-200 transition-all">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-bold text-slate-900 text-xs block">{{ '@' . $tweet->author_username }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($tweet->posted_at)->diffForHumans() }}</span>
                        </div>
                        <span class="px-2 py-1 rounded-md text-[10px] font-bold border tracking-wide {{ $tweet->sentiment_label == 'negative' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($tweet->sentiment_label == 'positive' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200') }}">
                            {{ strtoupper($tweet->sentiment_label) }}
                        </span>
                    </div>
                    <!-- Full Text tanpa truncate -->
                    <div class="text-slate-600 text-xs leading-relaxed break-words border-l-2 pl-3 {{ $tweet->sentiment_label == 'negative' ? 'border-rose-100' : ($tweet->sentiment_label == 'positive' ? 'border-emerald-100' : 'border-slate-100') }}">
                        {{ $tweet->content }}
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 text-xs py-10">Belum ada data tweet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('chart-{{ $policy->id }}'), {
            type: 'doughnut',
            data: {
                labels: ['Positif', 'Negatif', 'Netral'],
                datasets: [{
                    data: [{{ $policy->positive_count }}, {{ $policy->negative_count }}, {{ $policy->neutral_count }}],
                    backgroundColor: ['#10b981', '#f43f5e', '#94a3b8'], 
                    borderWidth: 0, hoverOffset: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '75%' }
        });
    </script>
    @endforeach
</div>
@endsection