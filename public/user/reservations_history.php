<?php
// Displays active, cancelled, and completed bookings

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Reserve.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$reserveModel = new Reserve($pdo);
$reserves = $reserveModel->getUserReserves($_SESSION['dni']);
?>

<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Historial de reservas</h2>

        <?php if (empty($reserves)): ?>
            <p>No tienes reservas anteriores.</p>
        <?php else: ?>

        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>Código</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($reserves as $r): ?>
                <?php if ($r['estado'] !== 'confirmada'): ?>
                    <tr>
                        <td><?= $r['codRecurso'] ?></td>
                        <td><?= $r['titulo'] ?></td>
                        <td><?= $r['fecha'] ?></td>
                        <td><?= ucfirst($r['estado']) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php endif; ?>

        <div class="mt-5"> 
            <a href="dashboard.php" class="btn btn-primary mt-3">Volver</a>
        </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
