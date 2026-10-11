<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use Exception;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);
        
        try {
            $userMessage = $request->input('message');
            $aiResponse = $geminiService->getResponse($userMessage);

            return response()->json([
                'response' => $aiResponse
            ]);
        } catch (Exception $e) {
            // Esto nos devolverá el error real directamente a la pantalla del chat
            return response()->json([
                'response' => 'Error técnico: ' . $e->getMessage()
            ], 500);
        }
    }
}