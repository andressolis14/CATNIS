# 🥐 CATNIS BAKERY - Documentación de Funcionalidades

Este documento detalla todas las capacidades del sistema de gestión comercial para **CATNIS BAKERY**, desarrollado bajo una arquitectura MVC sustentada en PHP y MySQL.

---

## 🔐 1. Módulo de Autenticación y Seguridad
*   **Registro de Usuarios**: Creación de nuevas cuentas con contraseñas encriptadas mediante el algoritmo *Bcrypt*.
*   **Inicio de Sesión (Login)**: Acceso seguro validando credenciales y gestionando sesiones PHP persistentes.
*   **Protección de Rutas**: Middleware que impide el acceso a módulos administrativos sin una sesión activa.
*   **Cierre de Sesión (Logout)**: Destrucción segura de la sesión del usuario.
*   **Diseño Responsivo**: Interfaz optimizada para móviles y tablets.

## 📦 2. Módulo de Inventario (Productos)
*   **Control de Stock**: Registro preciso de existencias disponibles para cada producto.
*   **Gestión CRUD**: Capacidad total para Crear, Leer, Actualizar y Eliminar productos.
*   **Alertas de Inventario**: Indicadores visuales automáticos:
    *   🔴 **Sin Stock**: Cuando el inventario llega a cero.
    *   🟠 **Bajo Stock**: Avisos cuando el producto iguala o baja del "Stock Mínimo" definido.
    *   🟢 **Stock OK**: Estado óptimo del inventario.
*   **Costo y Venta**: Gestión diferenciada de precios de compra (costo) y precios de venta para calcular márgenes de utilidad.

## 💰 3. Módulo de Ventas (Punto de Venta - POS)
*   **Carrito de Ventas Interactivo**: Interfaz dinámica en JavaScript que permite agregar productos, ajustar cantidades y calcular totales de forma instantánea.
*   **Tipos de Venta**:
    *   💵 **Contado**: Venta inmediata con ingreso de efectivo al negocio.
    *   📋 **Crédito (Fiado)**: Registro de ventas sin pago inmediato, vinculadas a un cliente específico.
*   **Validación de Stock**: El sistema impide vender más de lo que existe físicamente en el inventario.
*   **Historial de Ventas**: Registro detallado de cada transacción con fecha, hora, vendedor y desglose de productos.

## 👥 4. Módulo de Clientes
*   **Base de Datos de Clientes**: Registro completo con nombre, teléfono, correo y dirección.
*   **Historial de Compras**: Vista individual por cliente que muestra todas las compras realizadas en el tiempo.
*   **Integración con Ventas**: Facilidad para seleccionar clientes recurrentes al momento de realizar una venta.

## 📒 5. Módulo de Deudas (Cuentas por Cobrar)
*   **Control de Fiados**: Seguimiento riguroso de cada venta realizada al crédito.
*   **Gestión de Abonos**: Posibilidad de registrar pagos parciales de clientes hasta liquidar la deuda.
*   **Estados de Pago**: Clasificación automática en:
    *   🔴 **Pendiente**
    *   🔵 **Parcial**
    *   🟢 **Pagada**
*   **Saldos en Tiempo Real**: Cálculo automático de cuánto resta pagar por cada deuda.

## 💸 6. Módulo de Gastos
*   **Registro de Salidas**: Control total de gastos fijos y variables (Servicios, Nómina, Alquiler, Transporte, etc.).
*   **Categorización**: Clasificación de gastos para un análisis financiero estructurado.
*   **Filtros Avanzados**: Búsqueda de gastos por rango de fechas y categoría específica.

## 📊 7. Módulo de Dashboard (Panel Principal)
*   **Resumen Diario/Mensual**: Visualización instantánea de las métricas clave (Ventas del día, Ventas del mes, Gastos del mes).
*   **Gráfica de Ventas**: Visualización de la tendencia de ingresos recientes.
*   **Alertas de Stock**: Listado directo de productos que necesitan reposición inmediata.

## 📈 8. Módulo de Reportes
*   **Análisis Mensual**: Balance de ingresos vs. egresos de cualquier mes del año.
*   **Gráficas Visuales (Chart.js)**:
    1.  **Línea**: Tendencia de ventas diarias.
    2.  **Dona**: Distribución de gastos por categoría.
    3.  **Barras**: Comparativo directo entre Ingresos, Gastos y Ganancia Neta.

---

## 🛠️ Especificaciones Técnicas
*   **Backend**: PHP 8.0+ (MVC Nativo).
*   **Frontend**: Bootstrap 5 + Vanilla JavaScript.
*   **Gráficas**: Chart.js v4.
*   **Base de Datos**: MySQL con controladores PDO (Seguridad contra SQL Injection).
*   **Estilo**: Premium Dark Mode con CSS3 personalizado.
