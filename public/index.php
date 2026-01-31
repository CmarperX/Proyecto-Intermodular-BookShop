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
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Carousel -->
    <div id="landingCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?= BASE_URL ?>/public/assets/images/carousel1.jpg" class="d-block w-100 carousel-img" alt="Books">
                <div class="carousel-caption">
                    <h5 class="fw-bold">Bienvenido a Savia Nexus</h5>
                    <p>Reserva, descubre y disfruta de la lectura.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="<?= BASE_URL ?>/public/assets/images/carousel2.jpg" class="d-block w-100 carousel-img" alt="Catalog">
                <div class="carousel-caption">
                    <h5 class="fw-bold">Miles de libros disponibles</h5>
                    <p>Accede a nuestro catálogo digital.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="<?= BASE_URL ?>/public/assets/images/carousel3.jpg" class="d-block w-100 carousel-img" alt="Reading">
                <div class="carousel-caption">
                    <h5 class="fw-bold">Lectura sostenible</h5>
                    <p>Un proyecto comprometido con el medio ambiente.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">

        <?php if ($isLoggedIn): ?>
            <h2 class="mb-5">Bienvenido, <?=htmlspecialchars($userName) ?></h2>
            <p>¿Qué deseas hacer?</p>

            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-lg text-center border-0">
                        <div class="card-body py-5">
                            <div class="mb-3" style="font-size:3rem">📖</div>
                                <h4 class="card-title fw-bold mb-3">Realizar una reserva</h4>
                                <p class="card-text mb-4">Accede al catálogo completo y reserva tus libros favoritos de forma rápida y sencilla.</p>
                                <a href="books.php" class="btn btn-primary btn-lg px-4">Ir al catálogo</a>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- if not logged in -->
        <?php else: ?>
            <h2>Bienvenido a Savia Nexus</h2>
            <p>Para acceder a las reservas debes iniciar sesión.</p>

            <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
            <a href="register.php" class="btn btn-primary">Registrarse</a>
        <?php endif; ?>
    </div>
    <!-- MAP -->
    <section id="location" class="container my-5">
        <h3 class="text-center mb-4">
            &#128205; Dónde estamos
        </h3>
        <div class="ratio ratio-16x9 shadow">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12527.924911318054!2d-0.716122!3d38.279928!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd63b7ad6e40fdc1%3A0x609104ad972f51c4!2sIES%20Severo%20Ochoa%20Elx!5e0!3m2!1ses!2ses!4v1764831161433!5m2!1ses!2ses" 
                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>