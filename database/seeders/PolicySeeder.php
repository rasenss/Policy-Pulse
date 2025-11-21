<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\TweetData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan pengecekan Foreign Key sementara (agar bisa hapus bersih)
        Schema::disableForeignKeyConstraints();

        // 2. Hapus Data Lama (Tweet & Kebijakan)
        TweetData::truncate();
        Policy::truncate();

        // 3. Nyalakan lagi pengecekannya
        Schema::enableForeignKeyConstraints();

        // 4. Data Berita Baru (Sesuai Request Spesifik)
        $policies = [
            [
                'title' => 'Larangan Penjualan LPG 3 Kg Eceran',
                'description' => 'Diterapkan 1 Januari 2025 dan dibatalkan 4 Februari 2025. Memicu kelangkaan parah, antrean panjang hingga korban jiwa lansia, dan lonjakan harga pasar gelap.',
                'source_link' => 'https://x.com/intinyadeh/status/1886410377803829491',
                'target_tweet_id' => '1886410377803829491', // Viral: 2.3 Juta Views
                'category' => 'Energi & Ekonomi',
                'date_issued' => '2025-01-01',
            ],
            [
                'title' => 'Pemangkasan APBN Rp306,6 Triliun (Inpres 1/2025)',
                'description' => 'Efisiensi anggaran ekstrem mulai 22 Januari 2025. Mengakibatkan PHK massal tenaga honorer dan pemotongan tunjangan ASN yang berdampak pada layanan publik.',
                'source_link' => 'https://x.com/txtdrorgmiskin/status/1897870354313625761',
                'target_tweet_id' => '1897870354313625761', // Viral: Kritik PHK Honorer
                'category' => 'Fiskal & Ekonomi',
                'date_issued' => '2025-01-22',
            ],
            [
                'title' => 'Revisi & Pengesahan UU TNI (Dwifungsi)',
                'description' => 'Disahkan kilat pada 26 Maret 2025. Mengizinkan TNI menduduki jabatan sipil, memicu tuduhan kembalinya Dwifungsi ABRI dan demo nasional #TolakRUUTNI.',
                'source_link' => 'https://x.com/barengwarga/status/1901206104186491346',
                'target_tweet_id' => '1901206104186491346', // Viral: 11 Juta Views
                'category' => 'Politik & Hukum',
                'date_issued' => '2025-03-26',
            ],
            [
                'title' => 'Program Makan Bergizi Gratis (MBG) - Keracunan Massal',
                'description' => 'Dimulai 6 Januari 2025. Diwarnai skandal menu tak layak, anggaran bengkak >Rp300 T, dan lebih dari 3.000 kasus keracunan makanan (KLB) di berbagai daerah.',
                'source_link' => 'https://x.com/knpharuspeduli/status/1886773944457552268',
                'target_tweet_id' => '1886773944457552268', // Viral: Kasus Keracunan
                'category' => 'Kesehatan & Sosial',
                'date_issued' => '2025-01-06',
            ],
            [
                'title' => 'Pembukaan Peluang Diplomasi Israel',
                'description' => 'Pernyataan resmi pemerintah pada Mei 2025 untuk membuka hubungan diplomatik dengan syarat. Memicu kemarahan publik dan dianggap pengkhianatan konstitusi.',
                'source_link' => 'https://x.com/tempodotco/status/1927641433080537416',
                'target_tweet_id' => '1927641433080537416', // Viral: Debat Nasional (17.7 Juta Views)
                'category' => 'Luar Negeri',
                'date_issued' => '2025-05-20',
            ],
        ];

        foreach ($policies as $p) {
            Policy::create($p);
        }
    }
}