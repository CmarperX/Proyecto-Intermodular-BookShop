<?php
// User registration
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../models/User.php';

// If we are already logged in, it returns us to the page
if (Auth::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// We instantiate the user model
$userModel = new User($pdo);

$error = null;
$success = null;

// Form processing with POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize everything except the password
    $data = [
        $dni = Validation::sanitize($_POST['dni'] ?? ''),
        $nombre = Validation::sanitize($_POST['nombre'] ?? ''),
        $apellido = Validation::sanitize($_POST['apellido'] ?? ''),
        $direccion = Validation::sanitize($_POST['direccion'] ?? ''),
        $email = Validation::sanitize($_POST['email'] ?? ''),
        $telefono = Validation::sanitize($_POST['telefono'] ?? ''),
        $clave = $_POST['clave'] ?? '',
        'rol' => 'cliente'
    ];
    

    // Validations

    if (
        !Validation::dni($data['dni']) ||
        !Validation::text($data['nombre']) ||
        !Validation::text($data['apellido']) ||
        !Validation::text($data['direccion']) ||
        !Validation::email($data['email']) ||
        !Validation::telefono($data['telefono']) ||
        !Validation::password($data['clave'])
    ) {
        $error = "Uno de los datos introducidos no es válido";

    } elseif ($userModel->emailExists($email)) {
        $error = "El email ya está registrado";

    } else {

        // Create user
        $createdUser = $userModel->create($data);

        if ($createdUser) {
            $success = "Usuario registrado correctamente.";

        } else {
            $error = "Error al registrar al usuario";
        }
    }
}
?>
<!-- Head -->
<?php include __DIR__ . '/../includes/head.php'; ?>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Registro</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <label for="dni">DNI:</label>
            <input type="text" name="dni" class="form-control mb-3" pattern="[0-9]{8}[A-Za-z]" title="Debe tener 8 números y una letra al final" placeholder="DNI" required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" class="form-control mb-3" placeholder="Apellido" required>
            
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" class="form-control mb-3" placeholder="Dirección" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" autocomplete="none" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" class="form-control mb-3" pattern="[0-9]{9}" title="Debe tener 9 números" placeholder="Teléfono" required>

            <div class="input-group">
                <input class="form-control" name="clave" type="password" minlength="8" placeholder="Nueva contraseña" id="passwordInput" autocomplete="new-password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" width="28" height="28" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                    </svg>    
                </button>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Registrarse</button>
                <a href="login.php" class="btn btn-primary">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="../public/assets/js/eyePassword.js"></script>
    <script src="cdn.jsdelivr.net" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>