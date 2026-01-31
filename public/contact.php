<?php 
    require_once __DIR__ . '/../includes/config.php';

    $errores = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre  = $_POST['full-name'] ?? '';
        $email   = $_POST['email'] ?? '';
        $asunto  = $_POST['matter'] ?? '';
        $mensaje = $_POST['message'] ?? '';

        // Validation
        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Email no válido';
        }

        if ($asunto === '') {
            $errores[] = 'El asunto es obligatorio';
        }

        if ($mensaje === '') {
            $errores[] = 'El mensaje es obligatorio';
        }

        if (strlen($mensaje) > 500) {
            $errores[] = 'El mensaje es demasiado largo';
        }

        if (empty($errores)) {

            $texto  = "--------------------------\n";
            $texto .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
            $texto .= "Nombre: $nombre\n";
            $texto .= "Email: $email\n";
            $texto .= "Asunto: $asunto\n";
            $texto .= "Mensaje:\n$mensaje\n\n";

            // created a text message
            file_put_contents(__DIR__ . '/contactos.txt', $texto, FILE_APPEND);

            header("Location: index.php?contact=ok");
            exit;
        }
    }

    include __DIR__ . '/../includes/head.php';
?>
<body data-shorcut-listen="true">
    <?php include __DIR__ . '/../includes/navbar.php';?>
    <div class="divider"></div>

    <!-- Contacto -->
    <div class="container d-flex justify-content-center align-items-center"> 
        <div class="shadow-sm p-4">
            <h3 class="text-center mb-3">Contáctanos</h3>
            
            <!-- Errores -->
            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="contact.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" name="full-name"
                        value="<?= htmlspecialchars($nombre ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email"
                        value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Asunto</label>
                    <input type="text" class="form-control" name="matter"
                        value="<?= htmlspecialchars($asunto ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mensaje</label>
                    <textarea class="form-control" name="message" rows="5" required><?= htmlspecialchars($mensaje ?? '') ?></textarea>
                </div>

                <button class="btn btn-primary w-100 my-2 py-2" type="submit">
                    Enviar mensaje
                </button>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php';?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>