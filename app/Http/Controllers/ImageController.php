<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary();
    }

    // Mostrar formulario
    public function index()
    {
        return view('upload');
    }

    // Subir imagen
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Subir a Cloudinary
            $result = $this->cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'casatek/images',
                    'public_id' => uniqid()
                ]
            );

            // Guardar en base de datos
            $image = Image::create([
                'public_id' => $result['public_id'],
                'cloudinary_url' => $result['secure_url'],
                'original_name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'format' => $result['format'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen subida correctamente',
                'image' => $image
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mostrar todas las imágenes
    public function showImages()
    {
        $images = Image::all();
        return view('gallery', compact('images'));
    }
}