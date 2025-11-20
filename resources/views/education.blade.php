@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto" x-data="quizApp()">
    
    <div class="text-center mb-12" x-show="state === 'start' || state === 'result'">
        <span class="inline-block px-4 py-1.5 mb-4 text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-50 rounded-full border border-blue-100">Modul Interaktif</span>
        <h1 class="text-4xl font-black text-slate-900 mb-4">Ujian Literasi Politik</h1>
        <p class="text-lg text-slate-500">Tes wawasan kebangsaan & analisis kritis (50 Soal).</p>
    </div>

    <!-- START -->
    <div x-show="state === 'start'" class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 p-16 text-center relative overflow-hidden hover-scale">
        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner text-6xl">🎓</div>
        <h2 class="text-3xl font-bold text-slate-800 mb-4">Total <span x-text="totalQuestions"></span> Soal</h2>
        <p class="text-slate-500 mb-10">Terdiri dari Pilihan Ganda & Essay Analisis.<br>Siapkan argumen logis Anda.</p>
        <button @click="startQuiz()" class="px-10 py-4 font-bold text-white bg-slate-900 rounded-full hover:bg-blue-600 transition-all hover:shadow-lg">Mulai Ujian</button>
    </div>

    <!-- QUIZ -->
    <div x-show="state === 'quiz'" class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden min-h-[500px]" style="display: none;">
        <!-- Header -->
        <div class="bg-white/90 backdrop-blur px-10 py-6 border-b border-slate-100 flex justify-between items-center">
            <span class="text-xs font-bold text-slate-400 uppercase">Progress</span>
            <span class="text-sm font-bold text-slate-700">Soal <span x-text="currentIdx + 1"></span> / <span x-text="totalQuestions"></span></span>
        </div>
        <div class="w-full h-1.5 bg-slate-100">
            <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500" :style="'width: ' + ((currentIdx + 1) / totalQuestions * 100) + '%'"></div>
        </div>

        <div class="p-10">
            <!-- Badge -->
            <div class="mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold tracking-wide uppercase shadow-sm border"
                      :class="questions[currentIdx]?.type === 'essay' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100'">
                    <span x-text="questions[currentIdx]?.type === 'essay' ? '✍️ Soal Essay (Analisis)' : '📝 Pilihan Ganda'"></span>
                </span>
            </div>

            <!-- Soal -->
            <h3 class="text-xl font-bold text-slate-800 mb-8 leading-relaxed" x-text="questions[currentIdx]?.question_text"></h3>

            <!-- INPUT PILIHAN GANDA -->
            <div x-show="questions[currentIdx]?.type !== 'essay'" class="space-y-3">
                <template x-for="(option, index) in questions[currentIdx]?.options" :key="index">
                    <button @click="selectAnswer(index)" 
                        class="w-full text-left p-5 rounded-2xl border-2 transition-all flex items-center"
                        :class="selectedAnswer === index ? 'border-blue-600 bg-blue-50/50' : 'border-slate-100 hover:border-blue-300'">
                        <span x-text="['A','B','C','D'][index]" class="w-8 h-8 rounded-lg border flex items-center justify-center mr-4 font-bold text-sm" :class="selectedAnswer === index ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-500'"></span>
                        <span x-text="option" class="text-slate-700 font-medium"></span>
                    </button>
                </template>
            </div>

            <!-- INPUT ESSAY -->
            <div x-show="questions[currentIdx]?.type === 'essay'">
                <textarea x-model="essayAnswer" 
                    class="w-full p-5 rounded-2xl border-2 border-slate-200 focus:border-purple-500 focus:ring-0 text-slate-700 leading-relaxed h-40 resize-none" 
                    placeholder="Tulis jawaban analisis Anda di sini... (Gunakan logika yang tepat)"></textarea>
                <p class="text-xs text-slate-400 mt-2 italic">* Sistem akan mendeteksi kata kunci logis dalam jawaban Anda.</p>
            </div>

            <div class="mt-10 flex justify-end">
                <button @click="submitAnswer()" 
                        :disabled="questions[currentIdx]?.type !== 'essay' && selectedAnswer === null || questions[currentIdx]?.type === 'essay' && essayAnswer.length < 5"
                        class="px-8 py-3 font-bold rounded-full text-white bg-slate-900 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    Lanjut
                </button>
            </div>
        </div>
    </div>

    <!-- RESULT -->
    <div x-show="state === 'result'" style="display: none;">
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 p-12 text-center mb-10">
            <p class="text-slate-400 font-bold uppercase text-xs mb-4">Skor Akhir</p>
            <div class="text-8xl font-black text-slate-900 mb-2" x-text="score"></div>
            <div class="inline-block px-6 py-2 bg-slate-50 rounded-full text-sm font-bold text-slate-500 mb-8">
                Total Poin Maksimal: <span x-text="totalQuestions * 10"></span>
            </div>
            <button @click="window.location.reload()" class="px-8 py-3 rounded-full border-2 border-slate-200 font-bold hover:border-blue-600 hover:text-blue-600 transition-all">Ulangi Ujian</button>
        </div>

        <div class="space-y-6">
            <h3 class="font-bold text-xl text-slate-800">Review Jawaban</h3>
            <template x-for="(q, idx) in questions" :key="idx">
                <div class="bg-white rounded-2xl p-8 border shadow-sm mb-4" 
                     :class="isCorrect(idx) ? 'border-emerald-100 bg-emerald-50/10' : 'border-rose-100 bg-rose-50/10'">
                    
                    <div class="flex gap-4">
                        <div class="mt-1">
                            <span x-show="isCorrect(idx)" class="text-emerald-500 text-xl">✅</span>
                            <span x-show="!isCorrect(idx)" class="text-rose-500 text-xl">❌</span>
                        </div>
                        <div class="flex-grow">
                            <p class="font-bold text-slate-800 mb-3"><span x-text="idx + 1"></span>. <span x-text="q.question_text"></span></p>
                            
                            <!-- Review PG -->
                            <div x-show="q.type !== 'essay'" class="text-sm space-y-1 mb-3">
                                <p class="text-slate-500">Jawaban Anda: <span class="font-bold" x-text="q.options[userAnswers[idx]]"></span></p>
                                <p class="text-emerald-600" x-show="!isCorrect(idx)">Kunci: <span class="font-bold" x-text="q.options[q.correct_answer]"></span></p>
                            </div>

                            <!-- Review Essay -->
                            <div x-show="q.type === 'essay'" class="text-sm space-y-2 mb-3">
                                <div class="p-3 bg-white border rounded-lg text-slate-600 italic">"<span x-text="userAnswers[idx]"></span>"</div>
                                <p class="text-emerald-600 text-xs">Kata Kunci Wajib: <span class="font-mono bg-emerald-100 px-1 rounded" x-text="q.keywords.join(', ')"></span></p>
                            </div>

                            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 text-sm text-slate-600">
                                <span class="font-bold text-blue-500 uppercase text-[10px] block mb-1">PENJELASAN</span>
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

            get totalQuestions() { return this.questions ? this.questions.length : 0; },

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
                    // LOGIKA ESSAY: Cek Keyword
                    this.userAnswers.push(this.essayAnswer);
                    let text = this.essayAnswer.toLowerCase();
                    let hits = 0;
                    // Cek berapa keyword yang muncul di jawaban user
                    if (currentQ.keywords) {
                        currentQ.keywords.forEach(key => {
                            if (text.includes(key.toLowerCase())) hits++;
                        });
                        // Jika mengandung minimal 1 keyword, dianggap benar (Logika Sederhana)
                        // Bisa diperketat misal hits > 1
                        if (hits >= 1) point = 10; 
                    }
                    this.essayAnswer = ''; // Reset
                } else {
                    // LOGIKA PG
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
                    // Cek ulang logika keyword untuk display centang/silang
                    let text = this.userAnswers[idx].toLowerCase();
                    let hits = 0;
                    q.keywords.forEach(key => { if (text.includes(key.toLowerCase())) hits++; });
                    return hits >= 1;
                } else {
                    return this.userAnswers[idx] == q.correct_answer;
                }
            },

            // Alias untuk tombol next agar kompatibel kode lama
            nextQuestion() { this.submitAnswer(); }
        }
    }
</script>
@endsection