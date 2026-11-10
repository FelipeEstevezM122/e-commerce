<div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse;font-size:11px">
        <thead>
            <tr style="color:#6b7280;text-align:left;text-transform:uppercase;font-size:9px">
                <th style="padding:9px 7px">SKU</th>
                <th>Producto</th>
                <th style="text-align:right">Unidades</th>
                <th style="text-align:right">Total vendido</th>
                <th style="text-align:right">Stock actual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['products'] as $product)
                <tr>
                    <td style="padding:10px 7px">{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td style="text-align:right">{{ number_format((int) $product->quantity_sold) }}</td>
                    <td style="text-align:right">Bs. {{ number_format((float) $product->total, 2, '.', ',') }}</td>
                    <td style="text-align:right">{{ number_format((int) $product->stock) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:20px;color:#9ca3af">No hay productos vendidos en este mes.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight:800;color:#bbf7d0;border-top:1px solid rgba(34,197,94,.25)">
                <td colspan="3" style="padding:12px 7px">TOTAL FINAL DEL MES</td>
                <td style="text-align:right">Bs. {{ number_format((float) $report['totalSales'], 2, '.', ',') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
