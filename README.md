# CASATEK

Sistema web de control de ventas con predicción de precios y asistente de IA.

Proyecto integrador de Proyecto de Sistemas I, Universidad Privada del Valle (Bolivia). Equipo C SHARK, paralelo A, gestión II-2026.

## ¿Qué es CASATEK?

CASATEK es una plataforma web de comercio electrónico para la venta de **equipos de seguridad y videovigilancia** (cámaras, sistemas de alarma, controles de acceso) de la empresa Casatek Bolivia. Centraliza el catálogo, los precios, el inventario, los clientes y los pedidos, e incorpora un **agente de Inteligencia Artificial** que apoya la predicción de precios según la variación del dólar.

## Problema que resuelve

Casatek vende productos importados cuyo costo depende del tipo de cambio del dólar, que en Bolivia varía de forma abrupta entre la cotización oficial y la paralela. Además, la empresa trabaja con catálogos impresos, mensajería instantánea, atención telefónica y archivos Excel que no están sincronizados entre las áreas comercial y administrativa.

Esto provoca:

- Demoras en la atención y falta de un catálogo digital actualizable.
- Ausencia de un historial de pedidos centralizado y riesgo de que el inventario no coincida con la realidad.
- Clientes sin ninguna referencia de cuánto puede variar el precio de un equipo en las próximas semanas.
- Falta de una herramienta que registre y analice cómo se comportan los precios frente al dólar.

La pregunta que guía el proyecto es: **¿cómo mejorar el control de ventas y apoyar la definición de precios en CASATEK mediante una solución web con asistencia de IA?**

## Objetivos

**General:** desarrollar una plataforma web para CASATEK que centralice productos, precios, clientes y pedidos, e incorpore un agente de IA que apoye la predicción de precios a partir del historial y de la variación del dólar.

**Específicos:**

- Ofrecer un catálogo con búsqueda, filtros y consulta de precios y especificaciones.
- Gestionar carrito, pedidos, inventario y clientes.
- Dar al administrador herramientas para usuarios, proveedores, stock y pedidos.
- Registrar el historial de precios y generar predicciones de precio máximo y mínimo.
- Aplicar estándares de desarrollo alineados a las prácticas de XP.

## Qué hace el sistema

- Registro, inicio de sesión seguro y roles de usuario (cliente, mayorista, administrador).
- Catálogo con búsqueda, filtros por categoría y marca, y consulta de precios, especificaciones técnicas, disponibilidad y stock.
- Carrito de compras y gestión de pedidos, con cambio de estado y registro del historial de estados.
- Tickets de compra únicos por pedido, descargables en PDF cuando el pedido está pagado.
- Panel de administración de productos, usuarios, stock y pedidos.
- Información de la empresa: mapa de ubicación, datos de contacto y modo oscuro.
- Asistente de IA (chat) para consultas sobre fluctuaciones de precios.
- En desarrollo: historial y comparación de precios, reportes exportables y predicción de precio máximo/mínimo (ver [tablero de tareas](#tablero-de-tareas)).

## Qué no hace

- Las predicciones del agente de IA son **orientativas**: apoyan la decisión, pero no garantizan el precio futuro ni reemplazan el criterio del administrador.
- La **integración completa del agente de predicción** (HU-24) y la **comparación de precios y costos** (HU-10, HU-17) aún están pendientes.
- El panel de administración ampliado y las notificaciones push están planificados para iteraciones posteriores.
- No guarda credenciales reales en el repositorio: las claves de TiDB Cloud, Cloudinary y los servicios de IA se configuran únicamente como variables de entorno (`.env`).

## Alcance

| Incluye | Planificado para iteraciones posteriores |
|---|---|
| Catálogo con búsqueda y filtros, carrito de compras y pedidos | Panel de administración ampliado |
| Registro, inicio de sesión y roles de usuario | Notificaciones push |
| Gestión de inventario, clientes, proveedores y pedidos | |
| Historial y comparación de precios | |
| Agente de IA para predicción de precios y recomendaciones | |

## Usuarios y roles

| Usuario | Qué hace |
|---|---|
| Cliente | Consulta, compara y compra productos |
| Mayorista | Accede al catálogo para compras de volumen |
| Administrador | Gestiona productos, precios, usuarios, proveedores, stock y pedidos |

## Tecnologías

| Capa | Tecnología |
|---|---|
| Presentación | HTML5, CSS3, JavaScript, Blade, Bootstrap, Google Fonts |
| Lógica del sistema | PHP con Laravel (patrón MVC, ORM Eloquent), módulo de agente de IA |
| Base de datos | TiDB Cloud (SQL distribuida, compatible con MySQL) |
| Imágenes | Cloudinary |
| Control de versiones | Git + GitHub |
| Gestión de tareas | Jira (tablero KAN) |

## Arquitectura

![Diagrama de arquitectura de CASATEK](docs/arquitectura.png)

El usuario (cliente o administrador) entra a la aplicación web, que se comunica con el backend mediante HTTP/JSON. El backend es una API REST que corre en un contenedor Docker local y se conecta con la base de datos (TiDB Cloud en la nube y TiDB local en Docker para desarrollo), con el agente de IA que predice precios y con una API de tipo de cambio (dólar / Bs).

**Frontend.** Aplicación web con catálogo, carrito y comparadores. Usa vistas Blade, Bootstrap, JavaScript y Google Fonts.

**Backend.** Laravel con patrón MVC: controladores, modelos Eloquent y middleware. Aquí están las reglas de negocio y el módulo del agente de IA.

**Datos.** Tablas principales: `users`, `roles`, `user_role`, `ranks`, `products`, `brands`, `categories`, `carts`, `cart_items`, `billing_info`, `orders`, `order_items`, `order_status_logs`, `tickets` y `accumulated_purchases`. Las imágenes de producto se guardan en Cloudinary.


## Tablero de tareas

Las tareas del proyecto son las **historias de usuario (HU)** del Product Backlog, gestionadas en el tablero de Jira (clave `KAN`) y organizadas en 5 iteraciones XP.

### Resumen por estado

| Estado | Historias | Puntos | % de puntos |
|---|---:|---:|---:|
| Hecho | 11 | 36 | 29.5% |
| En revisión | 1 | 8 | 6.6% |
| Por hacer | 12 | 78 | 63.9% |
| Total | 24 | 122 | 100% |

### Resumen por iteración

| Iteración | Enfoque | Historias | Hechas | Puntos |
|---|---|---:|---:|---:|
| 1 | Acceso y catálogo básico | 2 | 2/2 | 5 |
| 2 | Precios, filtros e historial | 6 | 4/6 | 19 |
| 3 | Compra, pedidos y administración | 7 | 5/7 | 36 |
| 4 | Reportes y recomendaciones | 5 | 0/5 | 31 |
| 5 | Comparación, stock e IA | 3 | 0/3 | 18 |
| 5+ | Integración con agente de IA (posterior a la iteración 5) | 1 | 0/1 | 13 |

### Product Backlog

| ID | Jira | Historia de usuario | Prioridad | Puntos | Iteración | Estado |
|---|---|---|---|---:|:---:|---|
| HU-01 | KAN-22 | Recomendación de productos | Media | 8 | 3 | Por hacer |
| HU-02 | KAN-86 | Búsqueda de equipos por categoría | Media | 2 | 1 | Hecho |
| HU-03 | KAN-21 | Comparador de variación de precios | Media | 8 | 5 | Por hacer |
| HU-04 | KAN-87 | Consulta de precios y especificaciones | Alta | 3 | 2 | Hecho |
| HU-05 | KAN-24 | Recomendación según presupuesto dinámico | Media | 8 | 4 | En revisión |
| HU-06 | KAN-25 | Reportes y estadísticas de variación de precios | Alta | 5 | 4 | Por hacer |
| HU-07 | KAN-26 | Recomendación de productos sencillos y económicos | Media | 5 | 4 | Por hacer |
| HU-08 | KAN-88 | Filtro de catálogo por presupuesto límite | Media | 3 | 2 | Por hacer |
| HU-09 | KAN-28 | Análisis de fluctuaciones de precio | Alta | 8 | 4 | Por hacer |
| HU-10 | KAN-29 | Comparación de precios | Muy alta | 5 | 5 | Por hacer |
| HU-11 | KAN-30 | Historial de precios por producto | Alta | 5 | 2 | Por hacer |
| HU-12 | KAN-89 | Filtrado de productos por marca | Media | 2 | 2 | Hecho |
| HU-13 | KAN-32 | Mapa de ubicación de la empresa | Media | 2 | 3 | Hecho |
| HU-14 | KAN-33 | Implementación de modo oscuro | Media | 3 | 3 | Hecho |
| HU-15 | KAN-34 | Consulta de información de contacto | Baja | 2 | 3 | Hecho |
| HU-16 | KAN-35 | Exportación de reportes de precios en PDF/Excel | Baja | 5 | 4 | Por hacer |
| HU-17 | KAN-38 | Comparación de costos | Alta | 5 | 5 | Por hacer |
| HU-18 | KAN-90 | Panel de administración para gestión de clientes | Alta | 8 | 3 | Por hacer |
| HU-19 | KAN-91 | Inicio de sesión seguro | Media | 3 | 1 | Hecho |
| HU-20 | KAN-92 | Visualización de especificaciones técnicas | Media | 3 | 2 | Hecho |
| HU-21 | KAN-93 | Gestión de carrito de compras | Alta | 5 | 3 | Hecho |
| HU-22 | KAN-83 | Consulta de disponibilidad y stock | Baja | 3 | 2 | Hecho |
| HU-23 | KAN-94 | Gestión de pedidos | Alta | 8 | 3 | Hecho |
| HU-24 | KAN-85 | Integración con agente de IA | Muy alta | 13 | 5+ | Por hacer |

## Equipo

**Equipo C SHARK** · Docente: Lic. Georgina Aruquipa Gutierrez

| Integrante | Rol(es) |
|---|---|
| Hugo Camilo Cussi Suxo | Líder, Backend, Base de datos, QA |
| Marco Arturo Cabrera Escobar | Analista, Backend, Base de datos |
| Alan Steven Torrez Lopez | Frontend, QA · Tablero de tareas y documentación |
| Felipe Uriel Estevez Martinez | Analista, Backend, QA |
| Francia Andrea Marin Moron | Frontend |