<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display the one-time products page.
     */
    public function index(Request $request, Team $current_team): Response
    {
        $this->authenticatedUser($request);

        $rawProducts = config('billing.products');
        $products = is_array($rawProducts) ? $rawProducts : [];

        return Inertia::render('products/index', [
            'products' => $products,
        ]);
    }

    /**
     * Redirect to Stripe Checkout for a one-time product purchase.
     */
    public function checkout(Request $request, Team $current_team, string $priceId): RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        $rawProducts = config('billing.products');
        $rawProducts = is_array($rawProducts) ? $rawProducts : [];

        $product = collect($rawProducts)->firstWhere('stripe_price_id', $priceId);

        abort_unless($product !== null, 404);

        return $current_team->checkout([$priceId => 1], [
            'success_url' => route('products.index', $current_team).'?checkout=success',
            'cancel_url' => route('products.index', $current_team),
            'customer_email' => $user->email,
        ])->redirect();
    }
}
