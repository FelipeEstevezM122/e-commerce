<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['adminIndex', 'generate']);
    }

    /**
     * Tickets del usuario autenticado (API Sanctum)
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = Ticket::whereHas('order', fn($q) => $q->where('user_id', $request->user()->id))
            ->with('order.items.product')
            ->orderBy('issued_at', 'desc')
            ->paginate(15);

        return response()->json(['datos' => $tickets, 'message' => 'Mis tickets'], Response::HTTP_OK);
    }

    public function show(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return response()->json([
            'datos'   => $ticket->load('order.items.product', 'order.billingInfo'),
            'message' => 'Detalle del ticket'
        ], Response::HTTP_OK);
    }

    public function showByOrder(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $ticket = $order->ticket;
        if (!$ticket) {
            return response()->json(['message' => 'Este pedido aún no tiene ticket'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'datos'   => $ticket->load('order.items.product', 'order.billingInfo'),
            'message' => 'Ticket del pedido'
        ], Response::HTTP_OK);
    }

    /**
     * Vista admin de tickets — usa SP sp_admin_filtrar_tickets.
     * La lógica completa vive en AdminController@tickets para mantener
     * consistencia con el resto del panel; este método lo delega.
     */
    public function adminIndex(Request $request)
    {
        // Delegar al método del AdminController que usa el SP
        return app(AdminController::class)->tickets($request);
    }

    /**
     * Generar ticket manualmente (admin)
     */
    public function generate(Request $request, Order $order)
    {
        $this->authorize('generate', Ticket::class);

        if (!in_array($order->status, ['paid', 'delivered'])) {
            return response()->json(['message' => 'Solo se puede generar ticket para pedidos pagados o entregados'], Response::HTTP_BAD_REQUEST);
        }

        if ($order->ticket()->exists()) {
            return response()->json(['message' => 'Este pedido ya tiene un ticket', 'datos' => $order->ticket], Response::HTTP_OK);
        }

        $ticket = Ticket::create([
            'order_id'      => $order->id,
            'ticket_number' => Ticket::generateTicketNumber(),
            'issued_at'     => now(),
        ]);

        return response()->json([
            'datos'   => $ticket->load('order.items.product', 'order.billingInfo'),
            'message' => 'Ticket generado exitosamente'
        ], Response::HTTP_CREATED);
    }
}
