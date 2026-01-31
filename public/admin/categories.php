<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Category.php';

if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin', 'empleado'])) {
    header('Location: ../index.php');
    exit;
}

$categoryModel = new Category($pdo);
$categories = $categoryModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['crear'])) {
        $nombre = Validation::sanitize($_POST['nombre']);
        if ($nombre) {
            $categoryModel->create($nombre);
        }
    }

    if (isset($_POST['toggle'])) {
        $categoryModel->toggle((int)$_POST['codigo']);
    }

    if (isset($_POST['editar'])) {
        $categoryModel->update(
            (int)$_POST['codigo'],
            Validation::sanitize($_POST['nombre'])
        );
    }

    header('Location: categories.php');
    exit;
}
?>

<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>

    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">

        <h2 class="mb-5">Categorías</h2>

        <form method="post" class="mb-4 d-flex gap-2">
            <input class="form-control" name="nombre" placeholder="Nueva categoría" required>
            <button name="crear" class="btn btn-primary">Añadir</button>
        </form>

        <table class="table table-striped">
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($categories as $c): ?>
                <tr>
                    <td>
                        <form method="post" class="d-flex gap-2">
                            <input type="hidden" name="codigo" value="<?= $c['codigo'] ?>">
                            <input class="form-control form-control-sm"
                                name="nombre"
                                value="<?= htmlspecialchars($c['nombre']) ?>">
                            <button name="editar" class="btn btn-sm btn btn-primary">Guardar</button>
                        </form>
                    </td>
                    <td><?= $c['activo'] ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="codigo" value="<?= $c['codigo'] ?>">
                            <button name="toggle" class="btn btn-sm btn-warning">
                                Activar / Desactivar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="mt-5">
            <a href="dashboard.php" class="btn btn-primary">Volver</a>
        </div>
        
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>            
</body>
</html>