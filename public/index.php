<?php 
require_once __DIR__ . '/../includes/config.php';

// Check if user is logged in
$isLoggedIn = Auth::isLoggedIn();

// If logged in, we can  get user data from session
$userDni = $_SESSION['dni'] ?? null;
$userName = $_SESSION['nombre'] ?? null;
$userRole = $_SESSION['rol'] ?? null;
?>
<!-- Head -->
<?php include __DIR__ . '/../includes/head.php'; ?>
<body>
    
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">

        <?php if ($isLoggedIn): ?>
            <h2 class="mb-5">Bienvenido, <?=htmlspecialchars($userName) ?></h2>

            <p>¿Qué deseas hacer?</p>

            <ul style="list-style-type: none;">
                <li><a href="books.php">Realizar una reserva</a></li>
                <li><a href="user/reservations.php">Ver mis reservas</a></li>
                <li><a href="user/dashboard.php">Mi perfil</a></li>

                <?php if (Auth::hasAnyRole(['admin', 'empleado'])): ?>
                    <li><a href="admin/dashboard.php">Panel de administración</a></li>
                <?php endif; ?>
            </ul>

        <?php else: ?>
            <!-- !isLoggedIn -->
            <h2>Bienvenido a la Savia Nexus</h2>

            <p>Para acceder a las reservas debes iniciar sesión.</p>

            <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
            <a href="register.php" class="btn btn-secondary">Registrarse</a>
        <?php endif; ?>
    </div>
    <section id="location" class="container my-5">
        <div class="row g-4 align-items-stretch">
            <div class="col-md-6">
                <div class="mapa-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12527.924911318054!2d-0.716122!3d38.279928!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd63b7ad6e40fdc1%3A0x609104ad972f51c4!2sIES%20Severo%20Ochoa%20Elx!5e0!3m2!1ses!2ses!4v1764831161433!5m2!1ses!2ses" 
                        style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>