<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas {{ $month }}</title>
    <style>
        @page { margin: 32px 36px; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { border-bottom: 3px solid #15803d; margin-bottom: 20px; padding-bottom: 12px; }
        .brand { color: #15803d; font-size: 10px; font-weight: bold; letter-spacing: 2px; }
        h1 { color: #111827; font-size: 22px; margin: 7px 0 4px; }
        .generated { color: #6b7280; font-size: 9px; }
        .summary { background: #f0fdf4; border: 1px solid #bbf7d0; margin-bottom: 18px; padding: 14px; }
        .summary-label { color: #166534; font-size: 9px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .summary-value { color: #14532d; font-size: 20px; font-weight: bold; margin-top: 5px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #166534; color: #fff; font-size: 9px; padding: 9px 7px; text-align: left; }
        td { border-bottom: 1px solid #e5e7eb; padding: 8px 7px; }
        td.number, th.number { text-align: right; }
        tfoot td { background: #f0fdf4; border-top: 2px solid #15803d; color: #14532d; font-weight: bold; }
        .empty { color: #6b7280; padding: 18px 10px; text-align: center; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 8px; margin-top: 24px; padding-top: 8px; }
    </style>
</head>
<body>
    @php
        $monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        [$year, $monthNumber] = explode('-', $month);
    @endphp

    <header class="header">
        <div class="brand">CASATEK</div>
        <h1>Reporte de ventas — {{ $monthNames[(int) $monthNumber] }} {{ $year }}</h1>
        <div class="generated">Pedidos entregados · Generado el {{ $generatedAt->format('d/m/Y H:i') }}</div>
    </header>

    <section class="summary">
        <div class="summary-label">Total final del mes</div>
        <div class="summary-value">Bs. {{ number_format((float) $totalSales, 2, '.', ',') }}</div>
    </section>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Producto</th>
                <th class="number">Unidades vendidas</th>
                <th class="number">Total vendido (Bs.)</th>
                <th class="number">Stock actual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="number">{{ number_format((int) $product->quantity_sold) }}</td>
                    <td class="number">{{ number_format((float) $product->total, 2, '.', ',') }}</td>
                    <td class="number">{{ number_format((int) $product->stock) }}</td>
                </tr>
            @empty
                <tr><td class="empty" colspan="5">No hay productos vendidos en este mes.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL FINAL DEL MES</td>
                <td class="number">Bs. {{ number_format((float) $totalSales, 2, '.', ',') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <footer class="footer">Reporte administrativo de ventas · CASATEK</footer>
</body>
</html>
