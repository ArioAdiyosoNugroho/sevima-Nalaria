# Analisis Problem Pendidikan untuk Hackathon SEMESTA 8
**Tema resmi:** *Empowering Youth for a Sustainable Future: Build with AI*
**Format:** Individu, 8 jam (6 jam efektif coding & dokumentasi), studi kasus resmi baru dibuka jam 08.30 Hari H

---

## 1. Batasan Teknis dari Guidebook (yang membentuk pilihan problem)

Sebelum memilih masalah, ini batasan nyata yang menentukan problem *seperti apa* yang layak diambil:

| Batasan | Implikasi untuk pemilihan problem |
|---|---|
| Individu, 6 jam efektif | Problem harus **sempit** — satu use case inti, bukan platform besar |
| Dinilai tanpa presentasi langsung (kecuali lolos showcase) | Solusi harus **self-explanatory** dari demo video + slide |
| Bobot tinggi: Fungsionalitas & Relevansi Tema/Inovasi | Problem harus punya **fitur AI yang benar-benar dipakai**, bukan tempelan |
| Kode harus baru, tidak boleh pakai proyek lama | Tidak bisa "recycle" MudaSkill/SecondChance/Tefa secara langsung — tapi pola desain & tech stack yang sudah dikuasai tetap bisa dipakai |
| Challenge poin bernilai tinggi: AI Agent Fungsional (+10), Deploy+CI/CD+Docker (+8), Real-time (+8) | Problem yang solusinya *natural* punya AI agent dengan ≥2 aksi berbeda akan lebih mudah meraih bonus besar |
| Studi kasus baru dibuka Hari H, tidak boleh bocor sebelumnya | Dokumen ini **bukan jawaban final** — ini kerangka berpikir supaya begitu studi kasus turun, kamu tinggal memetakan ke salah satu arah di bawah, bukan mulai brainstorming dari nol |

---

## 2. Apa Kata Sumber Global (artikel Concern Worldwide)

Artikel yang kamu lampirkan membahas *"10 of the biggest problems facing education"* — daftar globalnya:
1. Konflik & kekerasan
2. Kekerasan & bullying di sekolah
3. Perubahan iklim (infrastruktur sekolah rusak)
4. Musim panen/pasar (anak putus sekolah sementara untuk membantu ekonomi keluarga)
5. Guru tidak dibayar/tidak terlatih
6. Biaya perlengkapan & seragam
7. Anak yang lebih tua lebih rentan putus sekolah demi bekerja
8. Kesenjangan gender
9. Wabah penyakit
10. Hambatan bahasa & literasi

**Catatan analisis:** sebagian besar poin ini (konflik, wabah, kekerasan bersenjata) adalah realitas negara rapuh/krisis kemanusiaan — kurang relevan sebagai konteks studi kasus hackathon berbasis teknologi di Indonesia. Tiga poin yang **paling relevan dan bisa diterjemahkan ke konteks Indonesia** adalah:
- **#10 Hambatan literasi** → versi Indonesia: krisis literasi-numerasi
- **#7 Anak lebih tua putus sekolah demi bekerja** → versi Indonesia: mismatch lulusan SMK/vokasi dengan dunia kerja, sehingga pendidikan dianggap "tidak worth it"
- **#6 Biaya & akses** → versi Indonesia: kesenjangan akses pendidikan berkualitas di daerah 3T vs kota

---

## 3. Konteks Aktual Pendidikan Indonesia (hasil riset tambahan)

| Masalah | Bukti/Data |
|---|---|
| **Darurat literasi-numerasi** | Hasil Asesmen Nasional menunjukkan 1 dari 2 peserta didik belum mencapai kompetensi minimum literasi; skor PISA membaca Indonesia tetap jauh di bawah rata-rata OECD selama 20 tahun terakhir |
| **Kelemahan penalaran matematis** | Hasil PISA 2022 & TIMSS 2023: siswa Indonesia lemah di penalaran matematis, pemecahan masalah non-rutin, dan menghubungkan konsep matematika dengan situasi nyata — pembelajaran masih hafalan prosedur |
| **Kesenjangan wilayah 3T** | Studi di NTT (Kupang) dan Papua Pegunungan (Wamena) menunjukkan learning loss literasi-numerasi lebih parah di daerah 3T akibat keterbatasan fasilitas, kualitas guru, dan hambatan bahasa lokal |
| **Learning loss pasca-pandemi belum pulih** | Kemendikbud & INOVASI mencatat kesenjangan hasil belajar makin tajam sejak pandemi dan efeknya diperkirakan berlanjut bertahun-tahun |
| **Mutu vs kuantitas pendidikan** | Ini bukan soal akses sekolah (Indonesia relatif tinggi partisipasinya), tapi **mutu pembelajaran** — sejalan dengan upaya Kemendikdasmen tahun ini yang menggandeng Tanoto Foundation, Gates Foundation, dan UNICEF khusus untuk memperkuat literasi-numerasi nasional |

**Kesimpulan konteks:** masalah pendidikan Indonesia yang paling didukung data resmi saat ini — dan paling jadi prioritas kebijakan nasional — adalah **kesenjangan literasi dan numerasi fungsional**, bukan akses sekolah semata.

---

## 4. Problem Terbesar yang Realistis Diselesaikan dalam 8 Jam

Menggabungkan urgensi nasional (bagian 3) dengan batasan format hackathon (bagian 1):

> ### 🎯 Kesenjangan literasi-numerasi fungsional siswa, khususnya kemampuan menerapkan konsep ke situasi nyata (bukan sekadar hafalan)

**Kenapa ini pilihan terkuat:**
- Datanya paling kuat dan paling "nasional", bukan isu niche
- Scope-nya bisa dipersempit sampai sangat kecil: 1 skill, 1 jenjang, 1 mata pelajaran — cocok untuk kerja 6 jam
- Solusi AI-nya jelas: *asesmen adaptif + umpan balik personal + latihan bertarget* adalah pola yang natural untuk AI agent (bukan sekadar chatbot tanya-jawab)
- Bisa didemokan dengan jelas dalam video 5 menit (soal → diagnosis AI → latihan yang disesuaikan)
- Berpeluang meraih banyak challenge poin: AI Agent Fungsional (+10, jika AI melakukan ≥2 aksi: mendiagnosis + membuat rencana belajar), Database (+2), Autentikasi (+2), Responsive (+2), Aksesibilitas (+2)

**Problem cadangan (jika studi kasus resmi condong ke isu vokasi/dunia kerja):**
> Mismatch antara kompetensi lulusan SMK/vokasi dan kebutuhan industri lokal — relevan karena kamu sendiri siswa RPL SMK, jadi punya insight langsung soal apa yang sering hilang antara materi sekolah dan ekspektasi klien/industri.

---

## 5. Dua Konsep MVP yang Bisa Dieksekusi Solo dalam 6 Jam

### Opsi A — "Diagnostik Numerasi Adaptif" (fokus: literasi-numerasi)
- **Problem & pengguna:** siswa SMP/SMA kesulitan menerapkan konsep matematika ke soal kontekstual (bukan hafalan rumus)
- **Fitur inti:**
  1. Siswa mengerjakan 5–8 soal kontekstual singkat
  2. AI menganalisis pola kesalahan (bukan cuma benar/salah, tapi *jenis* miskonsepsi)
  3. AI menghasilkan 3 soal latihan bertarget + penjelasan personal sesuai miskonsepsi tsb
- **Kenapa efisien:** UI sederhana (form soal + hasil), beban terberat ada di prompt-engineering AI, bukan di kompleksitas frontend
- **Peluang challenge poin:** AI Agent (+10, karena AI melakukan 2 aksi: diagnosis + generate soal baru), Database progres (+2), Responsive (+2)

### Opsi B — "SkillBridge" (fokus: mismatch vokasi-industri)
- **Problem & pengguna:** siswa SMK bingung skill apa yang sebenarnya dicari industri/klien freelance di bidangnya
- **Fitur inti:**
  1. Siswa input jurusan + skill yang sudah dikuasai
  2. AI membandingkan dengan contoh lowongan/kebutuhan proyek riil dan menandai *skill gap*
  3. AI menyusun roadmap belajar mingguan yang konkret untuk menutup gap tsb
- **Kenapa relevan buatmu:** kamu sudah punya pengalaman freelance web dev + proyek sekolah (Tefa), jadi kamu paham betul kesenjangan ini dari sisi pengguna
- **Peluang challenge poin:** AI Agent (+10, jika AI melakukan analisis gap + generate roadmap sebagai dua aksi terpisah), Autentikasi (+2), Responsive (+2)

**Rekomendasi:** siapkan pola pikir untuk **kedua opsi**, tapi begitu studi kasus resmi turun jam 08.30, cocokkan dulu ke arah mana problem statement panitia condong — jangan memaksakan salah satu jika studi kasusnya ternyata beda arah sama sekali. Pola "diagnosis AI → rekomendasi bertarget" di kedua opsi ini bisa dipakai ulang untuk problem lain apa pun yang diberikan panitia.

---

## 6. Rencana Kerja 8 Jam (mengikuti rundown resmi Hari H)

| Jam | Fase | Fokus |
|---|---|---|
| 08.30–08.45 | Pengarahan tema | Petakan studi kasus resmi ke salah satu konsep di atas (atau modifikasi cepat). Isi field 1 Narasi Teknis: *Problem & Scope* |
| 08.45–12.15 | Coding Fase 1 (3,5 jam) | Bangun *end-to-end* paling sederhana dulu: input → AI call → output tampil. Jangan polish dulu |
| 12.15–13.15 | Ishoma | Istirahat atau lanjut jika masih on-track |
| 13.15–16.45 | Coding Fase 2 (3,5 jam) | Edge case, polish UX (loading/error/empty state), kejar 1–2 challenge poin bernilai tinggi kalau core sudah solid — jangan serakah ambil banyak challenge setengah-setengah |
| 16.45 | Deadline keras | Upload repo, demo video (≤5 menit), slide (≤10), Narasi Teknis (.pdf, ≤500 kata) — upload lebih awal, antisipasi jaringan |

**Pengingat 5 field Narasi Teknis** (isi saat kejadian, bukan direkonstruksi di akhir):
1. Problem & Scope — isi jam 08.30–09.00
2. Hambatan Teknis — catat saat hambatan muncul
3. Perubahan Arah — catat tiap kali ganti pendekatan
4. Keputusan Stack & Tools — isi setelah scaffold selesai
5. Satu Hal Berikutnya — isi jam 15.00–16.00

---

## 7. Sumber

- [10 of the biggest problems facing education — Concern Worldwide](https://www.concern.net/news/problems-with-education-around-the-world)
- Siaran Pers Kemendikdasmen No. 263/sipers/A6/IV/2026 — kolaborasi multipihak literasi-numerasi nasional bersama Tanoto Foundation, Gates Foundation, dan UNICEF
- Studi learning loss literasi-numerasi di SMKN 1 Amabi Oefeto Timur, NTT (NUSRA Jurnal, 2026)
- Studi literasi-numerasi 3T di Wamena, Papua Pegunungan (Jurnal Pendas, 2026)
- Meta-analisis kelemahan penalaran matematis siswa Indonesia berdasar PISA 2022 & TIMSS 2023 (Al-Asma Journal, 2026)
- Guidebook Hackathon SEMESTA 8 — Tech Career Academy by SEVIMA (dokumen yang dilampirkan)

*Catatan: analisis ini adalah kerangka persiapan sebelum studi kasus resmi diumumkan. Sesuaikan begitu problem statement dari juri turun di hari H.*
