<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $ticket->ticket_number }}</title>
    <style>
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2, p { margin: 0; }
        h1 { color: #166534; font-size: 22px; }
        h2 { border-bottom: 1px solid #d1d5db; font-size: 13px; margin: 22px 0 10px; padding-bottom: 6px; }
        .header { border-bottom: 3px solid #22c55e; padding-bottom: 14px; }
        .muted { color: #6b7280; }
        .details { line-height: 1.8; margin-top: 12px; }
        table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        th { background: #f3f4f6; text-align: left; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; }
        .right { text-align: right; }
        .total { color: #166534; font-size: 16px; font-weight: bold; margin-top: 16px; text-align: right; }
        .notice { background: #fef3c7; margin-top: 18px; padding: 9px; }
    </style>
</head>
<body>
    @php($order = $ticket->order)
    @php($billing = $order->billingInfo)
    <header class="header">
        <h1>CASATEK · Ticket de compra</h1>
        <p class="muted">Comprobante {{ $ticket->ticket_number }}</p>
    </header>

    <section class="details">
        <strong>Pedido:</strong> {{ $order->order_number }}<br>
        <strong>Fecha:</strong> {{ $ticket->issued_at->format('d/m/Y H:i') }}<br>
        <strong>Estado:</strong> {{ ucfirst($order->status) }}<br>
        <strong>Forma de pago:</strong> {{ ucfirst($order->payment_method) }}
    </section>

    <h2>Datos del cliente</h2>
    <div class="details">
        <strong>Nombre:</strong> {{ $order->user->name }}<br>
        <strong>Correo:</strong> {{ $order->user->email }}<br>
        @if($order->user->phone)
            <strong>Teléfono:</strong> {{ $order->user->phone }}<br>
        @endif
        @if($billing)
            @if($billing->address)
                <strong>Dirección:</strong> {{ $billing->address }}<br>
            @endif
            @if($billing->nit)
                <strong>NIT:</strong> {{ $billing->nit }}<br>
            @endif
            @if($billing->business_name)
                <strong>Razón social:</strong> {{ $billing->business_name }}<br>
            @endif
            @if($billing->company_name)
                <strong>Empresa:</strong> {{ $billing->company_name }}<br>
            @endif
            @if($billing->whatsapp)
                <strong>WhatsApp:</strong> {{ $billing->whatsapp }}<br>
            @endif
        @endif
    </div>

    <h2>Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="right">Cantidad</th>
                <th class="right">Precio unitario</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Producto eliminado' }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">Bs. {{ number_format($item->price_when_ordered, 2) }}</td>
                    <td class="right">Bs. {{ number_format($item->price_when_ordered * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: Bs. {{ number_format($order->total, 2) }}</p>
    @if($order->status === 'pending')
        <p class="notice">Pedido pendiente de confirmación de pago. Este comprobante no confirma que el pago haya sido recibido.</p>
    @endif
</body>
</html>
