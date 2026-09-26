# BUILD.md — Panduan Teknis Build & Spesifikasi Fitur Penambah Poin

> Pelengkap `AGENTS.md`. Kalau `AGENTS.md` mengatur *urutan & disiplin kerja*, file ini
> mengatur *cara teknis* membangun tiap bagian — termasuk detail implementasi semua
> challenge poin, supaya AI tahu persis apa yang harus dibuat, bagaimana caranya,
> dan kapan dianggap "lolos syarat".

---

## 1. Pemilihan Tech Stack (Prinsip: Cepat & Minim Risiko)

Untuk solo hackathon 6 jam, pilih stack yang:
- Sudah familiar bagi AI **dan** saya (bukan yang eksotis)
- Punya banyak boilerplate/starter siap pakai (hemat waktu setup)
- Mendukung testing dan realtime tanpa setup rumit

**Rekomendasi default kalau tidak ada preferensi khusus:**
- Frontend: **React + Vite + Tailwind CSS**
- Backend: **Node.js + Express** (atau Next.js API routes kalau mau satu framework saja)
- Database: **Supabase (Postgres)** atau **Firebase Firestore** — keduanya punya realtime bawaan, sekaligus hemat waktu setup auth & hosting
- Testing: **Vitest/Jest** (frontend & backend JS) dengan `--coverage`
- Deploy: **Vercel** (frontend/Next.js) atau **Railway/Render** (kalau backend terpisah)

> AI: jangan ganti stack di tengah jalan kecuali benar-benar buntu. Ganti stack = reset waktu = risiko besar.

---

## 2. Struktur Project (Skeleton Awal)

Buat struktur ini di awal (bagian 1 dari urutan build di AGENTS.md), supaya bagian-bagian berikutnya tinggal diisi, bukan dibongkar ulang:

```
project/
├── src/
│   ├── components/        # UI reusable
│   ├── pages/ (atau routes/)
│   ├── lib/                # koneksi db, helper, business logic murni
│   │   └── logic/          # <- ISI DI SINI logika bisnis inti (target unit test)
│   ├── hooks/               # kalau realtime pakai hook custom
│   └── tests/               # unit test
├── server/ (jika backend terpisah)
├── docs/
│   └── narasi-teknis-draft.md   # draft berjalan, lihat AGENTS.md Bagian 5
├── .github/workflows/       # kalau ambil challenge CI/CD
├── package.json
└── README.md
```

**Prinsip penting:** pisahkan **logika bisnis murni** (fungsi kalkulasi/validasi/aturan) dari komponen UI, taruh di `src/lib/logic/`. Ini bukan sekadar rapi — ini yang membuat unit testing (Bagian 4) jadi mudah dan cepat ditulis, karena fungsi murni tidak butuh mocking UI/DOM.

---

## 3. Alur Build per Bagian (Detail Teknis)

Mengikuti urutan di `AGENTS.md` Bagian 3, ini detail teknisnya:

### Bagian 1 — Setup
- Init project, install dependency inti saja (jangan install library "buat jaga-jaga")
- Setup koneksi database, tes 1 query paling sederhana (misal `SELECT 1`) untuk pastikan koneksi hidup sebelum lanjut
- Commit di titik ini (`chore: project setup`)

### Bagian 2 — Alur CRUD Inti End-to-End
- Pilih **1 entitas paling penting** dari problem statement, buat alur penuh: form input → simpan ke DB → tampil di UI → bisa diedit/dihapus
- Tujuan bagian ini murni membuktikan pipeline nyambung dari ujung ke ujung, bukan kelengkapan fitur
- Commit di titik ini (`feat: core CRUD flow`)

### Bagian 3 — Fitur Inti Tambahan
- Tambah fitur sesuai scope MVP, **satu per satu**, commit tiap fitur selesai
- Setiap fitur baru: tulis dulu fungsi logikanya di `src/lib/logic/`, baru sambungkan ke UI — ini mempermudah testing nanti

### Bagian 4 — UI Dirapikan
- Konsistensi warna/spacing, bukan redesign total
- Pastikan alur utama bisa dipakai orang yang belum pernah lihat aplikasinya (jangan asumsikan user tahu harus klik apa)

### Bagian 5 — Edge Case
- Untuk tiap alur utama, tambahkan: state loading (spinner/skeleton), state error (pesan jelas, bukan crash), state kosong (misal "belum ada data" bukan halaman blank)

---

## 4. Spesifikasi Teknis Challenge Poin (Semua Opsi)

AI harus paham **syarat lolos** tiap challenge sebelum mengerjakan — supaya tidak kerja setengah jalan lalu tidak dihitung poinnya.

### 🔴 Bobot Tinggi

**Integrasi AI Agent Fungsional (+10)**
- Syarat: AI agent melakukan **minimal 2 aksi berbeda** yang relevan ke use case (bukan cuma chatbot tanya-jawab)
- Contoh teknis: panggil LLM API (mis. Claude API) untuk 1) mengekstrak/mengklasifikasi data user, 2) generate rekomendasi/ringkasan berdasarkan data itu — dua fungsi berbeda, bukan dua prompt template yang mirip
- Implementasi: satu endpoint/fungsi yang menerima input → panggil AI → parsing output terstruktur (gunakan format JSON dari prompt) → aksi nyata di aplikasi (simpan, update state, trigger notifikasi)

**Deploy & CI/CD Pipeline & Docker (+8)**
- Syarat: **tiga-tiganya sekaligus** — aplikasi live online + pipeline GitHub Actions otomatis + Dockerfile valid. Kurang satu = tidak dihitung.
- Implementasi cepat:
  - `Dockerfile` sederhana (multi-stage build kalau Node.js)
  - `.github/workflows/deploy.yml` — minimal jalankan test + build otomatis saat push
  - Deploy ke Vercel/Railway/Render (yang paling cepat setup, hindari config server manual)
- **Kerjakan hanya jika bagian 1–6 di AGENTS.md sudah selesai** — resiko tinggi menghabiskan waktu untuk debug config deployment.

**Fitur Real-time (+8)** — lihat Bagian 5 di bawah, dibahas detail karena diminta khusus.

**Unit Testing 100% & Coverage >80% (+6)** — lihat Bagian 6 di bawah, dibahas detail karena diminta khusus.

### 🟡 Bobot Standar (masing-masing +2, ambil kalau core sudah solid & masih ada waktu)

**Database & Persistensi Data (+2)**
- Syarat: pakai database sungguhan (Postgres/Firestore/dst), bukan `localStorage`. Data tidak hilang saat refresh browser.
- Catatan: kalau stack sudah pakai Supabase/Firebase dari awal (Bagian 1), poin ini **otomatis lolos** tanpa kerja tambahan — jadi prioritaskan pilihan stack yang sudah cover ini dari awal.

**Autentikasi Pengguna (+2)**
- Syarat: login/register berfungsi — custom (email+password) atau OAuth (Google/GitHub)
- Implementasi tercepat: pakai auth bawaan Supabase/Firebase (`supabase.auth.signIn...`) — hindari bikin sistem auth custom dari nol, terlalu makan waktu.

**Multi-platform / Responsive (+2)**
- Syarat: tampilan & fungsi tetap baik di desktop **dan** mobile
- Implementasi: pakai utility responsive Tailwind (`sm:`, `md:`, `lg:`) sejak awal membangun komponen, jangan ditambal di akhir.

**Aksesibilitas / a11y (+2)**
- Syarat: bisa dipakai mandiri oleh penyandang disabilitas
- Implementasi cepat: label `alt` di semua gambar, `<label>` yang terhubung ke tiap input form, kontras warna cukup, semua aksi bisa dijalankan lewat keyboard (tab + enter), gunakan elemen semantik (`<button>` bukan `<div onClick>`).

---

## 5. Detail Implementasi: Fitur Real-time (+8)

### Langkah wajib sebelum mulai
Jawab dulu: **"Fitur apa di aplikasi ini yang benar-benar butuh update live tanpa refresh?"** Kalau jawabannya tidak jelas dari problem statement, **jangan paksakan fitur ini** — lebih baik pastikan core solid dan ambil challenge lain yang lebih pasti relevan.

Contoh use case yang wajar pakai realtime:
- Dashboard yang berubah saat ada aksi dari user lain (mis. status pesanan, ketersediaan slot)
- Notifikasi/alert yang muncul otomatis
- Fitur kolaboratif (mis. voting, chat, papan status bersama)

### Cara implementasi (pilih sesuai stack)

**Jika pakai Supabase:**
```js
// subscribe ke perubahan tabel secara realtime
const channel = supabase
  .channel('table-changes')
  .on('postgres_changes',
    { event: '*', schema: 'public', table: 'nama_tabel' },
    (payload) => {
      // update state UI di sini, TANPA refresh manual
      updateLocalState(payload);
    }
  )
  .subscribe();
```

**Jika pakai Firebase Firestore:**
```js
import { onSnapshot, collection } from 'firebase/firestore';

onSnapshot(collection(db, 'nama_koleksi'), (snapshot) => {
  const data = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
  setState(data); // UI otomatis update
});
```

**Jika stack tidak punya realtime bawaan:** pakai **Socket.IO** (server + client) — hanya kalau waktu masih cukup banyak (>2 jam tersisa), karena butuh setup server socket terpisah.

### Syarat lolos & cara buktikan
- Update terjadi **tanpa reload halaman**
- Di demo video: tunjukkan **dua sisi/dua sesi** — buka aplikasi di dua tab/browser, lakukan aksi di satu sisi, tunjukkan sisi lain berubah otomatis
- Jelaskan di Narasi Teknis kenapa fitur ini dibutuhkan use case (bukan cuma "karena bisa")

---

## 6. Detail Implementasi: Unit Testing 100% & Coverage >80% (+6)

### Prinsip
Test **logika bisnis inti**, bukan mengetes UI atau library orang lain. Target: fungsi-fungsi murni di `src/lib/logic/` (lihat struktur Bagian 2).

### Contoh yang PANTAS ditest
- Fungsi validasi input (mis. cek format, cek batas nilai)
- Fungsi kalkulasi/skor/agregasi data
- Fungsi transformasi data (mapping, filtering dengan aturan bisnis)
- Fungsi penentu status/state machine (mis. status pesanan berubah dari A ke B kalau kondisi X)

### Contoh yang TIDAK perlu ditest (buang waktu)
- Komponen UI murni tampilan
- Fungsi bawaan library (fetch, axios call langsung) — cukup mock kalau memang harus
- Kode setup/config

### Setup cepat (Jest/Vitest)
```bash
npm install -D vitest @vitest/coverage-v8
```
`package.json`:
```json
{
  "scripts": {
    "test": "vitest run",
    "test:coverage": "vitest run --coverage"
  }
}
```

### Pola penulisan test
```js
// src/lib/logic/hitungSkor.js
export function hitungSkor(data) {
  if (!data || data.nilai < 0) throw new Error('Nilai tidak valid');
  return data.nilai * data.bobot;
}
```
```js
// src/tests/hitungSkor.test.js
import { describe, it, expect } from 'vitest';
import { hitungSkor } from '../lib/logic/hitungSkor';

describe('hitungSkor', () => {
  it('menghitung skor dengan benar', () => {
    expect(hitungSkor({ nilai: 10, bobot: 2 })).toBe(20);
  });

  it('melempar error kalau nilai negatif', () => {
    expect(() => hitungSkor({ nilai: -1, bobot: 2 })).toThrow();
  });
});
```

### Alur kerja AI (WAJIB)
1. Setiap kali selesai membuat 1 fungsi logika bisnis di `src/lib/logic/`, **langsung tulis test-nya di commit yang sama** — jangan tunda ke Bagian 6 di urutan AGENTS.md kalau fungsinya sudah selesai lebih awal.
2. Di akhir (slot waktu Bagian 6 AGENTS.md), jalankan `npm run test:coverage`, lihat laporan coverage, **tunjukkan ke saya** hasil persentasenya.
3. Kalau coverage < 80%, identifikasi fungsi mana yang belum ditest dan tambahkan test-nya — jangan menambah test asal-asalan hanya untuk mengejar angka.
4. Simpan hasil laporan coverage (screenshot dari terminal, atau file `coverage/index.html` kalau ada) ke repo, atau sertakan ringkasan angkanya di README/Narasi Teknis sebagai bukti.

---

## 7. Anti-pattern — Hal yang Harus Dihindari AI

- ❌ Menulis seluruh aplikasi dalam satu kali generate tanpa jeda review → sulit di-debug kalau error, dan saya tidak tahu progres sebenarnya
- ❌ Menambahkan library/dependency besar untuk kebutuhan kecil (mis. state management library besar untuk app sederhana)
- ❌ Mengejar semua 8 challenge poin sekaligus — fokus core dulu, ambil maksimal 2 challenge tinggi yang benar-benar relevan
- ❌ Menunda semua unit test ke akhir sesi — akan kehabisan waktu dan hasilnya asal
- ❌ Memaksakan fitur realtime padahal use case tidak butuh — mengorbankan waktu core demi poin yang berisiko tidak lolos syarat relevansi
- ❌ Refactor besar-besaran mendekati deadline — kalau sudah jalan, jangan diotak-atik lagi kecuali bug kritikal

---

## 8. Referensi Silang

- Urutan waktu & disiplin kerja → lihat `AGENTS.md` Bagian 3
- Field Narasi Teknis yang harus diisi berbarengan dengan progres build ini → `AGENTS.md` Bagian 5
- Checklist submission akhir → `AGENTS.md` Bagian 6
