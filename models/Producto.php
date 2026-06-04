<?php
// ============================================================
// MODELO: Producto
// ============================================================
require_once APP_ROOT . '/config/db.php';

class Producto {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function todos(): array {
        return $this->db->query("SELECT * FROM productos WHERE activo = 1 ORDER BY tipo ASC, nombre ASC")->fetchAll();
    }

    public function buscarPorId(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): bool {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre, tipo, descripcion, precio_compra, precio_venta, stock, stock_minimo, imagen) VALUES (:nombre,:tipo,:descripcion,:precio_compra,:precio_venta,:stock,:stock_minimo,:imagen)");
        return $stmt->execute($datos);
    }

    public function actualizar(int $id, array $datos): bool {
        $sql = "UPDATE productos SET nombre=:nombre, tipo=:tipo, descripcion=:descripcion, precio_compra=:precio_compra, precio_venta=:precio_venta, stock=:stock, stock_minimo=:stock_minimo";
        if (array_key_exists(':imagen', $datos)) {
            $sql .= ", imagen=:imagen";
        }
        $sql .= " WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $datos[':id'] = $id;
        return $stmt->execute($datos);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("UPDATE productos SET activo = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function reducirStock(int $id, int $cantidad): bool {
        // Los servicios no tienen stock — verificar tipo antes de reducir
        $prod = $this->buscarPorId($id);
        if (!$prod || ($prod['tipo'] ?? 'producto') === 'servicio') return true;
        $stmt = $this->db->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad");
        return $stmt->execute([':cantidad' => $cantidad, ':id' => $id]);
    }

    public function bajoStock(): array {
        return $this->db->query("SELECT * FROM productos WHERE activo = 1 AND tipo = 'producto' AND stock <= stock_minimo ORDER BY stock ASC")->fetchAll();
    }

    public function masVendidos(int $limite = 5): array {
        $stmt = $this->db->prepare("
            SELECT p.nombre, SUM(dv.cantidad) as total_vendido
            FROM detalle_ventas dv
            JOIN productos p ON p.id = dv.producto_id
            GROUP BY dv.producto_id
            ORDER BY total_vendido DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
