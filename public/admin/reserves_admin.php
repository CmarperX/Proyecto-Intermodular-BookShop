<?php
// admin/empleado -> Reserves management

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Reserve.php';
require_once __DIR__ . '/../../models/Notification.php';

// only logged users with admin or empleado role
if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin', 'empleado'])) {
    header('Location: ../index.php');
    exit;
}

// reserves model instantiation
$reservesModel = new Reserve($pdo);

// Notification model instantiation
$notificationModel = new Notification($pdo);

// Search text
$search = Validation::sanitize($_GET['search'] ?? '');

// Order
$order = $_GET['order'] ?? 'DESC';
$order = ($order === 'ASC') ? 'ASC' : 'DESC';

// Current page
$page = max(1, (int)($_GET['page'] ?? 1));

$perPage = 5;

// Offset 
$offset = ($page - 1) * $perPage;

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo = (int)($_POST['codigo'] ?? 0);
    $estado = $_POST['estado'] ?? '';

    if (!$codigo || !$estado) {
        $error = "Estos datos no son válidos";
        
    } else {

        if ($estado === 'finalizada') {

            if ($reservesModel->finish($codigo)) {

                $reserves = $reservesModel->getById($codigo);

                if($reserves) {

                    $mensaje = "La reserva con ID ($codigo) ha sido modificada a: $estado";
                    $notificationModel->create($reserves['codUsuario'], $mensaje);
                }
            
                $success = "El estado de la reserva ha sido actualizado";

            } else {

                $error = "No se pudo actualizar el estado";
            }

        } else {

            // change status 
            if ($reservesModel->updateEstado($codigo, $estado)) {

                $reserva = $reservesModel->getById($codigo);

                if ($reserva) {
                    $mensaje = "La reserva #$codigo ha cambiado a estado: $estado";
                    $notificationModel->create($reserva['codUsuario'], $mensaje);
                }

                $success = "Estado actualizado correctamente";

            } else {

                $error = "No se pudo actualizar el estado";
            }
        }
    }    
}


// Total reservations
$totalReserves = $reservesModel->count($search);
$totalPages = (int)ceil($totalReserves / $perPage);

// Reservas de la página actual
$reserves = $reservesModel->getAll(
    $search,
    $order,
    $perPage,
    $offset
);
?>

<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Gestión de reservas</h2>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Search -->
        <form method="get" class="d-flex mb-5">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar por usuario o código de reserva..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Buscar</button>
            <a href="reserves_admin.php" class="btn btn-primary ms-2">Limpiar</a>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>Código Reserva</th>
                <th>Usuario</th>
                <th>Código libro</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($reserves as $r): ?>
                <tr>
                    <td><?= $r['codigo'] ?></td>
                    <td><?= htmlspecialchars($r['nombre']) ?></td>
                    <td><?= $r['codRecurso'] ?></td>
                    <td><?= $r['fecha'] ?></td>
                    <td><?= ucfirst($r['estado']) ?></td>

                    <td>
                        <form method="post" class="d-flex gap-1">
                            <input type="hidden" name="codigo" value="<?= $r['codigo'] ?>">

                            <select name="estado"
                                    class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                <?php foreach (['confirmada','cancelada','finalizada'] as $estado): ?>
                                    <option value="<?= $estado ?>"
                                        <?= $r['estado'] === $estado ? 'selected' : '' ?>>
                                        <?= ucfirst($estado) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link"
                        href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <div class="mt-5 text-center">
            <a href="dashboard.php" class="btn btn-primary mb-3">
                Volver atras
            </a>
        </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>