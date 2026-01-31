<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';

$bookModel = new Book($pdo);
$categoryModel = new Category($pdo);

// Filter 
$categoria = $_GET['categoria'] ?? '';
$search = trim($_GET['search'] ?? '');

// Pagination 
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$limit = 5;
$offset = ($page - 1) * $limit;

// Categories 
$categories = $categoryModel->getAllActive();

// Books
if ($categoria !== '') {

    $books = $bookModel->getByCategory((int)$categoria);
    $totalBooks = count($books);
    $totalPages = 1;

} else {

    $totalBooks = (int)$bookModel->count($search);
    $totalPages = ceil($totalBooks / $limit);

    $books = $bookModel->getAll(
        $search,
        'codigo',
        'ASC',
        $limit,
        $offset
    );
}
?>

<!-- Head -->
<?php include __DIR__ . '/../includes/head.php'; ?>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">

        <h2 class="mb-5">Catálogo de libros</h2>

        <!-- Filter  categories -->
        <form method="get" class="mb-4">
            <select name="categoria" class="form-select" onchange="this.form.submit()">
                
                <option value="" <?= $categoria === '' ? 'selected' : '' ?>>
                    Todas las categorías
                </option>
                
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['codigo'] ?>"
                        <?= $categoria == $c['codigo'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Search -->
            <div class="col-md-6 input-group mb-4 mt-2">
                <input
                    type="text" name="search" class="form-control" placeholder="Buscar por título, autor o ISBN" value="<?= htmlspecialchars($search) ?>">
                    
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="books.php" class="btn btn-primary ms-2 w-100">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>

        <!-- List -->
        <?php foreach ($books as $b): ?>
        <div class="card mb-4">
            <div class="row g-0">

                <div class="col-md-3 text-center mt-4 mb-4 ms-2">
                    <?php if ($b['imagen']): ?>
                        <img src="../uploads/<?= htmlspecialchars($b['imagen']) ?>"
                             class="img-fluid">
                    <?php endif; ?>
                </div>

                <div class="col-md-9 mt-4 mb-4">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($b['titulo']) ?></h5>

                        <p><strong>Autor:</strong> <?= htmlspecialchars($b['autor']) ?></p>

                        <p><?= htmlspecialchars($b['descripcion']) ?></p>

                        <!-- book availability -->
                        <?php if ($b['stock'] > 0): ?>
                            <a href="user/create_reservation.php?id=<?= $b['codigo'] ?>"
                               class="btn btn-primary btn-sm">
                                Reservar
                            </a>
                        <?php else: ?>
                            <span class="badge bg-danger">No disponible</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-5">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link"
                            href="?page=<?= $i ?>&categoria=<?= urlencode($categoria) ?>&search=<?= urlencode($search) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>