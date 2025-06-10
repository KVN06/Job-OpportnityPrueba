<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnemployedController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\FavoriteOfferController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\SettingsController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('main');

// Test route
Route::get('/test', function () {
    return 'Test route working!';
})->name('test');

// Debug route for company creation
Route::post('/debug-company', function (Illuminate\Http\Request $request) {
    \Log::info('Debug company route hit', $request->all());
    
    try {
        $user = auth()->user();
        \Log::info('User info', [
            'id' => $user->id,
            'type' => $user->type,
            'name' => $user->name
        ]);
        
        $company = new \App\Models\Company([
            'company_name' => $request->company_name,
            'description' => $request->description,
            'location' => $request->location,
            'industry' => $request->industry,
            'website' => $request->website,
        ]);
        
        $company->user()->associate($user);
        $company->save();
        
        \Log::info('Company created successfully', ['company_id' => $company->id]);
        
        return redirect()->route('dashboard')->with('success', 'Empresa creada exitosamente!');
        
    } catch (\Exception $e) {
        \Log::error('Error creating company', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
})->middleware(['auth', 'role:company'])->name('debug.company');

// Home route
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    // User Management
    Route::middleware(['role:admin'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    Route::put('/user/preferences', [UserController::class, 'updatePreferences'])->name('user.preferences');

    // Job Offers
    Route::prefix('job-offers')->group(function () {
        Route::get('/', [JobOfferController::class, 'index'])->name('job-offers.index');
        Route::get('/search', [JobOfferController::class, 'search'])->name('job-offers.search');
        Route::get('/filter', [JobOfferController::class, 'filter'])->name('job-offers.filter');
        
        // Company only routes
        Route::middleware(['role:company'])->group(function () {
            Route::get('/create', [JobOfferController::class, 'create'])->name('job-offers.create');
            Route::post('/', [JobOfferController::class, 'store'])->name('job-offers.store');
            Route::get('/{jobOffer}/edit', [JobOfferController::class, 'edit'])->name('job-offers.edit');
            Route::put('/{jobOffer}', [JobOfferController::class, 'update'])->name('job-offers.update');
            Route::delete('/{jobOffer}', [JobOfferController::class, 'destroy'])->name('job-offers.destroy');
            Route::post('/{jobOffer}/toggle-status', [JobOfferController::class, 'toggleStatus'])->name('job-offers.toggle-status');
        });
        
        Route::get('/{jobOffer}', [JobOfferController::class, 'show'])->name('job-offers.show');
        Route::post('/{jobOffer}/favorite', [FavoriteOfferController::class, 'toggle'])->name('job-offers.toggle-favorite');
    });

    // Job Applications
    Route::prefix('applications')->group(function () {
        Route::get('/', [JobApplicationController::class, 'index'])->name('applications.index');
        Route::post('/', [JobApplicationController::class, 'store'])->name('applications.store');
        Route::get('/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
        Route::put('/{application}', [JobApplicationController::class, 'update'])->name('applications.update');
        Route::delete('/{application}', [JobApplicationController::class, 'destroy'])->name('applications.destroy');
    });

    // Companies
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/search', [CompanyController::class, 'search'])->name('companies.search');
        
        Route::middleware(['role:company'])->group(function () {
            Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
            Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
            Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
            Route::put('/{company}', [CompanyController::class, 'update'])->name('companies.update');
            Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        });
        
        Route::get('/{company}', [CompanyController::class, 'show'])->name('companies.show');
    });

    // Unemployed Profiles
    Route::prefix('unemployed')->middleware(['role:unemployed'])->group(function () {
        Route::get('/create', [UnemployedController::class, 'create'])->name('unemployed.create');
        Route::post('/', [UnemployedController::class, 'store'])->name('unemployed.store');
        Route::get('/{unemployed}', [UnemployedController::class, 'show'])->name('unemployed.show');
        Route::get('/{unemployed}/edit', [UnemployedController::class, 'edit'])->name('unemployed.edit');
        Route::put('/{unemployed}', [UnemployedController::class, 'update'])->name('unemployed.update');
    });

    // Portfolios
    Route::prefix('portfolios')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('portfolios.index');
        Route::get('/search', [PortfolioController::class, 'search'])->name('portfolios.search');
        
        Route::middleware(['role:unemployed'])->group(function () {
            Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
            Route::post('/', [PortfolioController::class, 'store'])->name('portfolios.store');
            Route::get('/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolios.edit');
            Route::put('/{portfolio}', [PortfolioController::class, 'update'])->name('portfolios.update');
            Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');
        });
        
        Route::get('/{portfolio}', [PortfolioController::class, 'show'])->name('portfolios.show');
    });

    // Messages
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/create', [MessageController::class, 'create'])->name('messages.create');
        Route::post('/', [MessageController::class, 'store'])->name('messages.store');
        Route::get('/{user}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::post('/mark-all-read', [MessageController::class, 'markAllRead'])->name('messages.mark-all-read');
        Route::get('/search', [MessageController::class, 'search'])->name('messages.search');
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Comments
    Route::prefix('comments')->group(function () {
        Route::post('/', [CommentController::class, 'store'])->name('comments.store');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/search', [CategoryController::class, 'search'])->name('categories.search');
        
        // Allow both companies and admins to create categories
        Route::middleware(['role:company,admin'])->group(function () {
            Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        });
        
        // Only admins can update and delete categories
        Route::middleware(['role:admin'])->group(function () {
            Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });
    });

    // Trainings
    Route::prefix('trainings')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('trainings.index');
        Route::get('/search', [TrainingController::class, 'search'])->name('trainings.search');
        
        Route::middleware(['role:company,admin'])->group(function () {
            Route::get('/create', [TrainingController::class, 'create'])->name('trainings.create');
            Route::post('/', [TrainingController::class, 'store'])->name('trainings.store');
            Route::get('/{training}/edit', [TrainingController::class, 'edit'])->name('trainings.edit');
            Route::put('/{training}', [TrainingController::class, 'update'])->name('trainings.update');
            Route::delete('/{training}', [TrainingController::class, 'destroy'])->name('trainings.destroy');
        });
        
        Route::get('/{training}', [TrainingController::class, 'show'])->name('trainings.show');
        Route::middleware(['role:unemployed'])->group(function () {
            Route::post('/{training}/enroll', [TrainingController::class, 'enroll'])->name('trainings.enroll');
            Route::post('/{training}/complete', [TrainingController::class, 'complete'])->name('trainings.complete');
        });
    });

    // Favorites
    Route::prefix('favorites')->middleware(['role:unemployed'])->group(function () {
        Route::get('/', [FavoriteOfferController::class, 'index'])->name('favorites.index');
        Route::post('/{jobOffer}', [FavoriteOfferController::class, 'toggle'])->name('favorites.toggle');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        
        // Profile settings
        Route::get('/profile', [SettingsController::class, 'profile'])->name('settings.profile');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
        
        // Security settings
        Route::get('/security', [SettingsController::class, 'security'])->name('settings.security');
        Route::put('/security/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
        
        // Notification settings
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
        
        // Privacy settings
        Route::get('/privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
        Route::put('/privacy', [SettingsController::class, 'updatePrivacy'])->name('settings.privacy.update');
        
        // Account settings
        Route::get('/account', [SettingsController::class, 'account'])->name('settings.account');
        Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
    });
});
