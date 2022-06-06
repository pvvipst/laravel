<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // функция срабатывает когда не авторизованный пользователь
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(['message' => 'Вы не авторизованы'], 401));
    }
}
