<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GeminiService
{
    public function getResponse(string $prompt)
    {
        // Leemos la clave directamente del .env para evitar problemas de caché de configuración
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            throw new Exception("La API Key de Gemini no está definida en el archivo .env");
        }

        // Usamos el nombre base limpio de la versión flash
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={$apiKey}";
        //$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-pro-preview:generateContent?key={$apiKey}";
        $response = Http::post($url, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Eres un asistente virtual amigable y experto para un e-commerce. Ayudas a los clientes con sus dudas sobre productos, envíos y compras. Responde de forma concisa.\n\nCliente: " . $prompt
                        ]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text') ?? 'Lo siento, no pude generar una respuesta.';
        }

        // Si Google rechaza la petición, devolvemos el error exacto que nos da la API
        return 'Error de la API de Gemini: ' . $response->body();
    }
}