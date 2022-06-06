<?php

namespace App\Http\Library;


use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    // проверка на админа
    protected function isAdmin($user): bool
    {
        if ($user->role === 1) {
            return true;
        }
        return false;
    }
    // успешный запрос
    protected function onSuccess($data, $status = 200): JsonResponse
    {
        return response()->json(['data'=>$data], $status);
    }
    // нет доступа
    protected function forbidden(): JsonResponse
    {
        return response()->json(['message'=>'Нет доступа'], 403);
    }

    protected function notFound(): JsonResponse
    {
        return response()->json(['message'=>'Не найдено'], 404);
    }
}
