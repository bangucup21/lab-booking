# Design System — Lab Management System

Dokumentasi UI/UX sistem manajemen laboratorium komputer SMKS Nuris Jember. Dokumen ini mencakup design tokens, komponen, pola interaksi, dan prinsip desain yang digunakan di seluruh sistem.

---

## Daftar Isi

- [Prinsip Desain](#prinsip-desain)
- [Color Palette](#color-palette)
- [Typography](#typography)
- [Spacing & Layout](#spacing--layout)
- [Komponen Dasar](#komponen-dasar)
- [Pola Halaman](#pola-halaman)
- [Pola Interaksi](#pola-interaksi)
- [Responsivitas](#responsivitas)
- [Aksesibilitas](#aksesibilitas)
- [Animasi & Transisi](#animasi--transisi)

---

## Prinsip Desain

### 1. Jelas Sebelum Cantik
Setiap elemen harus menyampaikan informasi dengan jelas sebelum memikirkan estetika. Status warna pada card tugas (hijau/merah/kuning) adalah contoh — pengguna tahu statusnya sebelum membaca satu kata pun.

### 2. Hirarki Visual yang Tegas
Informasi paling penting (deadline, status, nama barang rusak) ditampilkan paling besar dan paling kontras. Informasi sekunder menggunakan ukuran dan warna yang lebih redup.

### 3. Feedback Langsung
Setiap aksi memberi respons — tombol berubah saat di-hover, form menampilkan error inline, dan perubahan data (booking baru, siswa kumpul tugas) muncul realtime via WebSocket tanpa reload.

### 4. Konsistensi Lintas Halaman
Warna, radius, bayangan, dan spacing menggunakan CSS variables yang sama di semua modul. Seseorang yang sudah familiar dengan panel booking akan langsung paham panel inventaris.

### 5. Minimalis untuk Siswa, Informatif untuk Admin
Halaman publik (jadwal, tugas siswa, jurnal) dirancang seefisien mungkin — tidak ada menu yang tidak perlu. Panel admin menampilkan lebih banyak informasi karena pengguna admin memang membutuhkannya.

---

## Color Palette

### Brand Colors

Sistem menggunakan dua warna utama: **Dartmouth Green** sebagai warna primer dan **Columbia Blue** sebagai warna aksen.

```css
:root {
    /* Primary — Hijau */
    --g9: #003d24;   /* Hijau sangat gelap — hero, header kartu, sidebar */
    --g8: #00693E;   /* Dartmouth Green — warna utama, tombol, link aktif */
    --g7: #00874f;   /* Hijau sedang — hover state, aksen sekunder */

    /* Accent — Biru Muda */
    --acc:  #B9D9EB; /* Columbia Blue — teks di atas background hijau gelap */
    --acc2: #8ec8e0; /* Biru lebih dalam — border aksen, fokus state */

    /* Neutral */
    --bg:     #f0f7fb; /* Background utama halaman */
    --white:  #fff;    /* Background kartu, modal */
    --border: #cce4f0; /* Border elemen */
    --text:   #0d2416; /* Teks utama — hijau sangat gelap */
    --muted:  #6b8fa3; /* Teks sekunder — biru abu */
    --sub:    #4a7a8a; /* Teks tersier */
    --shadow: 0 2px 12px rgba(0,105,62,.07);
}
```

### Warna Semantik

| Fungsi | Warna | Penggunaan |
|--------|-------|------------|
| **Success** | `#16a34a` / `#dcfce7` | Status disetujui, dinilai, aktif |
| **Warning** | `#d97706` / `#fef3c7` | Status pending, mendesak (<24 jam) |
| **Danger** | `#dc2626` / `#fee2e2` | Error, hapus, barang rusak, deadline lewat |
| **Info** | `#1d4ed8` / `#eff6ff` | Download, informasi tambahan, materi lanjutan |
| **Neutral** | `#94a3b8` / `#f1f5f9` | Status ditutup, nonaktif, arsip |

### Penggunaan Warna per Konteks

**Status card tugas siswa:**
- Strip hijau = tugas aktif, masih bisa dikumpulkan
- Strip merah = deadline mendesak (< 24 jam)
- Strip abu = ditutup / deadline lewat

**Status booking:**
- `#ddf0e4` / hijau muda = disetujui
- `#fffbeb` / kuning muda = pending
- Dashed border / abu = ditolak

**Status barang inventaris:**
- `cond-excellent` → `#d1fae5` / teks `#065f46`
- `cond-good`      → `#dcfce7` / teks `#166534`
- `cond-fair`      → `#fef3c7` / teks `#92400e`
- `cond-poor`      → `#fee2e2` / teks `#991b1b`
- `cond-broken`    → `#fecaca` / teks `#7f1d1d`

---

## Typography

### Font Stack

| Font | Peran | Weight |
|------|-------|--------|
| **DM Sans** | Body, UI elemen | 400, 500, 600 |
| **Outfit** | Heading, angka besar, judul kartu | 600, 700, 800 |
| **Plus Jakarta Sans** | Halaman tugas siswa & guru | 400, 500, 600, 700, 800 |
| **JetBrains Mono** | Kode, token, PIN display | 400, 500 |

Font disajikan dari file lokal (`/fonts/*.woff2`) — tidak bergantung Google Fonts di production.

### Skala Tipografi

```
10px — Label uppercase, caption, meta info
11px — Badge, chip, label form uppercase
12px — Body kecil, timestamp, hint
13px — Body utama, tombol kecil
14px — Body normal, form input, nilai penting
16px — Heading kartu, judul modal
18px — Heading halaman
22px — Judul besar (hero section)
26–30px — Stat value, angka besar di dashboard
```

### Aturan Tipografi

- **Bold (700–800)** untuk semua heading, label, dan nilai penting
- **Uppercase + letter-spacing** untuk label kategori, badge status
- **line-height 1.3–1.5** untuk heading, **1.6** untuk paragraf/deskripsi
- Teks di atas background hijau gelap selalu menggunakan `--acc` (Columbia Blue) atau `#fff`

---

## Spacing & Layout

### Grid System

Sistem tidak menggunakan grid kolom tetap — menggunakan **CSS Grid dengan `auto-fill`** yang responsif:

```css
/* Task grid — auto-fill dengan min 300px */
grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));

/* Stats — 4 kolom di desktop, 2 di tablet */
grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));

/* Show page tugas — 2 kolom: form + sidebar info */
grid-template-columns: 1fr 340px;
```

### Spacing Scale

```
4px  — gap sangat kecil (icon + teks dalam badge)
6px  — gap kecil (meta-row items)
8px  — padding chip, gap antar badge
10px — padding xs button, margin kecil
12px — padding button standard, gap antar elemen
14px — padding form field, gap grid kecil
16px — padding card section, margin antar komponen
20px — padding kartu, padding modal section
24px — padding halaman, padding sidebar
36px — padding utama main content
```

### Border Radius

```css
--r:    14px;  /* Radius default untuk kartu */
--r-sm: 10px;  /* Radius untuk elemen kecil (input, badge besar) */

/* Specific: */
4px   — Badge kecil, chip
8–10px — Button kecil, input
12–14px — Button standard, kartu kecil
16–20px — Kartu utama
24px   — Modal, kartu besar, PIN screen
999px  — Pill badge, status dot
```

### Shadow

```css
/* Default kartu */
--shadow: 0 2px 12px rgba(0,105,62,.07);

/* Kartu hover */
0 8px 24px rgba(0,105,62,.1)

/* Modal */
0 24px 60px rgba(0,61,36,.35)

/* Tombol primer */
0 4px 16px rgba(0,61,36,.22)

/* Tombol primer hover */
0 8px 24px rgba(0,61,36,.3)
```

---

## Komponen Dasar

### Tombol

**Hierarki tombol (dari paling penting ke paling tidak penting):**

```
1. btn-primary   — Gradient hijau gelap, teks acc (Columbia Blue)
2. btn-secondary — Background abu, border, teks gelap
3. btn-danger    — Background merah muda, teks merah
4. btn-warning   — Background kuning muda, teks amber
5. btn-info      — Background biru muda, teks biru
6. btn-ghost     — Transparan, border, teks muted
```

**Ukuran:**
- `btn-xs` — `4px 9px`, `font-size: 11px`
- `btn-sm` — `6px 12px`, `font-size: 12px`
- Default — `9px 16px`, `font-size: 13px`
- Large — `12–15px 22px`, `font-size: 14–15px`

**Aturan:** Tombol aksi utama di setiap halaman selalu menggunakan gradient hijau. Tidak pernah ada dua tombol primer berdampingan.

### Badge & Chip

```
Badge status   — Pill (border-radius: 999px), uppercase, bold 700-800
                 Digunakan untuk status booking, status tugas, kondisi inventaris

Chip info      — Border-radius 8px, tidak uppercase
                 Digunakan untuk informasi tambahan (pertemuan ke-N, ada soal)

Badge kondisi  — cond-excellent, cond-good, cond-fair, cond-poor, cond-broken
                 Warna berbeda untuk setiap level kondisi
```

### Kartu

Semua kartu menggunakan pola yang sama:
```
background: white
border-radius: 14–20px
border: 1–1.5px solid --border
box-shadow: --shadow
overflow: hidden (untuk clip strip warna di atas)
```

**Anatomi kartu tugas siswa:**
1. Strip warna 4–5px di atas (hijau/merah/abu sesuai status)
2. Header: judul + status badge
3. Deadline block besar (area paling mencolok)
4. Meta chips (jumlah kumpul, ada soal, dll)
5. Footer: tombol aksi

**Anatomi kartu inventaris:**
1. Kolom: nama barang, kategori badge, merk, angka total/baik/rusak, kondisi badge
2. Baris rusak mendapat highlight merah muda
3. Detail muncul di sidebar card di sebelah kanan

### Form & Input

```css
/* Input default */
border: 1.5–2px solid --border;
border-radius: 10px;
padding: 10–11px 14px;
background: --bg (#f0f7fb);
transition: border-color, box-shadow;

/* Focus state */
border-color: --g7;
box-shadow: 0 0 0 3–4px rgba(0,135,79,.1);
background: white;
```

**Label:** Selalu uppercase, font-size 10–11px, letter-spacing .07em, warna `--muted`.

**Error state:** Border merah (`#ef4444`), pesan error di bawah field dengan ikon warning.

### Tabel

```css
thead: background #f4f9fd, font-size 10px uppercase, sticky top
tbody tr: border-bottom 1px #f0f6fb
tbody tr:hover: background #f8fcff
td: padding 12–13px 14–16px
```

Kolom angka selalu rata tengah. Kolom nama/teks rata kiri.

### Modal

Semua modal menggunakan pola yang sama:
- **Overlay:** `rgba(3,20,11,.5)` + `backdrop-filter: blur(5px)`
- **Box:** `max-height: 92–93vh`, scrollable body, fixed header & footer
- **Header:** Gradient hijau untuk modal aksi penting, putih untuk modal info
- **Animasi:** `translateY(20px) scale(.97)` → normal, durasi 250ms

**Modal besar** (seperti panel Kelola Tugas) menggunakan tabs untuk memisahkan konten:
- Tab aktif: garis bawah warna primer, background putih
- Tab nonaktif: teks muted, background abu sangat muda

---

## Pola Halaman

### Halaman Publik (tanpa login)

**Layout:** Navbar sticky di atas → Hero section → Konten → Footer

**Navbar:** Background gradient hijau gelap (`--g9 → --g8`), logo putih, link navigasi, tombol Login/Dashboard di kanan.

**Hero section:** Gradient hijau (`--g9 → --g8 → --g7`), teks putih, eyebrow uppercase, judul besar. Beberapa hero menggunakan kurva bawah (`clip-path: ellipse`) untuk transisi halus ke konten.

**Konten:** `max-width: 860–940px`, `margin: 0 auto`, padding vertikal 28–36px.

**Contoh:** Halaman jadwal publik, halaman tugas siswa, halaman jurnal, halaman inventaris publik.

### Halaman Admin (dengan login)

**Layout:** Sidebar fixed (260px) + Main content (margin-left 260px)

**Sidebar admin utama:**
- Background gradient gelap (`#003d24 → #00331e`)
- Nav items: teks `rgba(185,217,235,.45)`, hover + active `#fff` + background rgba
- Item aktif: border-left 3px Columbia Blue
- User info + logout di bawah

**Sidebar panel guru (tugas admin):**
- Background gradient hijau (`#003d24 → #004d2e`)
- Nav items: warna biru muda untuk kontras di background gelap
- Brand EduZone Lab di atas

**Main content header:** Topbar putih (height 62–64px), judul halaman, tanggal, notifikasi bell, avatar user.

**Contoh:** Dashboard, Booking, Inventaris Admin, Panel Guru Tugas.

### Halaman Login

**Layout:** Split 50/50 di desktop — panel kiri (statistik lab + brand) + panel kanan (form login).

**Panel kiri:** Background gradient hijau gelap, menampilkan 4 stat realtime (lab aktif, booking, inventaris, penggunaan hari ini), dengan animasi masuk saat load.

**Panel kanan:** Background putih bersih, form dengan floating label, toggle password visibility, caps lock warning.

Di mobile: panel kiri disembunyikan, hanya form yang tampil.

### Halaman Editor Laporan

**Layout:** Full-width, toolbar sticky di atas (background gradient hijau), konten cetakan di bawah.

**Toolbar:** Tombol Kembali di kiri, judul editor di tengah, kontrol ukuran kop + pilih ukuran kertas + tombol Cetak di kanan.

**Pilih ukuran kertas:** Custom dropdown (bukan `<select>` native) dengan grup "Seri A" dan "US", ikon portrait (biru) / landscape (ungu), pilihan aktif highlight hijau.

**Area cetak:** Simulasikan tampilan halaman cetak dengan padding dan box-shadow.

---

## Pola Interaksi

### Realtime via WebSocket

Sistem menggunakan Laravel Reverb untuk update realtime. Pola yang diikuti:

**Penambahan data baru:**
- Inject elemen DOM dengan animasi `fadeIn` atau `cardIn`
- Tidak pernah reload halaman paksa kecuali diperlukan (misal reset form)

**Perubahan status:**
- Update badge/chip secara in-place
- Toast notification di pojok kanan bawah (5 detik auto-dismiss)

**Penghapusan data:**
- Fade-out + scale-down + remove dari DOM (400ms)

**Contoh implementasi:**
- Booking baru → kartu muncul di tabel mingguan + toast admin
- Tugas dibuka → kartu muncul di halaman siswa
- Siswa kumpul tugas → baris baru di daftar submission + toast guru

### Toast Notification

```
Posisi: fixed, bottom-right (24px dari tepi)
Background: --g9 (hijau gelap) untuk success, merah tua untuk error
Animasi masuk: translateY(14px) scale(.95) → normal
Animasi keluar: translateY(8px) scale(.96), opacity → 0
Auto-dismiss: 5 detik
Tombol close: ×
```

### Loading States

- **Skeleton loader:** Untuk tabel mingguan booking saat navigasi antar minggu
- **Tombol disable + teks loading:** Tombol submit form menjadi disabled + teks "⏳ Mengupload..." saat diproses
- **Loading screen:** Overlay penuh dengan animasi dot saat transisi halaman pertama kali

### Navigasi AJAX

Halaman-halaman yang menggunakan navigasi AJAX (tanpa reload penuh):
- **Tabel mingguan booking** — `fetch()` ke endpoint `/booking/weekly-grid`, swap konten `#bk-weekly-container`
- **Tab panel di modal** — switch antar tab tanpa request server
- URL browser di-update via `history.pushState` agar bisa di-bookmark

### Form Validation

- Validasi sisi server (Laravel), pesan ditampilkan inline di bawah field
- Error class (`is-error`) diterapkan ke input yang error
- Flash message sukses muncul di atas halaman, auto-dismiss 5 detik
- Konfirmasi hapus menggunakan `confirm()` native atau modal konfirmasi kustom

---

## Responsivitas

### Breakpoints

```
640px  — Komponen kecil: grid 2-kolom → 1-kolom
720px  — Show page tugas: 2-kolom → 1-kolom
768px  — Date nav: row → column
1024px — Admin layout: sidebar collapse, mobile-nav tampil
```

### Pola Responsif

| Komponen | Desktop | Mobile |
|----------|---------|--------|
| Sidebar admin | Fixed 260px | Hidden, toggle via hamburger |
| Stats grid | 4 kolom | 2 kolom |
| Task grid | auto-fill ≥300px | 1 kolom |
| Modal | max-width 1100px | Full width, max-height 92vh |
| Show page tugas | 2 kolom | 1 kolom (info card turun ke bawah) |
| Login page | Split 50/50 | Form saja |
| PIN keypad | 3x4 grid, 50px | 3x4 grid, 44px |

### Mobile Navigation

Pada layar ≤1024px, sidebar admin disembunyikan dan diganti dengan **mobile-nav** (topbar tipis) yang berisi logo + hamburger button. Tap hamburger → sidebar slide in dari kiri dengan `transform: translateX(0)`.

---

## Aksesibilitas

### Implementasi yang Sudah Ada

- `aria-label` pada tombol icon tanpa teks (close modal, toggle password, PIN delete)
- `aria-live="polite"` pada PIN boxes untuk screen reader
- `role="group"` dan `aria-label` pada PIN keypad
- Keyboard support pada PIN keypad (angka + Backspace + Enter)
- `autocomplete` attribute pada semua form input yang relevan
- Warna teks selalu memenuhi kontras minimum WCAG AA

### Catatan

Full WCAG compliance memerlukan pengujian manual dengan assistive technology (NVDA, JAWS, VoiceOver). Saat ini implementasi aksesibilitas baru mencakup elemen-elemen kritis.

---

## Animasi & Transisi

### Animasi Halaman

```css
@keyframes fadeUp  { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
@keyframes fadeIn  { from { opacity:0; transform:translateY(6px); }  to { opacity:1; transform:none; } }
@keyframes cardIn  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
@keyframes popIn   { from { opacity:0; transform:scale(.92); } to { opacity:1; transform:scale(1); } }
@keyframes modalUp { from { opacity:0; transform:translateY(20px) scale(.97); } to { opacity:1; transform:none; } }
```

**Durasi standar:**
- Transisi elemen kecil (hover, focus): 150–200ms
- Animasi masuk kartu/modal: 250–300ms
- Animasi loading screen: 600ms
- Animasi dot loading: 1.4s infinite

### Hover Effects

```css
/* Kartu */
transform: translateY(-3px);
box-shadow: 0 10px 32px rgba(0,105,62,.12);

/* Tombol primer */
transform: translateY(-1 atau -2px);
box-shadow: 0 8px 24px rgba(0,61,36,.3);

/* Tombol kecil */
opacity: 0.87 atau 0.9;
```

### Animation Delay pada Grid

Card di grid menggunakan `animation-delay` berbeda untuk efek masuk bertahap:
```css
style="animation-delay: {{ $idx * 55 }}ms"
```

Maksimal 8 item diberi delay berbeda untuk menghindari delay terlalu lama.

### `prefers-reduced-motion`

```css
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: .01ms !important;
        transition-duration: .01ms !important;
    }
}
```

---

## File & Struktur CSS

```
resources/css/
├── app.css              ← Global variables, Tailwind, font-face
├── schedule.css         ← Halaman jadwal publik
├── schedule-cards.css   ← Day-card komponen jadwal
├── booking.css          ← Panel booking admin
├── inventory.css        ← Inventaris publik
├── inventoryadmin.css   ← Panel inventaris admin
├── assignment.css       ← Semua halaman tugas (siswa + guru)
├── journal.css          ← Halaman jurnal lab
├── rekap.css            ← Halaman rekap publik
├── kelas.css            ← Manajemen kelas
├── sekolah.css          ← Manajemen sekolah/organisasi
├── teacher.css          ← Manajemen guru
├── important.css        ← Jadwal penting
├── users.css            ← Manajemen pengguna
└── dashboard.css        ← Dashboard admin
```

Setiap CSS file mendefinisikan `:root` variables yang mewarisi dari `app.css` — tidak ada override warna brand di level file.

---

## Konvensi Penamaan CSS

### BEM-like untuk komponen tertentu

```
ia-*     → Inventory Admin (ia-table, ia-stat, ia-quick-panel)
bk-*     → Booking (bk-panel, bk-card, bk-table)
dc-*     → Day Card jadwal (dc-slot, dc-head, dc-footer)
pub-*    → Halaman publik (pub-hero, pub-wrap, pub-flash)
show-*   → Show page tugas (show-card, show-sub-row)
task-*   → Task card siswa (task-card, task-title, task-meta)
br-*     → Broken items inventaris (br-section, br-item)
```

### Komponen global (tanpa prefix)
```
.btn, .btn-primary, .btn-sm   → Tombol
.badge, .badge-*              → Badge status
.chip, .chip-*                → Chip info
.flash, .flash-ok, .flash-err → Pesan flash
.modal-overlay, .modal-box    → Modal
.inp                          → Input field
.field, .field-label          → Form field
```
