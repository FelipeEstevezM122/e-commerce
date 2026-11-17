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

        $tickets = Ticket::whereHas('order', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id)
                ->where(function ($query) {

                    // Tickets de compras confirmadas
                    $query->whereIn('status', [
                        'paid',
                        'shipped',
                        'delivered'
                    ])

                    // Comprobantes de reservas presenciales vigentes
                    ->orWhere(function ($reserva) {
                        $reserva->where('status', 'pending')
                            ->where('payment_method', 'efectivo')
                            ->whereNotNull('expires_at')
                            ->where('expires_at', '>', now());
                    });

                });
            })
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
        // Verificar que el usuario pueda consultar este pedido
        $this->authorize('view', $order);

        // Buscar el ticket asociado al pedido
        $ticket = $order->ticket;

        if (!$ticket) {
            return response()->json([
                'message' => 'Este pedido aún no tiene ticket'
            ], Response::HTTP_NOT_FOUND);
        }

        // Verificar los permisos y la vigencia del ticket
        $this->authorize('view', $ticket);

        return response()->json([
            'datos' => $ticket->load(
                'order.user',
                'order.items.product.brand',
                'order.billingInfo'
            ),
            'message' => 'Ticket del pedido'
        ], Response::HTTP_OK);
    }


    /**
     * Listado administrativo de comprobantes pagados.
     * Mantiene la respuesta paginada { datos: { data, current_page, last_page } }
     * que ya consume tickets_admin.html. No depende de AdminController::tickets().
     */
    public function adminIndex(Request $request)
    {
        // La ruta también exige auth:sanctum y middleware admin en routes/api.php.
        $this->authorize('viewAll', Ticket::class);

        $tickets = Ticket::query()
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->with(['order.user', 'order.items.product.brand', 'order.billingInfo'])
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(15);

        return response()->json([
            'datos' => $tickets,
            'message' => 'Tickets de los clientes',
        ], Response::HTTP_OK);
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
