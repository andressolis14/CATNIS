<!DOCTYPE html>
<html lang="es">

<head>
    <!-- sftp-sync -->
    <link rel="icon" type="image/png" href="<?= defined('APP_URL') ? APP_URL : '' ?>/img/favicon.png?v=<?= time() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera Digital - CATNIS BAKERY</title>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Inter:wght@400;700;900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8b4513;
            --accent: #ffb703;
            /* Amarillo más brillante */
            --accent-gold: #fbbf24;
            --bg: #0c0a09;
            --card-bg: rgba(28, 25, 23, 0.7);
            --text: #ffffff;
            --text-dim: #d6d3d1;
            --neon-glow: 0 0 20px rgba(245, 158, 11, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at center, #1c1917 0%, #0c0a09 100%);
            color: var(--text);
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            display: flex;
        }

        /* ---- BACKGROUND EFFECTS ---- */
        .bg-glow {
            position: fixed;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background:
                radial-gradient(circle at 20% 30%, rgba(139, 69, 19, 0.2) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(245, 158, 11, 0.15) 0%, transparent 40%);
            z-index: -1;
            animation: drift 15s infinite alternate ease-in-out;
        }

        @keyframes drift {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(5%, 5%) rotate(5deg);
            }
        }

        /* ---- LEFT SIDE: HERO SLIDER ---- */
        .slider-section {
            flex: 1.6;
            height: 100%;
            position: relative;
            background: #000;
            overflow: hidden;
            box-shadow: 20px 0 50px rgba(0, 0, 0, 0.5);
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), transform 12s linear;
            display: flex;
            align-items: flex-end;
            padding: 80px;
        }

        .slide.active {
            opacity: 1;
            transform: scale(1.15);
            z-index: 10;
        }

        .slide-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.6);
        }

        .slide-content {
            position: relative;
            z-index: 20;
            max-width: 900px;
            animation: fadeInUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 0 30px rgba(0, 0, 0, 0.8));
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-category {
            font-family: 'Fredoka', sans-serif;
            color: var(--accent);
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 15px;
            text-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
        }

        .slide-title {
            font-size: 6rem;
            font-weight: 900;
            line-height: 0.9;
            margin-bottom: 25px;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fff, #d1d5db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .slide-price {
            font-size: 5rem;
            color: #fff;
            font-weight: 900;
            background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
            display: inline-block;
            padding: 15px 45px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), var(--neon-glow);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        /* ---- RIGHT SIDE: MENU LIST ---- */
        .menu-section {
            flex: 1;
            height: 100%;
            background: linear-gradient(180deg, rgba(28, 25, 23, 0.9) 0%, rgba(12, 10, 9, 0.95) 100%);
            border-left: 2px solid var(--accent);
            backdrop-filter: blur(30px);
            display: flex;
            flex-direction: column;
            padding: 50px;
            z-index: 30;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 50px;
        }

        .logo-area h1 {
            font-family: 'Fredoka', sans-serif;
            font-size: 3rem;
            color: var(--accent);
            letter-spacing: -1px;
            text-shadow: var(--neon-glow);
        }

        .logo-area p {
            color: var(--accent);
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 5px;
            opacity: 0.8;
        }

        .menu-list-container {
            flex: 1;
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(to bottom, transparent, #000 10%, #000 90%, transparent);
        }

        .menu-list {
            list-style: none;
            position: absolute;
            width: 100%;
            animation: scrollMenu 50s linear infinite;
        }

        @keyframes scrollMenu {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50%);
            }
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 0;
            border-bottom: 1px solid rgba(245, 158, 11, 0.1);
            transition: all 0.3s ease;
        }

        .menu-item-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #fff;
        }

        .menu-item-info p {
            color: var(--text-dim);
            font-size: 1.1rem;
            font-style: italic;
        }

        .menu-item-price {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--accent);
            text-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
        }

        /* ---- FOOTER MARQUEE ---- */
        .footer-marquee {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, #d97706 50%, var(--primary) 100%);
            padding: 18px 0;
            white-space: nowrap;
            overflow: hidden;
            z-index: 100;
            box-shadow: 0 -20px 60px rgba(0, 0, 0, 0.8);
            border-top: 2px solid var(--accent);
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 70s linear infinite;
        }

        .marquee-content span {
            font-size: 1.2rem;
            font-weight: 700;
            padding: 0 40px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .badge-new {
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="bg-glow"></div>

    <!-- SLIDER IZQUIERDO -->
    <div class="slider-section" id="heroSlider">
        <?php foreach ($productosConImagen as $index => $p):
            $img = !empty($p['imagen']) ? APP_URL . '/img/productos/' . $p['imagen'] : APP_URL . '/img/hero.png';
            if (empty($p['imagen'])) {
                if (stripos($p['nombre'], 'pastel') !== false)
                    $img = APP_URL . '/img/cakes.png';
                if (stripos($p['nombre'], 'galleta') !== false)
                    $img = APP_URL . '/img/cookies.png';
                if (stripos($p['nombre'], 'pan') !== false)
                    $img = APP_URL . '/img/bread.png';
            }
            ?>
            <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= $img ?>" class="slide-img">
                <div class="slide-content">
                    <div class="slide-category"><i class="fas fa-bone me-2"></i>Especialidad de la casa</div>
                    <div class="slide-title"><?= htmlspecialchars($p['nombre']) ?></div>
                    <div class="slide-price">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MENU DERECHO -->
    <div class="menu-section">
        <div class="logo-area">
            <div style="font-size: 3rem; color: var(--accent); margin-bottom: 10px;"><i class="fas fa-paw"></i></div>
            <h1>CATNIS BAKERY</h1>
            <p>Dejando huella en el alma</p>
        </div>

        <div class="menu-list-container">
            <ul class="menu-list" id="scrollingList">
                <?php
                // Duplicamos la lista para un scroll infinito suave
                $menuItems = array_merge($productos, $productos);
                foreach ($menuItems as $p): ?>
                    <li class="menu-item">
                        <div class="menu-item-info">
                            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                            <p><?= htmlspecialchars(substr($p['descripcion'], 0, 40)) ?>...</p>
                        </div>
                        <div class="menu-item-price">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- MARQUEE INFERIOR -->
    <div class="footer-marquee">
        <div class="marquee-content">
            <?php for ($i = 0; $i < 10; $i++): ?>
                <span>🐾 Dejando huella en el alma ✨ CATNIS BAKERY 🐾 Calidad artesanal para tu mejor amigo ❤️</span>
            <?php endfor; ?>
        </div>
    </div>

    <script>
        // Lógica del Slider
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, 7000); // Cambia cada 7 segundos

        // Recargar la página cada hora para actualizar datos
        setTimeout(() => {
            location.reload();
        }, 3600000);
    </script>
</body>

</html>