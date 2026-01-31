<?php
// list user data

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/User.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// model instantiation
$userModel = new User($pdo);
$user = $userModel->getByDni($_SESSION['dni']);

if (!$user) {
    die('Usuario no encontrado');
}
?>

<?php include __DIR__ . '/../../includes/head.php'; ?>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Mis datos</h2>

        <table class="table mt-4">
            <tr><th>DNI</th><td><?= htmlspecialchars($user['dni']) ?></td></tr>
            <tr><th>Nombre</th><td><?= htmlspecialchars($user['nombre']) ?></td></tr>
            <tr><th>Apellido</th><td><?= htmlspecialchars($user['apellido']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
            <tr><th>Teléfono</th><td><?= htmlspecialchars($user['telefono']) ?></td></tr>
            <tr><th>Dirección</th><td><?= htmlspecialchars($user['direccion']) ?></td></tr>
        </table>
        <div class="mt-5">
            <a href="dashboard.php" class="btn btn-primary">Volver</a>
        </div>
        
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
