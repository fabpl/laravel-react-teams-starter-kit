<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BillingPortalController extends Controller
{
    /**
     * Redirect to the Stripe Customer Portal.
     */
    public function __invoke(Request $request, Team $current_team): RedirectResponse
    {
        $this->authenticatedUser($request);

        return $current_team->redirectToBillingPortal(
            route('subscriptions.index', $current_team),
        );
    }
}
