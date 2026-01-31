<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Book.php';

// Only admin or empleado
if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin', 'empleado'])) {
    header('Location: ../index.php');
    exit;
}

// Model instantiation
$bookModel = new Book($pdo);

// Obtain categories
$categorias = $bookModel->getCategories();

// Obtain ID
$codigo = (int)($_GET['id'] ?? 0);

// Fetch book data
$book = $bookModel->getById($codigo);

// If book does not exist, redirect
if (!$book) {
    header('Location: books.php');
    exit;
}

$error = null;

// Update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'titulo' => Validation::sanitize($_POST['titulo'] ?? ''),
        'isbn' => Validation::sanitize($_POST['isbn'] ?? ''),
        'autor' => Validation::sanitize($_POST['autor'] ?? ''),
        'editorial' => Validation::sanitize($_POST['editorial'] ?? ''),
        'descripcion' => Validation::sanitize($_POST['descripcion'] ?? ''),
        'stock' => (int)($_POST['stock'] ?? 0),
        'imagen' => $book['imagen']
    ];

    // validation
    if (
        !Validation::text($data['titulo']) ||
        !preg_match('/^[0-9]{13}$/', $data['isbn']) ||
        !Validation::text($data['autor']) ||
        !Validation::text($data['editorial']) ||
        !Validation::text($data['descripcion']) ||
        $data['stock'] < 0
    ) {
        $error = "Uno de los datos introducidos no es válido";

    } else {
        // Update book
        if (isset($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

            $allowed = ['jpg','jpeg','png'];
            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Formato de imagen no permitido";

            } else {
                $imageName = uniqid('book_', true) . '.' . $ext;
                $pathImage = __DIR__ . '/../../uploads/' . $imageName;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $pathImage)) {
                    $data['imagen'] = $imageName;
                    
                } else {
                    $error = "Error al subir la imagen";
                }
            }
        }

        if (!$error && $bookModel->update($codigo, $data)) {
            header('Location: books.php');
            exit;
        }
    }
}
?>

<!-- HEAD -->
<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Editar libro</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">

            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control mb-2" value="<?= htmlspecialchars($book['titulo']) ?>">

            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control mb-2" value="<?= htmlspecialchars($book['isbn']) ?>">

            <label class="form-label">Autor</label>
            <input type="text" name="autor" class="form-control mb-2" value="<?= htmlspecialchars($book['autor']) ?>">

            <label class="form-label">Editorial</label>
            <input type="text" name="editorial" class="form-control mb-2" value="<?= htmlspecialchars($book['editorial']) ?>">

            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control mb-3"><?= htmlspecialchars($book['descripcion']) ?></textarea>

            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control mb-2" min="0" value="<?= (int)$book['stock'] ?>">

            <label for="codCategoria">Categoría:</label>
            <select name="codCategoria" class="form-select mb-3">
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['codigo'] ?>"
                        <?= ($book['codCategoria'] == $cat['codigo']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="imagen" class="mb-3 mt-3">Portada actual:</label>
            <?php if ($book['imagen']): ?>
                <div class="mb-2 text-center">
                    <img src="<?= '../../uploads/' . htmlspecialchars($book['imagen']) ?>" alt="Portada" width="120">
                </div>
            <?php endif; ?>

            <label for="imagen" class="mb-3 mt-3">Cambiar portada:</label>
            <input type="file" name="imagen" class="form-control mb-3" accept="image/*">
            
            <div class="mt-5">
                <button class="btn btn-primary">Guardar cambios</button>
                <a href="books.php" class="btn btn-primary">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
