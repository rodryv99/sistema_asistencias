</div>
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Sistema de Control de Asistencias. Desarrollado con PHP.</p>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>/public/js/main.js"></script>
    
    <script>
        function closeAlert() {
            const alert = document.getElementById('flash-message');
            if (alert) {
                alert.style.display = 'none';
            }
        }

        // Auto-cerrar alertas después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('flash-message');
            if (alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        });

        // Menú móvil
        const mobileMenu = document.getElementById('mobile-menu');
        const navMenu = document.querySelector('.nav-menu');

        mobileMenu.addEventListener('click', function() {
            mobileMenu.classList.toggle('is-active');
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>