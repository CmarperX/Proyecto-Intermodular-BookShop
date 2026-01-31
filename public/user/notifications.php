<?php
// User notifications page

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Notification.php';

// Only logged users can access their notifications
if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// Instantiate Notification model
$notificationModel = new Notification($pdo);

// Get current logged user 
$codUsuario = $_SESSION['dni'];

$error = null;
$success = null;

// POST request to mark notification as read
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo = (int) ($_POST['codigo'] ?? 0);

    if (!$codigo) {
        $error = "Notificación no válida";

    } else {
        if ($notificationModel->markAsRead($codigo, $codUsuario)) {
            $success = "Notificación marcada como leida";
        } else {
            $error = "No se ha podido modificar la notificación";
        }
    }
}

// Get all notifications of the user
$notifications = $notificationModel->getUserNotifications($codUsuario);
?>

<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Mis notificaciones</h2>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (empty($notifications)): ?>
            <p>No tienes notificaciones.</p>

        <?php else: ?>
            <table class="table table-striped">
                <thead>
                <tr class="text-center">
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($notifications as $n): ?>
                    <tr class="text-center">
                        <td><?= htmlspecialchars($n['mensaje']) ?></td>
                        <td><?= $n['fecha_creacion'] ?></td>
                        <td>
                            <?= $n['leida'] === 'leida' ? 'Leída' : 'No leída' ?>
                        </td>

                        <td>
                            <?php if ($n['leida'] === 'no leida'): ?>
                                <form method="post">
                                    <input type="hidden" name="codigo" value="<?= $n['codigo'] ?>">
                                    <button class="btn btn-sm btn-primary">
                                        Marcar como leída
                                    </button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <div class="mt-5">
            <a href="dashboard.php" class="btn btn-primary mb-3">
                Volver
            </a>
        </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>                            
</body>
</html>