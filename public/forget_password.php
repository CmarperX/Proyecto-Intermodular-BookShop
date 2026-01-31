<?php
require_once __DIR__ . '/../includes/config.php';

// Check if user is logged in
if (Auth::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = null;
$success = null;

// Processed the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize
    $email = Validation::sanitize($_POST['email'] ?? '');

    // Validate
    if (!Validation::email($email)) {
        $error = "Introduce un email válido.";
    } else {

        // Searched for the user by email
        $sql = "SELECT dni 
                FROM usuarios 
                WHERE email = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {

            // Generate a random temporary password
            $newPassword = substr(bin2hex(random_bytes(6)), 0, 8);

            // Encrypted the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Overwrote the password in the database
            $sql = "UPDATE usuarios 
                    SET clave = ? 
                    WHERE dni = ? ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hashedPassword, $user['dni']]);

            $success = "Se ha generado una nueva contraseña.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/head.php'; ?>

<body>
    <!-- NAVBAR -->
    <?php include PROJECT_ROOT . '/includes/navbar.php'; ?>

    <!-- DIVIDER -->
    <div class="divider"></div>

    <!-- FORMULARIO -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="shadow-sm p-4" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-3">Recuperar contraseña</h3>

            <!-- Error messages-->
            <?php if ($error): ?>
                <div class="alert alert-danger mb-3">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Success messages -->
            <?php if ($success): ?>
                <div class="alert alert-success mb-3">
                    <?= $success ?>
                </div>

                <!-- New password-->
                 <?php if ($newPassword): ?>
                    <div class="alert alert-warning text-center">
                        <strong>Nueva contraseña:</strong><br>
                        <code><?= $newPassword ?></code>
                    </div>
                <?php endif; ?>

                <div class="text-center">
                    <a href="login.php" class="btn btn-primary">Ir al login</a>
                </div>
            <?php else: ?>

            <!-- Formulario -->
            <form action="forget_password.php" method="post">
                <div class="mb-3">
                    <label class="form-label mb-2">Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="Introduce tu email" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    Generar nueva contraseña
                </button>
                <a href="login.php" class="btn btn-primary w-100 py-2 mt-2">Ir al login</a>
            </form>

            <?php endif; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>