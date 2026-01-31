<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Book.php';
require_once __DIR__ . '/../../models/Reserve.php';
require_once __DIR__ . '/../../models/User.php';

if (!Auth::hasAnyRole(['admin'])) {
    header('Location: ../login.php');
    exit;
}

// Models
$bookModel = new Book($pdo);
$reserveModel = new Reserve($pdo);

// most reserved books
$sql = "SELECT r.codRecurso, b.titulo, COUNT(*) AS total_reservas
        FROM reservas r
        JOIN libros b ON b.codigo = r.codRecurso
        GROUP BY r.codRecurso
        ORDER BY total_reservas DESC
        LIMIT 5";

$stmt = $pdo->query($sql);
$topResources = $stmt->fetchAll(PDO::FETCH_ASSOC);

// number of bookings per user
$sql = "SELECT u.dni, u.nombre, COUNT(*) AS total_reservas
        FROM reservas r
        JOIN usuarios u ON u.dni = r.codUsuario
        GROUP BY r.codUsuario
        ORDER BY total_reservas DESC
        LIMIT 5";

$stmt = $pdo->query($sql);
$reservasUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total bookings in the current month
$sql = "SELECT COUNT(*) AS total_mes
        FROM reservas
        WHERE YEAR(fecha) = YEAR(CURDATE())
          AND MONTH(fecha) = MONTH(CURDATE())";

$stmt = $pdo->query($sql);
$reservasMes = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">

        <h2 class="mb-5 text-center">
            &#128202; 
            Estadísticas del sistema
        </h2>

        <div class="row g-4">

            <!-- most reserved books -->
            <div class="col-md-12 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            &#128218; 
                            Recursos más reservados
                        </h5>
                        <?php if($topResources): ?>
                            <ul class="list-group list-group-flush mt-3">
                                <?php foreach($topResources as $r): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= htmlspecialchars($r['titulo']) ?>
                                        <span class="badge bg-primary rounded-pill"><?= $r['total_reservas'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center mt-3">No hay reservas registradas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- number of bookings per user -->
            <div class="col-md-12 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            &#128100; 
                            Reservas por usuario
                        </h5>
                        <?php if($reservasUsuarios): ?>
                            <ul class="list-group list-group-flush mt-3">
                                <?php foreach($reservasUsuarios as $u): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= htmlspecialchars($u['nombre']) ?>
                                        <span class="badge bg-success rounded-pill"><?= $u['total_reservas'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center mt-3">No hay usuarios con reservas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Total bookings in the current month -->
            <div class="col-md-12 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            &#128197; 
                            Reservas totales este mes
                        </h5>
                        <p class="display-6 text-center mt-4">
                            <?= $reservasMes['total_mes'] ?? 0 ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center">
            <a href="export_reservation_csv.php" class="btn btn-primary">Exportar CSV</a>
            <a href="dashboard.php" class="btn btn-primary">Volver al panel</a>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>