<?php
require_once APP_ROOT . '/config/db.php';

class CatalogoWeb
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ==========================================
    // CATEGORÍAS
    // ==========================================

    public function todasCategorias(): array
    {
        return $this->db->query("SELECT * FROM catalogo_categorias ORDER BY orden ASC, id ASC")->fetchAll();
    }

    public function categoriasActivas(): array
    {
        return $this->db->query("SELECT * FROM catalogo_categorias WHERE activo = 1 ORDER BY orden ASC, id ASC")->fetchAll();
    }

    public function buscarCategoriaPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM catalogo_categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crearCategoria(string $nombre, string $icono, int $orden): bool
    {
        $stmt = $this->db->prepare("INSERT INTO catalogo_categorias (nombre, icono, orden) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $icono, $orden]);
    }

    public function actualizarCategoria(int $id, string $nombre, string $icono, int $orden, int $activo): bool
    {
        $stmt = $this->db->prepare("UPDATE catalogo_categorias SET nombre=?, icono=?, orden=?, activo=? WHERE id=?");
        return $stmt->execute([$nombre, $icono, $orden, $activo, $id]);
    }

    public function eliminarCategoria(int $id): bool
    {
        // Desasociar productos antes de eliminar
        $this->db->prepare("UPDATE catalogo_productos SET categoria_id = NULL WHERE categoria_id = ?")->execute([$id]);
        $stmt = $this->db->prepare("DELETE FROM catalogo_categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ==========================================
    // PRODUCTOS
    // ==========================================

    public function todosProductos(): array
    {
        return $this->db->query("
            SELECT p.*, c.nombre as categoria_nombre, c.icono as categoria_icono
            FROM catalogo_productos p
            LEFT JOIN catalogo_categorias c ON c.id = p.categoria_id
            ORDER BY p.orden ASC, p.id DESC
        ")->fetchAll();
    }

    public function productosActivos(): array
    {
        return $this->db->query("
            SELECT p.*, c.nombre as categoria_nombre, c.icono as categoria_icono, c.id as cat_id
            FROM catalogo_productos p
            LEFT JOIN catalogo_categorias c ON c.id = p.categoria_id
            WHERE p.activo = 1
            ORDER BY p.orden ASC, p.id ASC
        ")->fetchAll();
    }

    public function buscarProductoPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre
            FROM catalogo_productos p
            LEFT JOIN catalogo_categorias c ON c.id = p.categoria_id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crearProducto(array $datos): int|false
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO catalogo_productos (categoria_id, nombre, descripcion, precio, imagen, activo, orden)
                VALUES (:categoria_id, :nombre, :descripcion, :precio, :imagen, :activo, :orden)
            ");
            $stmt->execute($datos);
            return (int) $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("CatalogoWeb::crearProducto: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarProducto(int $id, array $datos): bool
    {
        try {
            $datos[':id'] = $id;
            $stmt = $this->db->prepare("
                UPDATE catalogo_productos SET
                    categoria_id = :categoria_id,
                    nombre       = :nombre,
                    descripcion  = :descripcion,
                    precio       = :precio,
                    activo       = :activo,
                    orden        = :orden
                WHERE id = :id
            ");
            return $stmt->execute($datos);
        } catch (Exception $e) {
            error_log("CatalogoWeb::actualizarProducto: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarImagen(int $id, string $imagen): bool
    {
        $stmt = $this->db->prepare("UPDATE catalogo_productos SET imagen = ? WHERE id = ?");
        return $stmt->execute([$imagen, $id]);
    }

    public function eliminarProducto(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM catalogo_productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
