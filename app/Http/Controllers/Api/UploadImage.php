<?php

namespace App\Http\Controllers\Api;

trait UploadImage
{
    public function uploadImage($request, $input = "image", $data, $name)
    {
        try {
            $dir = 'images/' . $name;
            if ($file = $request->file($input)) {
                $image_ext = $file->getClientOriginalExtenion();
                $image_name = $data->uuid;
                $image_full_name = $image_name . "." . $image_ext;
                $file->move($dir, $image_full_name);
                $data->updateOrCreate(
                    ['uuid' => $data->uuid],
                    [$input => $dir . $image_full_name]
                );
                return true;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}