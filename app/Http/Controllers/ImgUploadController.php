<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImgUploadController extends Controller
{

    public function setImage(Request $request)
    {
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/imagenes'), $imageName);
                $imageUrl = asset('uploads/imagenes/' . $imageName);
                return response()->json(["status" => true, "imageUrl" => $imageUrl, "message" => "Image upload successfully"], 200);
              }
              return response()->json(["status" => false, "message" => "Upload error",], 200);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["status" => false, "message" => "Upload error", "error" => $e], 200);
        }


    }




    public function getImage($imageName)
    {

        $imagePath = public_path('uploads/imagenes/' . $imageName);

        if (file_exists($imagePath)) {
            return response()->file($imagePath);
        }
        return response()->json(['status' => false, 'message' => 'Image not found'], 200);
    }
    public function getAllImages()
    {
        $imageDirectory = public_path('uploads/images');
        $images = scandir($imageDirectory);
        $images = array_diff($images, ['.', '..']);

        return response()->json(['images' => $images], 200);

    }
    public function deleteImage(Request $request)
    {
        $imageName = $request->input('imageName');
        $imagePath = public_path('uploads/images/' . $imageName);
        if (file_exists($imagePath)) {
            unlink($imagePath);
            return response()->json(['status' => true, 'message' => 'Image deleted'], 200);
        }
    }
}
