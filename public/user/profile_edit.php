<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/User.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// model instantiation
$userModel = new User($pdo);

$dni = $_SESSION['dni'];
$user = $userModel->getByDni($dni);

if (!$user) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'nombre' => Validation::sanitize($_POST['nombre'] ?? ''),
        'apellido' => Validation::sanitize($_POST['apellido'] ?? ''),
        'direccion' => Validation::sanitize($_POST['direccion'] ?? ''),
        'telefono' => Validation::sanitize($_POST['telefono'] ?? '')
    ];

    if (
        !Validation::text($data['nombre']) ||
        !Validation::text($data['apellido']) ||
        !Validation::text($data['direccion']) ||
        !Validation::telefono($data['telefono'])
    ) {
        $error = "Alguno de los datos introducidos no es válido";

    } else {

        $userModel->updateUserData($dni, $data);

        // Cambio de contraseña (opcional)
        if (!empty($_POST['clave'])) {

            if (!Validation::password($_POST['clave'])) {
                $error = "La contraseña debe tener al menos 8 caracteres";
            } else {
                $userModel->updatePassword($dni, $_POST['clave']);
            }
        }

        if (!$error) {
            $success = "Perfil actualizado correctamente";
            $user = $userModel->getByDni($dni);
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
    <h2 class="mb-5">Editar perfil</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="nombre">Nombre:</label>
        <input class="form-control mb-3" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>">
        
        <label for="apellido">Apellido:</label>
        <input class="form-control mb-3" name="apellido" value="<?= htmlspecialchars($user['apellido']) ?>">
        
        <label for="direccion">Dirección:</label>
        <input class="form-control mb-3" name="direccion" value="<?= htmlspecialchars($user['direccion']) ?>">
        
        <label for="telefono">Teléfono:</label>
        <input class="form-control mb-3" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>">
        
        <label for="clave">Nueva contraseña:</label>
        <div class="input-group mb-4">
            <input class="form-control" name="clave" type="password" minlength="8" placeholder="Nueva contraseña" id="passwordInput">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" width="28" height="28" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                </svg>
            </button>
        </div>

        <div class="mt-5">
            <button class="btn btn-primary">Guardar</button>
            <a href="dashboard.php" class="btn btn-primary">Cancelar</a>
        </div>
    </form>
</div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="../assets/js/eyePassword.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
