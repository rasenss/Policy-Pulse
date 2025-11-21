<?php

namespace App\Services;

use Sastrawi\Stemmer\StemmerFactory;

class SentimentService
{
    protected $stemmer;
    
    protected $slang = [
        'yg' => 'yang', 'ga' => 'tidak', 'gak' => 'tidak', 'engga' => 'tidak', 
        'bgt' => 'sangat', 'banget' => 'sangat', 'sdh' => 'sudah', 'udh' => 'sudah', 
        'dgn' => 'dengan', 'tdk' => 'tidak', 'blm' => 'belum', 'tau' => 'tahu', 
        'klo' => 'kalau', 'krn' => 'karena', 'tp' => 'tapi', 'utk' => 'untuk', 
        'jd' => 'jadi', 'bs' => 'bisa', 'skrg' => 'sekarang', 'sm' => 'sama', 
        'nggak' => 'tidak', 'tak' => 'tidak', 'gajelas' => 'tidak jelas',
        'ancur' => 'hancur', 'nyusahin' => 'menyusahkan', 'astagfirullah' => 'kecewa',
        'ya allah' => 'keluhan', 'innalillahi' => 'berduka'
    ];

    protected $lexicon = [
        // Positif
        'bagus' => 3, 'baik' => 2, 'setuju' => 3, 'dukung' => 3, 'sepakat' => 2, 
        'terima kasih' => 2, 'mantap' => 3, 'hebat' => 3, 'keren' => 3, 'maju' => 2, 
        'sukses' => 3, 'berhasil' => 3, 'tepat' => 2, 'solusi' => 3, 'bantu' => 2, 
        'manfaat' => 2, 'lancar' => 2, 'optimis' => 2, 'gaspol' => 3, 'lanjut' => 2,
        'bijak' => 3, 'cerdas' => 3, 'amanah' => 4, 'berkah' => 3, 'salut' => 3,

        // Negatif (Umum)
        'buruk' => -3, 'jelek' => -3, 'salah' => -3, 'bohong' => -4, 'tipu' => -4,
        'gagal' => -4, 'rusak' => -3, 'hancur' => -4, 'kacau' => -3, 'mundur' => -2,
        'tolak' => -4, 'lawan' => -4, 'protes' => -3, 'demo' => -3, 'ancam' => -3,
        'korupsi' => -5, 'maling' => -5, 'rampok' => -5, 'tindas' => -4, 'sengsara' => -4,
        'susah' => -3, 'sulit' => -3, 'mahal' => -3, 'miskin' => -3, 'lapar' => -3,
        'bingung' => -2, 'ribet' => -3, 'lambat' => -2, 'lelet' => -2, 'kecewa' => -4,
        'marah' => -4, 'benci' => -4, 'muak' => -4, 'jijik' => -4, 'takut' => -2,
        
        // Negatif (Religius/Keluhan)
        'astagfirullah' => -3, 'istighfar' => -2, 'ya allah' => -2, 'innalillahi' => -3,
        'zalim' => -5, 'laknat' => -5, 'dosa' => -3, 'azab' => -4, 'musibah' => -3,
        
        // Negatif (Konteks Palestina vs Israel)
        'free' => -3, 'palestine' => -3, 'palestina' => -3, 'genocide' => -5, 
        'genosida' => -5, 'penjajah' => -5, 'zionis' => -5, 'israel' => -2,
        'boikot' => -4, 'save' => -2, 'rafah' => -3, 'ceasefire' => -3,
    ];

    protected $idioms = [
        'omong kosong' => -5,
        'tidak becus' => -5,
        'tidak guna' => -4,
        'kurang ajar' => -4,
        'tidak masuk akal' => -4,
        'akal bulus' => -4,
        'pencitraan' => -4,
        'turunkan' => -4,
        'mundur aja' => -4,
        'astagfirullah' => -3, 
        'ya allah' => -2, 
        'hasbunallah' => -3, 
        'all eyes' => -3, 
        'free palestine' => -5,
        'stop genocide' => -5,
    ];

    protected $negations = ['tidak', 'bukan', 'jangan', 'tak', 'kurang', 'anti', 'stop', 'hentikan'];

    public function __construct()
    {
        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();
    }

    public function analyze($text)
    {
        $text = strtolower($text);
        
        // 1. Cek Hashtag Spesifik (Langsung vonis Negatif)
        if (str_contains($text, '#freepalestine') || 
            str_contains($text, '#alleyesonrafah') || 
            str_contains($text, '#ceasefirenow')) {
            return ['score' => -0.8, 'label' => 'negative'];
        }

        // Bersihkan
        $cleanText = preg_replace('/http\S+|www\S+|@\S+/', '', $text);
        $cleanText = preg_replace('/[^a-z0-9\s]/', ' ', $cleanText);
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);
        $cleanText = trim($cleanText);

        $score = 0;

        // 2. Cek Idiom
        foreach ($this->idioms as $idiom => $val) {
            if (str_contains($cleanText, $idiom)) {
                $score += $val;
                $cleanText = str_replace($idiom, '', $cleanText);
            }
        }

        // 3. Cek Kata
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
                if ($i > 0 && in_array($words[$i-1], $this->negations)) $val = -$val;
                elseif ($i > 1 && in_array($words[$i-2], $this->negations)) $val = -$val;
                
                $score += $val;
                $count++;
            }
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