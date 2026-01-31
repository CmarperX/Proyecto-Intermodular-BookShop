<?php
// admin/empleado -> Book management

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Book.php';

// only logged users with admin or empleado role
if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin', 'empleado'])) {
    header("Location: ../index.php");
    exit;
}

// model instantiation
$bookModel = new Book($pdo);

// Search, order and pagination

// Search text - sanitize
$search = Validation::sanitize($_GET['search'] ?? '');

// Order column
$column = $_GET['column'] ?? 'codigo';

// Order direction
$order = $_GET['order'] ?? 'ASC';

// Current page 
$page = max(1, (int)($_GET['page'] ?? 1));

// Rows per page
$perPage = 5;

// offset
$offset = ($page -1) * $perPage;

// prevent SQL injection
$columnsBook = ['codigo', 'titulo', 'autor', 'stock', 'activo'];
if (!in_array($column, $columnsBook)) {
    $column = 'codigo';
}

// Order validation
$order = ($order === 'DESC') ? 'DESC' : 'ASC';

// Alternate order 
$alternateOrder = ($order === 'ASC') ? 'DESC' : 'ASC';

$error = null;
$success = null;

// ToggleActive
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo = (int) ($_POST['codigo'] ?? 0);
    $action = $_POST['action'] ?? '';

    if (!$codigo || $action !== 'toggle') {
        $error = "Esta acción no es válida";

    } else {

        if ($bookModel->toggleActive($codigo)) {
            $success = "Se ha modificado el estado del libro";

        } else {
            $error = "No se pudo modificar el estado del libro";
        }
    }
}

// Pagination
$totalBooks = $bookModel->count($search);
$totalPages = (int) ceil($totalBooks/$perPage);

// Books of current page
$books = $bookModel->getAll(
    $search,
    $column,
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

        <h2 class="mb-5">Gestión de libros</h2>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Search form -->
        <form method="get" class="d-flex mb-5">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar libro..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Buscar</button>
            <a href="books.php" class="btn btn-primary ms-2">Limpiar</a>

        </form>

        <!-- Books table -->
        <div class="table-responsive">
            <table class="table table-striped mb-5 text-center">
                <thead>
                    <tr class="text-center">
                        <!-- codigo column -->
                        <th>
                            <a href="?column=codigo&order=<?= $alternateOrder ?>&search=<?= urlencode($search) ?>">
                                Código
                            </a>
                        </th>
                        <!-- titulo column -->
                        <th>
                            <a href="?column=titulo&order=<?= $alternateOrder ?>&search=<?= urlencode($search) ?>">
                                Título
                            </a>
                        </th>
                        <!-- autor column -->
                        <th>
                            <a href="?column=autor&order=<?= $alternateOrder ?>&search=<?= urlencode($search) ?>">
                                Autor
                            </a>
                        </th>
                        <!-- stock column -->
                        <th>
                            <a href="?column=stock&order=<?= $alternateOrder ?>&search=<?= urlencode($search) ?>">
                                Stock
                            </a>
                        </th>
                        <!-- activo column -->
                        <th>
                            <a href="?column=activo&order=<?= $alternateOrder ?>&search=<?= urlencode($search) ?>">
                                Estado
                            </a>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= $book['codigo'] ?></td>
                            <td><?= htmlspecialchars($book['titulo']) ?></td>
                            <td><?= htmlspecialchars($book['autor']) ?></td>
                            <td><?= (int)$book['stock'] ?></td>
                            <td><?= htmlspecialchars($book['activo']) ?></td>

                            <td class="d-flex gap-1 pb-5 justify-content-center align-items-center">
                            
                                <a href="book_edit.php?id=<?= $book['codigo'] ?>" class="btn btn-sm btn-primary">
                                    Editar
                                </a>

                                <form method="post" onsubmit="return confirm('¿Quieres cambiar el estado del libro: <?= htmlspecialchars($book['titulo']) ?>?');">
                                    <input type="hidden" name="codigo" value="<?= $book['codigo'] ?>">
                                    <input type="hidden" name="action" value="toggle">
                                    <button class="btn btn-sm btn-warning">
                                        <?= $book['activo'] === 'activo' ? 'Desactivar' : 'Activar' ?>
                                    </button>
                                </form>
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
            <a href="book_create.php" class="btn btn-primary mb-3">
                Añadir libro
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