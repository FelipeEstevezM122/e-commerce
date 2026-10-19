<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $tickets = Ticket::whereHas('order', fn($q) => $q
                ->where('user_id', $request->user()->id)
                ->where('status', 'paid'))
            ->with('order.user', 'order.billingInfo', 'order.items.product.brand')
            ->orderBy('issued_at', 'desc')
            ->paginate(15);

        return response()->json(['datos' => $tickets, 'message' => 'Mis tickets'], Response::HTTP_OK);
    }

    public function show(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return response()->json([
            'datos'   => $ticket->load('order.user', 'order.items.product.brand', 'order.billingInfo'),
            'message' => 'Detalle del ticket'
        ], Response::HTTP_OK);
    }

    public function download(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load('order.user', 'order.items.product.brand', 'order.billingInfo');

        return Pdf::loadView('tickets.pdf', compact('ticket'))
            ->setPaper('a4')
            ->download($ticket->ticket_number . '.pdf');
    }

    public function showByOrder(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status !== 'paid') {
            return response()->json(['message' => 'El ticket estará disponible cuando el pedido esté pagado.'], Response::HTTP_NOT_FOUND);
        }

        $ticket = $order->ticket;
        if (!$ticket) {
            return response()->json(['message' => 'Este pedido aún no tiene ticket'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'datos'   => $ticket->load('order.user', 'order.items.product.brand', 'order.billingInfo'),
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

        if ($order->status !== 'paid') {
            return response()->json(['message' => 'Solo se puede generar un ticket para pedidos pagados'], Response::HTTP_BAD_REQUEST);
        }

        if ($order->ticket()->exists()) {
            return response()->json([
                'message' => 'Este pedido ya tiene un ticket',
                'datos' => $order->ticket()->with('order.user', 'order.items.product.brand', 'order.billingInfo')->first(),
            ], Response::HTTP_OK);
        }

        $ticket = Ticket::createForOrder($order);

        return response()->json([
            'datos'   => $ticket->load('order.user', 'order.items.product.brand', 'order.billingInfo'),
            'message' => 'Ticket generado exitosamente'
        ], Response::HTTP_CREATED);
    }
}
