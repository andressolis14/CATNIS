</div><!-- end page-content -->
</div><!-- end main-content -->
</div><!-- end wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ---- Sidebar Responsivo ----
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');
    const toggleBtn = document.getElementById('sidebarToggle');

    function isMobile() { return window.innerWidth <= 768; }
    function isTablet() { return window.innerWidth <= 992 && window.innerWidth > 768; }

    function closeMobileSidebar() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', function () {
        if (isMobile()) {
            // En móvil: abrir/cerrar como drawer
            const isOpen = sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('visible', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        } else {
            // En desktop/tablet: colapsar/expandir
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    });

    // Cerrar sidebar al tocar el overlay
    overlay.addEventListener('click', closeMobileSidebar);

    // Cerrar sidebar al elegir una opción del menú en móvil
    sidebar.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function () {
            if (isMobile()) closeMobileSidebar();
        });
    });

    // Ajustar al girar el dispositivo
    window.addEventListener('resize', function () {
        if (!isMobile()) {
            closeMobileSidebar();
        }
    });

    // Toggle theme
    const themeBtn = document.getElementById('themeToggle');
    const darkIcon = themeBtn.querySelector('.dark-icon');
    const lightIcon = themeBtn.querySelector('.light-icon');

    function updateIcons(currentTheme) {
        if (currentTheme === 'light') {
            darkIcon.style.display = 'none';
            lightIcon.style.display = 'inline-block';
        } else {
            darkIcon.style.display = 'inline-block';
            lightIcon.style.display = 'none';
        }
    }

    // Init icons
    updateIcons(document.documentElement.getAttribute('data-theme'));

    themeBtn.addEventListener('click', function () {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcons(newTheme);
    });

    // Auto-dismiss alerts after 4s
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            let bsAlert = new bootstrap.Alert(a);
            bsAlert.close();
        });
    }, 4000);
</script>
<?php if (!empty($extraJs)): ?>
    <?= $extraJs ?>
<?php endif; ?>
</body>

</html>