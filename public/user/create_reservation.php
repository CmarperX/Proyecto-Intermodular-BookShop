<?php 
// Create a new reservation

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Reserve.php';
require_once __DIR__ . '/../../models/Book.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Notification.php';
require_once __DIR__ . '/../../services/MailService.php';

// Only logged-in customers can access
if (!Auth::isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// instantiate models
$bookModel = new Book($pdo);
$reserveModel = new Reserve($pdo);
$userModel = new User($pdo);
$notificationModel = new Notification($pdo);

$codLibro = (int)($_GET['id'] ?? 0);
$book = $bookModel->getById($codLibro);

if (!$book || $book['stock'] <= 0) {
    header('Location: ../books.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fechaInicio = $_POST['fecha_inicio'] ?? '';
    $fechaFin = $_POST['fecha_fin'] ?? '';

    // Validation
    if (!$fechaInicio || !$fechaFin) {
        $error = "Debes seleccionar una fecha válida";

    } else {

        $reserveModel->create(
            $_SESSION['dni'],
            $fechaInicio,
            null,
            null,
            'libro',
            $codLibro,
            $fechaFin
        );

        $bookModel->decreaseStock($codLibro);

        $mensaje = "Reserva confirmada del libro '{$book['titulo']}'";

        $notificationModel->create($_SESSION['dni'], $mensaje);

        $user = $userModel->getByDni($_SESSION['dni']);

        MailService::sendReservationConfirmation(
            $user['email'],
            $user['nombre'],
            $book['titulo'],
            $fechaInicio,
            $fechaFin
        );

        $success = "Reserva realizada correctamente";
    }
}
?>

<!-- Head -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savia Nexus</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>/public/assets/images/favicon-savia-nexus.ico" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/calendar.css">
    
</head>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="container mt-5 mb-5">
        <h2 class="mb-5">Nueva reserva</h2>

        <p><strong>Libro:</strong> <?= htmlspecialchars($book['titulo']) ?></p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post">

            <!-- Calendar -->
            <div id="controles" class="mb-3">
                <button type="button" id="prev">⬅</button>
                <select id="mes"></select>
                <select id="year"></select>
                <button type="button" id="next">➡</button>
            </div>
            
            <table id="calendario" class="mb-4"></table>

            <!-- Start date --> 
            <input type="hidden" name="fecha_inicio" id="fecha_inicio">
            
            <!-- End date -->
            <input type="hidden" name="fecha_fin" id="fecha_fin">

            <p id="infoFechas" class="mt-3 text-primary"></p>

            <!-- Reserve -->
            <div class="mt-5">
                <button class="btn btn-primary mt-5">Confirmar reserva</button>
                <a href="../books.php" class="btn btn-primary mt-5">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <!-- JS -->
    <script src="../assets/js/calendar/calendar.js"></script>
    <script src="../assets/js/calendar/selection.js"></script>
</body>
</html>