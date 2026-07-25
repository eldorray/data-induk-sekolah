# Redesign Halaman Public — Design Spec

Tanggal: 2026-07-25
Status: Approved

## Tujuan

Refresh **visual** seluruh halaman public ke gaya **Modern SaaS dengan aksen blue**. Struktur konten, markup logic, dan fungsionalitas tetap — hanya look & feel berubah.

## Scope

| Halaman | File | Layout |
|---|---|---|
| Landing (`/`) | `resources/views/welcome.blade.php` | `layouts.app` |
| Form Tracer Alumni (`/tracer-alumni`) | `resources/views/livewire/tracer-alumni-form.blade.php` | `layouts.app` |
| Login, Forgot, Reset Password | `resources/views/livewire/auth/*.blade.php` | `layouts.guest` |

**Tidak disentuh:**
- `layouts.admin` dan semua halaman admin
- `ui-kit/` components
- Logika Livewire (`.php`), routes, controller
- Font Inter sudah di-load di `layouts.app` — pastikan juga di `layouts.guest`

## Arah Visual

Modern SaaS (referensi: Linear/Stripe) — bersih, putih, tipografi tegas, glow gradient halus.

### Design Tokens (scoped public)

- **Aksen utama:** blue-600 `#2563eb` (menggantikan emerald pada halaman public)
- **Glow:** blue-200 `#bfdbfe` / blue-100 `#dbeafe`, `filter: blur(70px)`, opacity halus
- **Heading:** `font-extrabold tracking-tight` (`letter-spacing: -0.03em`)
- **Gradient text** untuk "MI & SMP": `linear-gradient(90deg, #2563eb, #60a5fa)`
- **Card:** `rounded-xl` (12px), border `1px solid` netral terang, shadow lembut
- **Button:** pill (`border-radius: 99px`), primary = blue solid + shadow blue, ghost = border blue + teks blue
- **Focus ring input:** blue
- **Background:** `#fbfbfd` (off-white ala Apple)

Implementasi token: tambah/update utilitas di `resources/css/app.css` pada section terpisah (comment `/* Public pages */`) agar tidak mengubah token admin yang ada. Class admin (`nav-apple`, `hero`, dll.) dibiarkan; halaman public memakai class/utilitas baru atau Tailwind inline.

## Per Halaman

### 1. Landing (`welcome.blade.php`)

Struktur section tetap: nav → hero → tracer callout → fitur → footer.

- **Nav:** sticky, `backdrop-blur`, border-b tipis, tombol "Masuk" pill dark (`#1d1d1f`)
- **Hero:**
  - Background off-white + 2 blob glow blue (blur besar, absolute, kiri-atas & kanan-bawah)
  - Badge: `bg-blue-100 text-blue-800` (ganti emerald)
  - H1: "Data Induk Sekolah" + "MI & SMP" dengan gradient text blue
  - CTA primary "Masuk ke Sistem": pill blue solid + shadow
  - CTA secondary "Isi Form Tracer Alumni": pill ghost blue (ganti emerald)
  - Hint text kecil di bawah CTA (tetap)
- **Tracer callout:** background `bg-blue-50` (ganti emerald-50), tombol blue solid
- **Fitur (6 card):** grid sama; icon tile unifikasi → `bg-blue-50 text-blue-600` (ganti warna-warni); hover lift halus (translateY -2px + shadow)
- **Footer:** tetap, sesuaikan warna teks saja

### 2. Form Tracer Alumni (`tracer-alumni-form.blade.php`)

- Header halaman: gradient blue halus (`from-blue-50 to-white`) dengan logo + judul
- Card form: putih, `rounded-xl`, shadow lembut
- Semua input/select/textarea: focus ring blue (ganti emerald jika ada)
- Tombol submit: pill/rounded blue solid, full-width di mobile
- Pesan sukses/error tetap, sesuaikan warna aksen

### 3. Auth (`layouts.guest` + login/forgot/reset)

- `layouts.guest`: tambah load font Inter jika belum ada
- Background: off-white + glow blue halus
- Card auth centered: putih, `rounded-xl`, border tipis, shadow lembut
- Logo + judul di atas card
- Input & tombol mengikuti token (focus blue, primary blue)
- Link (forgot password, dsb.): blue-600

## Animasi

- Pertahankan `animate-fade-up` yang ada; hover lift pada card fitur
- Tidak ada animasi/dependency baru

## Error Handling & Testing

- Tidak ada perubahan logic → tidak ada error handling baru
- Verifikasi manual: `php artisan serve`, cek `/`, `/tracer-alumni`, `/login`, `/forgot-password`, submit form tracer (validasi error state tetap jalan)
- Jalankan test suite existing (`php artisan test`) untuk memastikan tidak ada regression view

## Mockup

Mockup hi-fi tersimpan di `.superpowers/brainstorm/13952-1784951128/content/final-design.html` (buka via server brainstorm atau langsung di browser).
