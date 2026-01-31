<?php
require_once __DIR__ . '/../../includes/config.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

?>
<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Panel de usuario</h2>

        <div class="row g-4">

            <!-- user data -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="profile.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128100; 
                                Mis datos personales
                            </h5>
                            <p class="card-text">
                                Consulta tus datos personales
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Edit user data -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="profile_edit.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128221;
                                Editar mi perfil
                            </h5>
                            <p class="card-text">
                                Consulta y modifica tus datos personales
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- User reservations -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="reservations.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128197; 
                                Mis reservas
                            </h5>
                            <p class="card-text">
                                Consulta y gestiona tus reservas activas
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- reservation history -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="reservations_history.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128344;
                                Mi historial de reservas
                            </h5>
                            <p class="card-text">
                                Consulta tu historial de reservas
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Notificaciones -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="notifications.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128276; 
                                Notificaciones
                            </h5>
                            <p class="card-text">
                                Consulta avisos y cambios en tus reservas
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
