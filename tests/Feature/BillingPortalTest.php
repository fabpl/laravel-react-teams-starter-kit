<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Cashier\Exceptions\InvalidCustomer;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;

/**
 * Returns a fake Stripe HTTP client that responds to billing portal session creation.
 */
function fakeStripePortalClient(string $portalUrl = 'https://billing.stripe.com/session/test', array &$capturedParams = []): ClientInterface
{
    return new class($portalUrl, $capturedParams) implements ClientInterface
    {
        public function __construct(
            private readonly string $portalUrl,
            private array &$capturedParams,
        ) {}

        public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null): array
        {
            $this->capturedParams = $params;

            return [
                json_encode([
                    'id' => 'bps_test',
                    'object' => 'billing_portal.session',
                    'url' => $this->portalUrl,
                    'return_url' => $params['return_url'] ?? '',
                    'customer' => $params['customer'] ?? '',
                    'created' => time(),
                    'livemode' => false,
                    'locale' => null,
                    'on_behalf_of' => null,
                    'configuration' => null,
                ]),
                200,
                ['Request-Id' => 'req_test'],
            ];
        }
    };
}

beforeEach(function (): void {
    config(['cashier.secret' => 'sk_test_fake_key']);
});

afterEach(function (): void {
    ApiRequestor::setHttpClient(null);
});

test('guests are redirected to login when accessing billing portal', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this->get(route('billing.portal', ['current_team' => $team->slug]));

    $response->assertRedirect(route('login'));
});

test('non-members cannot access billing portal', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($otherUser)
        ->get(route('billing.portal', ['current_team' => $team->slug]));

    $response->assertForbidden();
});

test('billing portal throws when team has no stripe customer', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $this->withoutExceptionHandling();

    $this->expectException(InvalidCustomer::class);

    $this
        ->actingAs($user)
        ->get(route('billing.portal', ['current_team' => $team->slug]));
});

test('team members with a stripe customer are redirected to the billing portal', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;
    $team->stripe_id = 'cus_test123';
    $team->save();

    ApiRequestor::setHttpClient(
        fakeStripePortalClient('https://billing.stripe.com/session/test_redirect')
    );

    $response = $this
        ->actingAs($user)
        ->get(route('billing.portal', ['current_team' => $team->slug]));

    $response->assertRedirect('https://billing.stripe.com/session/test_redirect');
});

test('billing portal passes subscriptions index as the return url', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;
    $team->stripe_id = 'cus_test123';
    $team->save();

    $capturedParams = [];
    ApiRequestor::setHttpClient(fakeStripePortalClient(capturedParams: $capturedParams));

    $this
        ->actingAs($user)
        ->get(route('billing.portal', ['current_team' => $team->slug]));

    expect($capturedParams)
        ->toHaveKey('return_url')
        ->and($capturedParams['return_url'])->toBe(route('subscriptions.index', $team));
});
