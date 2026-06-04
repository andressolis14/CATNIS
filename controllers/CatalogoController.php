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

    public function cartelera(): void {
        // Cargar productos activos para la cartelera
        $productos = $this->model->todos();
        
        // Filtrar solo los que tienen imagen para el carrusel principal
        $productosConImagen = array_filter($productos, function($p) {
            return !empty($p['imagen']);
        });

        if (empty($productosConImagen)) {
            $productosConImagen = array_slice($productos, 0, 5); // Fallback
        }

        require_once APP_ROOT . '/views/cartelera.php';
    }
}
