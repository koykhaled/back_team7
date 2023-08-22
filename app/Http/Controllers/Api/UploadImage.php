<?php

namespace App\Http\Controllers\Api;

trait UploadImage
{
    public function uploadImage($request, $input = "image", $data, $name)
    {
        try {
            $dir = 'images/' . $name;
            if ($fileData = $request->input($input)) {
                // Extract the image extension from the base64 data
                $extension = explode('/', mime_content_type($fileData))[1];
                $image_name = 'data';
                $image_full_name = $image_name . "." . $extension;

                // Decode the base64 image data
                $file = base64_decode($fileData);

                // Save the image file
                file_put_contents($dir . $image_full_name, $file);

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