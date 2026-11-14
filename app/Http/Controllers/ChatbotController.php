<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\GroqService;
use App\Http\Services\ChatbotRateLimitException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request, GroqService $assistant)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'sometimes|array|max:4',
            'history.*' => 'required|array:role,content',
            'history.*.role' => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:1200',
        ]);
        
        try {
            // Identity comes exclusively from Sanctum, never from message/user_id/roles.
            $user = Auth::guard('sanctum')->user();
            if ($request->bearerToken() && !$user) {
                return response()->json(['response' => 'Tu sesión expiró. Inicia sesión de nuevo.'], 401);
            }
            $aiResponse = $assistant->getResponse($request->input('message'), $user, $request->input('history', []));

            return response()->json([
                'response' => $aiResponse
            ]);
        } catch (ChatbotRateLimitException $e) {
            return response()->json(['response' => 'El servicio de IA alcanzó su límite temporal. Intenta de nuevo más tarde.'], 429);
        } catch (Throwable $e) {
            Log::warning('Chatbot no disponible', ['type' => get_class($e), 'code' => $e->getCode()]);
            return response()->json([
                'response' => 'El asistente no está disponible en este momento. Intenta de nuevo en unos minutos.'
            ], 503);
        }
    }
}
