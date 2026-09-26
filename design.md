# DESIGN.md — Reference-Driven UI Design System

## 0. TUJUAN UTAMA

File ini adalah aturan WAJIB untuk AI coding agent dalam membangun dan mengembangkan tampilan website berdasarkan gambar referensi yang diberikan pengguna.

Tujuan utama:

> Analisis gambar referensi secara menyeluruh, identifikasi sistem desain yang digunakan, lalu implementasikan desain tersebut sedekat mungkin dengan referensi sebagai DESIGN UTAMA website.

Agent TIDAK BOLEH membuat desain baru berdasarkan preferensi pribadi, tren UI terbaru, asumsi, atau kreativitas yang mengubah karakter visual referensi.

Referensi gambar adalah sumber kebenaran utama untuk visual.

Jika pengguna memberikan beberapa gambar referensi, semua gambar harus dianalisis sebagai satu sistem desain yang konsisten, kecuali pengguna secara eksplisit mengatakan bahwa gambar tersebut menggunakan desain yang berbeda.

---

# 1. ATURAN PALING PENTING

## 1.1 Reference = Source of Truth

Setiap gambar referensi harus dianggap sebagai visual specification.

Agent wajib berusaha mempertahankan:

- layout
- spacing
- ukuran elemen
- proporsi
- posisi elemen
- alignment
- typography
- font
- font weight
- font size
- line height
- letter spacing
- warna
- border
- border radius
- shadow
- icon style
- button style
- card style
- navbar/sidebar style
- image ratio
- visual hierarchy
- whitespace
- density
- responsive behavior yang dapat disimpulkan dari referensi

Jangan mengganti desain hanya karena agent menganggap ada desain yang "lebih modern", "lebih bagus", "lebih clean", atau "lebih UX-friendly".

---

## 1.2 JANGAN MENGUBAH DESAIN REFERENSI SECARA MENDADAK

Setelah design system berhasil ditentukan dari gambar referensi, design tersebut menjadi baseline permanen.

Agent TIDAK BOLEH secara tiba-tiba:

- mengganti warna utama
- mengganti font
- mengganti layout
- memindahkan sidebar
- mengubah navbar
- mengubah bentuk card
- mengubah radius
- mengubah spacing
- mengubah ukuran tombol
- mengubah hierarchy
- menambahkan gradient yang tidak ada
- menghapus whitespace yang terlihat penting
- mengubah visual density
- mengganti icon style
- membuat komponen menjadi terlalu rounded
- membuat desain menjadi terlalu minimalis
- membuat desain menjadi terlalu ramai
- menggunakan template UI yang berbeda
- mencampurkan design system lain
- mengganti desain hanya karena library/framework menyediakan default style tertentu

Perubahan visual hanya boleh dilakukan apabila:

1. pengguna memintanya secara eksplisit;
2. gambar referensi baru menunjukkan perubahan;
3. perubahan diperlukan agar fungsi yang diminta dapat bekerja, tetapi perubahan tersebut harus seminimal mungkin;
4. terdapat konflik nyata antarreferensi dan pengguna memberikan arahan untuk memilih salah satunya.

Jika terjadi perubahan, pertahankan semua bagian lain yang sudah sesuai.

---

# 2. WORKFLOW WAJIB SEBELUM CODING UI

Jangan langsung menulis kode UI setelah menerima gambar.

Lakukan proses berikut:

1. Inspect gambar.
2. Analisis keseluruhan layout.
3. Analisis struktur halaman.
4. Analisis grid dan alignment.
5. Analisis spacing.
6. Analisis typography.
7. Identifikasi font.
8. Analisis warna.
9. Analisis komponen UI.
10. Analisis iconography.
11. Analisis border/radius/shadow.
12. Analisis image treatment.
13. Analisis whitespace.
14. Analisis visual hierarchy.
15. Analisis responsive clues jika tersedia.
16. Bentuk design tokens.
17. Bentuk component rules.
18. Baru implementasikan UI.

Jika ada bagian yang tidak dapat dipastikan dari gambar, jangan mengarang secara agresif. Gunakan pendekatan paling konservatif yang tetap konsisten dengan referensi.

---

# 3. ANALISIS GAMBAR REFERENSI

Untuk setiap gambar, agent harus memahami minimal:

## 3.1 Page Structure

Identifikasi:

- header
- navbar
- sidebar
- hero
- breadcrumb
- main content
- section
- card
- table
- form
- footer
- floating element
- modal
- CTA
- navigation control

Catat hubungan antar bagian.

Contoh analisis:

```text
Page
├── Header
├── Sidebar
└── Main Content
    ├── Page Header
    ├── Summary Cards
    ├── Main Content Card
    └── Secondary Content
```

Jangan langsung menganggap struktur berdasarkan framework. Struktur harus mengikuti apa yang terlihat.

---

# 4. LAYOUT & GRID

Analisis secara detail:

- max-width
- container width
- content width
- sidebar width
- navbar height
- section width
- column count
- column gap
- row gap
- padding kiri/kanan
- padding atas/bawah
- alignment
- vertical rhythm
- horizontal rhythm

Perhatikan apakah layout:

- full width
- centered
- asymmetric
- two-column
- three-column
- sidebar + content
- card grid
- dashboard grid
- editorial layout
- stacked layout

Jika terlihat ada whitespace besar, pertahankan whitespace tersebut.

Jangan menganggap whitespace sebagai ruang yang harus diisi.

---

# 5. SPACING SYSTEM

Analisis spacing visual secara konsisten.

Perhatikan:

- page padding
- section spacing
- card padding
- card gap
- element gap
- text-to-icon gap
- heading-to-description gap
- label-to-input gap
- button padding
- navbar item spacing

Buat spacing tokens berdasarkan referensi.

Contoh:

```css
--space-xs: ...;
--space-sm: ...;
--space-md: ...;
--space-lg: ...;
--space-xl: ...;
--space-2xl: ...;
```

Nilainya harus mengikuti hasil analisis gambar, bukan angka acak.

Jika ukuran tidak dapat diketahui secara pasti, prioritaskan konsistensi visual.

---

# 6. TYPOGRAPHY ANALYSIS

Typography adalah bagian WAJIB dari analisis.

Agent harus menganalisis:

## 6.1 Font Family

Identifikasi kemungkinan font berdasarkan:

- bentuk huruf
- karakter `a`
- karakter `g`
- angka
- bentuk kapital
- tinggi x-height
- ketebalan
- proporsi
- bentuk tanda baca

Jika font dapat diidentifikasi secara kuat, gunakan font tersebut.

Jika tidak dapat dipastikan:

1. cari kecocokan visual terdekat;
2. jangan mengganti dengan font populer secara asal;
3. dokumentasikan bahwa font merupakan estimasi.

Jangan menggunakan Times New Roman kecuali memang terlihat pada referensi atau pengguna memintanya.

---

## 6.2 Font Hierarchy

Analisis:

- Display / Hero
- H1
- H2
- H3
- H4
- body
- caption
- label
- button
- navigation
- metadata
- placeholder

Untuk setiap level, tentukan:

- font family
- font size
- font weight
- line height
- letter spacing
- color

Contoh token:

```css
--font-display: ...;
--font-heading: ...;
--font-body: ...;
--font-label: ...;

--text-xs: ...;
--text-sm: ...;
--text-base: ...;
--text-lg: ...;
--text-xl: ...;
--text-2xl: ...;
```

Jangan menggunakan satu ukuran font untuk seluruh interface.

---

# 7. COLOR ANALYSIS

Identifikasi seluruh warna utama yang terlihat.

Minimal analisis:

- primary
- secondary
- accent
- background
- surface
- card
- text-primary
- text-secondary
- muted
- border
- success
- warning
- danger
- info
- hover
- active
- disabled

Jika memungkinkan, estimasikan HEX.

Contoh:

```css
:root {
    --color-primary: #...;
    --color-secondary: #...;
    --color-accent: #...;

    --color-background: #...;
    --color-surface: #...;

    --color-text-primary: #...;
    --color-text-secondary: #...;
    --color-text-muted: #...;

    --color-border: #...;

    --color-success: #...;
    --color-warning: #...;
    --color-danger: #...;
}
```

Jangan membuat palette baru yang tidak terdapat pada referensi kecuali dibutuhkan untuk state yang tidak ditampilkan.

---

# 8. VISUAL STYLE

Tentukan karakter visual referensi secara deskriptif dan teknis.

Analisis apakah desain memiliki:

- flat style
- minimal style
- editorial style
- corporate style
- playful style
- modern SaaS style
- glassmorphism
- neumorphism
- brutalism
- soft UI
- dense dashboard
- spacious interface
- monochrome
- colorful interface

Jangan memaksakan label jika tidak sesuai.

Yang paling penting adalah meniru karakter visual yang benar-benar terlihat.

---

# 9. COMPONENT ANALYSIS

Identifikasi setiap komponen yang muncul.

## Buttons

Analisis:

- height
- width
- padding
- radius
- font
- weight
- icon
- icon position
- border
- shadow
- background
- hover
- active

## Cards

Analisis:

- radius
- padding
- border
- shadow
- background
- header
- footer
- internal spacing

## Inputs

Analisis:

- height
- radius
- border
- placeholder
- label
- icon
- focus state
- error state

## Navigation

Analisis:

- navbar height
- sidebar width
- active item
- icon
- text
- spacing
- separators
- collapse behavior

## Tables

Analisis:

- header height
- row height
- borders
- typography
- alignment
- action buttons
- pagination

---

# 10. ICONOGRAPHY

Agent wajib menganalisis:

- icon library style
- stroke width
- filled/outline
- icon size
- icon-to-text spacing
- icon alignment

Jangan mencampur icon style.

Contoh masalah yang harus dihindari:

```text
Lucide outline icons
+
Material filled icons
+
Font Awesome solid icons
```

Jika referensi terlihat menggunakan satu style, gunakan style tersebut secara konsisten.

---

# 11. BORDER, RADIUS, SHADOW

Analisis:

## Border

- thickness
- color
- opacity
- location

## Radius

Identifikasi apakah:

- sharp
- slightly rounded
- medium rounded
- highly rounded
- pill

Jangan otomatis memberikan `border-radius: 16px` ke semua komponen.

## Shadow

Perhatikan:

- blur
- spread
- opacity
- direction
- elevation

Jika referensi hampir tidak memiliki shadow, jangan menambahkan shadow berat.

---

# 12. IMAGE ANALYSIS

Untuk gambar/foto/illustration, analisis:

- aspect ratio
- crop
- object-fit
- object-position
- radius
- overlay
- opacity
- border
- background
- placement

Jangan mengganti image treatment secara bebas.

---

# 13. RESPONSIVE DESIGN

Jika hanya tersedia satu desktop screenshot:

Jangan mengubah desktop design hanya untuk membuat responsive.

Buat responsive behavior dengan prinsip:

> Preserve the visual identity of the reference at every breakpoint.

Prioritas:

1. Desktop harus semirip mungkin.
2. Tablet menyesuaikan layout tanpa mengubah style.
3. Mobile melakukan stacking/collapse jika diperlukan.
4. Typography hanya berubah jika memang diperlukan agar layout tidak rusak.
5. Warna, radius, component identity, dan visual hierarchy tetap konsisten.

Jika tersedia screenshot mobile, gunakan screenshot tersebut sebagai source of truth tambahan.

---

# 14. DESIGN TOKENS

Setelah analisis, buat satu sumber design tokens.

Contoh:

```css
:root {
    /* Colors */
    --color-primary: ...;
    --color-background: ...;

    /* Typography */
    --font-primary: ...;

    /* Spacing */
    --space-1: ...;
    --space-2: ...;
    --space-3: ...;

    /* Radius */
    --radius-sm: ...;
    --radius-md: ...;
    --radius-lg: ...;

    /* Shadows */
    --shadow-sm: ...;
    --shadow-md: ...;
}
```

Semua komponen harus menggunakan token tersebut.

Jangan membuat nilai visual acak berulang-ulang di berbagai file.

---

# 15. COMPONENT CONSISTENCY

Setiap komponen baru harus mengikuti design system yang sudah dianalisis.

Misalnya:

Jika button referensi memiliki:

- radius kecil
- font medium
- height tertentu
- padding tertentu

Maka button baru harus mengikuti pola tersebut.

Jangan membuat:

```text
Button A = 6px radius
Button B = 20px radius
Button C = pill
```

kecuali referensi memang menunjukkan variasi tersebut.

---

# 16. PIXEL-ACCURATE IMPLEMENTATION

Target implementasi:

> Match the reference as closely as reasonably possible.

Perhatikan secara visual:

- position
- size
- spacing
- proportions
- typography
- colors
- alignment
- component dimensions
- whitespace

Setelah implementasi, lakukan visual comparison.

Checklist:

```text
[ ] Layout sama
[ ] Header sama
[ ] Sidebar sama
[ ] Content width sama
[ ] Spacing sama
[ ] Font sama/dekat
[ ] Font weight sama
[ ] Warna sama
[ ] Card sama
[ ] Button sama
[ ] Radius sama
[ ] Shadow sama
[ ] Icon style sama
[ ] Image ratio sama
[ ] Visual hierarchy sama
```

---

# 17. VISUAL REGRESSION RULE

Setiap kali mengubah UI, jangan hanya memeriksa komponen yang sedang diedit.

Periksa kembali halaman secara keseluruhan.

Perubahan pada satu komponen tidak boleh merusak design system yang sudah ada.

Jika perubahan diperlukan:

1. identifikasi bagian yang berubah;
2. pertahankan bagian lain;
3. pastikan perubahan tetap konsisten dengan referensi.

---

# 18. NO UNAUTHORIZED REDESIGN

Agent dilarang melakukan redesign tanpa instruksi.

Contoh yang DILARANG:

- "Saya membuat navbar lebih modern."
- "Saya mengganti font agar lebih readable."
- "Saya membuat card lebih rounded."
- "Saya mengubah warna agar lebih menarik."
- "Saya menambahkan gradient."
- "Saya mengganti layout agar lebih clean."
- "Saya menggunakan dashboard template yang lebih bagus."

Jika referensi menunjukkan sesuatu yang berbeda, referensi harus diprioritaskan.

---

# 19. FRAMEWORK DEFAULT TIDAK BOLEH MENGALAHKAN REFERENSI

Jika menggunakan:

- Bootstrap
- Tailwind
- Laravel Blade
- React
- Vue
- Flutter
- component library
- UI kit

default style framework tidak boleh menentukan tampilan akhir.

Framework hanya digunakan sebagai alat implementasi.

Design reference tetap menjadi source of truth.

---

# 20. JANGAN MENGADA-ADA DETAIL YANG TIDAK TERLIHAT

Jika gambar tidak menunjukkan:

- hover
- dropdown
- modal
- mobile layout
- error state
- loading state

jangan mengklaim bahwa detail tersebut pasti sama dengan referensi.

Buat state tambahan seminimal mungkin dan tetap mengikuti design language.

---

# 21. SAAT REFERENSI BARU DIBERIKAN

Jika pengguna memberikan gambar referensi baru:

1. Analisis gambar baru.
2. Bandingkan dengan design system yang sudah ada.
3. Tentukan apakah gambar tersebut:
   - memperluas design system;
   - menunjukkan variasi komponen;
   - mengganti halaman tertentu;
   - atau benar-benar mengganti design system.
4. Jangan otomatis mengganti seluruh website.
5. Terapkan perubahan hanya pada area yang dimaksud.

Jika pengguna mengatakan:

> "Gunakan gambar ini sebagai referensi utama."

Maka gambar tersebut menjadi source of truth utama.

---

# 22. SAAT ADA KONFLIK ANTAR REFERENSI

Jika dua gambar memiliki perbedaan:

Jangan memilih secara acak.

Analisis:

- apakah perbedaannya karena komponen berbeda?
- apakah perbedaannya karena state berbeda?
- apakah perbedaannya karena halaman berbeda?
- apakah salah satu merupakan mobile?
- apakah salah satu merupakan modal/overlay?

Jika tetap tidak jelas, gunakan pendekatan yang paling konsisten dengan mayoritas visual reference.

Jangan melakukan redesign global hanya karena satu perbedaan.

---

# 23. ANALISIS HARUS DITULIS SEBELUM IMPLEMENTASI

Sebelum coding, agent harus menghasilkan internal design analysis yang mencakup:

```text
REFERENCE ANALYSIS
------------------
Layout:
...

Typography:
...

Font:
...

Colors:
...

Spacing:
...

Components:
...

Icons:
...

Borders:
...

Radius:
...

Shadows:
...

Images:
...

Responsive:
...

Overall Style:
...
```

Analisis ini digunakan sebagai blueprint implementasi.

Jika pengguna meminta, analisis tersebut dapat ditampilkan secara eksplisit.

---

# 24. IMPLEMENTATION PRIORITY

Urutan implementasi:

1. Global background
2. Container/layout
3. Header/navigation/sidebar
4. Typography
5. Main content structure
6. Cards/sections
7. Buttons/forms
8. Icons
9. Images
10. Micro-spacing
11. Responsive behavior
12. States

Jangan melakukan micro-polish sebelum struktur utama benar.

---

# 25. QUALITY CHECK SEBELUM SELESAI

Sebelum mengatakan UI selesai, lakukan audit:

## Layout
- Apakah struktur sama?
- Apakah posisi elemen sama?
- Apakah spacing sama?

## Typography
- Apakah font sesuai?
- Apakah weight sesuai?
- Apakah ukuran sesuai?
- Apakah line-height sesuai?

## Color
- Apakah warna sesuai?
- Apakah background sesuai?
- Apakah contrast mengikuti referensi?

## Components
- Apakah button sesuai?
- Apakah card sesuai?
- Apakah input sesuai?
- Apakah navigation sesuai?

## Visual
- Apakah radius sesuai?
- Apakah shadow sesuai?
- Apakah icon style sesuai?
- Apakah whitespace dipertahankan?

## Consistency
- Apakah halaman baru mengikuti design system?
- Apakah tidak ada komponen yang terlihat berasal dari design system lain?

---

# 26. ATURAN KETIKA USER MEMINTA PERUBAHAN

Jika pengguna berkata:

> "Ubah tombol ini menjadi merah."

Hanya ubah tombol tersebut.

Jangan sekaligus:

- mengganti font
- mengganti radius
- mengganti card
- mengganti navbar
- mengganti background

Jika pengguna meminta perubahan global, baru terapkan secara global.

---

# 27. ATURAN KETIKA USER MEMINTA FITUR BARU

Jika fitur baru membutuhkan UI yang belum ada pada referensi:

1. Cari komponen paling mirip pada referensi.
2. Gunakan visual language yang sama.
3. Reuse:
   - font
   - color
   - spacing
   - radius
   - shadow
   - icon style
4. Jangan menciptakan design system baru.

Fitur baru harus terlihat seperti bagian asli dari website.

---

# 28. VISUAL PRIORITY

Jika harus memilih antara:

```text
functional tetapi sedikit berbeda
vs
visual sangat berbeda tetapi fungsional
```

tetap buat fungsi berjalan, tetapi setelah itu lakukan visual correction agar kembali sedekat mungkin dengan referensi.

---

# 29. DESIGN FREEZE

Setelah reference analysis dan implementasi baseline selesai, anggap design system dalam kondisi:

> DESIGN FROZEN

Artinya:

- jangan mengubah secara spontan;
- jangan melakukan redesign;
- jangan mengikuti tren UI;
- jangan mengganti library hanya karena preferensi agent;
- jangan mengubah warna;
- jangan mengubah font;
- jangan mengubah layout.

Perubahan hanya berasal dari instruksi pengguna atau referensi baru.

---

# 30. FINAL COMMAND UNTUK AI CODING AGENT

Gunakan instruksi berikut sebagai prinsip eksekusi:

```text
ANALYZE THE REFERENCE FIRST.

Do not start by inventing a UI.

Treat the provided reference image(s) as the primary visual source of truth.

Analyze:
- layout
- spacing
- grid
- typography
- font family
- font weights
- font sizes
- line heights
- letter spacing
- colors
- borders
- border radius
- shadows
- icons
- imagery
- component structure
- visual hierarchy
- whitespace
- responsive behavior

Then convert the analysis into a consistent design system and implement the website based on that system.

The implementation must visually match the reference as closely as reasonably possible.

Do NOT redesign the reference.

Do NOT modernize the reference.

Do NOT beautify the reference according to your own preference.

Do NOT introduce a different UI style.

Do NOT randomly change colors, fonts, spacing, radius, shadows, layout, or component shapes.

Do NOT replace the reference with a framework's default styling.

If a new component is required and it does not exist in the reference, construct it using the same visual language as the existing reference.

Once the design baseline has been established, treat it as DESIGN FROZEN.

Never make sudden visual changes to the established design system.

Only change the design when:
1. the user explicitly requests the change;
2. a new reference image requires the change;
3. a functional requirement makes a minimal change necessary.

When making a change, preserve all unrelated parts of the established design.

The goal is not to create a UI that is merely inspired by the reference.

The goal is to reproduce the reference's visual system as faithfully as possible while keeping the application functional, maintainable, responsive, and consistent.
```

---

# 31. CORE PRINCIPLE

> REFERENCE FIRST. ANALYZE SECOND. SYSTEMIZE THIRD. IMPLEMENT FOURTH. POLISH LAST.

> DO NOT REDESIGN WHAT THE USER ALREADY DESIGNED.

> CONSISTENCY IS MORE IMPORTANT THAN CREATIVITY.

> A NEW FEATURE MUST LOOK LIKE IT BELONGS TO THE SAME ORIGINAL DESIGN SYSTEM.
