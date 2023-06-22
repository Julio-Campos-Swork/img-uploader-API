<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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


    /**
     * Esta función descarga una imagen de una URL dada, genera un nombre único para ella y la guarda
     * en una carpeta de destino específica.
     *
     * @param Request request  es un objeto que contiene la información de la solicitud HTTP,
     * incluido el método de solicitud, los encabezados y cualquier dato enviado en el cuerpo de la
     * solicitud. Es una instancia de la clase Illuminate\Http\Request en Laravel.
     *
     * return una respuesta JSON con un estado y un mensaje que indica si la imagen se cargó
     * correctamente o no. Si la carga fue exitosa, el estado será verdadero y el mensaje será "Imagen
     * cargada exitosamente". Si hubo un error durante la carga, el estado será falso y el mensaje será
     * "Error de carga", junto con un mensaje de error en el campo "error" del JSON
     */
    public function uploadIMGByUrl(Request $request)
    {
        try {
        $imageURL = $request->input('imageURL');
        $imageLabel = $request->input('label');
        // Obtener la ruta de la imagen sin los parámetros
        $parsedURL = parse_url($imageURL);
        $imagePath = $parsedURL['scheme'] . '://' . $parsedURL['host'] . $parsedURL['path'];
        // Descargar la imagen desde la URL
        $imageData = file_get_contents($imagePath);

        $imageID = Str::uuid()->toString();
        // Generar un nombre único para la imagen
        $imageName = $imageID . '_' .$imageLabel . '_' . time() . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
        // Guardar la imagen en la carpeta de destino
        $imageDestination = public_path('uploads/mySplash/' . $imageName);
        file_put_contents($imageDestination, $imageData);

            return response()->json(["status" => true,  "message" => "Image upload successfully"], 200);

        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["status" => false, "message" => "Upload error", "error" => $e], 200);
        }
    }


    /**
     * Esta función recupera una lista de imágenes de un directorio específico y devuelve una respuesta
     * JSON con la lista de URL de imágenes.
     *
     * return Esta función devuelve una respuesta JSON que contiene el estado de la operación, un
     * mensaje y una matriz de URL de imágenes. Si hay imágenes en el directorio especificado, sus URL
     * se devuelven en la respuesta. Si no hay imágenes, se devuelve un mensaje que indica que aún no
     * se han cargado imágenes.
     */
    public function getAllImages()
    {
        $imageDirectory = public_path('uploads/mySplash');
        $images = scandir($imageDirectory);
        $images = array_diff($images, ['.', '..']);
        if(count($images)){

            foreach ($images as $key => $image) {
                $imagesURL[] = asset('uploads/mySplash/' . $image);
            }
            return response()->json(["status" => true, 'message'=> "Images list",'images' => $imagesURL], 200);
        }else{
            return response()->json(["status" => false, 'message'=> "No images yet, please upload some"], 200);
        }

    }
    /**
     * Esta función de PHP elimina un archivo de imagen de un directorio específico y devuelve una
     * respuesta JSON que indica si la eliminación se realizó correctamente o no.
     *
     * @param Request request  es un objeto de la clase Request que contiene los datos enviados
     * en la solicitud HTTP. Se utiliza para recuperar datos de entrada, encabezados, cookies y otra
     * información relacionada con la solicitud. En esta función, se utiliza para recuperar el nombre
     * de archivo de la imagen que se va a eliminar.
     *
     * return Esta función devuelve una respuesta JSON con un estado y un mensaje que indica si la
     * imagen se eliminó correctamente o no. Si se elimina la imagen, el estado es verdadero y el
     * mensaje es "Imagen eliminada". Si no se encuentra la imagen, el estado es falso y el mensaje es
     * "Imagen no encontrada". Si hay un error al eliminar la imagen, el estado es falso y el mensaje
     * es "Error al eliminar",
     */
    public function deleteImage(Request $request)
    {
        try {
            $imageName = $request->input('fileName');
            $imagePath = public_path('uploads/mySplash/' . $imageName);
            if (file_exists($imagePath)) {
                unlink($imagePath);
                return response()->json(['status' => true, 'message' => 'Image deleted'], 200);
            }
            return response()->json(['status' => false, 'message' => 'Image not found'], 200);
        } catch (\Exception $e) {
           return response()->json(['status' => false, 'message' => 'Error deleting', "error"=> $e], 200);
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
}
