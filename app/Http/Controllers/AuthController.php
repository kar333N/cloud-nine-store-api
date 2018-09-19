<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AuthController extends Controller
{
    /**
     * Авторизация пользователя и создания токена
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        //установка флага remember_me дает возможность не авторизововываться заново 1 неделю
        // если флаг не установлен то через 5 минут последует запрос повторной авторизации
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        } else {
            $token->expires_at = Carbon::now()->addMinute(5);
        }
        $token->save();

        return [
            'success' => 1,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
    }

    /**
     * Logout пользователя
     * @param Request $request
     * @return array
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return [
            'message' => 'Successfully logged out'
        ];
    }
}