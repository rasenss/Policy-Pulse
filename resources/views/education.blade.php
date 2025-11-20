@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto" x-data="quizApp()">
    
    <div class="text-center mb-12 md:mb-16" x-show="state === 'start' || state === 'result'">
        <span class="inline-block px-4 py-1.5 mb-4 text-xs font-bold tracking-widest text-blue-600 uppercase bg-white rounded-full border border-blue-100 shadow-sm">Modul Interaktif</span>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight leading-tight">Akademi Literasi <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Politik Digital</span></h1>
        <p class="text-base md:text-xl text-slate-500 max-w-2xl mx-auto font-medium leading-relaxed px-4">
            Uji tajamnya analisis kritis Anda melawan Hoax, Framing Media, dan Bias Politik.
        </p>
    </div>

    <!-- START SCREEN -->
    <div x-show="state === 'start' && totalQuestions > 0" class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-16 text-center relative overflow-hidden hover-scale">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        
        <div class="w-20 h-20 md:w-32 md:h-32 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
            <span class="text-4xl md:text-6xl">🎓</span>
        </div>
        
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">Tantangan <span x-text="totalQuestions"></span> Soal</h2>
        <p class="text-slate-600 mb-10 text-base md:text-lg">Kombinasi Pengetahuan Dasar & Studi Kasus Analisis. <br class="hidden md:block">Soal diacak otomatis dari bank data.</p>
        
        <button @click="startQuiz()" class="w-full md:w-auto group relative inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-200 bg-slate-900 font-lg rounded-full hover:bg-blue-600 hover:shadow-xl hover:-translate-y-1 focus:outline-none ring-offset-2 focus:ring-2 ring-blue-600">
            <span class="mr-2">Mulai Kuis Sekarang</span>
        </button>
    </div>

    <!-- ERROR SCREEN -->
    <div x-show="totalQuestions === 0" class="bg-rose-50 rounded-[2rem] border border-rose-100 p-8 md:p-12 text-center">
        <div class="text-5xl mb-4">⚠️</div>
        <h2 class="text-2xl font-bold text-rose-900 mb-2">Bank Soal Kosong</h2>
        <p class="text-rose-700 mb-6 text-sm md:text-base">Sistem belum memiliki data soal. Silakan jalankan Seeder di terminal.</p>
        <div class="overflow-x-auto">
            <code class="bg-white px-4 py-2 rounded-lg border border-rose-200 text-rose-500 font-mono text-xs md:text-sm inline-block whitespace-nowrap">
                php artisan db:seed --class=QuestionSeeder
            </code>
        </div>
    </div>

    <!-- QUIZ SCREEN -->
    <div x-show="state === 'quiz'" class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden relative min-h-[500px]" style="display: none;">
        <div class="bg-white/90 backdrop-blur px-6 md:px-10 py-6 border-b border-slate-100 flex justify-between items-center sticky top-0 z-10">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ujian</span>
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-slate-700">Soal <span x-text="currentIdx + 1"></span> <span class="text-slate-300 mx-1">/</span> <span x-text="totalQuestions"></span></span>
            </div>
        </div>
        <div class="w-full h-1.5 bg-slate-100">
            <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-700 ease-out rounded-r-full" :style="'width: ' + ((currentIdx + 1) / totalQuestions * 100) + '%'"></div>
        </div>
        <div class="p-6 md:p-12">
            <div class="mb-6 md:mb-8">
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] md:text-xs font-bold tracking-wide uppercase shadow-sm border transition-colors duration-300"
                      :class="questions[currentIdx]?.type === 'essay' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100'">
                    <span x-text="questions[currentIdx]?.type === 'essay' ? '✍️ Soal Essay (Analisis)' : '📝 Pilihan Ganda'"></span>
                </span>
            </div>
            <h3 class="text-lg md:text-3xl font-bold text-slate-800 mb-8 md:mb-10 leading-snug" x-text="questions[currentIdx]?.question_text"></h3>
            
            <!-- Pilihan Ganda -->
            <div x-show="questions[currentIdx]?.type !== 'essay'" class="space-y-3 md:space-y-4">
                <template x-for="(option, index) in questions[currentIdx]?.options" :key="index">
                    <button @click="selectAnswer(index)" 
                        class="w-full text-left p-4 md:p-6 rounded-2xl border-2 transition-all duration-200 flex items-start md:items-center group relative overflow-hidden"
                        :class="selectedAnswer === index ? 'border-blue-600 bg-blue-50/50 shadow-md transform scale-[1.01]' : 'border-slate-100 hover:border-blue-300 hover:bg-slate-50'">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 rounded-xl border-2 flex items-center justify-center mr-4 text-xs md:text-sm font-bold transition-colors z-10"
                             :class="selectedAnswer === index ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-slate-200 text-slate-400 group-hover:border-blue-400 group-hover:text-blue-600'">
                            <span x-text="['A','B','C','D'][index]"></span>
                        </div>
                        <span class="text-slate-700 font-medium z-10 text-sm md:text-lg pt-1 md:pt-0" x-text="option"></span>
                    </button>
                </template>
            </div>

            <!-- Essay -->
            <div x-show="questions[currentIdx]?.type === 'essay'">
                <textarea x-model="essayAnswer" 
                    class="w-full p-5 rounded-2xl border-2 border-slate-200 focus:border-purple-500 focus:ring-0 text-slate-700 leading-relaxed h-48 resize-none text-sm md:text-base shadow-inner" 
                    placeholder="Tulis jawaban analisis Anda di sini..."></textarea>
                <p class="text-xs text-slate-400 mt-2 italic">* Sistem akan mendeteksi kata kunci logis.</p>
            </div>

            <div class="mt-10 md:mt-12 flex justify-end pt-6 border-t border-slate-50">
                <button @click="submitAnswer()" 
                        :disabled="questions[currentIdx]?.type !== 'essay' && selectedAnswer === null || questions[currentIdx]?.type === 'essay' && essayAnswer.length < 5"
                        class="w-full md:w-auto inline-flex justify-center items-center px-8 py-4 border border-transparent text-base font-bold rounded-full shadow-lg text-white bg-slate-900 hover:bg-blue-600 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:-translate-y-1">
                    <span x-text="currentIdx === totalQuestions - 1 ? 'Selesai & Lihat Hasil' : 'Lanjut Soal Berikutnya'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- RESULT SCREEN -->
    <div x-show="state === 'result'" style="display: none;">
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 p-10 md:p-12 text-center mb-10 relative overflow-hidden hover-scale">
            <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-emerald-400 via-blue-500 to-purple-500"></div>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mb-4">Skor Akhir</p>
            <div class="text-7xl md:text-8xl font-black text-slate-900 mb-2 tracking-tighter" x-text="score"></div>
            <p class="text-slate-500 text-sm mb-8 font-medium">dari total <span x-text="totalQuestions * 10"></span> poin</p>
            <button @click="window.location.reload()" class="w-full md:w-auto px-10 py-4 rounded-full border-2 border-slate-200 text-slate-700 font-bold hover:border-blue-600 hover:text-blue-600 transition-all hover:shadow-lg">Ulangi Ujian</button>
        </div>

        <div class="space-y-6">
            <h3 class="font-bold text-xl text-slate-800 mb-6 px-2">Kunci Jawaban & Pembahasan</h3>
            <template x-for="(q, idx) in questions" :key="idx">
                <div class="bg-white rounded-2xl p-6 md:p-8 border shadow-sm hover:shadow-md transition-all mb-4" 
                     :class="isCorrect(idx) ? 'border-emerald-100 bg-gradient-to-br from-white to-emerald-50/30' : 'border-rose-100 bg-gradient-to-br from-white to-rose-50/30'">
                    <div class="flex gap-4 md:gap-6">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-sm md:text-lg shadow-sm ring-4 ring-white"
                                 :class="isCorrect(idx) ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'">
                                <span x-text="isCorrect(idx) ? '✓' : '✕'"></span>
                            </div>
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="font-bold text-slate-800 text-base md:text-lg mb-4 leading-snug"><span x-text="idx + 1"></span>. <span x-text="q.question_text"></span></p>
                            
                            <div x-show="q.type !== 'essay'" class="text-sm space-y-2 mb-4">
                                <div class="p-3 rounded-xl border bg-white/50">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Jawaban Kamu</span>
                                    <span class="font-semibold text-slate-700" x-text="q.options[userAnswers[idx]]"></span>
                                </div>
                            </div>

                            <div x-show="q.type === 'essay'" class="text-sm space-y-2 mb-4">
                                <div class="p-3 rounded-xl border bg-white/50 italic text-slate-600">
                                    "<span x-text="userAnswers[idx]"></span>"
                                </div>
                                <p class="text-[10px] text-emerald-600 font-mono bg-emerald-50 inline-block px-2 py-1 rounded">Keywords: <span x-text="q.keywords.join(', ')"></span></p>
                            </div>

                            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 text-sm text-slate-600 leading-relaxed">
                                <span class="font-bold text-blue-500 uppercase text-[10px] tracking-widest block mb-1">PENJELASAN</span>
                                <span x-text="q.explanation"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function quizApp() {
        return {
            state: 'start',
            questions: {!! json_encode($questions) !!}, 
            currentIdx: 0,
            selectedAnswer: null,
            essayAnswer: '',
            userAnswers: [],
            score: 0,
            get totalQuestions() { return (this.questions && this.questions.length > 0) ? this.questions.length : 0; },
            startQuiz() {
                if (this.totalQuestions === 0) return;
                this.state = 'quiz';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            selectAnswer(index) { this.selectedAnswer = index; },
            submitAnswer() {
                let currentQ = this.questions[this.currentIdx];
                let point = 0;
                if (currentQ.type === 'essay') {
                    this.userAnswers.push(this.essayAnswer);
                    let text = this.essayAnswer.toLowerCase();
                    let hits = 0;
                    if (currentQ.keywords) {
                        currentQ.keywords.forEach(key => { if (text.includes(key.toLowerCase())) hits++; });
                        if (hits >= 1) point = 10; 
                    }
                    this.essayAnswer = ''; 
                } else {
                    this.userAnswers.push(this.selectedAnswer);
                    if (this.selectedAnswer == currentQ.correct_answer) point = 10;
                    this.selectedAnswer = null;
                }
                this.score += point;
                if (this.currentIdx < this.totalQuestions - 1) {
                    this.currentIdx++;
                } else {
                    this.finishQuiz();
                }
            },
            finishQuiz() {
                this.state = 'result';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            isCorrect(idx) {
                let q = this.questions[idx];
                if (q.type === 'essay') {
                    let text = this.userAnswers[idx].toLowerCase();
                    let hits = 0;
                    q.keywords.forEach(key => { if (text.includes(key.toLowerCase())) hits++; });
                    return hits >= 1;
                } else {
                    return this.userAnswers[idx] == q.correct_answer;
                }
            },
            nextQuestion() { this.submitAnswer(); }
        }
    }
</script>
@endsection