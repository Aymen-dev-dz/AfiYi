<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Marketplace\CartController;
use App\Http\Controllers\Marketplace\OrderController;
use App\Http\Controllers\Marketplace\MarketplaceController;
use App\Http\Controllers\Marketplace\CheckoutController;
use App\Http\Controllers\Destiny\DestinyController;
use App\Http\Controllers\Teletherapy\BookingController;
use App\Http\Controllers\WellnessController;
use App\Http\Controllers\LanguageController;
use Illuminate\Http\Request;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Quick Login Helper for Testing
Route::get('/login-as/{email}', function ($email) {
    try {
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            $role = str_contains($email, 'therapist') ? 'therapist' : (str_contains($email, 'admin') ? 'admin' : (str_contains($email, 'seller') ? 'seller' : 'patient'));
            $user = new \App\Models\User();
            $user->name = ucfirst(explode('@', $email)[0]);
            $user->email = $email;
            $user->password = \Illuminate\Support\Facades\Hash::make('password');
            $user->role = $role;
            $user->email_verified_at = now();
            $user->save();

            if ($role === 'admin') { try { $user->assignRole('Super Admin'); } catch (\Throwable $e){} }
            if ($role === 'therapist') { try { $user->assignRole('Therapist'); } catch (\Throwable $e){} }
            if ($role === 'seller') { try { $user->assignRole('Seller'); } catch (\Throwable $e){} }
            if ($role === 'patient') { try { $user->assignRole('User'); } catch (\Throwable $e){} }
        }
        \Illuminate\Support\Facades\Auth::login($user, true);

        if ($user->role === 'therapist' || $user->hasRole('Therapist')) {
            return redirect()->route('therapist.dashboard');
        }
        if ($user->role === 'seller' || $user->hasRole('Seller')) {
            return redirect()->route('seller.orders.index');
        }
        if ($user->role === 'admin' || $user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/dashboard');
    } catch (\Throwable $e) {
        return "Error logging in: " . $e->getMessage();
    }
})->name('login-as');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard & Role Redirection
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && ($user->role === 'therapist' || $user->hasRole('Therapist'))) {
            return redirect()->route('therapist.dashboard');
        }
        if ($user && ($user->role === 'seller' || $user->hasRole('Seller'))) {
            return redirect()->route('seller.orders.index');
        }
        if ($user && ($user->role === 'admin' || $user->hasRole('Admin') || $user->hasRole('Super Admin'))) {
            return redirect()->route('admin.dashboard');
        }

        $products = \App\Models\Product::active()->with('seller')->get();
        $categories = $products->pluck('category')->unique()->filter()->values();
        $therapists = \App\Models\TherapistProfile::with('user')->get();
        $activeTokens = \App\Models\OrderItem::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })->whereNotNull('destiny_token')->get();
        $consultations = \App\Models\Consultation::where('patient_id', auth()->id())
            ->with('therapistProfile.user')
            ->latest()
            ->get();
        $destinyMatches = \App\Models\DestinyMatch::where('user_a_id', auth()->id())
            ->orWhere('user_b_id', auth()->id())
            ->latest()
            ->get();
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->get();

        return view('dashboard', compact(
            'products',
            'categories',
            'therapists',
            'activeTokens',
            'consultations',
            'destinyMatches',
            'orders'
        ));
    })->name('dashboard');

    // Dedicated Role Dashboards
    Route::get('/therapist/dashboard', \App\Livewire\Therapist\ConsultationDashboard::class)->name('therapist.dashboard');
    Route::get('/therapist/schedule', \App\Livewire\Therapist\ScheduleManager::class)->name('therapist.schedule');
    Route::get('/therapist/profile', \App\Livewire\Therapist\ProfileManager::class)->name('therapist.profile');

    Route::get('/seller/products', \App\Livewire\Seller\ProductManager::class)->name('seller.products.index');

    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/users', \App\Livewire\Admin\UserManager::class)->name('admin.users.index');
    Route::get('/admin/therapists', \App\Livewire\Admin\TherapistManager::class)->name('admin.therapists.index');
    Route::get('/admin/products', \App\Livewire\Admin\ProductManager::class)->name('admin.products.index');
    Route::get('/admin/orders', \App\Livewire\Admin\OrderManager::class)->name('admin.orders.index');

    // Marketplace routes
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/product/{product}', [MarketplaceController::class, 'show'])->name('marketplace.show');
    Route::post('/marketplace/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items/add', [CartController::class, 'addItem'])->name('cart.items.add');
    Route::post('/cart/items/{item}/update', [CartController::class, 'updateItem'])->name('cart.items.update');
    Route::post('/cart/items/{item}/remove', [CartController::class, 'removeItem'])->name('cart.items.remove');
    Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Orders & Seller routes
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/seller/orders', [OrderController::class, 'sellerIndex'])->name('seller.orders.index');
    Route::get('/seller/orders/{order}', [OrderController::class, 'sellerShow'])->name('seller.orders.show');

    // Teletherapy routes
    Route::get('/teletherapy/directory', [BookingController::class, 'directory'])->name('teletherapy.directory');
    Route::get('/teletherapy/profile/{therapist}', [BookingController::class, 'showProfile'])->name('teletherapy.profile');
    Route::post('/teletherapy/book/{therapist}', [BookingController::class, 'book'])->name('teletherapy.book');
    Route::get('/teletherapy/checkout/{consultation}', [BookingController::class, 'checkout'])->name('teletherapy.checkout');
    Route::get('/teletherapy/success/{consultation}', [BookingController::class, 'success'])->name('teletherapy.success');
    Route::get('/teletherapy/room/{consultation}', [\App\Http\Controllers\Teletherapy\SessionController::class, 'room'])->name('teletherapy.room');
    Route::post('/teletherapy/consent/{consultation}', [\App\Http\Controllers\Teletherapy\SessionController::class, 'submitConsent'])->name('teletherapy.consent.submit');
    Route::get('/teletherapy/feedback/{consultation}', [BookingController::class, 'feedbackForm'])->name('teletherapy.feedback');
    Route::post('/teletherapy/feedback/{consultation}', [BookingController::class, 'submitFeedback'])->name('teletherapy.feedback.submit');
    Route::delete('/dashboard/consultation/{consultation}', function (\App\Models\Consultation $consultation) {
        abort_unless($consultation->patient_id === auth()->id(), 403);
        $consultation->delete();
        return back()->with('success', 'Consultation supprimée de l\'historique.')->with('active_tab', 'journal');
    })->name('dashboard.consultations.destroy');

    // Destiny routes
    Route::get('/destiny/lobby', \App\Livewire\Destiny\AnonymousLobby::class)->name('destiny.lobby');
    Route::get('/destiny/chat/{match}', \App\Livewire\Destiny\AnonymousChat::class)->name('destiny.chat');
    
    Route::get('/destiny/connect/{token?}', [DestinyController::class, 'connect'])->name('destiny.connect');
    Route::get('/destiny/qrcode/{token}', [DestinyController::class, 'qrCode'])->name('destiny.qrcode.download');
    
    Route::delete('/dashboard/destiny/{match}', function (\App\Models\DestinyMatch $match) {
        abort_unless($match->user_a_id === auth()->id() || $match->user_b_id === auth()->id(), 403);
        $match->delete();
        return back()->with('success', 'Échange supprimé de l\'historique.')->with('active_tab', 'journal');
    })->name('dashboard.destiny.destroy');

    // Wellness & Activities
    Route::get('/wellness', [WellnessController::class, 'activitiesIndex'])->name('wellness.space');
    Route::post('/teletherapy/activities/complete', [WellnessController::class, 'completeActivity'])->name('teletherapy.activities.complete');
    Route::get('/premium', function () {
        return view('welcome');
    })->name('premium.index');

    // Preferences
    Route::post('/profile/preferences', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'share_mood_with_therapist' => 'required|boolean',
        ]);
        auth()->user()->update([
            'share_mood_with_therapist' => $request->share_mood_with_therapist,
        ]);
        return back()->with('status', 'preferences-updated');
    })->name('profile.preferences');

    // Test Login
    Route::get('/test-login', function () {
        auth()->loginUsingId(2);
        return redirect()->route('dashboard');
    });
});
