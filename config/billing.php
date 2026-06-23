<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Define your subscription plans here. Each plan maps to a Stripe Price ID.
    | Create the products and prices in your Stripe dashboard and set the
    | corresponding price IDs in your environment variables.
    |
    */

    'plans' => [
        [
            'id' => 'starter',
            'name' => 'Starter',
            'description' => 'Perfect for small teams getting started.',
            'price' => 990,
            'interval' => 'month',
            'stripe_price_id' => env('STRIPE_PRICE_STARTER_MONTHLY', ''),
            'features' => [
                'Up to 5 team members',
                '10 GB storage',
                'Email support',
            ],
        ],
        [
            'id' => 'pro',
            'name' => 'Pro',
            'description' => 'For growing teams that need more power.',
            'price' => 2990,
            'interval' => 'month',
            'stripe_price_id' => env('STRIPE_PRICE_PRO_MONTHLY', ''),
            'features' => [
                'Unlimited team members',
                '100 GB storage',
                'Priority support',
                'Advanced analytics',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | One-Time Products
    |--------------------------------------------------------------------------
    |
    | Define your one-time purchase products here. Each product maps to a
    | Stripe Price ID. Create the products in your Stripe dashboard and set
    | the corresponding price IDs in your environment variables.
    |
    */

    'products' => [
        [
            'id' => 'extra_storage',
            'name' => 'Extra Storage Pack',
            'description' => 'Add 50 GB of storage to your team workspace.',
            'price' => 499,
            'stripe_price_id' => env('STRIPE_PRICE_EXTRA_STORAGE', ''),
        ],
        [
            'id' => 'priority_onboarding',
            'name' => 'Priority Onboarding',
            'description' => 'Dedicated onboarding session with our team (1 hour).',
            'price' => 19900,
            'stripe_price_id' => env('STRIPE_PRICE_PRIORITY_ONBOARDING', ''),
        ],
    ],

];
