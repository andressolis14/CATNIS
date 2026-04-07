<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Perruno Digital - <?= APP_NAME ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8b4513; /* Chocolate/Brown */
            --secondary: #fdf5e6; /* Old Lace / Cream */
            --accent: #f59e0b; /* Amber */
            --accent-pink: #ff69b4;
            --text-dark: #2d1b0d;
            --text-muted: #7d6b5d;
            --radius: 24px;
        }
        body {
            background-color: var(--secondary);
            color: var(--text-dark);
            font-family: 'Fredoka', sans-serif;
            padding-bottom: 100px;
        }
        
        /* ---- HERO SECTION ---- */
        .hero {
            height: 400px;
            background: url('<?= APP_URL ?>/img/hero.png') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            margin-bottom: -50px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            box-shadow: 0 10px 30px rgba(139,69,19,0.2);
        }
        .hero::before {
            content: '';
            position: absolute; top:0; left:0; width:100%; height:100%;
            background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.5));
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .hero-content { position: relative; z-index: 10; padding: 0 20px; }
        .hero h1 { font-size: 3.5rem; font-weight: 700; text-shadow: 0 4px 10px rgba(0,0,0,0.3); margin-bottom: 5px; }
        .hero p { font-size: 1.2rem; font-weight: 500; text-shadow: 0 2px 5px rgba(0,0,0,0.3); opacity: 0.9; }

        /* ---- SEARCH ---- */
        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
            position: relative;
            z-index: 20;
            padding: 0 20px;
        }
        .search-inner {
            background: #fff;
            border-radius: 30px;
            padding: 8px 10px 8px 25px;
            display: flex;
            align-items: center;
            box-shadow: 0 15px 35px rgba(139,69,19,0.15);
        }
        .search-inner input {
            border: none; outline: none; flex: 1; font-size: 18px; color: var(--text-dark);
        }
        .search-btn {
            background: var(--accent); color: #fff;
            border: none; width: 45px; height: 45px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* ---- CARDS ---- */
        .product-card {
            background: #fff;
            border-radius: var(--radius);
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 25px rgba(139,69,19,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(139,69,19,0.15);
        }
        .product-img {
            height: 200px;
            background-color: #fce7d2;
            position: relative;
            overflow: hidden;
        }
        .product-img img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.5s;
        }
        .product-card:hover .product-img img { transform: scale(1.1); }
        
        .p-content { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .p-category { 
            font-size: 12px; font-weight: 600; color: var(--accent); 
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;
            display: flex; align-items: center; gap: 5px;
        }
        .p-title { font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .p-desc { font-size: 14px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px; flex: 1; }
        
        .p-footer { display: flex; align-items: center; justify-content: space-between; }
        .p-price { font-size: 24px; font-weight: 700; color: var(--primary); }
        
        .btn-wa-add {
            background: #25d366; color: #fff; border: none;
            padding: 8px 15px; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            text-decoration: none; display: flex; align-items: center; gap: 8px;
            transition: background 0.2s;
        }
        .btn-wa-add:hover { background: #128c7e; color: #fff; }

        .paw-icon { position: absolute; top: 15px; right: 15px; font-size: 24px; color: rgba(255,255,255,0.3); z-index: 5; }

        /* ---- CATEGORIES ---- */
        .cat-pills { display: flex; gap: 10px; justify-content: center; margin-bottom: 30px; flex-wrap: wrap; }
        .cat-pill {
            background: #fff; padding: 10px 20px; border-radius: 20px;
            font-weight: 600; color: var(--text-muted); cursor: pointer;
            transition: all 0.2s; border: 1px solid transparent;
        }
        .cat-pill:hover, .cat-pill.active { background: var(--primary); color: #fff; box-shadow: 0 5px 15px rgba(139,69,19,0.2); }

        /* ---- FLOATING CART ---- */
        .cart-float {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            background: var(--primary); color: #fff;
            padding: 15px 30px; border-radius: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: flex; align-items: center; gap: 20px;
            z-index: 1000; display: none;
            animation: slideUp 0.4s ease;
        }
        @keyframes slideUp { from { bottom: -100px; } to { bottom: 20px; } }
        
        .badge-none { background: rgba(0,0,0,0.05); color: var(--text-muted); font-size: 11px; padding: 3px 8px; border-radius: 10px; }

    </style>
</head>
<body>

    <section class="hero">
        <div class="hero-content">
            <h1>CATNIS BAKERY</h1>
            <p>¡El paraíso perruno más dulce del mundo! 🐶🧁</p>
        </div>
    </section>

    <div class="search-container">
        <div class="search-inner">
            <input type="text" id="busqueda" placeholder="¿Qué le regalamos al cumpleañero?..." onkeyup="filtrar()">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="container">
        <div class="cat-pills">
            <div class="cat-pill active" onclick="filtrarCategoria('todos', this)">Todos</div>
            <div class="cat-pill" onclick="filtrarCategoria('pastel', this)">Pasteles</div>
            <div class="cat-pill" onclick="filtrarCategoria('galleta', this)">Galletas</div>
            <div class="cat-pill" onclick="filtrarCategoria('donas', this)">Donas</div>
        </div>

        <div class="row g-4" id="grid">
                <?php foreach ($productos as $p): ?>
                <?php if ($p['activo']): 
                    // 1. Priorizar imagen subida personalizada
                    if (!empty($p['imagen'])) {
                        $img = APP_URL . '/img/productos/' . $p['imagen'];
                    } else {
                        // 2. Fallback a imágenes temáticas
                        $img = APP_URL . '/img/hero.png';
                        if (stripos($p['nombre'], 'pastel') !== false) $img = APP_URL . '/img/cakes.png';
                        if (stripos($p['nombre'], 'galleta') !== false) $img = APP_URL . '/img/cookies.png';
                        if (stripos($p['nombre'], 'pan') !== false) $img = APP_URL . '/img/bread.png';
                        if (stripos($p['nombre'], 'croissant') !== false) $img = APP_URL . '/img/bread.png';
                    }
                ?>
                <div class="col-md-4 product-item" data-nombre="<?= strtolower($p['nombre']) ?>">
                    <div class="product-card">
                        <i class="fas fa-paw paw-icon"></i>
                        <div class="product-img">
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                        </div>
                        <div class="p-content">
                            <div class="p-category">
                                <i class="fas fa-bone"></i> Repostería Canina
                            </div>
                            <div class="p-title"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="p-desc"><?= htmlspecialchars($p['descripcion']) ?></div>
                            <div class="p-footer">
                                <div class="p-price">$<?= number_format($p['precio_venta'], 2) ?></div>
                                <?php if ($p['stock'] > 0): ?>
                                    <button class="btn-wa-add" onclick="pedir('<?= htmlspecialchars($p['nombre']) ?>', '<?= $img ?>')">
                                        <i class="fab fa-whatsapp"></i> Pedir
                                    </button>
                                <?php else: ?>
                                    <span class="badge-none">Agotado</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function filtrarCategoria(categoria, el) {
            // Actualizar UI de botones
            document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
            el.classList.add('active');

            const items = document.querySelectorAll('.product-item');
            items.forEach(item => {
                const nombre = item.getAttribute('data-nombre');
                if (categoria === 'todos' || nombre.includes(categoria)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filtrar() {
            const input = document.getElementById('busqueda').value.toLowerCase();
            const items = document.querySelectorAll('.product-item');
            
            items.forEach(item => {
                const nombre = item.getAttribute('data-nombre');
                item.style.display = nombre.includes(input) ? 'block' : 'none';
            });
        }

        function pedir(nombre, imgUrl) {
            let msg = `🐶¡Hola Catnis Bakery! Vi su catálogo y me encantó el *${nombre}* para mi perrito.\n\n`;
            msg += `Foto del producto: ${imgUrl}\n\n`;
            msg += `¿Cómo puedo comprarlo?`;
            
            const waUrl = `https://wa.me/<?= WHATSAPP_NUMBER ?>?text=${encodeURIComponent(msg)}`;
            window.open(waUrl, '_blank');
        }
    </script>

</body>
</html>
