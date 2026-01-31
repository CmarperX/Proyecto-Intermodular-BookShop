<?php
// shows active reservations
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Reserve.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$reserveModel = new Reserve($pdo);

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    $codReserve = (int)$_POST['cancel'];
    
    if ($reserveModel->cancel($codReserve)) {
        $success = "Reserva cancelada y stock actualizado.";

    } else {
        $error = "No se pudo cancelar la reserva.";
    }
    // Refrescamos las reservas para ver el cambio de estado
    $reserves = $reserveModel->getUserReserves($_SESSION['dni']);
}
$reserves = $reserveModel->getUserReserves($_SESSION['dni']);
?>
<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <!-- Navbar -->
    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Reservas activas</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped mb-5 text-center">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reserves as $r): ?>
                        <?php if ($r['estado'] === 'confirmada'): ?>
                            
                            <!-- cancel your reservation one day before the pickup date -->
                            <?php 
                                $reservationDate = new DateTime($r['fecha']);
                                $today = new DateTime();
                                $diff = $today->diff($reservationDate);

                                // It can be cancelled while there is a 1 day difference and the date is future
                                $isCancelable = ($reservationDate > $today && $diff->days >= 1);
                            ?>
                            <tr>
                                <td><?= $r['codigo'] ?></td>
                                <td><?= htmlspecialchars($r['titulo']) ?></td>
                                <td><?= $reservationDate->format('d/m/Y') ?></td>
                                <td><?= date('d/m/Y', strtotime($r['fechaDevolucion'])) ?></td>
                                <td><span class="badge bg-success"><?= $r['estado'] ?></span></td>
                                <td>
                                    <?php if ($isCancelable): ?>
                                        <form method="POST" onsubmit="return confirm('¿Seguro que quieres cancelar?')">
                                            <input type="hidden" name="cancel" value="<?= $r['codigo'] ?>">
                                            <button class="btn btn-sm btn-warning">Cancelar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted" title="No se puede cancelar pasadas las 24H previas a la reserva">No disponible</span>
                                    <?php endif; ?>
                                </td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
