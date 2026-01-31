<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../models/Book.php';

// Only admin or empleado
if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin','empleado'])) {
    header('Location: ../index.php');
    exit;
}

// Model instantiation
$bookModel = new Book($pdo);

// Obtain categories
$categorias = $bookModel->getCategories();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'titulo' => Validation::sanitize($_POST['titulo'] ?? ''),
        'isbn' => Validation::sanitize($_POST['isbn'] ?? ''),
        'autor' => Validation::sanitize($_POST['autor'] ?? ''),
        'editorial' => Validation::sanitize($_POST['editorial'] ?? ''),
        'descripcion' => Validation::sanitize($_POST['descripcion'] ?? ''),
        'stock' => (int)($_POST['stock'] ?? 0),
        'codCategoria' => Validation::sanitize($_POST['codCategoria'] ?? ''),
        'imagen' => null
    ];

    if (
        !Validation::text($data['titulo']) ||
        !preg_match('/^[0-9]{13}$/', $data['isbn']) ||
        !Validation::text($data['autor']) ||
        !Validation::text($data['editorial']) ||
        !Validation::text($data['descripcion']) ||
        $data['stock'] < 0 ||
        !Validation::text($data['codCategoria'])
    ) {
        $error = "Uno de los datos introducidos no es válido";

    } else {

        // Check if the user has selected a file
        if (!empty($_FILES['imagen']['name'])) {

            // Allowed image extensions
            $allowed = ['jpg','jpeg','png'];
            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Formato de imagen no permitido";

            } else {

                // Path where we will save the image
                $uploadDir = __DIR__ . '/../../uploads/';

                // generate a name for the image, preventing two images from having the same name.
                $imageName = uniqid('book_', true) . '.' . $ext;

                // full path where the image is saved
                $pathImage = $uploadDir . $imageName;

                // move the temporary file to the uploads folder
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $pathImage)) {
                    $error = "Error al subir la imagen";

                } else {
                    $data['imagen'] = $imageName;
                }
            }
        }

        if (!$error && $bookModel->create($data)) {
            header('Location: books.php');
            exit;
        } 
    }
}
?>
<!-- Head -->
<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Añadir libro</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control mb-2" placeholder="Título">

            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" class="form-control mb-2" placeholder="ISBN">

            <label for="autor">Autor:</label>
            <input type="text" name="autor" class="form-control mb-2" placeholder="Autor">

            <label for="editorial">Editorial:</label>
            <input type="text" name="editorial" class="form-control mb-2" placeholder="Editorial">

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control mb-3" placeholder="Descripción"></textarea>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" class="form-control mb-2" min="0" placeholder="Stock">
        
            <label for="codCategoria">Categoría:</label>
            <select name="codCategoria" class="form-select mb-3">
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['codigo'] ?>"
                        <?= (isset($data['codCategoria']) && $data['codCategoria'] == $cat['codigo']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="imagen">Portada:</label>
            <input type="file" name="imagen" class="form-control mb-3" accept="image/*">

            <div class="mt-5">
                <button class="btn btn-primary">Guardar</button>
                <a href="books.php" class="btn btn-primary">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>