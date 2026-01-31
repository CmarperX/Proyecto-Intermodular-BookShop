<?php
// Admin panel

require_once __DIR__ . '/../../includes/config.php';

// Check if user is logged in
if (!Auth::isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

// Check if user is admin or empleado
if (!Auth::hasAnyRole(['admin', 'empleado'])) {
    header('Location: ../index.php');
    exit;
}
?>

<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Panel de Administración</h2>

        <div class="row g-4">

            <!-- User management-->
            <?php if (Auth::hasRole('admin')): ?>
                <!-- Usuarios -->
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="users.php" class="text-decoration-none">
                        <div class="card h-100 shadow-sm text-center dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    &#128101; 
                                    Usuarios
                                </h5>
                                <p class="card-text">
                                    Gestión de usuarios y roles
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            <?php endif; ?>

            <!-- Category management -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="categories.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#127991; 
                                Categorías
                            </h5>
                            <p class="card-text">
                                Crear, editar o desactivar categorías
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Books management -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="books.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128218; 
                                Libros
                            </h5>
                            <p class="card-text">
                                Alta, edición y stock de libros
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Reservations management -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="reserves_admin.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128197; 
                                Reservas
                            </h5>
                            <p class="card-text">
                                Gestiona las reservas del sistema
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Statistics -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="statistics.php" class="text-decoration-none">
                    <div class="card h-100 shadow-sm text-center dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                &#128200; 
                                Informe estadístico
                            </h5>
                            <p class="card-text">
                                Gestiona las reservas del sistema
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