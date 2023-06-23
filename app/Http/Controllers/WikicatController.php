<?php


// $API_KEY = 'live_ObalGqySYbvel2M4m7EmM8ZCP8wXPEQU5AweCqeIJgRVyiNBagAbqyV1gtz5G5oV';
//
namespace App\Http\Controllers;

use App\Models\wikicat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class WikicatController extends Controller
{

    public function getAllBreeds(){

        //e.g. https://api.thecatapi.com/v1/images/search?breed_ids={breed.id}
        $BREEDS_URL = 'https://api.thecatapi.com/v1/breeds';
        try {
           $response = Http::get($BREEDS_URL);
           if($response->successful()){
            $breedsResponse = $response->json();
            $breeds = [];
            foreach ($breedsResponse as $breed) {
                if(isset($breed['reference_image_id'])){
                    $breeds[] = [
                        'catData' => $breed,
                        'reference_image_id' => $breed['reference_image_id'],
                    ];
                }else{
                    $breeds[] = [
                        'catData' => $breed,
                        'reference_image_id' => '',
                    ];
                }
            }

            //
        $IMAGES_URL = 'https://api.thecatapi.com/v1/images/';

            //mezclamos el array para obtener siempre imagenes aleatorias
        shuffle($breeds);

        $breedImages = [];
        $count = 0;

        /* Este código recorre una variedad de razas de gatos y verifica si cada raza tiene una
        identificación de imagen de referencia. Si es así, envía una solicitud GET a la API de Cat
        utilizando el ID de la imagen de referencia para obtener la URL de la imagen. Luego agrega
        el nombre de la raza y la URL de la imagen a una matriz llamada ``. El ciclo se
        detiene después de agregar 4 imágenes a la matriz. Este código se usa para obtener 4
        imágenes aleatorias de razas de gatos para mostrarlas en un carrusel. */
        foreach ($breeds as $breed) {
            if (isset($breed['reference_image_id'])) {
                $response = Http::get($IMAGES_URL . $breed['reference_image_id']);
                $breedImages[] = [
                    "name" => $breed['catData']['name'],
                    "imageUrl" => $response->json()['url'],
                    ]
                ;
                $count++;

                if ($count >= 4) {
                    break; // Detiene el bucle después de obtener 4 imágenes
                }
            }
        }
            return response()->json(["status" => true, "message"=> "Success", "data" => $breeds, "imagenes" => $breedImages],200);
        }else{
               return response()->json(["status" => false, "message"=> "Error getting breeds"],200);

           }
        } catch (\Exception $th) {
            return response()->json(["status" => false, "message"=> "Error on the conexion", "error" => $th],200);
        }
    }
    public function getCarouselImages(Request $request){
        $IMAGES_URL = 'https://api.thecatapi.com/v1/images/';

    }
}
