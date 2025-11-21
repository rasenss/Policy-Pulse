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
        // 1. Matikan pengecekan Foreign Key sementara agar bisa hapus bersih data lama
        Schema::disableForeignKeyConstraints();

        // 2. Hapus Data Lama (Tweet & Kebijakan)
        TweetData::truncate();
        Policy::truncate();

        // 3. Nyalakan lagi pengecekannya
        Schema::enableForeignKeyConstraints();

        // 4. Data Berita Baru (Update Terkini)
        $policies = [
            [
                'title' => 'Revisi UU TNI (Perluasan Tugas Sipil)',
                'description' => 'Disahkan DPR pada Maret 2025, mengizinkan TNI menjabat posisi sipil secara luas. Memicu kritik keras "Kembalinya Dwifungsi ABRI" dan demonstrasi besar.',
                'source_link' => 'https://x.com/PBHI_Nasional/status/1902565309812498779',
                'target_tweet_id' => '1902565309812498779', // Viral Breaking News
                'category' => 'Politik & Pertahanan',
                'date_issued' => '2025-03-26',
            ],
            [
                'title' => 'Sengketa 4 Pulau Aceh-Sumut',
                'description' => 'Kemendagri menetapkan 4 pulau masuk wilayah Sumut, memicu protes keras warga Aceh. Presiden Prabowo akhirnya mengembalikan status pulau ke Aceh pada Juni 2025.',
                'source_link' => 'https://x.com/geloraco/status/1934183023194636327',
                'target_tweet_id' => '1934183023194636327', // Viral Kritik Sengketa
                'category' => 'Dalam Negeri',
                'date_issued' => '2025-06-17',
            ],
            [
                'title' => 'Izin Tambang Nikel Raja Ampat (Dicabut)',
                'description' => 'Izin tambang kontroversial untuk 4 perusahaan yang mengancam 500+ hektar hutan & terumbu karang. Viral dengan tagar #SaveRajaAmpat, izin dicabut Presiden pada 10 Juni 2025.',
                'source_link' => 'https://x.com/e100ss/status/1930627957934166087',
                'target_tweet_id' => '1930627957934166087', // Viral Kritik Lingkungan
                'category' => 'Lingkungan',
                'date_issued' => '2025-06-10',
            ],
            [
                'title' => 'Larangan Penjualan LPG 3 Kg Eceran',
                'description' => 'Diterapkan 1 Januari 2025 dan dibatalkan 4 Februari 2025. Memicu kelangkaan parah, antrean panjang hingga korban jiwa lansia, dan lonjakan harga pasar gelap.',
                'source_link' => 'https://x.com/intinyadeh/status/1886410377803829491',
                'target_tweet_id' => '1886410377803829491', // Viral Kelangkaan
                'category' => 'Energi & Ekonomi',
                'date_issued' => '2025-01-01',
            ],
            [
                'title' => 'Pembukaan Peluang Diplomasi Israel',
                'description' => 'Pernyataan resmi pemerintah pada Mei 2025 untuk membuka hubungan diplomatik dengan syarat pengakuan Palestina. Memicu perdebatan nasional dan dianggap pengkhianatan konstitusi.',
                'source_link' => 'https://x.com/tempodotco/status/1927641433080537416',
                'target_tweet_id' => '1927641433080537416', // Viral Debat Nasional
                'category' => 'Luar Negeri',
                'date_issued' => '2025-05-20',
            ],
        ];

        foreach ($policies as $p) {
            Policy::create($p);
        }
    }
}