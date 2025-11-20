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

        // 2. Hapus Data Lama (Tweet & Kebijakan) agar tidak tercampur
        TweetData::truncate();
        Policy::truncate();

        // 3. Nyalakan lagi pengecekannya
        Schema::enableForeignKeyConstraints();

        // 4. Data Berita Baru (Berdasarkan Request User)
        $policies = [
            [
                'title' => 'Larangan Penjualan Elpiji 3 Kg oleh Pengecer',
                'description' => 'Kebijakan Energi Subsidi yang diterapkan 1 Januari 2025 namun dibatalkan total pada 4 Februari 2025 setelah menuai protes keras akibat kelangkaan.',
                'source_link' => 'https://x.com/intinyadeh/status/1886410377803829491',
                'target_tweet_id' => '1886410377803829491', // Sumber Viral (35k likes)
                'category' => 'Energi & Ekonomi',
                'date_issued' => '2025-01-01',
            ],
            [
                'title' => 'Pemangkasan APBN Rp306,6 Triliun (Inpres No. 1/2025)',
                'description' => 'Kebijakan Fiskal untuk efisiensi anggaran yang berdampak pada PHK massal tenaga honorer. Viral karena kritik keras dari masyarakat terdampak.',
                'source_link' => 'https://x.com/txtdrorgmiskin/status/1897870354313625761',
                'target_tweet_id' => '1897870354313625761', // Sumber Viral (53k likes)
                'category' => 'Ekonomi Makro',
                'date_issued' => '2025-01-22',
            ],
            [
                'title' => 'Revisi UU TNI (UU No. 3/2025) – Dwifungsi & Jabatan Sipil',
                'description' => 'Kebijakan Pertahanan yang disahkan 26 Maret 2025. Memicu kontroversi besar terkait kembalinya peran militer dalam jabatan sipil (Dwifungsi).',
                'source_link' => 'https://x.com/PBHI_Nasional/status/1902565309812498779',
                'target_tweet_id' => '1902565309812498779', // Sumber Viral Breaking News (98k likes)
                'category' => 'Politik & Hukum',
                'date_issued' => '2025-03-26',
            ],
            [
                'title' => 'Pencabutan Izin Tambang Nikel di Raja Ampat',
                'description' => 'Kebijakan Lingkungan tegas pada 10 Juni 2025. Izin tambang dicabut total setelah viralnya kerusakan alam di kawasan wisata prioritas.',
                'source_link' => 'https://x.com/AnggiCartwheel/status/1930502426551091477',
                'target_tweet_id' => '1930502426551091477', // Sumber Viral Before-After (68k likes)
                'category' => 'Lingkungan',
                'date_issued' => '2025-06-10',
            ],
            [
                'title' => 'Peluang Diplomasi Israel "Jika Palestina Diakui"',
                'description' => 'Kebijakan Luar Negeri kontroversial yang disampaikan Mei 2025. Pemerintah membuka opsi hubungan dengan Israel dengan syarat pengakuan Palestina.',
                'source_link' => 'https://x.com/tempodotco/status/1927641433080537416',
                'target_tweet_id' => '1927641433080537416', // Sumber Viral Debat Nasional (24k likes)
                'category' => 'Luar Negeri',
                'date_issued' => '2025-05-15',
            ],
        ];

        foreach ($policies as $p) {
            Policy::create($p);
        }
    }
}