<?php

namespace App\Services;

use Sastrawi\Stemmer\StemmerFactory;

class SentimentService
{
    protected $stemmer;

    // 1. BLACKLIST SPAM (Judol, Porno, Iklan, Link Aneh)
    // Jika tweet mengandung ini, langsung BUANG.
    protected $spamKeywords = [
        // Judol & Slot
        'gacor', 'slot', 'zeus', 'maxwin', 'pragmatic', 'hoki', 'depo', 'wd', 
        'rtp', 'link bio', 'link di bio', 'situs', 'judi', 'bola88', 'bet', 
        'scatter', 'bonanza', 'petir', 'jackpot', 'pola', 'receh',
        
        // Konten 18+ / Porno
        '18+', 'bokep', 'video syur', 'link pemersatu', 'vcs', 'open bo', 
        'video hot', 'video viral', 'full durasi', 'desah', 'montok', 'bugil', 
        'colmek', 'kakek merah', 'lendir', 'wikwik', 'sange', 'crot',
        
        // Iklan Sampah
        'peninggi', 'pelangsing', 'penggemuk', 'obat kuat', 'paid4link', 
        'shopee', 'tokopedia', 'lazada', 'cek keranjang', 'racun shopee',
        'affiliate', 'giveaway', 'followers', 'jual', 'promo',
        
        // Link Mencurigakan (Shortlink aneh)
        'bit.ly', 't.me', 'wa.me', 'shorturl', 'paid4', 'linktre'
    ];
    
    protected $slang = [
        'yg' => 'yang', 'ga' => 'tidak', 'gak' => 'tidak', 'engga' => 'tidak', 
        'bgt' => 'sangat', 'sdh' => 'sudah', 'udh' => 'sudah', 'dgn' => 'dengan', 
        'tdk' => 'tidak', 'blm' => 'belum', 'tau' => 'tahu', 'klo' => 'kalau', 
        'krn' => 'karena', 'tp' => 'tapi', 'utk' => 'untuk', 'jd' => 'jadi', 
        'skrg' => 'sekarang', 'sm' => 'sama', 'nggak' => 'tidak', 'tak' => 'tidak',
        'gajelas' => 'tidak jelas', 'ancur' => 'hancur', 'nyusahin' => 'menyusahkan',
        'konoha' => 'indonesia buruk', // Mapping Satire
        'wakanda' => 'indonesia buruk',
        'prindavan' => 'indonesia buruk',
        '+62' => 'indonesia'
    ];

    protected $lexicon = [
        // --- POSITIF ---
        'bagus' => 3, 'baik' => 2, 'setuju' => 3, 'dukung' => 3, 'sepakat' => 2, 
        'terima kasih' => 2, 'mantap' => 3, 'hebat' => 3, 'keren' => 3, 'maju' => 2, 
        'sukses' => 3, 'berhasil' => 3, 'tepat' => 2, 'solusi' => 3, 'bantu' => 2, 
        'amanah' => 4, 'berkah' => 3, 'salut' => 3, 'optimis' => 2, 'gaspol' => 3,

        // --- NEGATIF (Kritik & Emosi) ---
        'buruk' => -3, 'jelek' => -3, 'salah' => -3, 'bohong' => -4, 'tipu' => -4,
        'gagal' => -4, 'rusak' => -3, 'hancur' => -4, 'kacau' => -3, 'mundur' => -2,
        'tolak' => -4, 'lawan' => -4, 'protes' => -3, 'demo' => -3, 'ancam' => -3,
        'korupsi' => -5, 'maling' => -5, 'rampok' => -5, 'tindas' => -4, 'sengsara' => -4,
        'susah' => -3, 'sulit' => -3, 'mahal' => -3, 'miskin' => -3, 'kecewa' => -4,
        'marah' => -4, 'benci' => -4, 'muak' => -4, 'jijik' => -4, 'takut' => -2,
        'bodoh' => -4, 'goblok' => -5, 'tolol' => -5, 'idiot' => -5, 'dungu' => -4,
        'gila' => -3, 'sinting' => -3, 'bangsat' => -5, 'bajingan' => -5, 'biadab' => -5,
        
        // --- KONTEKS SARKASME & POLITIK (Sesuai Request) ---
        'konoha' => -3, // Sebutan sindiran untuk Indonesia
        'wakanda' => -3, // Sebutan sindiran hukum tumpul
        'dagelan' => -4, // Pemerintahan dianggap lelucon
        'badut' => -4, // Pejabat dianggap badut
        'waras' => -2, // "Masih waras?" = Negatif
        'sehat' => -1, // "Lu sehat?" (Sarkas)
        'kocak' => -3, // "Rezim kocak"
        'lawak' => -3, // "Negara lawak"
        'dongeng' => -3,
        'drama' => -3,
        
        // Konteks Religius (Keluhan)
        'astagfirullah' => -3, 'istighfar' => -2, 'ya allah' => -2, 'innalillahi' => -3,
        'zalim' => -5, 'laknat' => -5, 'dosa' => -3, 'azab' => -4,

        // Palestina vs Israel (Penolakan Hubungan)
        'israel' => -3, 'zionis' => -5, 'penjajah' => -5, 'genocide' => -5,
        'genosida' => -5, 'boikot' => -4, 'free' => -2, 'palestine' => -3, 
        'save' => -2, 'rafah' => -3
    ];

    protected $idioms = [
        'omong kosong' => -5,
        'pintar berdalih' => -5,
        'tidak becus' => -5,
        'tidak guna' => -4,
        'kurang ajar' => -4,
        'akal bulus' => -4,
        'pencitraan' => -4,
        'turunkan' => -4,
        'mundur aja' => -4,
        'tidak waras' => -5,
        'gak waras' => -5,
        'masih waras' => -3, // Pertanyaan retoris "Masih waras?"
        'negara konoha' => -4,
        'pejabat konoha' => -4,
        'negeri wakanda' => -4,
        'all eyes' => -4,
        'free palestine' => -5,
    ];

    protected $negations = ['tidak', 'bukan', 'jangan', 'tak', 'kurang', 'anti', 'stop', 'hentikan', 'gak'];

    public function __construct()
    {
        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();
    }

    public function analyze($text)
    {
        $lowerText = strtolower($text);

        // --- 1. FILTER SPAM (Judol, 18+, Link) ---
        // Jika terdeteksi, kembalikan label SPAM agar dibuang oleh Controller
        foreach ($this->spamKeywords as $spam) {
            if (str_contains($lowerText, $spam)) {
                return ['score' => 0, 'label' => 'spam'];
            }
        }

        // --- 2. DETEKSI HASHTAG POLITIK ---
        if (str_contains($lowerText, '#freepalestine') || 
            str_contains($lowerText, '#alleyesonrafah') || 
            str_contains($lowerText, '#tolak')) {
            return ['score' => -0.8, 'label' => 'negative'];
        }

        // Bersihkan teks
        $cleanText = preg_replace('/http\S+|www\S+|@\S+/', '', $lowerText);
        $cleanText = preg_replace('/[^a-z0-9\s]/', ' ', $cleanText);
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);
        $cleanText = trim($cleanText);

        $score = 0;

        // --- 3. CEK IDIOM (Frasa Kuat) ---
        foreach ($this->idioms as $idiom => $val) {
            if (str_contains($cleanText, $idiom)) {
                $score += $val;
                $cleanText = str_replace($idiom, '', $cleanText);
            }
        }

        // --- 4. CEK KATA (Lexicon) ---
        $words = explode(' ', $cleanText);
        $count = 0;

        for ($i = 0; $i < count($words); $i++) {
            $word = $words[$i];
            if (isset($this->slang[$word])) $word = $this->slang[$word];
            if (strlen($word) < 2) continue;

            $val = 0;
            $stem = $this->stemmer->stem($word);

            if (isset($this->lexicon[$word])) $val = $this->lexicon[$word];
            elseif (isset($this->lexicon[$stem])) $val = $this->lexicon[$stem];

            if ($val != 0) {
                // Cek Negasi 2 kata ke belakang
                if ($i > 0 && in_array($words[$i-1], $this->negations)) $val = -$val;
                elseif ($i > 1 && in_array($words[$i-2], $this->negations)) $val = -$val;
                
                $score += $val;
                $count++;
            }
        }

        // --- 5. LOGIKA KHUSUS SARKASME ---
        // Jika ada kata "Konoha", "Wakanda", "Dagelan" -> Skor otomatis minus (Negatif)
        if (str_contains($lowerText, 'konoha') || str_contains($lowerText, 'wakanda')) {
            $score -= 2; 
        }

        $label = 'neutral';
        if ($score > 0.5) $label = 'positive';
        elseif ($score < -0.5) $label = 'negative';

        $normScore = 0;
        if ($score != 0) {
            $div = $count > 0 ? $count : 1;
            $normScore = max(-1, min(1, $score / $div));
        }

        return ['score' => $normScore, 'label' => $label];
    }
}