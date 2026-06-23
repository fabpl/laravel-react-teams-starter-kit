<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Cashier\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription plans page.
     */
    public function index(Request $request, Team $current_team): Response
    {
        $this->authenticatedUser($request);

        $rawPlans = config('billing.plans');
        $rawPlans = is_array($rawPlans) ? $rawPlans : [];

        $plans = collect($rawPlans)->map(fn (array $plan): array => array_merge($plan, [
            'isSubscribed' => $current_team->subscribedToPrice($plan['stripe_price_id']),
        ]));

        $subscription = $current_team->subscription('default');

        return Inertia::render('subscriptions/index', [
            'plans' => $plans,
            'currentSubscription' => $subscription instanceof Subscription ? [
                'type' => $subscription->type,
                'stripeStatus' => $subscription->stripe_status,
                'stripePrice' => $subscription->stripe_price,
                'trialEndsAt' => $subscription->trial_ends_at?->toIso8601String(),
                'endsAt' => $subscription->ends_at?->toIso8601String(),
                'onGracePeriod' => $subscription->onGracePeriod(),
                'cancelled' => $subscription->canceled(),
            ] : null,
        ]);
    }

    /**
     * Redirect to Stripe Checkout for a subscription plan.
     */
    public function checkout(Request $request, Team $current_team, string $priceId): RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        $rawPlans = config('billing.plans');
        $rawPlans = is_array($rawPlans) ? $rawPlans : [];

        $plan = collect($rawPlans)->firstWhere('stripe_price_id', $priceId);

        abort_unless($plan !== null, 404);

        return $current_team
            ->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('subscriptions.index', $current_team).'?checkout=success',
                'cancel_url' => route('subscriptions.index', $current_team),
                'customer_email' => $user->email,
            ])
            ->redirect();
    }

    /**
     * Redirect to Stripe Customer Portal to manage an existing subscription.
     */
    public function portal(Request $request, Team $current_team): RedirectResponse
    {
        $this->authenticatedUser($request);

        return $current_team->redirectToBillingPortal(
            route('subscriptions.index', $current_team),
        );
    }
}
