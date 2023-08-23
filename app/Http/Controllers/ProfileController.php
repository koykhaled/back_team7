<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiResponse;
use App\Http\Controllers\Api\UploadImage;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use UploadImage, ApiResponse;
    //

    public function update(Request $request, $id)
    {
        try {
            $user = User::where('uuid', $id)->first();
            if ($user) {
                $this->uploadImage($request, 'photo', $user, 'profile/');
                $user->user_name = $request->user_name ?? $user->user_name;
                $user->phone = $request->phone ?? $user->phone;
                $user->save();
                $result = new ProfileResource($user);
                return $this->successResponse($result, 'Updating Successfully', 200);
            } else {
                return $this->errorResponse('User Not Found', 404);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse("Error . " . $th->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
    }
}