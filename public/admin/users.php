<?php
// admin -> users management

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/User.php';

// check if the user is logged in and if they are the admin.
if (!Auth::isLoggedIn() || !Auth::hasRole('admin')) {
    header('Location: ../index.php');
    exit;
}

// instantiate users models
$userModel = new User($pdo);

// search, sort and pagination

// search text
$search = Validation::sanitize($_GET['search'] ?? '');

// column to sort by
$column = $_GET['column'] ?? 'dni';

// order direction
$order  = ($_GET['order'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

// alternate order direction
$altenateOrder = ($order === 'ASC') ? 'DESC' : 'ASC';

// current page, minimum will be 1
$page = max(1, (int)($_GET['page'] ?? 1));

// users per page
$usersPerPage = 5;

// offset calculation for pagination
$offset = ($page -1) * $usersPerPage;

// columns to order by
$columnsPerOrder = ['dni', 'nombre', 'email', 'rol', 'activo'];
if (!in_array($column, $columnsPerOrder)) {
    $column = 'dni';
}

$error = null;
$success = null;

// Processing actions with request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dni = Validation::sanitize($_POST['dni'] ?? '');
    $action = $_POST['action'] ?? '';

    // if user ID exists and is not the current user 
    if ($action === 'toggle' && $dni && $dni !== $_SESSION['dni']) {

        if ($userModel->toggleActive($dni)) {
            $success = "Estado del usuario actualizado";
        } else {
            $error = "No se pudo actualizar el estado";
        }
    }
}

// We get all users per pagination
$totalUsers = $userModel->count($search);
$totalPages = ceil($totalUsers/$usersPerPage);

// Users current page
$users = $userModel->getAllUsers(
    $search,
    $column,
    $order,
    $usersPerPage,
    $offset
);

?>

<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="container-fluid mt-5 mb-5">
        <h2 class="mb-5">Gestión de usuarios</h2>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Search form -->
        <form method="get" class="d-flex mb-5">
            <input type="text"
                name="search"
                class="form-control me-2"
                placeholder="Buscar usuario..."
                value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Buscar</button>
        </form>

        <!-- Users -->
        <div class="table-responsive">
            <table class="table table-striped mb-5 text-center">
                <thead>
                    <tr>
                        <?php foreach ($columnsPerOrder as $col): ?>
                            <th>
                                <a href="?column=<?= $col ?>&order=<?= $altenateOrder ?>&search=<?= urlencode($search) ?>">
                                    <?= ucfirst($col) ?>
                                </a>
                            </th>
                        <?php endforeach; ?>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['dni'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['rol'] ?></td>
                        <td><?= $u['activo'] ?></td>

                        <td class="d-flex gap-1 justify-content-center">
                
                            <a href="user_edit.php?dni=<?= $u['dni'] ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                
                            <!-- Toggle active -->
                            <!-- admin cannot deactivate himself -->
                            <?php if ($_SESSION['dni'] !== $u['dni']): ?>
                                <form method="post" onsubmit="return confirm('¿Quieres cambiar el estado del usuario: <?= htmlspecialchars($u['nombre']) ?>?');">
                                    <input type="hidden" name="dni" value="<?= $u['dni'] ?>">
                                    <input type="hidden" name="action" value="toggle">
                                    <button class="btn btn-sm btn-warning">
                                        <?= $u['activo'] === 'activo' ? 'Desactivar' : 'Activar' ?>
                                    </button>
                                </form>
                            <?php endif; ?>     
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        

        <!-- PAGINATION -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item text-center <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link"
                        href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <div class="mt-5 text-center">
            <a href="user_create.php" class="btn btn-primary mb-3">
                Añadir usuario
            </a>
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