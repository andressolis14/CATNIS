-- ============================================================
-- SISTEMA DE GESTIÓN CATNIS BAKERY - TIPO TREINTA
-- Script SQL para phpMyAdmin
-- Fecha: 2026-03-30
-- ============================================================

CREATE DATABASE IF NOT EXISTS catnis_bakery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE catnis_bakery;

-- -----------------------------------------------------------
-- TABLA: usuarios
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: productos
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    imagen VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: clientes
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(150),
    direccion TEXT,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: ventas
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cliente_id INT,
    tipo ENUM('contado', 'credito') DEFAULT 'contado',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('completada', 'pendiente', 'cancelada') DEFAULT 'completada',
    notas TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: detalle_ventas
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS detalle_ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: deudas (Cuentas por cobrar)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS deudas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    cliente_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    abonado DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    saldo DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'parcial', 'pagada') DEFAULT 'pendiente',
    fecha DATE NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: abonos
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS abonos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deuda_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nota TEXT,
    FOREIGN KEY (deuda_id) REFERENCES deudas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: gastos
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS gastos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    numero_factura VARCHAR(50) DEFAULT NULL,
    monto DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    categoria ENUM('servicios','compras','transporte','nomina','alquiler','otros') DEFAULT 'otros',
    fecha DATE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- TABLA: detalle_gastos
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS detalle_gastos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gasto_id INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (gasto_id) REFERENCES gastos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- DATOS INICIALES: Usuario administrador
-- Contraseña por defecto: admin123
-- -----------------------------------------------------------
INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES
('Administrador', 'admin@catnisbakery.com', '$2y$10$FycCnvNAbb9qw1.y9vyoO.ZakmRyLz0hHDDS0fWJLLHU.a625pRB2', 'admin');

-- Contraseña: admin123
-- Para producción, cambiar contraseña desde la interfaz

-- Productos de ejemplo (panadería)
INSERT INTO productos (nombre, descripcion, precio_compra, precio_venta, stock, stock_minimo) VALUES
('Pan Blanco', 'Pan blanco artesanal por unidad', 0.50, 1.00, 100, 20),
('Pan Integral', 'Pan integral artesanal por unidad', 0.70, 1.50, 50, 10),
('Pastel de Chocolate', 'Pastel pequeño de chocolate', 8.00, 15.00, 10, 3),
('Croissant', 'Croissant de mantequilla', 1.20, 2.50, 30, 5),
('Galletas', 'Paquete de galletas artesanales', 2.00, 4.00, 40, 8);

-- Clientes de ejemplo
INSERT INTO clientes (nombre, telefono, correo) VALUES
('Cliente General', '', ''),
('María García', '555-1234', 'maria@email.com'),
('Carlos López', '555-5678', 'carlos@email.com');
