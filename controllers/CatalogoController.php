<?php
// ============================================================
// CONTROLADOR: Catálogo Público
// ============================================================
require_once APP_ROOT . '/models/Producto.php';

class CatalogoController {
    private Producto $model;

    public function __construct() {
        $this->model = new Producto();
    }

    public function index(): void {
        // Solo productos activos y con stock opcionalmente
        // Por ahora, todos los productos disponibles
        $productos = $this->model->todos();
        
        // Vista pública (no requiere layout de admin)
        require_once APP_ROOT . '/views/catalogo.php';
    }
}
