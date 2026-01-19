<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// ===== AUTH ROUTES =====
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Note: POST /login, /logout, /register are handled by API routes in routes/api.php

// ===== USER/CALOMATE ROUTES =====
Route::middleware(['web'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Food Journal
    Route::prefix('food-journal')->name('food-journal.')->group(function () {
        Route::get('/', function () {
            return view('food-journal');
        })->name('index');
    });

    // Nutrition History
    Route::get('/nutrition-history', function () {
        return view('nutrition-history');
    })->name('nutrition-history');

    // Diet Target
    Route::prefix('diet-target')->name('diet-target.')->group(function () {
        Route::get('/', function () {
            return view('diet_target.index');
        })->name('index');
    });

    // Rekomendasi
    Route::prefix('rekomendasi')->name('rekomendasi.')->group(function () {
        Route::get('/', function () {
            return view('rekomendasi.index');
        })->name('index');
    });

    // Chat AI
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', function () {
            return view('chat.index');
        })->name('index');
    });

    // Artikel
    Route::prefix('artikel')->name('artikel.')->group(function () {
        Route::get('/', function () {
            return view('artikel.index');
        })->name('index');
        Route::get('/{id}', function ($id) {
            return view('artikel.show', ['id' => $id]);
        })->name('show');
    });

    // Komunitas
    Route::prefix('komunitas')->name('komunitas.')->group(function () {
        Route::get('/', function () {
            return view('komunitas.index');
        })->name('index');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('index');
    });
});

// ===== ADMIN ROUTES =====
Route::middleware(['web'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Admin Food Management
    Route::prefix('foods')->name('foods.')->group(function () {
        Route::get('/', function () {
            return view('admin.foods.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.foods.create');
        })->name('create');
        Route::get('/{id}/edit', function ($id) {
            return view('admin.foods.edit', ['id' => $id]);
        })->name('edit');
    });

    // Admin Komunitas Management
    Route::prefix('komunitas')->name('komunitas.')->group(function () {
        Route::get('/', function () {
            return view('admin.komunitas.index');
        })->name('index');
    });

    // Admin Artikel Management
    Route::prefix('artikel')->name('artikel.')->group(function () {
        Route::get('/', function () {
            return view('admin.artikel.index');
        })->name('index');
    });
});
