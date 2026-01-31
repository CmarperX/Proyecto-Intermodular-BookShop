<?php
// Displays active, cancelled, and completed bookings

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Reserve.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// Search
$search = $_GET['search'] ?? '';

$reserveModel = new Reserve($pdo);
$reserves = $reserveModel->getUserReserves($_SESSION['dni'], $search);
?>

<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Historial de reservas</h2>

        <!-- Search form -->
        <form method="GET" class="d-flex mb-4">
            <input type="text" name="search" class="form-control me-2" 
                placeholder="Buscar por código, título o nombre..." 
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if($search): ?>
                <a href="reservations_history.php" class="btn btn-primary ms-2">Limpiar</a>
            <?php endif; ?>
        </form>

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
                <tr>
                    <td><?= $r['codigo'] ?></td>
                    <td><?= htmlspecialchars($r['titulo']) ?></td>
                    <td><?= $r['fecha'] ?></td>
                    <td><?= ucfirst($r['estado']) ?></td>
                    <td>
                        <span class="badge bg-<?= 
                            $r['estado'] === 'confirmada' ? 'success' :
                            ($r['estado'] === 'cancelada' ? 'danger' : 'secondary')
                        ?>">
                            <?= ucfirst($r['estado']) ?>
                        </span>
                    </td>
                </tr>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>                
</body>
</html>
