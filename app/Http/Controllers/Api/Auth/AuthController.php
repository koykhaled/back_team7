<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        try {
            if (User::where('phone', $request->phone)->exists()) {
                return $this->errorResponse('User Already register', 400);
            } else {
                $user = User::create([
                    'user_name' => $request->name,
                    'phone' => $request->phone,
                    'role' => 'student',
                    'collage_id' => $request->collage_id,
                ]);
                $data['id'] = $user->uuid;
                $data['user_name'] = $user->name;

                // create code for login 

                return $this->successResponse($data, "Registerd Successfully", 201);
            }
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('name', $request->name)->first();

            if (!Auth::attempt($request->only('name', 'code'))) {
                return $this->errorResponse('name or code incorrect', 404);
            } else {
                $token = $user->createToken($user->name . "_token")->plainTextToken;
                $success['token'] = $token;
                $success['name'] = $user->name;
                return $this->successResponse($success, 'login success', 200);
            }
        } catch (\Throwable $e) {

            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
    public function logout(LoginRequest $request)
    {
        try {
            $user = User::whereId(Auth::id())->first();
            $user->currentAccessToken()->delete();
            return $this->successResponse(null, 'Logout Done', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
}