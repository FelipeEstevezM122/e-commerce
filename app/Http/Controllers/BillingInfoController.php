<?php

namespace App\Http\Controllers;

use App\Models\BillingInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Listar todas las direcciones del usuario autenticado
    public function index(Request $request)
    {
        $billingInfos = BillingInfo::where('user_id', $request->user()->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'datos'   => $billingInfos,
            'message' => 'Datos de facturación'
        ], Response::HTTP_OK);
    }

    // Ver una dirección específica (solo si es del usuario)
    public function show(Request $request, BillingInfo $billingInfo)
    {
        if ($billingInfo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para ver este registro'], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'datos'   => $billingInfo,
            'message' => 'Detalle de facturación'
        ], Response::HTTP_OK);
    }

    // Crear nueva dirección/datos de facturación
    public function store(Request $request)
    {
        $request->validate([
            'address'       => 'nullable|string|max:500',
            'company_name'  => 'nullable|string|max:150',
            'nit'           => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:150',
            'whatsapp'      => 'nullable|string|max:20',
            'is_default'    => 'nullable|boolean',
        ]);

        $user = $request->user();

        // Si es la primera dirección, marcarla como default automáticamente
        $isFirst   = BillingInfo::where('user_id', $user->id)->count() === 0;
        $isDefault = $request->boolean('is_default', $isFirst);

        // Si se marca como default, quitar el default a las demás
        if ($isDefault) {
            BillingInfo::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $billingInfo = BillingInfo::create([
            'user_id'       => $user->id,
            'address'       => $request->address,
            'company_name'  => $request->company_name,
            'nit'           => $request->nit,
            'business_name' => $request->business_name,
            'whatsapp'      => $request->whatsapp,
            'is_default'    => $isDefault,
        ]);

        return response()->json([
            'datos'   => $billingInfo,
            'message' => 'Datos de facturación creados'
        ], Response::HTTP_CREATED);
    }

    // Actualizar una dirección existente
    public function update(Request $request, BillingInfo $billingInfo)
    {
        if ($billingInfo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para editar este registro'], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'address'       => 'nullable|string|max:500',
            'company_name'  => 'nullable|string|max:150',
            'nit'           => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:150',
            'whatsapp'      => 'nullable|string|max:20',
            'is_default'    => 'nullable|boolean',
        ]);

        // Si se marca como default, quitar el default a las demás
        if ($request->boolean('is_default')) {
            BillingInfo::where('user_id', $request->user()->id)
                ->where('id', '!=', $billingInfo->id)
                ->update(['is_default' => false]);
        }

        $billingInfo->update($request->only([
            'address', 'company_name', 'nit', 'business_name', 'whatsapp', 'is_default'
        ]));

        return response()->json([
            'datos'   => $billingInfo->fresh(),
            'message' => 'Datos de facturación actualizados'
        ], Response::HTTP_OK);
    }

    // Marcar como dirección predeterminada
    public function setDefault(Request $request, BillingInfo $billingInfo)
    {
        if ($billingInfo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso'], Response::HTTP_FORBIDDEN);
        }

        $billingInfo->setAsDefault();

        return response()->json([
            'datos'   => $billingInfo->fresh(),
            'message' => 'Dirección marcada como predeterminada'
        ], Response::HTTP_OK);
    }

    // Eliminar una dirección
    public function destroy(Request $request, BillingInfo $billingInfo)
    {
        if ($billingInfo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar este registro'], Response::HTTP_FORBIDDEN);
        }

        // No permitir eliminar si es la única dirección
        $count = BillingInfo::where('user_id', $request->user()->id)->count();
        if ($count <= 1) {
            return response()->json([
                'message' => 'No puedes eliminar tu única dirección de facturación'
            ], Response::HTTP_BAD_REQUEST);
        }

        $wasDefault = $billingInfo->is_default;
        $billingInfo->delete();

        // Si era la default, asignar la más reciente como nueva default
        if ($wasDefault) {
            BillingInfo::where('user_id', $request->user()->id)
                ->latest()
                ->first()
                ?->update(['is_default' => true]);
        }

        return response()->json(['message' => 'Dirección eliminada'], Response::HTTP_OK);
    }
}
