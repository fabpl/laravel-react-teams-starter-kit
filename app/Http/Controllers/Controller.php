<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Resolve the authenticated user for the given request.
     *
     * Routes that reach a controller are guarded by the "auth" middleware, so
     * a user is always present. This helper makes that guarantee explicit to
     * static analysis and fails closed if the invariant is ever broken.
     */
    protected function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }
}
