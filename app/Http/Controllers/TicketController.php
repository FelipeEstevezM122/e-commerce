<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Listar todos los tickets del usuario autenticado
    public function index(Request $request)
    {
        $tickets = Ticket::whereHas('order', fn($q) => $q->where('user_id', $request->user()->id))
            ->with('order.items.product')
            ->orderBy('issued_at', 'desc')
            ->paginate(15);

        return response()->json([
            'datos'   => $tickets,
            'message' => 'Mis tickets'
        ], Response::HTTP_OK);
    }

    // Ver un ticket específico (solo si el pedido es del usuario)
    public function show(Request $request, Ticket $ticket)
    {
        if ($ticket->order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para ver este ticket'], Response::HTTP_FORBIDDEN);
        }

        $ticket->load('order.items.product', 'order.billingInfo');

        return response()->json([
            'datos'   => $ticket,
            'message' => 'Detalle del ticket'
        ], Response::HTTP_OK);
    }

    // Ver ticket por número de orden (útil para el frontend)
    public function showByOrder(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para ver este pedido'], Response::HTTP_FORBIDDEN);
        }

        $ticket = $order->ticket;

        if (!$ticket) {
            return response()->json(['message' => 'Este pedido aún no tiene ticket generado'], Response::HTTP_NOT_FOUND);
        }

        $ticket->load('order.items.product', 'order.billingInfo');

        return response()->json([
            'datos'   => $ticket,
            'message' => 'Ticket del pedido'
        ], Response::HTTP_OK);
    }

    // ── ADMIN: listar todos los tickets ──────────────────────────

    public function adminIndex(Request $request)
    {
        $this->authorizeAdmin($request);

        $query = Ticket::with('order.user', 'order.billingInfo');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('order', fn($q) => $q->where('order_number', 'LIKE', "%{$search}%"));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('issued_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('issued_at', '<=', $request->to_date);
        }

        return response()->json([
            'datos'   => $query->orderBy('issued_at', 'desc')->paginate(15),
            'message' => 'Lista de todos los tickets'
        ], Response::HTTP_OK);
    }

    // ── ADMIN: generar ticket manualmente para un pedido pagado ──

    public function generate(Request $request, Order $order)
    {
        $this->authorizeAdmin($request);

        if (!in_array($order->status, ['paid', 'delivered'])) {
            return response()->json([
                'message' => 'Solo se puede generar ticket para pedidos pagados o entregados'
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($order->ticket()->exists()) {
            return response()->json([
                'message'  => 'Este pedido ya tiene un ticket',
                'datos'    => $order->ticket
            ], Response::HTTP_OK);
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

    // Helper para verificar admin sin middleware global
    private function authorizeAdmin(Request $request): void
    {
        if (!$request->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, 'Solo administradores pueden realizar esta acción');
        }
    }
}
