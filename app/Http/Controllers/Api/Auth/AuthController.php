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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    'user_name' => $request->user_name,
                    'phone' => $request->phone,
                    'role' => 'student',
                ]);
                $data['id'] = $user->uuid;
                $data['name'] = $user->user_name;

                $college=College::where("uuid",$request->college_id)->first();
                do{
                    $code=random_int(100000, 999999);
                     }while(Code::where('code',$code)->exists());
                $user->code()->create([
                 'code'=>$code,
                 'college_id'=>$college->id
                ]);
                
                return $this->successResponse($data, "Registerd Successfully", 201);
            }
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR. " . $e->getMessage(), 500);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('user_name', $request->user_name)->first();

            if (!Auth::attempt($request->only('name', 'code'))) {
                return $this->errorResponse('name or code incorrect', 404);
            } else {
                $token = $user->createToken($user->user_name . "_token")->plainTextToken;
                $success['token'] = $token;
                $success['name'] = $user->user_name;
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