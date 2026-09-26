# AGENT.md --- Hackathon SEMESTA 8

## 0. PERAN FILE INI

File ini adalah konteks kerja untuk AI coding agent yang membantu
membangun produk pada Hackathon SEMESTA 8 by SEVIMA.

Tujuan utama: 1. Memahami aturan dan kriteria hackathon. 2. Membantu
mengubah studi kasus menjadi produk yang benar-benar berfungsi. 3.
Memprioritaskan fitur inti sebelum mengejar challenge point. 4. Membantu
membangun aplikasi menggunakan Laravel sebagai backend. 5. Menjaga agar
keputusan teknis, AI Agent, database, deployment, testing, responsive
UI, dan dokumentasi dapat dibuktikan saat submission.

**PENTING:** Studi kasus hackathon belum dimasukkan ke file ini. Pada
hari H, bagian `STUDI KASUS HARI H` di bagian paling bawah akan diisi
dengan soal/permasalahan resmi yang diberikan panitia. Jangan mengarang
studi kasus sebelum pengguna mengisinya.

------------------------------------------------------------------------

# 1. KONTEKS HACKATHON

Nama: **Hackathon SEMESTA 8 by SEVIMA**

Tema: **"Empowering Youth for a Sustainable Future: Build with AI"**

Format: - Kompetisi pengembangan produk digital. - Dikerjakan secara
individual. - One-day hackathon. - Peserta bebas memilih tech stack. -
AI dan vibe coding diperbolehkan. - Peserta tetap harus memahami
fundamental dari kode yang digunakan. - Semua kode harus baru dibuat
saat hackathon. - Studi kasus diberikan pada hari H. - Submission
dinilai berdasarkan hasil yang dikumpulkan; tidak mengandalkan
presentasi langsung untuk penilaian utama.

Waktu efektif: - Total acara: 8 jam. - Istirahat: 1 jam. - Efektif
coding + dokumentasi: sekitar 6 jam.

------------------------------------------------------------------------

# 2. OUTPUT WAJIB

Produk akhir harus menghasilkan 4 output:

1.  **Demo Video**
    -   Maksimal 5 menit.
    -   Live demo.
    -   Tunjukkan fitur utama benar-benar berjalan.
    -   Sertakan keputusan teknis penting.
2.  **GitHub Repository**
    -   Public repository.
    -   1 peserta = 1 akun GitHub.
    -   Commit history menjadi bukti proses pengerjaan selama hackathon.
3.  **Slide Deck**
    -   Maksimal 10 slide.
    -   Harus menjelaskan problem, solusi, demo, stack, challenge,
        hambatan, dan rencana berikutnya.
4.  **Technical Narrative PDF**
    -   Maksimal 500 kata.
    -   Terdiri dari 5 field:
        1.  Problem & Scope
        2.  Hambatan Teknis
        3.  Perubahan Arah
        4.  Keputusan Stack & Tools
        5.  Satu Hal Berikutnya

Technical Narrative harus dicatat selama proses berlangsung, bukan
dibuat dengan mengarang ulang semuanya di akhir.

------------------------------------------------------------------------

# 3. KRITERIA PENILAIAN

## Prioritas utama

### A. Fungsionalitas Aplikasi --- Bobot Tinggi

Pastikan: - Produk benar-benar berjalan. - Fitur utama menyelesaikan
problem dari studi kasus. - Workflow produk masuk akal. - Pemanfaatan AI
saat membangun produk dapat dijelaskan.

### B. Relevansi Tema & Inovasi --- Bobot Tinggi

Pastikan: - Produk relevan dengan studi kasus. - Solusi memiliki
pendekatan yang jelas. - Ada nilai inovasi atau cara penyelesaian yang
masuk akal.

### C. UX & Desain --- Bobot Sedang

Pastikan: - Alur mudah dipahami. - UI rapi. - Pengguna nyata dapat
memahami cara menggunakan produk. - Responsive desktop dan mobile jika
memungkinkan.

### D. Kualitas Submission --- Bobot Sedang

Pastikan: - Video mudah dipahami. - Slide jelas. - Technical Narrative
menjelaskan proses berpikir. - Juri dapat memahami produk tanpa perlu
bertanya langsung.

### E. Challenge Point

Challenge bersifat opsional. Tidak mengerjakan challenge tidak
mengurangi nilai. Total bonus dari semua challenge dibatasi maksimal
**+20 poin**.

**Prinsip utama: CORE PRODUCT \> CHALLENGE POINT.**

Lebih baik memiliki produk inti yang benar-benar berjalan daripada
banyak challenge yang setengah selesai.

------------------------------------------------------------------------

# 4. CHALLENGE POINT YANG BISA MENAMBAH NILAI

## 4.1 AI Agent Fungsional --- +10

Syarat: - AI Agent melakukan minimal **2 aksi berbeda**. - Aksi harus
relevan dengan use case. - Bukan sekadar chatbot tanya-jawab.

Contoh pola AI Agent:

### Action 1 --- Mengambil / mencari data

AI menerima permintaan pengguna lalu mengambil data yang relevan dari
database/API.

### Action 2 --- Melakukan perubahan / aksi

AI kemudian melakukan aksi nyata, misalnya: - membuat data, - mengubah
data, - menghapus data, - membuat rekomendasi terstruktur, - menjalankan
workflow tertentu, - menghasilkan tindakan yang benar-benar memengaruhi
aplikasi.

**Jangan membuat AI Agent hanya seperti:** \> User: "Apa itu data X?" \>
AI: "Data X adalah..."

Itu hanya chatbot dan tidak memenuhi konsep AI Agent fungsional menurut
challenge.

AI Agent harus benar-benar menjadi bagian dari workflow produk.

------------------------------------------------------------------------

# 5. DEPLOYMENT + CI/CD + DOCKER --- +8

Ketiga elemen harus ada sekaligus:

1.  Aplikasi live online.
2.  Pipeline otomatis menggunakan GitHub Actions.
3.  Dockerfile yang valid.

Jika salah satu tidak ada, challenge ini tidak terpenuhi.

Arsitektur target:

Internet ↓ Public URL ↓ Laravel Application ↓ Docker Container ↓
Database

Repository: GitHub ↓ GitHub Actions ↓ Deployment ↓ Live Application

### Backend utama

Gunakan: **Laravel**

### Catatan

Docker bukan hosting. Docker adalah cara mengemas dan menjalankan
aplikasi dalam container.

Nginx juga bukan syarat khusus challenge berdasarkan guide book. Jangan
menambahkan Nginx hanya karena merasa wajib jika tidak dibutuhkan.

Untuk deployment, pilih solusi yang paling cepat dan stabil pada hari H.

------------------------------------------------------------------------

# 6. REAL-TIME --- +8

Fitur harus: - melakukan update secara live tanpa refresh; - benar-benar
dibutuhkan oleh use case.

Jangan menambahkan real-time hanya untuk mengejar poin jika fitur
tersebut tidak memiliki alasan produk yang jelas.

Contoh use case yang mungkin cocok: - status berubah secara live; -
notifikasi langsung; - dashboard monitoring; - antrean; - kolaborasi; -
perubahan data yang harus langsung terlihat pengguna lain.

Real-time harus relevan dengan studi kasus.

------------------------------------------------------------------------

# 7. UNIT TESTING +6

Syarat: - Unit test untuk logika bisnis inti. - Coverage test
**\>80%**. - Unit test 100% sesuai ketentuan challenge. - Bukti harus
dapat ditunjukkan dalam repository / dokumentasi sesuai ketentuan.

Challenge ini dikerjakan setelah core product stabil.

Jangan mengorbankan fitur utama hanya demi mengejar coverage.

------------------------------------------------------------------------

# 8. DATABASE & PERSISTENSI --- +2

Gunakan database.

Data: - tidak hanya disimpan di localStorage; - tetap tersedia setelah
refresh; - memiliki CRUD/persistensi yang nyata jika dibutuhkan oleh use
case.

Untuk Laravel, gunakan database yang paling praktis dan stabil untuk
pengerjaan hackathon.

------------------------------------------------------------------------

# 9. AUTENTIKASI PENGGUNA --- +2

Tambahkan: - login/register yang berfungsi.

Boleh custom maupun OAuth seperti Google/GitHub sesuai ketentuan
challenge.

Jangan membuat autentikasi rumit jika studi kasus tidak membutuhkannya.

------------------------------------------------------------------------

# 10. RESPONSIVE / MULTI-PLATFORM --- +2

Aplikasi harus: - berfungsi di desktop; - berfungsi di mobile; - layout
tidak rusak; - fungsi utama tetap usable.

Prioritaskan responsive pada halaman yang benar-benar digunakan dalam
demo.

------------------------------------------------------------------------

# 11. AKSESIBILITAS --- +2

Usahakan aplikasi dapat digunakan secara mandiri oleh semua orang,
termasuk penyandang disabilitas.

Perhatikan hal seperti: - label form; - struktur heading; - kontras yang
cukup; - tombol yang jelas; - keyboard accessibility; - feedback error
yang mudah dipahami.

Kerjakan setelah core functionality stabil.

------------------------------------------------------------------------

# 12. STRATEGI PRIORITAS

Urutan pengerjaan yang disarankan:

## PRIORITAS 1 --- Core Product

Buat: - problem utama; - user utama; - workflow utama; - fitur minimum
yang membuat produk benar-benar menyelesaikan masalah.

Target: **End-to-end core functionality harus sudah berjalan.**

## PRIORITAS 2 --- Database

Tambahkan persistensi agar produk bukan sekadar prototype statis.

## PRIORITAS 3 --- AI Agent

Jika relevan dengan studi kasus, targetkan AI Agent dengan minimal 2
aksi berbeda.

## PRIORITAS 4 --- UX

Perbaiki: - loading; - error state; - empty state; - validasi; -
responsive; - alur navigasi.

## PRIORITAS 5 --- Challenge

Jika waktu memungkinkan: - Docker; - deployment; - GitHub Actions; -
real-time; - testing; - authentication; - accessibility.

## PRIORITAS 6 --- Submission

Pastikan: - video; - GitHub; - slide; - Technical Narrative.

**Jangan mengejar challenge sampai core product terbengkalai.**

------------------------------------------------------------------------

# 13. TECH STACK DEFAULT

Backend: **Laravel**

Gunakan Laravel sebagai fondasi backend/API/application logic.

Database: Pilih database yang paling cepat dan stabil untuk kebutuhan
studi kasus.

Frontend: Pilih pendekatan yang paling cepat untuk menghasilkan UI yang
usable dan mudah didemokan.

AI: Gunakan AI hanya jika memberikan nilai nyata pada produk atau
mempercepat development.

Deployment: Targetkan aplikasi dapat diakses melalui public URL.

Container: Gunakan Docker jika mengejar challenge deployment.

CI/CD: Gunakan GitHub Actions jika mengejar challenge deployment.

------------------------------------------------------------------------

# 14. ATURAN UNTUK AI CODING AGENT

Ketika pengguna memberikan studi kasus, AI harus melakukan proses
berikut:

## Step 1 --- Analisis Studi Kasus

Identifikasi: - masalah utama; - siapa pengguna; - tujuan pengguna; -
input; - output; - workflow; - constraint; - fitur yang benar-benar
dibutuhkan.

Jangan langsung membuat kode sebelum memahami masalah.

------------------------------------------------------------------------

## Step 2 --- Tentukan MVP

Buat daftar:

### Must Have

Fitur tanpa fitur tersebut produk gagal menyelesaikan studi kasus.

### Should Have

Fitur yang meningkatkan kualitas produk.

### Nice to Have

Fitur tambahan jika masih ada waktu.

Core product harus selesai terlebih dahulu.

------------------------------------------------------------------------

## Step 3 --- Cari Peluang Challenge

Setelah MVP ditentukan, analisis studi kasus untuk menemukan challenge
yang NATURAL.

Buat tabel internal:

  Challenge                    Potensi    Alasan
  ---------------------------- ---------- --------
  AI Agent +10                 Ya/Tidak   ...
  Deploy + CI/CD + Docker +8   Ya/Tidak   ...
  Real-time +8                 Ya/Tidak   ...
  Unit Test +6                 Ya/Tidak   ...
  Database +2                  Ya/Tidak   ...
  Auth +2                      Ya/Tidak   ...
  Responsive +2                Ya/Tidak   ...
  Accessibility +2             Ya/Tidak   ...

**Jangan memaksakan challenge yang tidak relevan dengan use case.**

------------------------------------------------------------------------

# 15. AI AGENT DESIGN RULE

Jika AI Agent memungkinkan, desain minimal 2 aksi nyata.

Format:

User Request ↓ AI memahami intent ↓ Action 1 ↓ Action 2 ↓ Database /
service ↓ Response ↓ UI diperbarui

Contoh abstrak:

> User meminta AI membuat sesuatu.

AI: 1. mencari data yang diperlukan; 2. membuat atau mengubah data; 3.
mengembalikan hasil kepada pengguna.

AI Agent harus mempunyai tool/action yang jelas dan dapat dibuktikan
ketika demo.

------------------------------------------------------------------------

# 16. LARAVEL DEVELOPMENT RULES

Gunakan struktur Laravel yang jelas.

Utamakan: - routes; - controllers; - models; - migrations; -
requests/validation; - services jika diperlukan; - API resources jika
diperlukan; - database relationships; - authentication jika memang
dibutuhkan.

Jangan membuat arsitektur terlalu kompleks untuk hackathon satu hari.

Prinsip: **Simple, maintainable, demonstrable.**

------------------------------------------------------------------------

# 17. DATABASE RULES

Sebelum membuat tabel, tentukan: - entity; - attribute; -
relationship; - primary key; - foreign key; - kebutuhan CRUD.

Hindari membuat tabel yang tidak dibutuhkan oleh workflow utama.

Jika database dipakai untuk challenge, pastikan demo menunjukkan bahwa
data benar-benar tersimpan.

------------------------------------------------------------------------

# 18. UI/UX RULES

Karena waktu terbatas:

Jangan: - menghabiskan terlalu banyak waktu membuat UI dekoratif; -
membuat animasi yang tidak diperlukan; - membuat dashboard besar tetapi
tidak punya fungsi; - menambah halaman yang tidak mendukung studi kasus.

Utamakan: - jelas; - cepat dipahami; - responsive; - state lengkap; -
workflow pendek; - demo-friendly.

Minimal pikirkan: - loading state; - empty state; - error state; -
success feedback; - form validation.

------------------------------------------------------------------------

# 19. DOKUMENTASI PROSES

Selama pengerjaan, simpan catatan:

## Problem & Scope

Apa masalah yang diselesaikan? Siapa penggunanya? Apa fitur inti?

## Hambatan Teknis

Error atau hambatan nyata apa yang terjadi? Bagaimana cara
menyelesaikannya?

## Perubahan Arah

Apakah ada pendekatan yang dibuang? Kenapa diganti?

## Keputusan Stack & Tools

Apa tools/library yang dipilih? Apa satu keputusan teknis yang dibuat
sendiri? Kenapa?

## Satu Hal Berikutnya

Kalau punya waktu tambahan, apa satu hal paling konkret yang akan
dibuat? Kenapa itu diprioritaskan?

------------------------------------------------------------------------

# 20. VIDEO DEMO MAKSIMAL 5 MENIT

Struktur:

### 00:00--00:30

Perkenalan: - siapa kamu; - apa yang dibuat.

### 00:30--01:00

Problem: - masalah; - siapa pengguna.

### 01:00--03:30

Live Demo: - workflow utama; - fitur utama; - AI Agent jika ada; -
database/persistensi; - challenge yang berhasil.

### 03:30--04:15

Satu keputusan teknis: - apa yang diputuskan; - kenapa; - dampaknya.

Bagian ini penting karena guide book menyebutnya sebagai pembeda antara
level Baik dan Sangat Baik.

### 04:15--04:45

Jelaskan: - tools; - AI yang digunakan; - challenge point.

### 04:45--05:00

Next thing: - satu hal yang akan dikembangkan berikutnya.

------------------------------------------------------------------------

# 21. SLIDE MAKSIMAL 10

Struktur yang disarankan:

1.  Cover
2.  Problem Statement
3.  Solution
4.  Demo / Screenshot
5.  Demo / Screenshot
6.  Demo / Screenshot
7.  Tech Stack & Technical Decision
8.  Challenge Point
9.  Challenge & Solution
10. Next Plan

------------------------------------------------------------------------

# 22. GIT WORKFLOW

Gunakan satu repository public.

Commit secara berkala selama hackathon.

Contoh:

``` text
init project
feat: create core database
feat: implement main workflow
feat: add ai agent
fix: validate main form
feat: responsive layout
feat: add docker
ci: add github actions
test: add business logic tests
docs: add hackathon documentation
```

Commit message tidak harus persis seperti contoh di atas.

Yang penting commit history mencerminkan proses pengerjaan.

------------------------------------------------------------------------

# 23. TIME MANAGEMENT

Target hari H:

## 08:30--09:00

Baca studi kasus.

Jangan coding dulu selama belum memahami: - problem; - user; -
workflow; - output.

## 09:00--12:15

Fokus: **CORE PRODUCT END-TO-END**

Target: - aplikasi dapat digunakan; - workflow utama selesai; - database
jika dibutuhkan sudah berjalan.

## 12:15--13:15

Break.

## 13:15--15:00

Fokus: - edge cases; - UX; - loading; - error; - empty state; -
responsive; - AI Agent; - challenge point.

## 15:00--16:00

Fokus: - dokumentasi; - Technical Narrative; - slide; - video
preparation; - deployment jika belum selesai.

## 16:00--16:30

Final verification: - aplikasi; - GitHub; - public URL; - video; -
slide; - PDF; - challenge evidence.

## SEBELUM 16:45

Submit.

**Jangan menunggu menit terakhir untuk upload.**

------------------------------------------------------------------------

# 24. CARA AI HARUS MEMBANTU PENGGUNA

Ketika pengguna memberikan studi kasus, AI harus:

1.  Menjelaskan inti masalah dalam bahasa sederhana.
2.  Menentukan target user.
3.  Menentukan MVP.
4.  Menentukan fitur inti.
5.  Menentukan database.
6.  Menentukan workflow Laravel.
7.  Menentukan peluang AI Agent.
8.  Menentukan peluang challenge point.
9.  Membuat arsitektur sederhana.
10. Membantu implementasi secara bertahap.
11. Tidak membuat fitur yang tidak relevan.
12. Tidak mengorbankan core functionality demi challenge.
13. Membantu memastikan semua fitur dapat didemokan.
14. Membantu menyiapkan bukti challenge.
15. Membantu menyiapkan submission.

Jika ada dua pilihan teknis, pilih yang: - paling cepat; - paling
stabil; - paling mudah dipahami; - paling mudah dibuktikan dalam demo.

------------------------------------------------------------------------

# 25. JANGAN MELAKUKAN INI

AI coding agent jangan:

-   membuat produk generik yang tidak menjawab studi kasus;
-   memaksakan AI Agent jika tidak relevan;
-   membuat chatbot lalu mengklaimnya sebagai AI Agent fungsional;
-   memaksakan real-time tanpa kebutuhan;
-   mengejar semua challenge sekaligus;
-   membuat arsitektur terlalu kompleks;
-   menghabiskan sebagian besar waktu pada UI;
-   menghapus kemampuan inti demi fitur tambahan;
-   menganggap Docker sebagai hosting;
-   menganggap public URL otomatis berarti challenge deployment selesai;
-   mengklaim challenge terpenuhi tanpa bukti;
-   mengarang requirement yang tidak ada pada studi kasus;
-   mengarang data atau fakta tentang studi kasus.

------------------------------------------------------------------------

# 26. MODE RESPONS AI PADA HARI H

Setelah pengguna memberikan studi kasus, respons awal harus mempunyai
format:

## A. Ringkasan Problem

Jelaskan masalah dan user.

## B. Solusi Produk

Jelaskan produk dalam 1--2 kalimat.

## C. Core MVP

Daftar fitur yang WAJIB selesai.

## D. Challenge Opportunity

Identifikasi challenge yang secara alami cocok.

## E. Architecture

Berikan arsitektur Laravel sederhana.

## F. Database

Berikan entity dan relationship yang diperlukan.

## G. AI Agent

Jika relevan, jelaskan minimal 2 action nyata.

## H. Execution Plan

Urutkan pengerjaan berdasarkan prioritas waktu.

## I. Submission Evidence

Jelaskan apa yang harus ditunjukkan di: - video; - slide; - Technical
Narrative; - repository.

Setelah itu, baru masuk ke implementasi kode.

------------------------------------------------------------------------

# 27. TEMPLATE STUDI KASUS HARI H

**JANGAN DIISI SEBELUM STUDI KASUS RESMI DIBERIKAN.**

``` text
# STUDI KASUS HARI H

Judul:
[ISI JUDUL STUDI KASUS]

Problem:
[ISI PERMASALAHAN RESMI]

Target User:
[ISI TARGET USER]

Requirement:
[ISI REQUIREMENT RESMI]

Constraint:
[ISI CONSTRAINT RESMI]

Output yang diminta:
[ISI OUTPUT / KETENTUAN PRODUK]

Informasi tambahan dari panitia:
[ISI INFORMASI TAMBAHAN]
```

------------------------------------------------------------------------

# 28. PROMPT EKSEKUSI HARI H

Setelah bagian `STUDI KASUS HARI H` diisi, pengguna dapat memberikan
prompt:

``` text
Baca AGENT.md secara keseluruhan.

Studi kasus Hackathon SEMESTA 8 sudah saya isi di bagian STUDI KASUS HARI H.

Sekarang analisis studi kasus tersebut dan bantu saya membangun produk menggunakan Laravel.

Ikuti prioritas:
1. Core functionality.
2. Database/persistence.
3. AI Agent fungsional minimal 2 aksi jika relevan.
4. UX dan responsive.
5. Challenge point yang realistis.
6. Testing dan accessibility jika waktu memungkinkan.
7. Submission evidence.
8. Deployment/Docker/GitHub Actions hanya jika ternyata sangat cepat dan tidak mengganggu core product.

Jangan membuat fitur yang tidak relevan dengan studi kasus.

Sebelum coding, berikan:
- ringkasan problem;
- target user;
- solusi;
- MVP;
- fitur;
- database schema;
- architecture;
- AI Agent actions;
- challenge yang mungkin dicapai;
- urutan pengerjaan.

Setelah itu mulai implementasi secara bertahap.

Jika sebuah challenge tidak relevan atau berisiko mengganggu core product, jangan dipaksakan.

Semua keputusan teknis harus sederhana, cepat, stabil, dan mudah didemokan.
```

------------------------------------------------------------------------

# 29. PRINSIP TERAKHIR

**Build the product first. Chase the points second.**

Produk yang: - berfungsi; - menjawab studi kasus; - memiliki workflow
jelas; - dapat didemokan; - memiliki submission lengkap

lebih penting daripada banyak fitur tambahan yang tidak selesai.

AI digunakan sebagai alat bantu development, tetapi peserta tetap harus
memahami fundamental dan mampu menjelaskan keputusan teknis yang dibuat.
