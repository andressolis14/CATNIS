# 🥐 CATNIS BAKERY - Sistema de Gestión

Sistema de gestión de negocios tipo "Treinta", desarrollado con PHP, MySQL y Bootstrap 5.

---

## 🚀 Instalación en XAMPP

### 1. Copiar el proyecto
Asegúrate de que la carpeta del proyecto esté en:
```
C:\xampp\htdocs\CATNIS BAKERY\
```

### 2. Habilitar mod_rewrite
En tu `C:\xampp\apache\conf\httpd.conf` asegúrate que:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
AllowOverride All
```

### 3. Crear la base de datos
1. Abre **phpMyAdmin**: http://localhost/phpmyadmin
2. Haz clic en **"Nueva"** base de datos
3. Ve a la pestaña **SQL** y pega el contenido de `database.sql`
4. Haz clic en **"Continuar"**

### 4. Configurar conexión (si es necesario)
Edita `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // Tu usuario MySQL
define('DB_PASS', '');       // Tu contraseña MySQL
define('DB_NAME', 'catnis_bakery');
```

### 5. Acceder al sistema
Abre tu navegador y ve a:
```
http://localhost/CATNIS BAKERY/public/
```

---

## 🔑 Credenciales de Demo

| Campo | Valor |
|-------|-------|
| Correo | admin@catnisbakery.com |
| Contraseña | password |

---

## 📁 Estructura del Proyecto

```
CATNIS BAKERY/
├── config/
│   ├── config.php      ← Configuración de la app
│   └── db.php          ← Conexión PDO
├── controllers/        ← Lógica de negocio
├── models/             ← Acceso a la base de datos
├── views/              ← Plantillas HTML
│   ├── auth/           ← Login y Registro
│   ├── layout/         ← Header y Footer compartidos
│   ├── dashboard/      ← Página principal
│   ├── productos/      ← Inventario
│   ├── clientes/       ← Gestión de clientes
│   ├── ventas/         ← POS y historial
│   ├── deudas/         ← Cuentas por cobrar
│   ├── gastos/         ← Gastos del negocio
│   └── reportes/       ← Análisis y gráficas
├── public/
│   ├── index.php       ← Punto de entrada (Router)
│   ├── css/app.css     ← Estilos del sistema
│   └── .htaccess       ← Rewrite rules
└── database.sql        ← Script de base de datos
```

---

## 🧩 Módulos del Sistema

| Módulo | Función |
|--------|---------|
| 🔐 **Auth** | Login, registro y manejo de sesiones |
| 📦 **Inventario** | CRUD de productos con control de stock |
| 💰 **Ventas** | POS interactivo, contado o crédito |
| 👥 **Clientes** | CRUD con historial de compras |
| 📒 **Deudas** | Cuentas por cobrar con registro de abonos |
| 💸 **Gastos** | Registro de salidas con filtros por categoría |
| 📊 **Dashboard** | KPIs financieros del día y mes actual |
| 📈 **Reportes** | Gráficas históricas con Chart.js |

---

## ⚠️ Notas Importantes

- El sistema usa la contraseña de demo `password` con hash bcrypt estándar.
- **Para producción**: cambia la contraseña desde el panel.
- El timezone por defecto es `America/El_Salvador`. Edítalo en `config/config.php`.
