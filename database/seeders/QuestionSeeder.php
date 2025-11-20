<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan database dulu agar tidak numpuk
        Question::truncate();

        // ==========================================
        // BAGIAN 1: 10 SOAL ESSAY (LOGICAL THINKING)
        // ==========================================
        $essays = [
            [
                'text' => 'Jelaskan mengapa fenomena "Buzzers" di media sosial dapat membahayakan demokrasi sehat?',
                'keywords' => ['opini', 'manipulasi', 'bayaran', 'palsu', 'giring', 'gaduh', 'objektif'],
                'answer' => 'Buzzer seringkali menggiring opini secara tidak organik/manipulatif demi bayaran, sehingga mengaburkan fakta dan menciptakan kegaduhan semu.',
                'expl' => 'Demokrasi butuh diskusi yang jujur, buzzer merusaknya dengan manipulasi opini berbayar.'
            ],
            [
                'text' => 'Apa perbedaan mendasar antara "Black Campaign" (Kampanye Hitam) dan "Negative Campaign" (Kampanye Negatif)?',
                'keywords' => ['fakta', 'data', 'fitnah', 'bohong', 'palsu', 'bukti', 'hoax'],
                'answer' => 'Kampanye Negatif menyerang lawan menggunakan data/fakta kelemahan mereka, sedangkan Kampanye Hitam menggunakan fitnah/kebohongan tanpa bukti.',
                'expl' => 'Menyerang rekam jejak buruk lawan (Negatif) diperbolehkan, tapi memfitnah (Hitam) adalah pidana.'
            ],
            [
                'text' => 'Mengapa kita dilarang menyebarkan foto/video korban kecelakaan atau bom bunuh diri di media sosial?',
                'keywords' => ['etika', 'trauma', 'takut', 'teror', 'keluarga', 'korban', 'psikologis'],
                'answer' => 'Untuk menghormati privasi keluarga korban, tidak menyebar ketakutan/teror, dan menjaga etika kemanusiaan.',
                'expl' => 'Tujuan teroris adalah menyebar ketakutan. Menyebar foto korban justru membantu tujuan teroris.'
            ],
            [
                'text' => 'Jika Anda menemukan berita dengan judul provokatif yang menyalahkan satu kelompok agama/etnis tertentu, apa yang harus dilakukan?',
                'keywords' => ['cek', 'verifikasi', 'sumber', 'adu domba', 'lapor', 'saring', 'provokasi'],
                'answer' => 'Jangan langsung disebar. Cek sumbernya, baca isi berita lengkapnya, dan verifikasi dengan media lain agar tidak terhasut adu domba.',
                'expl' => 'Judul provokatif seringkali clickbait yang bertujuan memecah belah.'
            ],
            [
                'text' => 'Jelaskan bahaya "Politik Identitas" dalam pemilu di Indonesia!',
                'keywords' => ['pecah', 'konflik', 'sara', 'agama', 'suku', 'benci', 'golongan'],
                'answer' => 'Politik identitas menggunakan SARA untuk mencari dukungan, yang menyebabkan perpecahan masyarakat dan konflik horizontal yang lama sembuh.',
                'expl' => 'Pemilu harusnya adu gagasan, bukan adu sentimen agama atau suku.'
            ],
            [
                'text' => 'Mengapa Golput (Golongan Putih) dianggap tidak menyelesaikan masalah dalam memilih pemimpin?',
                'keywords' => ['hak', 'suara', 'hilang', 'terburuk', 'ubah', 'pasif', 'menang'],
                'answer' => 'Karena dengan Golput, kita membiarkan orang lain menentukan nasib kita, dan berpotensi membiarkan kandidat yang buruk menang.',
                'expl' => 'Tidak memilih berarti membuang kesempatan untuk mencegah yang terburuk berkuasa.'
            ],
            [
                'text' => 'Apa fungsi Mahkamah Konstitusi (MK) terkait hasil Pemilu?',
                'keywords' => ['sengketa', 'gugat', 'adil', 'putusan', 'hasil', 'perselisihan'],
                'answer' => 'MK berwenang mengadili dan memutus perselisihan/sengketa tentang hasil pemilihan umum secara final dan mengikat.',
                'expl' => 'MK adalah benteng terakhir keadilan pemilu jika terjadi kecurangan.'
            ],
            [
                'text' => 'Sebutkan ciri-ciri akun palsu (fake account) yang sering menyebar hoax!',
                'keywords' => ['foto', 'profil', 'anonim', 'angka', 'baru', 'follower', 'curian'],
                'answer' => 'Foto profil ambil dari google/kartun, username kombinasi angka acak, akun baru dibuat, dan followers sedikit/bot.',
                'expl' => 'Akun palsu dibuat massal untuk tujuan spamming propaganda.'
            ],
            [
                'text' => 'Mengapa ASN (PNS), TNI, dan Polri harus netral dalam Pemilu?',
                'keywords' => ['layanan', 'publik', 'adil', 'diskriminasi', 'profesional', 'pihak', 'kuasa'],
                'answer' => 'Agar pelayanan kepada masyarakat tidak diskriminatif dan tidak ada penyalahgunaan kekuasaan negara untuk memenangkan calon tertentu.',
                'expl' => 'Aparat negara digaji rakyat, bukan oleh partai politik.'
            ],
            [
                'text' => 'Apa dampak jika masyarakat mudah percaya teori konspirasi tanpa bukti?',
                'keywords' => ['sesat', 'bodoh', 'logika', 'fakta', 'ilmu', 'percaya', 'kacau'],
                'answer' => 'Masyarakat menjadi anti-sains, mudah dimanipulasi, mengambil keputusan yang salah (misal menolak vaksin), dan terjadi pembodohan massal.',
                'expl' => 'Teori konspirasi menumpulkan nalar kritis dan menjauhkan dari fakta ilmiah.'
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

        // ==========================================
        // BAGIAN 2: 40 SOAL PILIHAN GANDA (BASIC)
        // ==========================================
        $mcqs = [
            // Politik Dasar (1-10)
            ['Kekuasaan untuk membuat Undang-Undang disebut kekuasaan?', ['Eksekutif', 'Legislatif', 'Yudikatif', 'Federatif'], '1', 'Legislatif (DPR) adalah pembuat undang-undang.'],
            ['Lembaga yang berwenang melantik Presiden dan Wakil Presiden terpilih adalah?', ['DPR', 'MK', 'MPR', 'MA'], '2', 'MPR melantik Presiden/Wapres.'],
            ['Asas Pemilu di Indonesia sering disingkat LUBER JURDIL. Apa arti "Luber"?', ['Luas, Bebas, Rahasia', 'Langsung, Umum, Bebas, Rahasia', 'Lancar, Umum, Bersih', 'Langsung, Urut, Bersih'], '1', 'Langsung, Umum, Bebas, Rahasia.'],
            ['Siapa pemegang kekuasaan tertinggi dalam negara demokrasi?', ['Presiden', 'Tentara', 'Rakyat', 'Pengusaha'], '2', 'Demokrasi artinya pemerintahan dari rakyat, oleh rakyat, untuk rakyat.'],
            ['Hak angket adalah hak DPR untuk?', ['Bertanya', 'Menyelidiki kebijakan pemerintah', 'Menyatakan pendapat', 'Mengubah UUD'], '1', 'Hak Angket adalah hak penyelidikan.'],
            ['Lembaga independen yang bertugas memberantas korupsi di Indonesia adalah?', ['Polri', 'Kejaksaan', 'KPK', 'BPK'], '2', 'Komisi Pemberantasan Korupsi.'],
            ['Amendemen UUD 1945 di Indonesia telah dilakukan sebanyak?', ['1 kali', '2 kali', '3 kali', '4 kali'], '3', 'UUD 1945 diamendemen 4 kali (1999-2002).'],
            ['Sikap menerima hasil pemilu yang sah secara hukum disebut?', ['Apatis', 'Legowo/Demokratis', 'Anarkis', 'Skeptis'], '1', 'Sikap demokratis adalah siap menang dan siap kalah.'],
            ['Trias Politika memisahkan kekuasaan menjadi tiga untuk mencegah?', ['Korupsi', 'Otoriterisme/Penyalahgunaan Kekuasaan', 'Kemiskinan', 'Inflasi'], '1', 'Agar tidak ada kekuasaan mutlak pada satu orang.'],
            ['Masa jabatan Presiden Indonesia adalah 5 tahun dan dapat dipilih kembali maksimal?', ['1 kali', '2 kali', '3 kali', 'Seumur hidup'], '0', 'Maksimal 2 periode (1 kali dipilih kembali).'],

            // Literasi Digital & Hoax (11-20)
            ['Clickbait adalah istilah untuk?', ['Judul berita yang akurat', 'Judul sensasional penjebak pembaca', 'Iklan baris', 'Tombol share'], '1', 'Clickbait menipu pembaca agar mengklik tautan.'],
            ['Deepfake menggunakan teknologi apa untuk memanipulasi video?', ['Photoshop', 'AI (Artificial Intelligence)', 'Kamera Analog', 'CGI Manual'], '1', 'Deepfake menggunakan AI untuk mengganti wajah/suara.'],
            ['Apa itu "Echo Chamber" di media sosial?', ['Ruang gema musik', 'Kondisi hanya mendengar opini yang sama/setuju', 'Grup debat terbuka', 'Fitur komentar'], '1', 'Echo Chamber membuat kita merasa pendapat kita paling benar karena dikelilingi yang sepemikiran.'],
            ['Langkah pertama jika menerima pesan broadcast mencurigakan di WA?', ['Langsung forward ke grup keluarga', 'Hapus WA', 'Cek kebenaran (Saring sebelum Sharing)', 'Marah-marah'], '2', 'Selalu verifikasi sebelum menyebarkan.'],
            ['Phishing adalah kejahatan siber yang bertujuan?', ['Mencuri data pribadi/password', 'Merusak hardware', 'Menghapus foto', 'Membuat viral'], '0', 'Phishing memancing korban menyerahkan data rahasia.'],
            ['Undang-Undang yang mengatur informasi dan transaksi elektronik di Indonesia adalah?', ['KUHP', 'UU ITE', 'UU Pers', 'UU Penyiaran'], '1', 'UU ITE (Informasi dan Transaksi Elektronik).'],
            ['Doxing adalah tindakan berbahaya berupa?', ['Meretas akun bank', 'Menyebarkan data pribadi orang lain ke publik', 'Membuat meme', 'Menulis opini'], '1', 'Doxing melanggar privasi dan membahayakan korban.'],
            ['Apa tujuan utama dari disinformasi?', ['Menghibur', 'Mendidik', 'Sengaja menipu dan mengacaukan', 'Tidak sengaja salah'], '2', 'Disinformasi adalah kebohongan yang disengaja.'],
            ['Akun anonim tanpa identitas jelas di Twitter sering disebut?', ['Selebgram', 'Alter Account', 'Verified Account', 'Official'], '1', 'Akun Alter sering digunakan untuk menyembunyikan identitas asli.'],
            ['Fitur "Report" di media sosial berfungsi untuk?', ['Menyukai postingan', 'Melaporkan konten berbahaya/melanggar', 'Menyimpan konten', 'Membagikan konten'], '1', 'Report membantu platform menghapus konten negatif.'],

            // Wawasan Kebangsaan & Sosial (21-30)
            ['Bhinneka Tunggal Ika memiliki arti?', ['Berbeda-beda tetapi tetap satu', 'Satu untuk semua', 'Bersatu kita teguh', 'Merdeka atau mati'], '0', 'Semboyan persatuan Indonesia.'],
            ['Sikap toleransi berarti?', ['Mengikuti ibadah agama lain', 'Menghormati perbedaan keyakinan orang lain', 'Tidak peduli', 'Mencampuradukkan ajaran'], '1', 'Toleransi adalah menghormati tanpa harus mengikuti.'],
            ['Gotong royong merupakan pengamalan Pancasila sila ke?', ['1', '2', '3', '5'], '2', 'Sila ke-3 (Persatuan) dan ke-5 (Keadilan/Kesejahteraan bersama). Tapi inti gotong royong adalah kebersamaan (Sila 3).'],
            ['Norma yang bersumber dari hati nurani manusia disebut norma?', ['Agama', 'Kesopanan', 'Hukum', 'Kesusilaan'], '3', 'Norma kesusilaan berasal dari hati nurani.'],
            ['Lagu kebangsaan Indonesia Raya diciptakan oleh?', ['Soekarno', 'W.R. Supratman', 'Ismail Marzuki', 'C. Simanjuntak'], '1', 'Wage Rudolf Supratman.'],
            ['Hari Lahir Pancasila diperingati setiap tanggal?', ['17 Agustus', '1 Juni', '1 Oktober', '28 Oktober'], '1', '1 Juni adalah hari lahir Pancasila.'],
            ['Sumpah Pemuda diikrarkan pada tahun?', ['1908', '1928', '1945', '1998'], '1', '28 Oktober 1928.'],
            ['Provinsi termuda di Indonesia (pemekaran Papua) saat ini adalah?', ['Papua Barat', 'Papua Barat Daya', 'Timor Timur', 'Nusa Tenggara'], '1', 'Papua Barat Daya (salah satu yang terbaru).'],
            ['Ibu Kota Negara (IKN) Nusantara terletak di provinsi?', ['Kalimantan Barat', 'Kalimantan Timur', 'Kalimantan Tengah', 'Kalimantan Selatan'], '1', 'Kalimantan Timur (Penajam Paser Utara).'],
            ['Sikap chauvinisme adalah?', ['Cinta tanah air berlebihan merendahkan bangsa lain', 'Cinta produk dalam negeri', 'Benci pemerintah', 'Suka budaya asing'], '0', 'Nasionalisme sempit yang merendahkan bangsa lain.'],

            // Isu Terkini & Analisis (31-40)
            ['Istilah "Post-Truth" merujuk pada era dimana?', ['Kebenaran sangat dijunjung', 'Emosi/keyakinan pribadi lebih berpengaruh daripada fakta', 'Semua orang jujur', 'Media massa mati'], '1', 'Di era Post-Truth, fakta kalah dengan emosi.'],
            ['Framing media adalah teknik?', ['Membuat bingkai foto', 'Menonjolkan sudut pandang tertentu dalam berita', 'Menulis berita palsu', 'Menghapus berita'], '1', 'Framing membentuk persepsi publik terhadap suatu isu.'],
            ['Apa itu "Politik Dinasti"?', ['Kekuasaan yang dipegang bergilir', 'Kekuasaan yang diwariskan dalam satu keluarga', 'Politik yang jujur', 'Politik luar negeri'], '1', 'Praktik mewariskan jabatan publik kepada keluarga.'],
            ['Demonstrasi mahasiswa dilindungi undang-undang sebagai bentuk?', ['Kebebasan berpendapat', 'Tindakan makar', 'Kejahatan jalanan', 'Hobi'], '0', 'Demo adalah hak konstitusional warga negara.'],
            ['Apa fungsi pers/media dalam negara demokrasi?', ['Alat propaganda pemerintah', 'Pilar keempat demokrasi (kontrol sosial)', 'Pencari keuntungan semata', 'Hiburan saja'], '1', 'Media berfungsi mengawasi jalannya pemerintahan.'],
            ['Jika pejabat publik melakukan kesalahan, sikap yang tepat adalah?', ['Membiarkan saja', 'Mengkritik secara konstruktif/melapor', 'Menghina fisiknya', 'Membela membabi buta'], '1', 'Kontrol sosial diperlukan agar pejabat amanah.'],
            ['Radikalisme adalah paham yang menginginkan?', ['Perubahan sosial/politik dengan cara kekerasan', 'Kemajuan ekonomi', 'Pendidikan gratis', 'Persatuan bangsa'], '0', 'Radikalisme sering menghalalkan kekerasan.'],
            ['Transparansi anggaran pemerintah bertujuan untuk?', ['Pamer kekayaan', 'Mencegah korupsi', 'Menghabiskan kertas', 'Mempersulit kerja'], '1', 'Agar rakyat tahu uang pajak dipakai untuk apa.'],
            ['Bonus Demografi adalah kondisi dimana?', ['Banyak orang tua', 'Penduduk usia produktif lebih banyak', 'Banyak bayi lahir', 'Penduduk sedikit'], '1', 'Peluang ekonomi jika penduduk usia kerja melimpah.'],
            ['Tujuan utama Otonomi Daerah adalah?', ['Memisahkan diri dari negara', 'Pemerataan pembangunan dan kemandirian daerah', 'Raja-raja kecil di daerah', 'Menambah beban pusat'], '1', 'Agar daerah bisa mengurus rumah tangganya sendiri dan maju.']
        ];

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