<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\app\Events\RegisterEvent;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Code;
use App\Models\College;
use App\Models\User;
use Exception;
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
                $college = College::where("uuid", $request->college_id)->first();
                if ($college) {
                    $user = User::create([
                        'user_name' => $request->user_name,
                        'phone' => $request->phone,
                        'role' => 'student',
                    ]);
                    $data['id'] = $user->uuid;
                    $data['name'] = $user->user_name;
                    $code = Str::random(8);
                    $user->codes()->create([
                        'code' => $code,
                        'college_id' => $college->id
                    ]);
                    $data['college_name'] = $college->name;
                    return $this->successResponse($data, "Registerd Successfully", 201);
                } else {
                    throw new Exception('College Not Found');
                }
            }
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('user_name', $request->user_name)->first();
            $code = Code::where('code', $request->code)->first();

            if (!$code and !$user) {
                return $this->errorResponse('user_name or code incorrect', 404);
            } else {
                $token = $code->createToken($user->user_name . "_token")->plainTextToken;
                $college = College::find($code->college_id);
                $success['token'] = $token;
                $success['name'] = $user->user_name;
                $success['college_name'] = $college->name;
                $success['college_id'] = $college->uuid;
                return $this->successResponse($success, 'login success', 200);
            }
        } catch (\Throwable $e) {

            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
    public function logout()
    {
        try {
            $code = User::where('id', Auth::id());
            // $code->tokens()->delete();
            return $this->successResponse($code, 'Logout Done', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
}