<?php

namespace App\Http\Controllers;

use App\Models\BillingInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingInfoController_con_policy extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', BillingInfo::class);

        $billingInfos = BillingInfo::where('user_id', $request->user()->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['datos' => $billingInfos, 'message' => 'Datos de facturación'], Response::HTTP_OK);
    }

    public function show(Request $request, BillingInfo $billingInfo)
    {
        $this->authorize('view', $billingInfo); // Policy: dueño o admin

        return response()->json(['datos' => $billingInfo, 'message' => 'Detalle de facturación'], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $this->authorize('create', BillingInfo::class);

        $request->validate([
            'address'       => 'nullable|string|max:500',
            'company_name'  => 'nullable|string|max:150',
            'nit'           => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:150',
            'whatsapp'      => 'nullable|string|max:20',
            'is_default'    => 'nullable|boolean',
        ]);

        $user      = $request->user();
        $isFirst   = BillingInfo::where('user_id', $user->id)->count() === 0;
        $isDefault = $request->boolean('is_default', $isFirst);

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

        return response()->json(['datos' => $billingInfo, 'message' => 'Datos de facturación creados'], Response::HTTP_CREATED);
    }

    public function update(Request $request, BillingInfo $billingInfo)
    {
        $this->authorize('update', $billingInfo); // Policy: dueño o admin

        $request->validate([
            'address'       => 'nullable|string|max:500',
            'company_name'  => 'nullable|string|max:150',
            'nit'           => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:150',
            'whatsapp'      => 'nullable|string|max:20',
            'is_default'    => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            BillingInfo::where('user_id', $request->user()->id)
                ->where('id', '!=', $billingInfo->id)
                ->update(['is_default' => false]);
        }

        $billingInfo->update($request->only(['address', 'company_name', 'nit', 'business_name', 'whatsapp', 'is_default']));

        return response()->json(['datos' => $billingInfo->fresh(), 'message' => 'Datos actualizados'], Response::HTTP_OK);
    }

    public function setDefault(Request $request, BillingInfo $billingInfo)
    {
        $this->authorize('setDefault', $billingInfo); // Policy: solo el dueño

        $billingInfo->setAsDefault();

        return response()->json(['datos' => $billingInfo->fresh(), 'message' => 'Dirección predeterminada actualizada'], Response::HTTP_OK);
    }

    public function destroy(Request $request, BillingInfo $billingInfo)
    {
        $this->authorize('delete', $billingInfo); // Policy: dueño o admin

        $count = BillingInfo::where('user_id', $request->user()->id)->count();
        if ($count <= 1) {
            return response()->json(['message' => 'No puedes eliminar tu única dirección de facturación'], Response::HTTP_BAD_REQUEST);
        }

        $wasDefault = $billingInfo->is_default;
        $billingInfo->delete();

        if ($wasDefault) {
            BillingInfo::where('user_id', $request->user()->id)->latest()->first()?->update(['is_default' => true]);
        }

        return response()->json(['message' => 'Dirección eliminada'], Response::HTTP_OK);
    }
}
