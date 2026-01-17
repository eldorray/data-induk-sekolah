# 🚀 Starter Kit Laravel 12 + Livewire 4 Admin Panel

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4.x-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)

## 📋 Deskripsi

**Starter Kit Livewire 4 Admin Panel** adalah template boilerplate untuk membangun aplikasi web dengan Laravel 12 dan Livewire 4. Starter kit ini menyediakan fondasi yang solid untuk pengembangan aplikasi dengan fitur-fitur autentikasi yang sudah siap pakai.

### ✨ Fitur Utama

- 🔐 **Sistem Autentikasi Lengkap**
    - Login
    - Register
    - Forgot Password
    - Reset Password
- 📊 **Dashboard Admin** dengan UI modern
- 👤 **Halaman Profil User**
- 🎨 **UI Kit** dengan desain Apple-inspired Shadcn
- ⚡ **Full Livewire 4** - Reactive components tanpa JavaScript
- 🌙 **Modern Design** dengan TailwindCSS

## 🤖 Dibuat dengan Bantuan AI Assistance

Proyek ini dikembangkan dengan bantuan **AI Coding Assistant (Gemini/Antigravity)**. AI membantu dalam:

- Migrasi dari Laravel Breeze ke pure Livewire 4
- Pembuatan komponen autentikasi Livewire
- Integrasi UI Kit dengan admin panel
- Debugging dan optimisasi kode

## 📦 Tech Stack

| Technology  | Version | Description          |
| ----------- | ------- | -------------------- |
| Laravel     | 12.x    | PHP Framework        |
| Livewire    | 4.x     | Full-stack framework |
| TailwindCSS | 3.x     | Utility-first CSS    |
| Vite        | Latest  | Frontend build tool  |
| PHP         | 8.2+    | Backend language     |

## 🛠️ Cara Instalasi

### Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM atau Yarn

### Langkah-langkah Instalasi

1. **Clone repository**

    ```bash
    git clone https://github.com/eldorray/starter-kit-livewire4-adminpanel.git
    cd starter-kit-livewire4-adminpanel
    ```

2. **Install dependencies PHP**

    ```bash
    composer install
    ```

3. **Copy file environment**

    ```bash
    cp .env.example .env
    ```

4. **Generate application key**

    ```bash
    php artisan key:generate
    ```

5. **Konfigurasi database**

    Edit file `.env` dan sesuaikan konfigurasi database:

    ```env
    DB_CONNECTION=sqlite
    # atau gunakan MySQL:
    # DB_CONNECTION=mysql
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=starter_kit
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```

6. **Jalankan migrasi database**

    ```bash
    php artisan migrate
    ```

7. **Install dependencies Node.js**

    ```bash
    npm install
    ```

8. **Build assets**
    ```bash
    npm run build
    ```

### 🚀 Menjalankan Aplikasi

**Development Mode (Recommended)**

```bash
composer dev
```

Perintah ini akan menjalankan:

- Laravel development server
- Queue listener
- Pail (log viewer)
- Vite development server

**Atau jalankan secara terpisah:**

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite (untuk hot reload)
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

## 📁 Struktur Direktori

```
├── app/
│   ├── Livewire/
│   │   └── Auth/           # Komponen autentikasi Livewire
│   │       ├── Login.php
│   │       ├── Register.php
│   │       ├── ForgotPassword.php
│   │       └── ResetPassword.php
│   └── View/
│       └── Components/     # View components
├── resources/
│   ├── views/
│   │   ├── components/     # UI components
│   │   ├── layouts/        # Layout templates
│   │   ├── livewire/       # Livewire views
│   │   ├── dashboard.blade.php
│   │   └── profile.blade.php
│   └── css/               # Stylesheets
├── routes/
│   └── web.php            # Web routes
└── ui-kit/                # Referensi UI Kit
```

## 🔗 Routes

| Route                     | Method | Description                  |
| ------------------------- | ------ | ---------------------------- |
| `/`                       | GET    | Welcome page                 |
| `/login`                  | GET    | Login page                   |
| `/register`               | GET    | Register page                |
| `/forgot-password`        | GET    | Forgot password page         |
| `/reset-password/{token}` | GET    | Reset password page          |
| `/dashboard`              | GET    | Dashboard (auth required)    |
| `/profile`                | GET    | User profile (auth required) |
| `/logout`                 | POST   | Logout                       |

## 📝 Quick Commands

```bash
# Setup lengkap (fresh install)
composer setup

# Development mode
composer dev

# Run tests
composer test

# Build untuk production
npm run build
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

⭐ **Jangan lupa beri star jika project ini bermanfaat!**
