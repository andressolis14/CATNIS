<?php
// ============================================================
// CONTROLADOR: Productos
// ============================================================
require_once APP_ROOT . '/models/Producto.php';

class ProductoController {
    private Producto $model;

    public function __construct() {
        $this->model = new Producto();
    }

    public function index(): void {
        $productos = $this->model->todos();
        require_once APP_ROOT . '/views/productos/index.php';
    }

    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagen = $this->cargarImagen();
            
            $datos = [
                ':nombre'         => trim($_POST['nombre'] ?? ''),
                ':descripcion'    => trim($_POST['descripcion'] ?? ''),
                ':precio_compra'  => (float)($_POST['precio_compra'] ?? 0),
                ':precio_venta'   => (float)($_POST['precio_venta'] ?? 0),
                ':stock'          => (int)($_POST['stock'] ?? 0),
                ':stock_minimo'   => (int)($_POST['stock_minimo'] ?? 5),
                ':imagen'         => $imagen,
            ];

            if (empty($datos[':nombre'])) {
                $_SESSION['error'] = 'El nombre es obligatorio.';
            } elseif ($this->model->crear($datos)) {
                $_SESSION['exito'] = 'Producto creado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al crear el producto.';
            }
            header('Location: ' . APP_URL . '/productos');
            exit;
        }
        require_once APP_ROOT . '/views/productos/form.php';
    }

    public function editar(): void {
        $id = (int)($_GET['id'] ?? 0);
        $producto = $this->model->buscarPorId($id);
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado.';
            header('Location: ' . APP_URL . '/productos');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                ':nombre'        => trim($_POST['nombre'] ?? ''),
                ':descripcion'   => trim($_POST['descripcion'] ?? ''),
                ':precio_compra' => (float)($_POST['precio_compra'] ?? 0),
                ':precio_venta'  => (float)($_POST['precio_venta'] ?? 0),
                ':stock'         => (int)($_POST['stock'] ?? 0),
                ':stock_minimo'  => (int)($_POST['stock_minimo'] ?? 5),
            ];

            $nuevaImagen = $this->cargarImagen();
            if ($nuevaImagen) {
                // Si subió nueva imagen
                $datos[':imagen'] = $nuevaImagen;
                // Opcional: Borrar la imagen vieja física si existe
                $vieja = APP_ROOT . '/public/img/productos/' . $producto['imagen'];
                if ($producto['imagen'] && file_exists($vieja)) @unlink($vieja);
            }

            if ($this->model->actualizar($id, $datos)) {
                $_SESSION['exito'] = 'Producto actualizado.';
            } else {
                $_SESSION['error'] = 'Error al actualizar.';
            }
            header('Location: ' . APP_URL . '/productos');
            exit;
        }
        require_once APP_ROOT . '/views/productos/form.php';
    }

    public function eliminar(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->eliminar($id)) {
            $_SESSION['exito'] = 'Producto eliminado.';
        } else {
            $_SESSION['error'] = 'No se pudo eliminar.';
        }
        header('Location: ' . APP_URL . '/productos');
        exit;
    }

    private function cargarImagen(): ?string {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        $fileSize = $_FILES['imagen']['size'];
        $fileType = $_FILES['imagen']['type'];
        $dotPos = strrpos($fileName, '.');
        $fileExtension = strtolower(substr($fileName, $dotPos + 1));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            return null;
        }

        // Nombre único
        $newFileName = time() . '_' . preg_replace('/[^a-z0-9]/i', '_', substr($fileName, 0, $dotPos)) . '.' . $fileExtension;
        
        $uploadFileDir = APP_ROOT . '/public/img/productos/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            return $newFileName;
        }

        return null;
    }
}
