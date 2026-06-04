<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController_con_policy extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

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
        $this->authorize('view', $ticket); // Policy: dueño del pedido o admin

        return response()->json([
            'datos'   => $ticket->load('order.items.product', 'order.billingInfo'),
            'message' => 'Detalle del ticket'
        ], Response::HTTP_OK);
    }

    public function showByOrder(Request $request, Order $order)
    {
        $this->authorize('view', $order); // reutiliza OrderPolicy

        $ticket = $order->ticket;
        if (!$ticket) {
            return response()->json(['message' => 'Este pedido aún no tiene ticket'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'datos'   => $ticket->load('order.items.product', 'order.billingInfo'),
            'message' => 'Ticket del pedido'
        ], Response::HTTP_OK);
    }

    public function adminIndex(Request $request)
    {
        $this->authorize('viewAll', Ticket::class); // Policy: solo admin

        $query = Ticket::with('order.user', 'order.billingInfo');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('order', fn($q) => $q->where('order_number', 'LIKE', "%{$search}%"));
        }

        if ($request->filled('from_date')) $query->whereDate('issued_at', '>=', $request->from_date);
        if ($request->filled('to_date'))   $query->whereDate('issued_at', '<=', $request->to_date);

        return response()->json([
            'datos'   => $query->orderBy('issued_at', 'desc')->paginate(15),
            'message' => 'Lista de todos los tickets'
        ], Response::HTTP_OK);
    }

    public function generate(Request $request, Order $order)
    {
        $this->authorize('generate', Ticket::class); // Policy: solo admin

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
