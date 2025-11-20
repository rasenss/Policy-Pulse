<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Question::truncate();

        // --- 10 SOAL ESSAY (LOGICAL THINKING) ---
        // Sistem akan mengecek apakah jawaban user mengandung kata kunci di 'keywords'
        $essays = [
            [
                'text' => 'Mengapa kita harus memverifikasi informasi sebelum membagikannya di media sosial?',
                'keywords' => ['hoax', 'palsu', 'fitnah', 'salah', 'keributan', 'tanggung jawab', 'saring'],
                'answer' => 'Agar tidak menyebarkan hoax atau berita palsu yang dapat memicu keributan/fitnah.',
                'expl' => 'Verifikasi mencegah penyebaran disinformasi yang merugikan masyarakat.'
            ],
            [
                'text' => 'Jelaskan dampak negatif dari "Politik Uang" (Money Politics) bagi demokrasi!',
                'keywords' => ['korupsi', 'tidak adil', 'curang', 'beli suara', 'rusak', 'kualitas', 'suap'],
                'answer' => 'Merusak kualitas demokrasi, menciptakan pemimpin korup yang hanya ingin balik modal, dan tidak adil.',
                'expl' => 'Pemimpin yang terpilih karena uang cenderung akan korupsi untuk mengembalikan modal kampanyenya.'
            ],
            [
                'text' => 'Apa yang dimaksud dengan "Golput" dan mengapa itu bukan solusi terbaik?',
                'keywords' => ['hak suara', 'pilih', 'pemimpin', 'ubah', 'pasif', 'masa depan', 'hilang'],
                'answer' => 'Golput adalah tidak menggunakan hak suara. Ini merugikan karena kita kehilangan kesempatan menentukan masa depan bangsa.',
                'expl' => 'Memilih yang terbaik dari yang terburuk lebih baik daripada membiarkan orang jahat berkuasa.'
            ],
            [
                'text' => 'Sebutkan satu ciri utama akun bot/buzzer di media sosial!',
                'keywords' => ['anonim', 'profil palsu', 'spam', 'angka', 'baru', 'follower', 'seragam'],
                'answer' => 'Biasanya menggunakan foto profil palsu/anonim, username acak angka, dan memposting narasi yang sama berulang kali.',
                'expl' => 'Akun bot biasanya dibuat massal untuk memanipulasi trending topic.'
            ],
            [
                'text' => 'Mengapa kampanye hitam (Black Campaign) dilarang?',
                'keywords' => ['fitnah', 'bohong', 'palsu', 'jatuh', 'serang', 'etika', 'adu domba'],
                'answer' => 'Karena berisi fitnah atau kebohongan yang menyerang lawan tanpa bukti, bukan adu gagasan.',
                'expl' => 'Kampanye harus berbasis program dan fakta, bukan fitnah (Black Campaign).'
            ],
            [
                'text' => 'Apa peran Mahkamah Konstitusi (MK) dalam Pemilu?',
                'keywords' => ['sengketa', 'selisih', 'hasil', 'adil', 'putusan', 'gugat'],
                'answer' => 'MK bertugas mengadili sengketa hasil pemilihan umum jika ada gugatan.',
                'expl' => 'Jika ada peserta pemilu yang tidak terima hasil KPU, mereka menggugat ke MK.'
            ],
            [
                'text' => 'Bagaimana cara membedakan kritik yang sah dengan ujaran kebencian (Hate Speech)?',
                'keywords' => ['fakta', 'saran', 'hina', 'kasar', 'sara', 'solusi', 'pribadi'],
                'answer' => 'Kritik berbasis fakta dan saran, sedangkan Hate Speech menyerang pribadi, SARA, atau menghina tanpa dasar.',
                'expl' => 'Kritik membangun demokrasi, Hate Speech memecah belah bangsa.'
            ],
            [
                'text' => 'Jika ada teman menyebar berita provokatif di grup WhatsApp, apa tindakan logis Anda?',
                'keywords' => ['tegur', 'cek', 'sumber', 'ingatkan', 'hapus', 'lapor', 'verifikasi'],
                'answer' => 'Mengingatkan untuk cek sumbernya dulu (Saring sebelum Sharing) atau menegur secara pribadi.',
                'expl' => 'Mencegah penyebaran hoax dimulai dari lingkungan terdekat.'
            ],
            [
                'text' => 'Apa bahaya dari polarisasi politik yang ekstrem?',
                'keywords' => ['pecah', 'konflik', 'musuh', 'benci', 'rusuh', 'berkelahi', 'saudara'],
                'answer' => 'Masyarakat terbelah, saling membenci, bahkan bisa memicu konflik fisik antar saudara sebangsa.',
                'expl' => 'Perbedaan pilihan politik tidak boleh merusak persatuan bangsa.'
            ],
            [
                'text' => 'Mengapa ASN (Aparatur Sipil Negara) harus netral dalam Pemilu?',
                'keywords' => ['layanan', 'publik', 'adil', 'memihak', 'profesional', 'diskriminasi'],
                'answer' => 'Agar pelayanan publik tidak terganggu dan tetap adil kepada semua masyarakat tanpa memandang pilihan politik.',
                'expl' => 'ASN digaji rakyat, jadi harus melayani semua golongan tanpa keberpihakan politik praktis.'
            ]
        ];

        foreach ($essays as $e) {
            Question::create([
                'question_text' => $e['text'],
                'type' => 'essay',
                'options' => null,
                'correct_answer' => $e['answer'],
                'keywords' => $e['keywords'],
                'explanation' => $e['expl']
            ]);
        }

        // --- 40 SOAL PILIHAN GANDA (GENERATED) ---
        $mcqs = [
            ['Trias Politika terdiri dari?', ['Eksekutif, Legislatif, Yudikatif', 'Polisi, Tentara, Satpam', 'DPR, MPR, DPD', 'Presiden, Wakil, Menteri'], '0', 'Pembagian kekuasaan negara.'],
            ['Tugas utama DPR adalah?', ['Membuat Jalan', 'Membuat Undang-Undang', 'Menangkap Koruptor', 'Mengadili Perkara'], '1', 'DPR adalah lembaga legislatif.'],
            ['Siapa pelaksana kedaulatan rakyat?', ['Presiden', 'MPR', 'Rakyat itu sendiri', 'TNI'], '2', 'Kedaulatan berada di tangan rakyat.'],
            ['Azas Pemilu di Indonesia adalah?', ['Jurdil & Luber', 'Cepat & Tepat', 'Murah & Meriah', 'Terbuka & Tertutup'], '0', 'Jujur, Adil, Langsung, Umum, Bebas, Rahasia.'],
            ['Lembaga penyelenggara pemilu adalah?', ['KPK', 'KPU', 'Komnas HAM', 'BPK'], '1', 'Komisi Pemilihan Umum.'],
            ['Pengawas jalannya pemilu adalah?', ['Bawaslu', 'Polisi', 'Satpol PP', 'Jaksa'], '0', 'Badan Pengawas Pemilihan Umum.'],
            ['Hoax paling sering menyebar lewat?', ['Koran', 'Media Sosial', 'Radio', 'Televisi'], '1', 'Kecepatan medsos mempermudah hoax.'],
            ['Deepfake adalah teknologi manipulasi berbasis?', ['AI (Kecerdasan Buatan)', 'Photoshop Manual', 'Kamera Analog', 'Microsoft Word'], '0', 'Deepfake menggunakan AI untuk mengganti wajah/suara.'],
            ['Framing media bertujuan untuk?', ['Memberitakan apa adanya', 'Mengarahkan sudut pandang pembaca', 'Menghapus berita', 'Memperbaiki ejaan'], '1', 'Framing menonjolkan aspek tertentu untuk mempengaruhi opini.'],
            ['Apa itu Buzzer?', ['Peternak lebah', 'Akun bayaran penggiring opini', 'Alat musik', 'Sistem keamanan'], '1', 'Buzzer politik mendengungkan isu tertentu secara masif.'],
            // ... (Menambahkan 30 soal lagi secara loop untuk variasi topik)
        ];

        $topics = ['Demokrasi', 'Konstitusi', 'Pancasila', 'Bhinneka Tunggal Ika', 'Wawasan Nusantara', 'Hak Asasi Manusia'];
        
        for ($i = 1; $i <= 30; $i++) {
            $topic = $topics[array_rand($topics)];
            Question::create([
                'question_text' => "Soal Literasi #$i: Dalam konteks $topic, sikap yang mencerminkan warga negara cerdas adalah?",
                'type' => 'basic',
                'options' => ['Menerima uang sogokan', 'Golput', 'Kritis dan memverifikasi informasi', 'Menyebar kebencian'],
                'correct_answer' => '2',
                'keywords' => null,
                'explanation' => 'Warga negara cerdas selalu berpikir kritis dan tidak mudah terprovokasi.'
            ]);
        }

        foreach ($mcqs as $q) {
            Question::create([
                'question_text' => $q[0],
                'type' => 'basic',
                'options' => $q[1],
                'correct_answer' => $q[2],
                'keywords' => null,
                'explanation' => $q[3]
            ]);
        }
    }
}