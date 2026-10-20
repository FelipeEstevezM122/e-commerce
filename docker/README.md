# Entorno local con Docker

Este Compose levanta Laravel/PHP-FPM, Nginx, MySQL 8 y n8n. Los puertos se publican solo en `localhost`; es una configuración de desarrollo, no de producción.

## Antes de iniciar

1. Instala Docker Desktop para Windows y espera a que indique que el motor está iniciado.
2. Abre PowerShell en la raíz del proyecto y crea el archivo local de configuración:

   ```powershell
   Copy-Item .env.docker.example .env.docker
   ```

3. Edita `.env.docker`. Cambia las contraseñas de MySQL y genera valores distintos para `APP_KEY` y `N8N_ENCRYPTION_KEY`. No compartas ni subas `.env.docker` al repositorio.

   Para generar una clave aleatoria en PowerShell, ejecuta este bloque dos veces y usa un valor distinto para cada variable:

   ```powershell
   $rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
   $bytes = New-Object byte[] 32
   $rng.GetBytes($bytes)
   [Convert]::ToBase64String($bytes)
   ```

   En `.env.docker`, `APP_KEY` debe quedar como `base64:<valor generado>`. `N8N_ENCRYPTION_KEY` puede usar otro valor generado. Conserva la clave de n8n: cambiarla puede impedir descifrar credenciales guardadas.

## Iniciar los servicios

```powershell
docker compose --env-file .env.docker config
docker compose --env-file .env.docker up -d --build
docker compose --env-file .env.docker ps
```

Direcciones locales:

- Laravel: <http://localhost:8080>
- Editor de n8n: <http://localhost:5678>
- MySQL desde el host: puerto `3307` (usuario y contraseña en `.env.docker`)

La primera vez, n8n solicitará crear una cuenta de propietario en el navegador. El volumen `n8n_data` conserva esa cuenta, los workflows y sus credenciales. `mysql_data` conserva la base de datos.

Comandos útiles:

```powershell
docker compose --env-file .env.docker logs -f app nginx mysql n8n
docker compose --env-file .env.docker exec app php artisan --version
docker compose --env-file .env.docker stop
docker compose --env-file .env.docker start
```

`docker compose down` detiene y elimina los contenedores, pero conserva los volúmenes nombrados. Evita `docker compose down -v` salvo que realmente quieras borrar también los datos de MySQL y n8n.

## Importante antes de migrar la base de datos

No ejecutes aún `php artisan migrate` contra esta base recién creada. Al revisar el código actual encontré una incompatibilidad que puede bloquear la migración de procedimientos y triggers: la migración de `accumulated_purchases` define `month` y `total_purchases`, mientras un trigger intenta insertar `total_amount` y no proporciona `month`. También hay lógica de descuento/reposición de stock tanto en controladores como en triggers, que puede duplicar los cambios. Conviene resolver esas discrepancias antes de migrar o procesar pedidos.

## Detener

```powershell
docker compose --env-file .env.docker down
```

Los datos siguen en los volúmenes nombrados después de este comando.
