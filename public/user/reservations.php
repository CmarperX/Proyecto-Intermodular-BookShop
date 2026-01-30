<?php
// shows active reservations
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
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <!-- Navbar -->
    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Reservas activas</h2>

        <div class="table-responsive">
            <table class="table table-striped mb-5 text-center">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reserves as $r): ?>
                        <?php if ($r['estado'] === 'confirmada'): ?>
                            <tr>
                                <td><?= $r['codRecurso'] ?></td>
                                <td><?= $r['titulo'] ?></td>
                                <td><?= $r['fecha'] ?></td>
                                <td><?= $r['fechaDevolucion'] ?></td>
                                <td><?= $r['estado'] ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            <a href="dashboard.php" class="btn btn-primary">Volver</a>
        </div>
        
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
