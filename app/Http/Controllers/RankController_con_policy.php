<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RankController_con_policy extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    public function index()
    {
        // viewAny con ?User → funciona sin autenticación
        $this->authorize('viewAny', Rank::class);

        return response()->json([
            'datos'   => Rank::orderBy('monthly_minimum_purchase')->get(),
            'message' => 'Lista de rangos'
        ], Response::HTTP_OK);
    }

    public function show(Rank $rank)
    {
        $this->authorize('view', $rank);

        return response()->json(['datos' => $rank, 'message' => 'Detalle del rango'], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Rank::class); // Policy: solo admin

        $request->validate([
            'name'                     => 'required|string|max:100|unique:ranks,name',
            'monthly_minimum_purchase' => 'required|numeric|min:0',
            'description'              => 'nullable|string',
        ]);

        $rank = Rank::create($request->only('name', 'monthly_minimum_purchase', 'description'));

        return response()->json(['datos' => $rank, 'message' => 'Rango creado'], Response::HTTP_CREATED);
    }

    public function update(Request $request, Rank $rank)
    {
        $this->authorize('update', $rank); // Policy: solo admin

        $request->validate([
            'name'                     => 'sometimes|string|max:100|unique:ranks,name,' . $rank->id,
            'monthly_minimum_purchase' => 'sometimes|numeric|min:0',
            'description'              => 'nullable|string',
        ]);

        $rank->update($request->only('name', 'monthly_minimum_purchase', 'description'));

        return response()->json(['datos' => $rank, 'message' => 'Rango actualizado'], Response::HTTP_OK);
    }

    public function destroy(Request $request, Rank $rank)
    {
        $this->authorize('delete', $rank); // Policy: solo admin

        if ($rank->users()->count() > 0) {
            return response()->json([
                'message' => "No se puede eliminar: {$rank->users()->count()} usuario(s) tienen este rango"
            ], Response::HTTP_BAD_REQUEST);
        }

        $rank->delete();
        return response()->json(['message' => 'Rango eliminado'], Response::HTTP_OK);
    }
}
