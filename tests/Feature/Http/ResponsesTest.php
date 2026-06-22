<?php

declare(strict_types=1);

use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\TwoFactorLoginResponse;
use App\Http\Responses\VerifyEmailResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

function jsonRequest(): Request
{
    return Request::create('/', 'POST', server: ['HTTP_ACCEPT' => 'application/json']);
}

test('login response returns a json payload when json is requested', function (): void {
    $response = (new LoginResponse)->toResponse(jsonRequest());

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(200);
});

test('register response returns a 201 json payload when json is requested', function (): void {
    $response = (new RegisterResponse)->toResponse(jsonRequest());

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(201);
});

test('verify email response returns a 204 json payload when json is requested', function (): void {
    $response = (new VerifyEmailResponse)->toResponse(jsonRequest());

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(204);
});

test('two factor login response returns a json payload when json is requested', function (): void {
    $response = (new TwoFactorLoginResponse)->toResponse(jsonRequest());

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(200);
});

test('two factor login response redirects to the current team dashboard', function (): void {
    $user = User::factory()->create();

    $request = Request::create('/', 'GET');
    $request->setUserResolver(fn (): User => $user);

    $response = (new TwoFactorLoginResponse)->toResponse($request);

    expect($response->getTargetUrl())->toContain($user->personalTeamOrFail()->slug);
});
