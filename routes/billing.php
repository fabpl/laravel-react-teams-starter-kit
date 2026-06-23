<?php

declare(strict_types=1);

use App\Http\Controllers\Billing\BillingPortalController;
use App\Http\Controllers\Billing\ProductController;
use App\Http\Controllers\Billing\SubscriptionController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function (): void {
        Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('subscriptions/checkout/{priceId}', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
        Route::get('subscriptions/portal', [SubscriptionController::class, 'portal'])->name('subscriptions.portal');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/checkout/{priceId}', [ProductController::class, 'checkout'])->name('products.checkout');

        Route::get('billing-portal', BillingPortalController::class)->name('billing.portal');
    });
