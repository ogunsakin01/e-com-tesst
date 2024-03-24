<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseHelper;

    public function register(RegistrationRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return $this->formattedResponse([
            'code' => 200,
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $passwordCheck = Hash::check($request->password, $user->password);
        if(!$passwordCheck){
            return response()->json([
                'message' => 'Invalid password'
            ], 422);
        }
        $token = $user->createToken('Testing');
        return $this->formattedResponse([
            'code' => 200,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token->plainTextToken
            ]
        ]);
    }
}
