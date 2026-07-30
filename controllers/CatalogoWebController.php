<?php
// ============================================================
// CONTROLADOR: Catálogo Web (Admin)
// ============================================================
require_once APP_ROOT . '/models/CatalogoWeb.php';

class CatalogoWebController
{
    private CatalogoWeb $model;

    public function __construct()
    {
        $this->requireAdmin();
        $this->model = new CatalogoWeb();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
        if (($_SESSION['rol'] ?? '') !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para acceder al Catálogo Web.';
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
    }

    public function index(): void
    {
        $productos   = $this->model->todosProductos();
        $categorias  = $this->model->todasCategorias();
        $pageTitle   = 'Catálogo Web';
        require_once APP_ROOT . '/views/catalogo_web/index.php';
    }

    public function crear(): void
    {
        $categorias = $this->model->todasCategorias();
        $producto   = null;
        $pageTitle  = 'Nuevo Producto del Catálogo';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagen = $this->cargarImagen();
            $datos  = [
                ':categoria_id' => $_POST['categoria_id'] ?: null,
                ':nombre'       => trim($_POST['nombre'] ?? ''),
                ':descripcion'  => trim($_POST['descripcion'] ?? ''),
                ':precio'       => (float) ($_POST['precio'] ?? 0),
                ':imagen'       => $imagen,
                ':activo'       => isset($_POST['activo']) ? 1 : 0,
                ':orden'        => (int) ($_POST['orden'] ?? 0),
            ];

            if (empty($datos[':nombre'])) {
                $_SESSION['error'] = 'El nombre es obligatorio.';
            } elseif ($this->model->crearProducto($datos)) {
                $_SESSION['exito'] = 'Producto agregado al catálogo.';
                header('Location: ' . APP_URL . '/catalogoweb');
                exit;
            } else {
                $_SESSION['error'] = 'Error al guardar el producto.';
            }
        }

        require_once APP_ROOT . '/views/catalogo_web/form.php';
    }

    public function editar(): void
    {
        $id       = (int) ($_GET['id'] ?? 0);
        $producto = $this->model->buscarProductoPorId($id);

        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado.';
            header('Location: ' . APP_URL . '/catalogoweb');
            exit;
        }

        $categorias = $this->model->todasCategorias();
        $pageTitle  = 'Editar Producto del Catálogo';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                ':categoria_id' => $_POST['categoria_id'] ?: null,
                ':nombre'       => trim($_POST['nombre'] ?? ''),
                ':descripcion'  => trim($_POST['descripcion'] ?? ''),
                ':precio'       => (float) ($_POST['precio'] ?? 0),
                ':activo'       => isset($_POST['activo']) ? 1 : 0,
                ':orden'        => (int) ($_POST['orden'] ?? 0),
            ];

            if (empty($datos[':nombre'])) {
                $_SESSION['error'] = 'El nombre es obligatorio.';
            } else {
                $this->model->actualizarProducto($id, $datos);

                $nuevaImagen = $this->cargarImagen();
                if ($nuevaImagen) {
                    $this->borrarImagenAntigua($producto['imagen']);
                    $this->model->actualizarImagen($id, $nuevaImagen);
                }

                $_SESSION['exito'] = 'Producto actualizado.';
                header('Location: ' . APP_URL . '/catalogoweb');
                exit;
            }
        }

        require_once APP_ROOT . '/views/catalogo_web/form.php';
    }

    public function eliminar(): void
    {
        $id       = (int) ($_GET['id'] ?? 0);
        $producto = $this->model->buscarProductoPorId($id);

        if ($producto) {
            $this->borrarImagenAntigua($producto['imagen']);
            $this->model->eliminarProducto($id);
            $_SESSION['exito'] = 'Producto eliminado del catálogo.';
        } else {
            $_SESSION['error'] = 'Producto no encontrado.';
        }

        header('Location: ' . APP_URL . '/catalogoweb');
        exit;
    }

    public function categorias(): void
    {
        $categorias = $this->model->todasCategorias();
        $pageTitle  = 'Categorías del Catálogo';
        require_once APP_ROOT . '/views/catalogo_web/categorias.php';
    }

    public function guardar_categoria(): void
    {
        $id     = (int) ($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $icono  = trim($_POST['icono'] ?? '🐾');
        $orden  = (int) ($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre de la categoría es obligatorio.';
            header('Location: ' . APP_URL . '/catalogoweb/categorias');
            exit;
        }

        if ($id > 0) {
            $this->model->actualizarCategoria($id, $nombre, $icono, $orden, $activo);
            $_SESSION['exito'] = 'Categoría actualizada.';
        } else {
            $this->model->crearCategoria($nombre, $icono, $orden);
            $_SESSION['exito'] = 'Categoría creada.';
        }

        header('Location: ' . APP_URL . '/catalogoweb/categorias');
        exit;
    }

    public function eliminar_categoria(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->eliminarCategoria($id);
            $_SESSION['exito'] = 'Categoría eliminada. Los productos de esa categoría quedaron sin categoría.';
        }
        header('Location: ' . APP_URL . '/catalogoweb/categorias');
        exit;
    }

    private function cargarImagen(): ?string
    {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileName    = $_FILES['imagen']['name'];
        $dotPos      = strrpos($fileName, '.');
        $extension   = strtolower(substr($fileName, $dotPos + 1));
        $permitidos  = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $permitidos)) {
            return null;
        }

        $nuevoNombre = time() . '_' . preg_replace('/[^a-z0-9]/i', '_', substr($fileName, 0, $dotPos)) . '.' . $extension;
        $destino     = APP_ROOT . '/public/img/catalogo/' . $nuevoNombre;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            return $nuevoNombre;
        }

        return null;
    }

    private function borrarImagenAntigua(?string $imagen): void
    {
        if (!$imagen) return;
        $ruta = APP_ROOT . '/public/img/catalogo/' . $imagen;
        if (file_exists($ruta)) @unlink($ruta);
    }
}
