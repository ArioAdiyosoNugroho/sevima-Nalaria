<?php

namespace Database\Seeders;

use App\Models\DiagnosticQuestion;
use Illuminate\Database\Seeder;

class DiagnosticQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'order' => 1,
                'domain' => 'aritmatika_sosial',
                'title' => 'Diskon Bertingkat Gerai Daur Ulang Sekolah',
                'context_scenario' => 'Dalam rangka program sekolah hijau bebas sampah plastik, koperasi sekolah memberikan promo diskon bertingkat untuk tumbler stainless steel seharga Rp100.000. Poster promo bertuliskan: "Diskon 50% + Ekstra Diskon 20% khusus anggota!". Seorang siswa berpendapat bahwa harga akhir tumbler tersebut adalah Rp30.000 karena total potongan adalah 70%.',
                'question_text' => 'Berapakah harga sebenarnya yang harus dibayar oleh seorang siswa anggota koperasi?',
                'options' => [
                    ['key' => 'A', 'text' => 'Rp30.000 (Mendapat potongan total 70% dari harga awal)', 'misconception' => 'Additive Sequential Discount Error'],
                    ['key' => 'B', 'text' => 'Rp40.000 (Diskon 50% menjadi Rp50.000, lalu didiskon lagi 20% dari Rp50.000)', 'misconception' => null],
                    ['key' => 'C', 'text' => 'Rp50.000 (Hanya diskon pertama yang dihitung)', 'misconception' => 'Incomplete Multi-step Calculation'],
                    ['key' => 'D', 'text' => 'Rp35.000 (Rata-rata diskon diambil dari total)', 'misconception' => 'Arbitrary Mean Approximation'],
                ],
                'correct_answer' => 'B',
                'misconception_map' => [
                    'A' => 'Miskonsepsi Penjumlahan Persentase Beruntun (Additive error): Menganggap diskon 50% + 20% sama dengan 70%, padahal diskon kedua dihitung dari basis harga setelah diskon pertama.',
                    'C' => 'Miskonsepsi Pengabaian Langkah Lanjutan: Berhenti pada perhitungan tahap pertama tanpa menerapkan ketentuan sekunder.',
                    'D' => 'Miskonsepsi Estimasi Acak: Mengira perhitungan persentase beruntun dapat diselesaikan dengan nilai rerata.',
                ],
                'conceptual_explanation' => 'Diskon bertingkat 50% + 20% bukan dijumlahkan menjadi 70%. Diskon pertama 50% dari Rp100.000 = Rp50.000 (sisa Rp50.000). Diskon kedua 20% dari sisa Rp50.000 = Rp10.000. Maka harga akhir yang dibayar adalah Rp50.000 - Rp10.000 = Rp40.000 (efektif diskon 60%).',
            ],
            [
                'order' => 2,
                'domain' => 'aljabar',
                'title' => 'Pemodelan Efisiensi Biaya Listrik Tenaga Surya',
                'context_scenario' => 'Laboratorium komputer sekolah memasang sistem pembangkit listrik tenaga surya (PLTS) hibrida. Biaya langganan jaringan dasar adalah Rp50.000 per bulan, ditambah biaya pemakaian daya tambahan sebesar Rp1.200 untuk setiap kilowatt-jam (kWh) yang dipakai melebihi pasokan panel surya. Jika sebuah kelas memakai x kWh daya tambahan dalam satu bulan.',
                'question_text' => 'Manakah model matematika yang tepat untuk menghitung total tagihan bulanan (T)?',
                'options' => [
                    ['key' => 'A', 'text' => 'T = 50.000x + 1.200', 'misconception' => 'Variable-Constant Reversal Trap'],
                    ['key' => 'B', 'text' => 'T = 51.200x', 'misconception' => 'Direct Proportional Over-generalization'],
                    ['key' => 'C', 'text' => 'T = 1.200x + 50.000', 'misconception' => null],
                    ['key' => 'D', 'text' => 'T = 50.000 / (1.200x)', 'misconception' => 'Inverse Ratio Misattribution'],
                ],
                'correct_answer' => 'C',
                'misconception_map' => [
                    'A' => 'Miskonsepsi Pembalikan Variabel dan Konstanta: Memasang variabel pengali pada komponen biaya tetap (abonemen) alih-alih tarif variabel per kWh.',
                    'B' => 'Miskonsepsi Generalisasi Proporsi Langsung: Menggabungkan biaya tetap dan biaya per unit langsung tanpa membedakan konstanta awal garis linier.',
                    'D' => 'Miskonsepsi Hubungan Berbanding Terbalik: Memasukkan bentuk pecahan pembagian pada situasi fungsi pertumbuhan linear positif.',
                ],
                'conceptual_explanation' => 'Tagihan listrik terdiri dari dua bagian: biaya tetap (intercept b = 50.000) yang tidak bergantung pada jumlah pemakaian, dan biaya variabel yang proporsional dengan pemakaian (slope m = 1.200 per kWh). Maka bentuk fungsi linier adalah T(x) = mx + b = 1.200x + 50.000.',
            ],
            [
                'order' => 3,
                'domain' => 'geometri',
                'title' => 'Skala Denah Pemasangan Panel Surya di Rooftop',
                'context_scenario' => 'Tim teknisi sekolah membuat denah atap serbaguna dengan skala 1 : 100. Pada gambar denah, area yang dialokasikan untuk panel surya berbentuk persegi panjang berukuran 4 cm × 6 cm. Seorang siswa menyimpulkan bahwa luas atap sebenarnya yang bisa dipasang panel surya adalah 24 m² karena 4 cm × 6 cm = 24 cm², lalu dikalikan 100.',
                'question_text' => 'Berapakah luas area atap sebenarnya yang dialokasikan untuk panel surya tersebut?',
                'options' => [
                    ['key' => 'A', 'text' => '24 m² (Luas denah 24 cm² dikali faktor skala 100)', 'misconception' => 'Linear Scaling Applied to 2D Area Error'],
                    ['key' => 'B', 'text' => '240 m² (Ukuran sebenarnya adalah 4 m × 6 m = 24 m², namun keliru konversi)', 'misconception' => 'Unit Metric Conversion Confusion'],
                    ['key' => 'C', 'text' => '24 m² (Konversi tiap sisi: 4 cm -> 4 m dan 6 cm -> 6 m, luas = 4 m × 6 m = 24 m²)', 'misconception' => null],
                    ['key' => 'D', 'text' => '2.400 m² (Mengalikan 24 cm² dengan 10.000 lalu salah meletakkan desimal)', 'misconception' => 'Area Factor Multiplier Miscalculation'],
                ],
                'correct_answer' => 'C',
                'misconception_map' => [
                    'A' => 'Miskonsepsi Skala Linear pada Luas 2D: Mengira faktor skala luas sama dengan skala panjang (mengalikan luas dengan k, bukan k²). Padahal jika skala 1:100, rasio luas adalah 1:10.000.',
                    'B' => 'Miskonsepsi Kesalahan Konversi Satuan Metrik: Kurang teliti dalam mengonversi dari sentimeter persegi ke meter persegi.',
                    'D' => 'Miskonsepsi Perhitungan Skala Bertingkat: Salah melakukan perhitungan ordo magnitudo pangkat sepuluh.',
                ],
                'conceptual_explanation' => 'Dengan skala 1 : 100, 1 cm di gambar mewakili 100 cm = 1 meter di lapangan. Sisi panjang sebenarnya = 6 cm × 100 = 600 cm = 6 m. Sisi lebar sebenarnya = 4 cm × 100 = 400 cm = 4 m. Maka luas sebenarnya = 6 m × 4 m = 24 m². Catatan: Jika mengalikan luas denah (24 cm²), pengalinya adalah 100² = 10.000 cm²/m², sehingga 24 × 10.000 cm² = 240.000 cm² = 24 m².',
            ],
            [
                'order' => 4,
                'domain' => 'data_ketidakpastian',
                'title' => 'Evaluasi Rata-rata Pemilahan Sampah Organik Antar Kelas',
                'context_scenario' => 'Dalam proyek keberlanjutan lingkungan, Kelas 8A (berisi 40 siswa) berhasil mengumpulkan rata-rata 3 kg sampah organik per siswa. Sedangkan Kelas 8B (berisi 10 siswa) mengumpulkan rata-rata 5 kg sampah organik per siswa. Seorang pengurus OSIS menghitung rata-rata gabungan kedua kelas dengan cara menjumlahkan (3 + 5) ÷ 2 = 4 kg per siswa.',
                'question_text' => 'Mengapa perhitungan pengurus OSIS tersebut keliru, dan berapa rata-rata per siswa sebenarnya dari gabungan kedua kelas?',
                'options' => [
                    ['key' => 'A', 'text' => 'Benar 4 kg, karena rata-rata dari 3 dan 5 adalah tepat 4.', 'misconception' => 'Simple Mean of Means Fallacy'],
                    ['key' => 'B', 'text' => 'Keliru; rata-rata sebenarnya adalah 3,4 kg karena jumlah siswa di Kelas 8A (40) jauh lebih banyak dari 8B (10), sehingga harus dihitung rata-rata terbobot.', 'misconception' => null],
                    ['key' => 'C', 'text' => 'Keliru; rata-rata sebenarnya adalah 3,8 kg karena kelas dengan rata-rata tinggi mendapat bobot ganda.', 'misconception' => 'Arbitrary Weighted Biasing'],
                    ['key' => 'D', 'text' => 'Keliru; rata-rata tidak bisa dihitung jika jumlah siswa kedua kelompok berbeda.', 'misconception' => 'Invariance Barrier Belief'],
                ],
                'correct_answer' => 'B',
                'misconception_map' => [
                    'A' => 'Miskonsepsi Rata-rata dari Rata-rata (Unweighted mean trap): Menyamakan rata-rata populasi gabungan dengan rata-rata aritmatika sederhana antar kelompok tanpa memperhitungkan perbedaan ukuran sampel (bobot n).',
                    'C' => 'Miskonsepsi Pembobotan Asimetris: Mengira kelompok berkinerja tinggi harus dibobot lebih berat tanpa rumus matematis valid.',
                    'D' => 'Miskonsepsi Hambatan Varians Data: Menganggap agregasi statistik tidak dapat dilakukan jika ukuran sub-sampel berbeda.',
                ],
                'conceptual_explanation' => 'Rata-rata gabungan harus menggunakan rata-rata terbobot (weighted mean). Total sampah Kelas 8A = 40 × 3 = 120 kg. Total sampah Kelas 8B = 10 × 5 = 50 kg. Total keseluruhan sampah = 120 + 50 = 170 kg. Total seluruh siswa = 40 + 10 = 50 siswa. Maka rata-rata sebenarnya = 170 ÷ 50 = 3,4 kg per siswa. Rata-rata bergeser lebih dekat ke 3 kg karena jumlah siswa kelas 8A mendominasi (80% populasi).',
            ],
            [
                'order' => 5,
                'domain' => 'aritmatika_sosial',
                'title' => 'Formulasi Pupuk Organik Cair (Rasio Bagian vs Total)',
                'context_scenario' => 'Petunjuk pembuatan pupuk kompos cair untuk kebun toga sekolah menyatakan bahwa rasio antara konsentrat fermentasi dan air bersih adalah 1 : 4. Doni ingin membuat larutan pupuk cair siap pakai sebanyak 10 liter untuk menyiram seluruh bedeng tanaman.',
                'question_text' => 'Berapa liter konsentrat fermentasi dan air bersih yang tepat dibutuhkan Doni?',
                'options' => [
                    ['key' => 'A', 'text' => '2,5 liter konsentrat dan 7,5 liter air (karena 10 liter dibagi 4 adalah 2,5)', 'misconception' => 'Part-to-Whole Division by Denominator Only'],
                    ['key' => 'B', 'text' => '2 liter konsentrat dan 8 liter air (total 1 + 4 = 5 bagian; tiap bagian = 2 liter)', 'misconception' => null],
                    ['key' => 'C', 'text' => '1 liter konsentrat dan 4 liter air (mengikuti angka rasio tanpa memperhatikan volume target 10 liter)', 'misconception' => 'Absolute Ratio Fixation (Ignoring Scale)'],
                    ['key' => 'D', 'text' => '1 liter konsentrat dan 9 liter air (karena rasio 1 bagian terhadap 10 liter)', 'misconception' => 'Percentage Base Confusion (10% heuristic)'],
                ],
                'correct_answer' => 'B',
                'misconception_map' => [
                    'A' => 'Miskonsepsi Pembagian Langsung Penyebut: Membagi volume total dengan rasio komponen terbesar (4), tanpa menjumlahkan seluruh bagian (1+4=5).',
                    'C' => 'Miskonsepsi Fiksasi Rasio Statis: Hanya membaca angka absolut rasio (1 dan 4) dan mengabaikan target volume nyata yang diinginkan (10 liter).',
                    'D' => 'Miskonsepsi Heuristik Desimal 10%: Menganggap rasio 1 bagian adalah 10% dari 10 liter (1 liter) sehingga sisanya 9 liter.',
                ],
                'conceptual_explanation' => 'Rasio 1 : 4 adalah rasio bagian-ke-bagian (part-to-part). Artinya setiap 1 liter konsentrat butuh 4 liter air, menghasilkan total larutan 1 + 4 = 5 bagian (part-to-whole). Untuk membuat 10 liter larutan (total): nilai 1 bagian = 10 ÷ 5 = 2 liter. Jadi konsentrat = 1 × 2 = 2 liter, dan air = 4 × 2 = 8 liter. Rasio 2 : 8 tepat ekuivalen dengan 1 : 4 dan jumlahnya pas 10 liter.',
            ],
        ];

        foreach ($questions as $q) {
            DiagnosticQuestion::updateOrCreate(
                ['order' => $q['order']],
                $q
            );
        }
    }
}
