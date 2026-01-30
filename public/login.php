<?php 
require_once __DIR__ . '/../includes/config.php';

// If we are already logged in, it returns us to the page
if (Auth::isLoggedIn()) {
  header('Location: index.php');
  exit;
}

$error = null;

// Form processing with POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Sanitize 
  $email = Validation::sanitize($_POST['email'] ?? '');
  $clave = $_POST['clave'] ?? '';

  // Validations 

  if (!Validation::email($email) || empty($clave)) {
      $error = "El email o contraseña no son correctos";

  } else {
    $result = Auth::login($email, $clave, $pdo);

    if ($result === true) {
      header('Location: index.php');
      exit;

    } else {
      $error = $result;
    }
  }      
}
?>

<?php include __DIR__ . '/../includes/head.php'; ?>

<body data-shorcut-listen="true">
    <!-- NAVBAR-->
    <?php include PROJECT_ROOT . '/includes/navbar.php';?>
    <!-- DIVIDER -->
    <div class="divider"></div>
    <!-- FORM LOGIN-->
    <div class="container d-flex justify-content-center align-items-center"> 
      <div class="shadow-sm p-4">
        <h3 class="text-center mb-3">Login</h3>
        <form action="login.php" method="post">
          <!-- Mostramos errores en el formulario con un alert-->
          <?php if ($error): ?>
            <div class="alert alert-danger mb-3">
              <?= $error ?>
            </div>
          <?php endif; ?>
          <div class="mb-3">
            <label for="email" class="form-label mb-2">Email:</label>
            <input type="email" class="form-control" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          </div>
          
          <label for="clave" class="form-label mb-2">Contraseña:</label>
          <div class="input-group">
            <input class="form-control" name="clave" type="password" placeholder="Nueva contraseña" id="passwordInput" autocomplete="new-password" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" width="28" height="28" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
              </svg>    
            </button>
          </div>
          <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember" id="checkDefault"/>
            <label class="form-check-label" for="checkDefault">
              Recuérdame
            </label>
          </div>
          <button type="submit" class="btn btn-primary w-100 py-2" style="text-decoration: none; color: white;">
            Iniciar sesión
          </button>
        </form>
        <a class="btn btn-primary w-100 my-2 py-2" href="register.php" style="text-decoration: none; color: white;">
            Registrarse
        </a>
        <div class="form-check text-center my-3">
            <a href="forget-password.php" style="text-decoration: none; color:var(--primary-color)">
            ¿Has olvidado tu contraseña?
            </a>
        </div>
      </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php';?>
    <script src="../public/assets/js/eyePassword.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>