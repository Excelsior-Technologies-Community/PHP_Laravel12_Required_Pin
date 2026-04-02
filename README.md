# PHP_Laravel12_Required_Pin

## Project Description

**PHP_Laravel12_Required_Pin** is a Laravel 12 application that demonstrates how to add a **PIN-based security layer** on top of standard Laravel Breeze authentication using the `ikechukwukalu/requirepin` package.

After logging in, users must enter a **4-digit PIN** before they can access protected pages like the Dashboard and Profile. Users can also change their PIN from the navbar. This adds an extra layer of security beyond just a password.

This project is **beginner-friendly** and helps understand how to use custom middleware, published vendor views, and PIN-based route protection in Laravel.

---

## Features

- 🔐 Full Authentication System via Laravel Breeze (Login, Register, Password Reset)
- 🔢 PIN verification required before accessing protected pages
- ✅ Default PIN `0000` allowed for first-time login (configurable)
- 🔑 Change PIN from navbar (Old PIN → New PIN → Confirm PIN)
- 🚫 Rate limiting — max 3 attempts before lockout
- ⏱️ PIN session duration — 300 seconds (5 minutes, configurable)
- 💬 Success and error messages on PIN forms
- 🎨 Clean Tailwind CSS UI with custom PIN and Change PIN views
- 🗄️ File-based sessions (`SESSION_DRIVER=file`)

---

## Technologies Used

| Technology | Purpose |
|---|---|
| PHP 8.1+ | Backend Language |
| Laravel 12 | PHP Framework |
| MySQL | Database |
| Laravel Breeze | Authentication Scaffolding |
| ikechukwukalu/requirepin | PIN protection middleware & logic |
| Tailwind CSS | UI Styling |
| Blade Templates | Frontend Views |
| Vite | Frontend Asset Bundling |

---

## How It Works

```
User logs in  →  require.pin middleware fires  →  PIN form shown  →  Enter 0000  →  Dashboard unlocked! 🔐
```

1. User registers and logs in via Laravel Breeze.
2. Any route protected with `require.pin` middleware triggers a PIN verification page.
3. Package generates a UUID and stores it in the session, redirecting to `/pin/required/{uuid}`.
4. User enters the 4-digit PIN — package validates it against the stored PIN.
5. On success, user is redirected to the originally requested page.
6. User can change their PIN anytime from the navbar via `/change/pin`.

---

## Installation Steps

---

### STEP 1: Create Laravel 12 Project

Open terminal / CMD and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_Required_Pin "12.*"
```

Go inside the project folder:

```bash
cd PHP_Laravel12_Required_Pin
```

> This installs a fresh Laravel 12 project and moves into the project folder.

---

### STEP 2: Database Setup

Update `.env` with your database details:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your_generated_key_here
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PHP_Laravel12_Required_Pin
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120

QUEUE_CONNECTION=sync
CACHE_STORE=database
CACHE_DRIVER=file

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Create database in MySQL / phpMyAdmin:

```
Database name: PHP_Laravel12_Required_Pin
```

> `SESSION_DRIVER=file` and `QUEUE_CONNECTION=sync` are important — the package works without Redis this way.

---

### STEP 3: Install Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

> Installs Laravel Breeze with Blade frontend — provides Login, Register, Dashboard, and Profile pages out of the box.

---

### STEP 4: Run Migrations

```bash
php artisan migrate
```

> Creates all default tables: `users`, `cache`, `jobs`, and sessions.

---

### STEP 5: Install Frontend Dependencies

```bash
npm install && npm run dev
```

> Compiles Tailwind CSS and JavaScript assets using Vite.

---

### STEP 6: Install the RequirePin Package

```bash
composer require ikechukwukalu/requirepin
```

> Installs the `requirepin` package which provides the `require.pin` middleware, PIN controller, routes, views, and migrations.

---

### STEP 7: Publish Package Files

```bash
php artisan vendor:publish --provider="Ikechukwukalu\Requirepin\RequirepinServiceProvider"
```

> Publishes the config file, migrations, and views:
> - `config/requirepin.php`
> - `database/migrations/xxxx_create_pins_table.php`
> - `resources/views/vendor/requirepin/`

---

### STEP 8: Run Migrations Again

```bash
php artisan migrate
```

> Creates the `pins` table used by the package to store user PINs.

---

### STEP 9: Register Middleware Alias

Open: `bootstrap/app.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'require.pin' => \Ikechukwukalu\Requirepin\Middleware\RequirePin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

> Registers `require.pin` as a named middleware alias so it can be used in routes.

---

### STEP 10: Update RequirePin Config

Open: `config/requirepin.php`

```php
<?php

return [
    'default'          => '0000',
    'allow_default_pin' => true,   // ← allows 0000 for first login
    'duration'         => 300,
    'verify_sender'    => true,
    'input'            => '_pin',
    'param'            => '_uuid',
    'max'              => 4,
    'min'              => 4,
    'check_all'        => true,
    'number'           => 4,
    'max_attempts'     => 3,
    'delay_minutes'    => 1,
    'max_trial'        => 3,
    'change_pin_route' => 'change/pin',
    'notify' => [
        'change' => true,
    ],
    'auth_route_guard' => 'auth',
    'auth_guard'       => 'web',
    'auth_middleware'  => 'web',   // ← important: use 'web' not 'sanctum'
];
```

> Key changes: `allow_default_pin => true` enables `0000` for testing. `auth_middleware => 'web'` ensures session-based auth works correctly.

---

### STEP 11: Add Routes

Open: `routes/web.php`

```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'require.pin'])->name('dashboard');

Route::middleware(['auth', 'require.pin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
```

> The `require.pin` middleware automatically handles the `/pin/required/{uuid}` and `/change/pin` routes — no need to define them manually.

---

### STEP 12: Update Navbar Layout

Open: `resources/views/layouts/app.blade.php`

```html
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">

        {{-- Navbar --}}
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <span class="text-gray-600 text-sm font-medium">{{ Auth::user()->name }}</span>

                            <a href="{{ route('changePinView') }}" class="text-sm text-blue-500 hover:text-blue-700 font-semibold">
                                Change PIN
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="text-sm text-red-500 hover:text-red-700 font-semibold">
                                    Logout
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Page Content --}}
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
```

> Uses `@yield('content')` — this is important. Do **not** use `{{ $slot }}` here.

---

### STEP 13: Update PIN Required View

Open: `resources/views/vendor/requirepin/pin/pinrequired.blade.php`

```html
@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="bg-blue-600 p-4 text-white text-center font-bold text-lg">
                🔐 {{ __('Pin Required') }}
            </div>

            <div class="p-8">
                @php
                    $msg = "Please enter your 4-digit PIN.";
                    $formAction = "#";

                    if (session('pin_validation')) {
                        $data = json_decode(session('pin_validation'), true);
                        if (is_array($data)) {
                            $msg = $data['message'] ?? $msg;
                            $formAction = $data[1] ?? "#";
                        }
                    }

                    if (session('return_payload')) {
                        $payload = json_decode(session('return_payload'), true);
                        if (is_array($payload)) {
                            $msg = $payload['message'] ?? 'Invalid PIN';
                        }
                    }
                @endphp

                <div class="mb-6 p-3 bg-blue-50 border-l-4 border-blue-500 text-blue-700 text-sm text-center italic">
                    {{ (string) $msg }}
                </div>

                <form method="POST" action="{{ (string) $formAction }}">
                    @csrf

                    <div class="mb-6">
                        <label for="_pin" class="block text-sm font-semibold text-gray-700 mb-2 text-center">
                            Enter PIN
                        </label>
                        <input id="_pin" type="password" name="_pin"
                            class="w-full border-2 border-gray-200 p-3 rounded-xl text-center text-3xl tracking-[1rem] focus:border-blue-500 focus:ring-0 transition-all @error('_pin') border-red-500 @enderror"
                            required autofocus maxlength="4" pattern="[0-9]{4}" inputmode="numeric">

                        @error('_pin')
                            <p class="text-red-500 text-xs mt-2 text-center font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-200">
                        Verify & Unlock
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

### STEP 14: Update Change PIN View

Open: `resources/views/vendor/requirepin/pin/changepin.blade.php`

```html
@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="bg-green-600 p-4 text-white text-center font-bold text-lg">
                🔑 {{ __('Change PIN') }}
            </div>

            <div class="p-8">
                @if(session('return_payload'))
                    @php
                        [$status, $code, $data] = json_decode(session('return_payload'), true);
                        $msg = is_array($data) ? ($data['message'] ?? 'Success') : $data;
                    @endphp
                    <div class="mb-6 p-3 rounded-lg text-center text-sm font-bold
                        {{ $status === 'fail' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200' }}">
                        {{ (string) $msg }}
                    </div>
                @endif

                <form method="POST" action="{{ route('changePinWeb') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Old PIN</label>
                        <input type="password" name="current_pin"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 outline-none transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New PIN</label>
                        <input type="password" name="pin"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 outline-none transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm PIN</label>
                        <input type="password" name="pin_confirmation"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 outline-none transition"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow-md transition">
                        Update PIN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

### STEP 15: Clear Cache and Run

```bash
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## Expected Output

| URL | What You See |
|---|---|
| `http://127.0.0.1:8000` | Laravel welcome page |
| `http://127.0.0.1:8000/register` | Registration page (Breeze) |
| `http://127.0.0.1:8000/login` | Login page (Breeze) |
| `http://127.0.0.1:8000/dashboard` | Redirects to PIN form first |
| `http://127.0.0.1:8000/pin/required/{uuid}` | PIN entry form |
| After entering `0000` | Dashboard unlocked ✅ |
| `http://127.0.0.1:8000/change/pin` | Change PIN form |

### Full Flow:

```
Register  →  Login  →  Visit /dashboard
                              ↓
               PIN form appears at /pin/required/{uuid}
                              ↓
                    Enter: 0000  →  Dashboard ✅
                              ↓
              Navbar → "Change PIN" → Enter old + new PIN
                              ↓
                       PIN updated ✅
```

---

<img width="1910" height="890" alt="Screenshot 2026-03-26 171322" src="https://github.com/user-attachments/assets/d8d3f3d4-2255-4feb-8614-a4b9e86e08e1" />
<img width="1919" height="842" alt="Screenshot 2026-03-26 171333" src="https://github.com/user-attachments/assets/b01a34d3-a7e6-48c2-a7c1-af6cc0ff7361" />
<img width="1914" height="692" alt="Screenshot 2026-03-26 171351" src="https://github.com/user-attachments/assets/ade94606-1c76-474a-ab11-ccde6f44279d" />


## Project Folder Structure

```
PHP_Laravel12_Required_Pin/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProfileController.php     ← Installed by Breeze
│   └── Models/
│       └── User.php
│
├── bootstrap/
│   └── app.php                           ← require.pin middleware alias registered here
│
├── config/
│   ├── requirepin.php                    ← Published config (allow_default_pin, duration, etc.)
│   └── ...
│
├── database/
│   └── migrations/
│       ├── xxxx_create_users_table.php
│       ├── xxxx_create_cache_table.php
│       ├── xxxx_create_jobs_table.php
│       └── xxxx_create_pins_table.php    ← Created by requirepin package
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php             ← Custom navbar layout (Tailwind)
│       ├── dashboard.blade.php           ← PIN-protected dashboard view
│       ├── auth/                         ← Login, Register views (Breeze)
│       └── vendor/
│           └── requirepin/
│               └── pin/
│                   ├── pinrequired.blade.php   ← Custom PIN entry form
│                   └── changepin.blade.php     ← Custom change PIN form
│
├── routes/
│   ├── web.php                           ← Dashboard + Profile routes with require.pin
│   └── auth.php                          ← Auth routes (Breeze)
│
├── .env                                  ← SESSION_DRIVER=file, QUEUE_CONNECTION=sync
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## Useful Commands

| Command | Purpose |
|---|---|
| `composer require laravel/breeze --dev` | Install Laravel Breeze |
| `php artisan breeze:install blade` | Scaffold Breeze with Blade |
| `composer require ikechukwukalu/requirepin` | Install RequirePin package |
| `php artisan vendor:publish --provider="Ikechukwukalu\Requirepin\RequirepinServiceProvider"` | Publish config, migrations, views |
| `php artisan migrate` | Run all migrations |
| `npm install && npm run dev` | Install and build frontend assets |
| `php artisan view:clear` | Clear compiled Blade views |
| `php artisan route:clear` | Clear cached routes |
| `php artisan serve` | Start local development server |
