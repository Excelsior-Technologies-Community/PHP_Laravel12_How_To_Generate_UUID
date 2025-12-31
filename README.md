# PHP_Laravel12_How_To_Generate_UUID

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/UUID-v4%20%7C%20Ordered%20%7C%20v7-success?style=for-the-badge">
</p>

---

## Overview

This project demonstrates how to generate **UUIDs in Laravel 12** using built-in helpers provided by Laravel.

The documentation explains how to generate:
- UUID v4 (Random)
- Ordered UUID (Time-based)
- UUID v7 (Recommended for Laravel 12)

---

## Features

- Generate UUID v4 using `Str::uuid()`
- Generate Ordered UUID using `Str::orderedUuid()`
- Generate UUID v7 using `Str::uuid7()`
- Simple controller-based implementation
- Easy to test using browser or Postman
- No external packages required

---

## Folder Structure

```text
laravel-uuid/
├── app/
│   └── Http/
│       └── Controllers/
│           └── UserController.php
├── routes/
│   └── web.php
├── .env
├── artisan
└── composer.json
```

---

## Laravel 12 – UUID Generation

This project demonstrates how to generate UUIDs in Laravel 12 using built-in helpers provided by Laravel.

It covers:
- UUID v4 (Random)
- Ordered UUID (Time-based)
- UUID v7 (Recommended for Laravel 12)

---

## Why Use UUID?

UUIDs are useful when:
- Building public APIs
- Avoiding predictable auto-increment IDs
- Working with distributed systems
- Improving security
- Scaling applications efficiently

---

## System Requirements

- PHP 8.2 or higher
- Composer
- Laravel 12.x

---

## Laravel 12 Installation

### STEP 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-uuid
```

---

### STEP 2: Environment Setup (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

### STEP 3: Start Development Server

```bash
php artisan serve
```

Laravel will start at:

```
http://127.0.0.1:8000
```

---

## Create Controller

Generate a controller using Artisan:

```bash
php artisan make:controller UserController
```

---

## Controller Code (UUID Examples)

`app/Http/Controllers/UserController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Generate Normal UUID (v4)
     */
    public function uuid()
    {
        $uuid = Str::uuid()->toString();

        return response()->json([
            'type' => 'UUID v4',
            'uuid' => $uuid
        ]);
    }

    /**
     * Generate Ordered UUID (Time-based)
     */
    public function orderedUuid()
    {
        $uuid = Str::orderedUuid()->toString();

        return response()->json([
            'type' => 'Ordered UUID',
            'uuid' => $uuid
        ]);
    }

    /**
     * Generate UUID v7 (Laravel 12)
     */
    public function uuid7()
    {
        $uuid = Str::uuid7()->toString();

        return response()->json([
            'type' => 'UUID v7',
            'uuid' => $uuid
        ]);
    }
}
```

---

## Routes Configuration

`routes/web.php`

```php
<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Generate a random UUID (UUID v4)
Route::get('/uuid', [UserController::class, 'uuid']);

// Generate a time-based ordered UUID
Route::get('/ordered-uuid', [UserController::class, 'orderedUuid']);

// Generate UUID v7 (recommended for Laravel 12)
Route::get('/uuid7', [UserController::class, 'uuid7']);
```

---

## Test UUID in Browser 

Make sure the server is running:

```bash
php artisan serve
```

Base URL:

```
http://127.0.0.1:8000
```

---

### UUID v4 (Random UUID)

Request URL:
```
http://127.0.0.1:8000/uuid
```

Response:
```json
{
  "type": "UUID v4",
  "uuid": "9baf2b4d-89bf-4a5d-b234-b06a86aaa93e"
}
```
<img width="607" height="147" alt="image" src="https://github.com/user-attachments/assets/3efcfa22-35a3-404d-9d85-916e692ef91c" />



---

### Ordered UUID (Time-based UUID)

Request URL:
```
http://127.0.0.1:8000/ordered-uuid
```

Response:
```json
{
  "type": "Ordered UUID",
  "uuid": "a0b9016d-9095-408b-a5a5-544a66b5769d"
}
```
<img width="647" height="152" alt="Screenshot 2025-12-31 123305" src="https://github.com/user-attachments/assets/2d86563d-b41f-41e8-bd48-25e1deaaed3a" />

---

### UUID v7 (Recommended – Laravel 12)

Request URL:
```
http://127.0.0.1:8000/uuid7
```

Response:
```json
{
  "type": "UUID v7",
  "uuid": "019b733a-3076-703a-88b8-f91787bf03ea"
}
```
<img width="596" height="140" alt="image" src="https://github.com/user-attachments/assets/40ccf5da-c73f-439d-9d8f-4a58e6045b9a" />


---

⚠️ **Note:** UUID value will be different on every request.
