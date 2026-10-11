# Primer workflow de n8n para escenarios de precio

El workflow `workflows/price-forecast.json` expone un webhook POST llamado `price-forecast`, valida los datos y devuelve un escenario de precio ponderado por el costo dolarizado del producto.

## Importar

1. Inicia Docker Compose y abre <http://localhost:5678>.
2. Crea la cuenta de propietario si es la primera vez.
3. En n8n, importa `n8n/workflows/price-forecast.json` desde el menú de workflows.
4. Guarda el workflow.
5. Para una prueba desde el editor, pulsa **Listen for test event** y envía la petición de prueba de abajo a la URL de prueba.
6. Cuando funcione, activa el workflow. La URL de producción local será `http://localhost:5678/webhook/price-forecast`.

## Probar desde PowerShell

Con el workflow escuchando una ejecución de prueba en n8n:

```powershell
$body = @{
  product_id = 123
  base_price = 250
  usd_cost_share = 0.65
  reference_exchange_rate = 6.90
  current_exchange_rate = 6.95
  forecast_exchange_rate = 7.10
} | ConvertTo-Json

Invoke-RestMethod `
  -Uri "http://localhost:5678/webhook-test/price-forecast" `
  -Method Post `
  -ContentType "application/json" `
  -Body $body
```

Los valores del ejemplo son ficticios para probar el cálculo, no son una cotización del BCB. `usd_cost_share` es una proporción de 0 a 1: por ejemplo, `0.65` significa que se estima que el 65 % del precio base depende de costos en dólares.

La fórmula aplicada es:

```text
precio estimado = precio base × ((1 - proporción USD) + proporción USD × (tipo de cambio escenario / tipo de cambio de referencia))
```

Si se omite `forecast_exchange_rate`, el workflow usa `current_exchange_rate` y calcula un escenario con el dato que le haya pasado el llamador. El workflow no consulta por sí solo al BCB.

## Qué falta para conectarlo al BCB

Este primer workflow no consulta directamente al BCB ni entrena un modelo de machine learning. El webhook recibe `current_exchange_rate` desde quien lo invoque y calcula el impacto cambiario; `forecast_exchange_rate` es opcional y, si se envía, representa un escenario futuro generado por otro componente.

Antes de agregar un nodo HTTP Request para el BCB hay que confirmar el endpoint oficial disponible, su formato, periodicidad y condiciones de uso. No se incluye una URL inventada ni se trata una regla aritmética como si fuera una predicción de IA. Una integración posterior puede agregar la consulta al BCB, guardar las cotizaciones históricas y, con suficientes datos de costos/precios, construir y evaluar una predicción real.

El webhook de este prototipo no tiene autenticación. Está enlazado solo a localhost en Compose; antes de exponerlo en una red o servidor, protégelo con autenticación y HTTPS.
