<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/png" href="<?= APP_URL ?>/img/favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Catnis Bakery - Repostería artesanal para perros. Pasteles, galletas y snacks hechos con amor e ingredientes naturales.">
    <title>Catnis Bakery 🐾 - Repostería para tu Peludo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @font-face {
            font-family: 'Happy School';
            src: url('<?= APP_URL ?>/fonts/happy_school-webfont.woff2') format('woff2'),
                 url('<?= APP_URL ?>/fonts/happy_school-webfont.woff') format('woff');
            font-weight: normal; font-style: normal; font-display: swap;
        }
        @font-face {
            font-family: 'Skull Bones';
            src: url('<?= APP_URL ?>/fonts/SKULL BONES Bold22.woff2') format('woff2');
            font-weight: normal; font-style: normal; font-display: swap;
        }
        @font-face {
            font-family: 'ITC Avant Garde';
            src: url('<?= APP_URL ?>/fonts/ITCAvantGardeGothicPro-Book.woff2') format('woff2'),
                 url('<?= APP_URL ?>/fonts/ITCAvantGardeGothicPro-Book.woff') format('woff');
            font-weight: 400; font-style: normal; font-display: swap;
        }
        @font-face {
            font-family: 'ITC Avant Garde';
            src: url('<?= APP_URL ?>/fonts/ITCAvantGardeGothicPro-Md.woff2') format('woff2'),
                 url('<?= APP_URL ?>/fonts/ITCAvantGardeGothicPro-Md.woff') format('woff');
            font-weight: 600; font-style: normal; font-display: swap;
        }

        :root {
            --dark:    #48151b;
            --primary: #63121c;
            --cream:   #fffbf4;
            --beige:   #efdbaf;
            --blue:    #c7dfeb;
            --radius:  20px;
            --shadow:  0 8px 32px rgba(72,21,27,.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { background: var(--cream); color: var(--dark); font-family: 'Inter', sans-serif; overflow-x: hidden; max-width: 100vw; }
        h1,h2,h3,h4,h5 { font-family: 'Happy School', sans-serif; }

        /* ======== NAVBAR ======== */
        .navbar-web {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            background: var(--cream);
            box-shadow: 0 2px 16px rgba(72,21,27,.08);
            padding: 14px 40px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo img { height: 70px; width: auto; object-fit: contain; }
        .nav-logo span { font-family: 'Happy School', sans-serif; font-size: 1.3rem; color: var(--dark); }
        .nav-links { display: flex; gap: 80px; list-style: none; margin: 0; padding: 0; }
        .nav-links a { text-decoration: none; font-family: 'ITC Avant Garde', sans-serif; font-weight: 600; font-size: 15px; color: #7a1522; transition: color .2s; }
        .nav-links a:hover { color: var(--primary); }
        .btn-login {
            background: #c6deea; color: #fff;
            padding: 9px 22px; border-radius: 50px;
            text-decoration: none; font-weight: 600; font-size: .88rem;
            transition: background .2s, transform .2s;
            white-space: nowrap;
        }
        .btn-login:hover { background: #b0d0e0; color: #fff; transform: translateY(-1px); }
        .hamburger { display: none; background: none; border: none; font-size: 1.3rem; color: var(--dark); cursor: pointer; }
        .mobile-menu {
            display: none; position: fixed; inset: 0;
            background: var(--dark); z-index: 9999;
            flex-direction: column; align-items: center; justify-content: center; gap: 28px;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a { color: var(--cream); text-decoration: none; font-size: 1.3rem; font-family: 'Skull Bones', sans-serif; text-transform: uppercase; }
        .close-btn { position: absolute; top: 20px; right: 24px; background: none; border: none; color: var(--cream); font-size: 1.5rem; cursor: pointer; }

        /* ======== HERO ======== */
        .hero {
            margin-top: 70px;
            line-height: 0;
            overflow: hidden;
        }
        .hero img {
            width: 100%;
            height: auto;
            display: block;
            margin-bottom: -400px;
        }

        /* ======== TRUST STRIP ======== */
        .trust-strip { background: #7a1522; padding: 18px 0; overflow: hidden; }
        .trust-track {
            display: flex; gap: 48px; align-items: center;
            animation: scroll-x 22s linear infinite; width: max-content;
        }
        @keyframes scroll-x { from { transform: translateX(0) } to { transform: translateX(-50%) } }
        .trust-item {
            display: flex; align-items: center; gap: 10px; white-space: nowrap;
            color: #fff; font-family: 'ITC Avant Garde', sans-serif; font-weight: 600; font-size: .88rem;
        }
        .trust-item i { color: var(--beige); }

        /* ======== SECTIONS ======== */
        section { padding: 88px 0; }
        .section-label {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--primary); font-family: 'ITC Avant Garde', sans-serif; font-weight: 600; font-size: 19px;
            letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;
        }
        .section-title { font-family: 'Skull Bones', sans-serif; font-size: 32px; color: #7a1522; line-height: 1.2; text-transform: uppercase; }
        .section-sub { font-family: 'ITC Avant Garde', sans-serif; font-size: 15px; color: var(--primary); max-width: 480px; line-height: 1.5; margin-top: 10px; }

        /* ======== ABOUT ======== */
        .about-section { background: var(--cream); }
        .about-section .section-title { font-size: 32px; }
        .about-section .section-label { color: #7a1522; }
        .about-section .section-sub { font-size: 17px; color: #7a1522; max-width: 100%; line-height: 1.3; }
        .about-section .value-text h6 { color: #7a1522; }
        .about-section .value-text p { color: #7a1522; }
        .about-placeholder {
            width: 100%; min-height: 380px; border-radius: 16px;
            background: var(--blue);
        }
        .value-card {
            display: flex; gap: 12px; align-items: flex-start;
            padding: 10px 0; margin-bottom: 6px;
        }
        .value-icon {
            width: 36px; height: 36px; border-radius: 8px; background: #7a1522;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: .85rem; color: var(--cream);
        }
        .value-text h6 { font-family: 'ITC Avant Garde', sans-serif; font-weight: 600; font-size: 15px; margin-bottom: 2px; color: var(--dark); -webkit-text-stroke: 0.4px currentColor; }
        .value-text p { font-family: 'Inter', sans-serif; font-size: 15px; color: var(--primary); line-height: 1.4; margin: 0; }

        /* ======== FEATURES ======== */
        .features-section { background: var(--cream); padding: 50px 0; }
        .feature-card { text-align: center; padding: 20px 16px; }
        .feature-icon {
            width: 60px; height: 60px; border-radius: 14px;
            margin: 0 auto 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem;
        }
        .feature-card h5 { font-family: 'Skull Bones', sans-serif; font-size: .95rem; margin-bottom: 8px; color: #c6deea; text-transform: uppercase; letter-spacing: .5px; }
        .feature-card p { font-family: 'Inter', sans-serif; font-size: 15px; color: #7a1522; line-height: 1.5; margin: 0; }

        /* ======== PRODUCTS ======== */
        .products-section { background: var(--cream); }
        .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 38px; }
        .filter-pill {
            background: transparent; border: 2px solid #c6deea;
            padding: 8px 22px; border-radius: 50px;
            font-weight: 600; font-size: .88rem; color: var(--dark);
            cursor: pointer; transition: all .2s; font-family: 'Inter', sans-serif;
        }
        .filter-pill:hover, .filter-pill.active {
            background: #c6deea; color: #fff; border-color: #c6deea;
        }
        .product-card {
            background: transparent; border-radius: 0; overflow: visible;
            box-shadow: none; transition: transform .3s;
            height: 100%; display: flex; flex-direction: column;
        }
        .product-card:hover { transform: translateY(-4px); }
        .p-img-wrap { position: relative; height: 240px; overflow: hidden; background: var(--beige); border-radius: 0; }
        .p-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
        .product-card:hover .p-img-wrap img { transform: scale(1.04); }
        .p-img-placeholder {
            width: 100%; height: 240px; background: var(--beige);
            display: flex; align-items: center; justify-content: center;
            font-size: 3.5rem;
        }
        .p-badge {
            position: absolute; top: 12px; left: 12px;
            background: var(--dark); color: var(--cream); font-size: .72rem; font-weight: 700;
            padding: 4px 12px; border-radius: 50px;
        }
        .p-body { padding: 14px 4px 18px; flex: 1; display: flex; flex-direction: column; text-align: center; }
        .p-category { font-family: 'Inter', sans-serif; font-size: .68rem; font-weight: 600; color: #aaa; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
        .p-name { font-size: 1.05rem; font-weight: 700; color: #7a1522; line-height: 1.35; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .p-stars { color: #f59e0b; font-size: .78rem; letter-spacing: 2px; margin-bottom: 8px; }
        .p-desc { font-family: 'Inter', sans-serif; font-size: .82rem; color: #7a1522; line-height: 1.5; flex: 1; margin-bottom: 14px; }
        .p-footer { display: flex; align-items: center; justify-content: center; gap: 16px; padding-top: 8px; border-top: none; margin-top: auto; }
        .p-price { font-size: 1.25rem; font-weight: 800; color: #7a1522; font-family: 'Inter', sans-serif; }
        .btn-wa-card {
            background: #7a1522; color: #fff; border: none;
            padding: 8px 16px; border-radius: 10px; font-family: 'Inter', sans-serif;
            font-size: .82rem; font-weight: 600;
            display: flex; align-items: center; gap: 6px;
            text-decoration: none; transition: background .2s;
        }
        .btn-wa-card:hover { background: var(--dark); color: #fff; }
        .empty-products {
            grid-column: 1 / -1; text-align: center;
            padding: 60px 20px; color: var(--primary);
        }

        /* ======== TESTIMONIALS ======== */
        .testimonials-section { background: #7a1522; }
        .testimonials-section .section-title { color: #fff; }
        .testimonials-section .section-label { color: var(--beige); }
        .testimonials-section .section-sub { color: rgba(255,255,255,.7); }
        .testi-card {
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
            border-radius: 22px; padding: 28px; backdrop-filter: blur(8px);
            transition: transform .3s;
            height: 100%;
        }
        .testi-card:hover { transform: translateY(-4px); background: rgba(255,255,255,.12); }
        .testi-stars { color: var(--beige); margin-bottom: 14px; }
        .testi-text { font-family: 'Inter', sans-serif; color: rgba(255,255,255,.9); font-size: .92rem; line-height: 1.7; margin-bottom: 20px; font-style: italic; }
        .testi-author { display: flex; align-items: center; gap: 12px; }
        .testi-avatar {
            width: 46px; height: 46px; border-radius: 50%;
            background: var(--beige); color: var(--dark);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Happy School', sans-serif; font-size: 1.2rem; flex-shrink: 0;
        }
        .testi-name { font-weight: 700; color: #fff; font-size: .92rem; }
        .testi-role { font-family: 'Inter', sans-serif; font-size: .8rem; color: rgba(255,255,255,.55); }

        /* ======== HOW TO ORDER ======== */
        .order-section { background: var(--cream); }
        .step-card {
            text-align: center; padding: 34px 22px; background: #eee2c5;
            border-radius: 22px; box-shadow: var(--shadow); position: relative;
        }
        .step-num {
            width: 50px; height: 50px; border-radius: 50%;
            background: #7a1522; color: var(--cream);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Happy School', sans-serif; font-size: 1.4rem;
            margin: 0 auto 16px;
        }
        .step-icon { font-size: 2rem; margin-bottom: 14px; }
        .step-card h5 { font-family: 'Skull Bones', sans-serif; font-size: 1.05rem; margin-bottom: 8px; color: #7a1522; text-transform: uppercase; }
        .step-card p { font-family: 'Inter', sans-serif; font-size: 15px; color: #7a1522; line-height: 1.4; margin: 0; }
        .order-section .section-label { color: #7a1522; }
        .order-section .section-sub { color: #7a1522; }
        .step-arrow { position: absolute; right: 0; top: 50%; transform: translate(50%, -50%); font-size: 1.3rem; color: #7a1522; z-index: 10; }
        .btn-wa-big {
            background: #c6deea; color: #fff; padding: 16px 44px; border-radius: 50px;
            font-weight: 700; font-size: 1.1rem; text-decoration: none;
            display: inline-flex; align-items: center; gap: 12px;
            box-shadow: 0 8px 28px rgba(198,222,234,.4);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-wa-big:hover { color: #fff; background: #b0d0e0; transform: translateY(-2px); }

        /* ======== FOOTER ======== */
        .footer { background: #7a1522; color: rgba(255,255,255,.75); padding: 56px 0 0; font-family: 'ITC Avant Garde', sans-serif; }
        .footer-logo span { font-family: 'ITC Avant Garde', sans-serif; font-size: 1.5rem; color: #fff; }
        .footer h6 { color: #fff; font-family: 'ITC Avant Garde', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 16px; letter-spacing: .5px; }
        .footer ul { list-style: none; padding: 0; }
        .footer ul li { margin-bottom: 9px; }
        .footer ul li a { color: rgba(255,255,255,.55); text-decoration: none; font-family: 'ITC Avant Garde', sans-serif; font-size: .88rem; transition: color .2s; }
        .footer ul li a:hover { color: var(--beige); }
        .footer-contact li { display: flex; gap: 12px; align-items: flex-start; color: rgba(255,255,255,.6); font-family: 'ITC Avant Garde', sans-serif; font-size: .88rem; margin-bottom: 11px; }
        .footer-contact li i { color: var(--beige); margin-top: 2px; flex-shrink: 0; }
        .social-links { display: flex; gap: 10px; margin-top: 16px; }
        .social-link {
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(239,219,175,.22); color: var(--beige);
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all .2s;
        }
        .social-link:hover { background: var(--primary); color: #fff; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07);
            padding: 18px 0; margin-top: 44px;
            text-align: center; font-family: 'ITC Avant Garde', sans-serif; font-size: .8rem; color: rgba(255,255,255,.3);
        }

        /* ======== FLOATING WA ======== */
        .wa-float {
            position: fixed; bottom: 26px; right: 26px; z-index: 998;
            width: 56px; height: 56px; border-radius: 50%;
            background: #25D366; color: #fff; font-size: 1.5rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 22px rgba(37,211,102,.4);
            text-decoration: none; transition: transform .2s;
            animation: pulse-wa 2.5s infinite;
        }
        .wa-float:hover { color: #fff; transform: scale(1.1); }
        @keyframes pulse-wa {
            0%,100% { box-shadow: 0 6px 22px rgba(37,211,102,.4), 0 0 0 0 rgba(37,211,102,.35); }
            50%      { box-shadow: 0 6px 22px rgba(37,211,102,.4), 0 0 0 12px rgba(37,211,102,0); }
        }

        /* ======== RESPONSIVE ======== */

        /* Tablet grande */
        @media (max-width: 991px) {
            .nav-links, .btn-login { display: none; }
            .hamburger { display: block; }
            .step-arrow { display: none; }
            .about-placeholder { min-height: 260px; }
            .navbar-web { padding: 12px 24px; }
        }

        /* Tablet / móvil grande */
        @media (max-width: 767px) {
            .navbar-web { padding: 10px 16px; }
            .nav-logo img { height: 52px; }

            section { padding: 48px 0; }
            .features-section { padding: 32px 0; }

            /* Hero - menos recorte en móvil */
            .hero { margin-top: 58px; }
            .hero img { margin-bottom: -40px; }

            /* Títulos */
            .section-title { font-size: 1.6rem; }
            .about-section .section-title { font-size: 1.35rem; }

            /* About - la imagen primero, texto después */
            .about-placeholder { min-height: 200px; margin-bottom: 24px; }

            /* Features */
            .feature-card { padding: 12px 8px; }

            /* Filtros: scroll horizontal */
            .filter-pills {
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                overflow-y: visible;
                padding-bottom: 10px;
                justify-content: flex-start !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            .filter-pills::-webkit-scrollbar { display: none; }
            .filter-pill { white-space: nowrap; flex-shrink: 0; }

            /* Productos 1 columna */
            .p-img-wrap { height: 210px; }
            .p-img-placeholder { height: 210px; }

            /* Steps */
            .step-card { padding: 22px 14px; margin-bottom: 16px; }

            /* Footer */
            .footer { padding: 36px 0 0; }
            .footer .col-lg-4,
            .footer .col-6,
            .footer .col-lg-2 { text-align: center; margin-bottom: 8px; }
            .footer-contact li { justify-content: center; text-align: left; }
            .social-links { justify-content: center; }
            .footer h6 { margin-top: 20px; }
            .footer-logo { text-align: center; }

            /* WA flotante */
            .wa-float { width: 48px; height: 48px; font-size: 1.3rem; bottom: 18px; right: 18px; }
        }

        /* Móvil pequeño */
        @media (max-width: 480px) {
            .navbar-web { padding: 8px 14px; }
            .nav-logo img { height: 44px; }

            section { padding: 36px 0; }
            .features-section { padding: 28px 0; }

            /* Hero */
            .hero { margin-top: 54px; }
            .hero img { margin-bottom: -20px; }

            /* Títulos */
            .section-title { font-size: 1.3rem; }
            .about-section .section-title { font-size: 1.2rem; }
            .section-sub { font-size: .88rem; }

            /* Trust strip */
            .trust-track { gap: 24px; }
            .trust-item { font-size: .75rem; }

            /* Filtros */
            .filter-pill { padding: 6px 14px; font-size: .8rem; }

            /* Productos */
            .p-img-wrap { height: 180px; }
            .p-img-placeholder { height: 180px; }
            .p-price { font-size: 1.1rem; }
            .p-body { padding: 14px; }

            /* Steps */
            .step-card { padding: 18px 12px; }
            .step-card h5 { font-size: .88rem; }

            /* Testimonios */
            .testi-card { padding: 18px; }
            .testi-text { font-size: .85rem; }

            /* Botón WA */
            .btn-wa-big { padding: 12px 24px; font-size: .9rem; gap: 8px; }

            /* Features */
            .feature-icon { width: 50px; height: 50px; font-size: 1.5rem; }
            .feature-card h5 { font-size: .82rem; }
            .feature-card p { font-size: .78rem; }

            /* Footer */
            .footer-bottom { font-size: .7rem; padding: 12px 0; margin-top: 28px; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar-web" id="navbar">
    <a href="#inicio" class="nav-logo">
        <img src="<?= APP_URL ?>/img/logo_web.png" alt="Catnis Bakery">
    </a>
    <ul class="nav-links">
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#nosotros">Nosotros</a></li>
        <li><a href="#productos">Productos</a></li>
        <li><a href="#testimonios">Opiniones</a></li>
        <li><a href="#pedido">¿Cómo pedir?</a></li>
    </ul>
    <a href="<?= APP_URL ?>/auth/login" class="btn-login">Iniciar sesión</a>
    <button class="hamburger" id="hamburger"><i class="fas fa-bars"></i></button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <button class="close-btn" id="closeMenu"><i class="fas fa-times"></i></button>
    <a href="#inicio" onclick="closeMobile()">Inicio</a>
    <a href="#nosotros" onclick="closeMobile()">Nosotros</a>
    <a href="#productos" onclick="closeMobile()">Productos</a>
    <a href="#testimonios" onclick="closeMobile()">Opiniones</a>
    <a href="#pedido" onclick="closeMobile()">¿Cómo pedir?</a>
    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" style="color:var(--beige);">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
</div>

<!-- ===== HERO ===== -->
<div class="hero" id="inicio">
    <img src="<?= APP_URL ?>/img/hero.png" alt="Catnis Bakery">
</div>

<!-- ===== TRUST STRIP ===== -->
<div class="trust-strip">
    <div class="trust-track">
        <?php
        $badges = [
            ['icon'=>'fa-shipping-fast','text'=>'Domicilios'],
            ['icon'=>'fa-leaf',         'text'=>'Ingredientes Naturales'],
            ['icon'=>'fa-certificate',  'text'=>'Calidad Premium'],
            ['icon'=>'fa-heart',        'text'=>'Hecho con Amor'],
            ['icon'=>'fa-star',         'text'=>'Artesanal 100%'],
            ['icon'=>'fa-dog',          'text'=>'Apto para perros'],
        ];
        $all = array_merge($badges, $badges);
        foreach ($all as $b): ?>
            <div class="trust-item">
                <i class="fas <?= $b['icon'] ?>"></i>
                <span><?= $b['text'] ?></span>
                <span style="opacity:.3; margin-left:6px;">✦</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ===== ABOUT ===== -->
<section class="about-section" id="nosotros">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <img src="<?= APP_URL ?>/img/about.jpg" alt="Catnis Bakery" style="width:100%;border-radius:16px;object-fit:cover;min-height:380px;">
            </div>
            <div class="col-lg-7">
                <div class="section-label"><i class="fas fa-paw"></i> Sobre Nosotros</div>
                <h2 class="section-title">Somos <span style="color:#7a1522">Catnis Bakery</span></h2>
                <p class="section-sub">
                    Nacimos con una misión simple: que cada perro pueda disfrutar algo delicioso,
                    saludable y hecho con los mismos cuidados que le darías a tu propia familia.
                </p>
                <div class="mt-4">
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-leaf"></i></div>
                        <div class="value-text">
                            <h6>Solo ingredientes naturales</h6>
                            <p>Sin colorantes, sin conservantes, sin ingredientes artificiales. Solo lo mejor para tu peludo.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-cookie-bite"></i></div>
                        <div class="value-text">
                            <h6>Elaboración artesanal</h6>
                            <p>Cada producto es hecho a mano con dedicación y amor, garantizando frescura en cada pedido.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="fas fa-dog"></i></div>
                        <div class="value-text">
                            <h6>Aprobado por perritos</h6>
                            <p>Formulado para ser 100% seguro y delicioso para perros de todas las razas y tamaños.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="features-section">
    <div class="container">
        <div class="row g-4">
            <?php
            $features = [
                ['icon'=>'🌿','bg'=>'#e8f5e9','titulo'=>'100% Natural',      'desc'=>'Sin aditivos, sin conservantes. Solo ingredientes frescos que conoces y confías.'],
                ['icon'=>'🎂','bg'=>'#fef3e2','titulo'=>'Personalización',   'desc'=>'Pasteles con el nombre de tu perro, sabores favoritos y decoraciones únicas.'],
                ['icon'=>'🚚','bg'=>'#e3f0f7','titulo'=>'Domicilios',         'desc'=>'Llevamos tu pedido hasta la puerta de tu casa con todo el cariño del mundo.'],
                ['icon'=>'❤️','bg'=>'#fde8e8','titulo'=>'Hecho con Amor',    'desc'=>'Cada galleta, cada pastel es preparado pensando en la felicidad de tu mascota.'],
            ];
            foreach ($features as $f): ?>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:<?= $f['bg'] ?>"><?= $f['icon'] ?></div>
                    <h5><?= $f['titulo'] ?></h5>
                    <p><?= $f['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== PRODUCTS ===== -->
<section class="products-section" id="productos">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label justify-content-center"><i class="fas fa-cookie-bite"></i> Nuestro Menú</div>
            <h2 class="section-title">LO QUE MÁS LES ENCANTA</h2>
        </div>

        <!-- Filtros desde DB -->
        <?php if (!empty($categorias)): ?>
        <div class="filter-pills justify-content-center" id="filterPills">
            <div class="filter-pill active" onclick="filtrar('todos', this)">🐾 Todos</div>
            <?php foreach ($categorias as $cat): ?>
                <div class="filter-pill" onclick="filtrar('cat-<?= $cat['id'] ?>', this)">
                    <?= $cat['icono'] ?> <?= htmlspecialchars($cat['nombre']) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Grid de productos del catálogo web -->
        <div class="row g-4" id="productGrid">
            <?php if (empty($productos)): ?>
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-cookie-bite" style="font-size:2.5rem; opacity:.25;"></i>
                    <p class="mt-3">Próximamente nuestros productos estarán disponibles aquí.</p>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $p):
                    $catClass = $p['cat_id'] ? 'cat-' . $p['cat_id'] : 'sin-cat';
                    $waMsg    = urlencode("🐾 ¡Hola Catnis Bakery! Me interesa *{$p['nombre']}* (\$" . number_format($p['precio'], 0, ',', '.') . "). ¿Está disponible?");
                ?>
                <div class="col-6 col-md-4 col-lg-3 product-item" data-cat="<?= $catClass ?>">
                    <div class="product-card">
                        <?php if ($p['imagen']): ?>
                            <div class="p-img-wrap">
                                <img src="<?= APP_URL ?>/img/catalogo/<?= htmlspecialchars($p['imagen']) ?>"
                                     alt="<?= htmlspecialchars($p['nombre']) ?>" loading="lazy">
                                <div class="p-badge">Artesanal</div>
                            </div>
                        <?php else: ?>
                            <div class="p-img-placeholder">🐾</div>
                        <?php endif; ?>
                        <div class="p-body">
                            <div class="p-category"><?= htmlspecialchars($p['categoria_nombre'] ?? 'Catnis Bakery') ?></div>
                            <div class="p-name"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="p-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            <div class="p-desc"><?= htmlspecialchars($p['descripcion'] ?: 'Delicioso y 100% natural. Hecho con amor para tu peludo.') ?></div>
                            <div class="p-footer">
                                <div class="p-price">$<?= number_format($p['precio'], 0, ',', '.') ?></div>
                                <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= $waMsg ?>"
                                   class="btn-wa-card" target="_blank">
                                    <i class="fab fa-whatsapp"></i> Pedir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="testimonials-section" id="testimonios">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label justify-content-center"><i class="fas fa-star"></i> Opiniones</div>
            <h2 class="section-title">Lo que dicen nuestros clientes</h2>
            <p class="section-sub mx-auto">Familias peludas que ya confían en Catnis Bakery</p>
        </div>
        <div class="row g-4">
            <?php
            $testimonios = [
                ['nombre'=>'María C.', 'inicial'=>'M', 'mascota'=>'Mamá de Rocky',
                 'texto'=>'¡El pastel de cumpleaños de Rocky fue increíble! Lo devoró en segundos y nosotros encantados de saber que era 100% natural. Definitivamente volvemos.', 'estrellas'=>5],
                ['nombre'=>'Carlos P.', 'inicial'=>'C', 'mascota'=>'Papá de Luna',
                 'texto'=>'Pedí las galletas de mantequilla de maní y Luna quedó obsesionada. El servicio fue rápido, la entrega puntual y el empaque hermoso. 100% recomendado.', 'estrellas'=>5],
                ['nombre'=>'Sofía R.', 'inicial'=>'S', 'mascota'=>'Mamá de Toby y Max',
                 'texto'=>'Tengo dos perritos y ambos aman los snacks de Catnis Bakery. Me encanta que sean sin conservantes. Es el regalo perfecto para cada ocasión especial.', 'estrellas'=>5],
            ];
            foreach ($testimonios as $t): ?>
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-stars"><?= str_repeat('<i class="fas fa-star"></i>', $t['estrellas']) ?></div>
                    <p class="testi-text">"<?= $t['texto'] ?>"</p>
                    <div class="testi-author">
                        <div class="testi-avatar"><?= $t['inicial'] ?></div>
                        <div>
                            <div class="testi-name"><?= $t['nombre'] ?></div>
                            <div class="testi-role"><?= $t['mascota'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== HOW TO ORDER ===== -->
<section class="order-section" id="pedido">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label justify-content-center"><i class="fas fa-shopping-bag"></i> Hacer un Pedido</div>
            <h2 class="section-title">Cómo pido?</h2>
            <p class="section-sub mx-auto">Es súper fácil. En 3 pasos tu pedido está listo.</p>
        </div>
        <div class="row g-4 mb-5 position-relative">
            <?php
            $steps = [
                ['num'=>1,'icon'=>'👀','titulo'=>'Elige tu producto','desc'=>'Navega nuestro menú y escoge lo que más le va a gustar a tu peludo.'],
                ['num'=>2,'icon'=>'💬','titulo'=>'Escríbenos',        'desc'=>'Contáctanos por WhatsApp con tu pedido, nombre y dirección de entrega.'],
                ['num'=>3,'icon'=>'🚚','titulo'=>'Recibe en casa',    'desc'=>'Coordinamos la entrega y tu pedido llega fresquito y listo para disfrutar.'],
            ];
            foreach ($steps as $i => $s): ?>
            <div class="col-md-4 position-relative">
                <div class="step-card">
                    <div class="step-num"><?= $s['num'] ?></div>
                    <div class="step-icon"><?= $s['icon'] ?></div>
                    <h5><?= $s['titulo'] ?></h5>
                    <p><?= $s['desc'] ?></p>
                </div>
                <?php if ($i < 2): ?>
                    <div class="step-arrow d-none d-md-block"><i class="fas fa-arrow-right"></i></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= urlencode('🐾 ¡Hola Catnis Bakery! Quiero hacer un pedido. ¿Qué necesito?') ?>"
               class="btn-wa-big" target="_blank">
                <i class="fab fa-whatsapp" style="font-size:1.3rem;"></i>
                Hacer mi pedido ahora
            </a>
            <div style="margin-top:14px; color:#7a1522; font-family:'Inter',sans-serif; font-size:14px; display:flex; flex-direction:column; align-items:center; gap:4px;">
                <span><i class="fas fa-clock me-2"></i><strong>Virtual:</strong> Martes a Sábado 9am – 6pm</span>
                <span style="padding-left:22px;"><strong>Físico:</strong> Sábados y Domingo (festivos) 11:30 – 6:30pm</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-logo mb-3 text-center">
                    <img src="<?= APP_URL ?>/img/logo_web.png" alt="Logo" style="height:80px;width:auto;object-fit:contain;">
                </div>
                <p style="font-family:'ITC Avant Garde',sans-serif; color:rgba(255,255,255,.5); font-size:.88rem; line-height:1.7; text-align:justify; max-width:220px; margin:0 auto;">
                    Repostería artesanal para <br>perros hecha con ingredientes <br>naturales y mucho amor. 🐾
                </p>
                <div class="social-links mt-3 justify-content-center">
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="social-link" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Menú</h6>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#nosotros">Nosotros</a></li>
                    <li><a href="#productos">Productos</a></li>
                    <li><a href="#testimonios">Opiniones</a></li>
                    <li><a href="#pedido">¿Cómo Pedir?</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Productos</h6>
                <ul>
                    <?php foreach ($categorias as $cat): ?>
                        <li><a href="#productos"><?= htmlspecialchars($cat['nombre']) ?></a></li>
                    <?php endforeach; ?>
                    <?php if (empty($categorias)): ?>
                        <li><a href="#productos">Pasteles</a></li>
                        <li><a href="#productos">Galletas</a></li>
                        <li><a href="#productos">Combos</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6>Contacto</h6>
                <ul class="footer-contact">
                    <li>
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank"
                           style="color:rgba(255,255,255,.6); text-decoration:none;">
                            +57 324 877 3971
                        </a>
                    </li>
                    <li><i class="fas fa-envelope"></i><span>hola@catnisbakery.com</span></li>
                    <li><i class="fas fa-clock"></i><span>Yumbo / Cali – Colombia</span></li>
                    <li><i class="fas fa-map-marker-alt"></i><span>Colombia 🇨🇴</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            © <?= date('Y') ?> Catnis Bakery · Hecho con amor · Todos los derechos reservados
        </div>
    </div>
</footer>

<!-- ===== FLOATING WHATSAPP ===== -->
<a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= urlencode('🐾 ¡Hola Catnis Bakery! Quiero hacer un pedido.') ?>"
   class="wa-float" target="_blank" title="Chateá con nosotros">
    <i class="fab fa-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mobile menu
document.getElementById('hamburger').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.add('open');
});
document.getElementById('closeMenu').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.remove('open');
});
function closeMobile() {
    document.getElementById('mobileMenu').classList.remove('open');
}

// Product filter
function filtrar(cat, el) {
    document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.product-item').forEach(item => {
        const show = cat === 'todos' || item.getAttribute('data-cat') === cat;
        item.style.display = show ? 'block' : 'none';
    });
}

// Scroll animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.product-card, .feature-card, .step-card, .testi-card, .value-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(18px)';
    el.style.transition = 'opacity .5s ease, transform .5s ease';
    observer.observe(el);
});
</script>
</body>
</html>
