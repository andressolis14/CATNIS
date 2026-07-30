<?php
// ============================================================
// CONTROLADOR: Catálogo Público
// ============================================================
require_once APP_ROOT . '/models/CatalogoWeb.php';

class CatalogoController
{
    private CatalogoWeb $model;

    public function __construct()
    {
        $this->model = new CatalogoWeb();
    }

    public function index(): void
    {
        $productos  = $this->model->productosActivos();
        $categorias = $this->model->categoriasActivas();
        require_once APP_ROOT . '/views/catalogo.php';
    }

    public function cartelera(): void
    {
        require_once APP_ROOT . '/views/cartelera.php';
    }
}
