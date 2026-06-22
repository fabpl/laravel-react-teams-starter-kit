<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

function controllerProbe(): object
{
    return new class extends Controller
    {
        public function resolve(Request $request): User
        {
            return $this->authenticatedUser($request);
        }
    };
}

test('authenticatedUser returns the resolved user', function (): void {
    $user = new User(['name' => 'Jane']);

    $request = Request::create('/');
    $request->setUserResolver(fn (): User => $user);

    expect(controllerProbe()->resolve($request))->toBe($user);
});

test('authenticatedUser aborts with 403 when no user is authenticated', function (): void {
    $request = Request::create('/');

    expect(fn (): User => controllerProbe()->resolve($request))
        ->toThrow(HttpException::class);
});
