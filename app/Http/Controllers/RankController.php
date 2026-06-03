<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RankController extends Controller
{
    public function __construct()
    {
        // Listar rangos es público; crear/editar/eliminar solo admin
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    // Listar todos los rangos (público, útil para el frontend)
    public function index()
    {
        $ranks = Rank::orderBy('monthly_minimum_purchase')->get();

        return response()->json([
            'datos'   => $ranks,
            'message' => 'Lista de rangos'
        ], Response::HTTP_OK);
    }

    // Ver un rango específico
    public function show(Rank $rank)
    {
        return response()->json([
            'datos'   => $rank,
            'message' => 'Detalle del rango'
        ], Response::HTTP_OK);
    }

    // ── ADMIN: crear rango ────────────────────────────────────────

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $request->validate([
            'name'                     => 'required|string|max:100|unique:ranks,name',
            'monthly_minimum_purchase' => 'required|numeric|min:0',
            'description'              => 'nullable|string',
        ]);

        $rank = Rank::create($request->only('name', 'monthly_minimum_purchase', 'description'));

        return response()->json([
            'datos'   => $rank,
            'message' => 'Rango creado exitosamente'
        ], Response::HTTP_CREATED);
    }

    // ── ADMIN: actualizar rango ───────────────────────────────────

    public function update(Request $request, Rank $rank)
    {
        $this->authorizeAdmin($request);

        $request->validate([
            'name'                     => 'sometimes|string|max:100|unique:ranks,name,' . $rank->id,
            'monthly_minimum_purchase' => 'sometimes|numeric|min:0',
            'description'              => 'nullable|string',
        ]);

        $rank->update($request->only('name', 'monthly_minimum_purchase', 'description'));

        return response()->json([
            'datos'   => $rank,
            'message' => 'Rango actualizado exitosamente'
        ], Response::HTTP_OK);
    }

    // ── ADMIN: eliminar rango ─────────────────────────────────────

    public function destroy(Request $request, Rank $rank)
    {
        $this->authorizeAdmin($request);

        // Evitar eliminar si hay usuarios con ese rango
        if ($rank->users()->count() > 0) {
            return response()->json([
                'message' => "No se puede eliminar: {$rank->users()->count()} usuario(s) tienen este rango"
            ], Response::HTTP_BAD_REQUEST);
        }

        $rank->delete();

        return response()->json(['message' => 'Rango eliminado'], Response::HTTP_OK);
    }

    private function authorizeAdmin(Request $request): void
    {
        if (!$request->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, 'Solo administradores pueden realizar esta acción');
        }
    }
}
