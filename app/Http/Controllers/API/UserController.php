<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // функция авторизации
    public function login(Request $request)
    {
        // пользователь не найден
        if (!Auth::attempt($request->only('login', 'password'))) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $user = User::where('login', $request['login'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user, 'access_token' => $token,]);
    }

    // функция регистрации
    public function signup(Request $request)
    {
        // валидация полей
        $validate = Validator::make($request->all(),
            [
                'fio' => 'required|string|min:5',
                'login' => 'required|string|min:5|unique:users',
                'password' => 'required|string|min:8'
            ],
            [
                'required' => 'Необходимо указать :attribute.',
                'string' => 'Поле :attribute должно содержать только символы.',
                'min' => 'Поле :attribute должно содержать минимум :min символов',
                'unique' => 'Такой пользователь уже зарегистрирован',
            ]);
        // отправка ошибок валидации
        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 422);
        }
        // добавление в бд
        $user = User::create([
            'fio' => $request->fio,
            'login' => $request->login,
            'password' => Hash::make($request->password)
        ]);
        // сздание сессии
        $token = $user->createToken('auth_token')->plainTextToken;
        // успешный ответ
        return response()->json(['data' => $user, 'access_token' => $token,], 201);
    }
}
