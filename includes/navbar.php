<?php
// indicates the number of notifications the user has in the notifications icon
$unreadNotifications = 0;

if (Auth::isLoggedIn()) {
    require_once __DIR__ . '/../models/Notification.php';
    $notificationModel = new Notification($pdo);
    $unreadNotifications = $notificationModel->countUnread($_SESSION['dni']);
}
?>
<!-- Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="<?= BASE_URL ?>/public/index.php">
            <img src="<?= BASE_URL ?>/public/assets/images/logo-savia-nexus.png" alt="logo savia nexus" height="140">
        </a>

        <!-- Burger menu + user login + notification -->
        <div class="d-flex align-items-center gap-3 order-lg-3 ms-lg-auto">

            <!-- hamburger button -->
            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarDropdown"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- User dropdown menu -->
            <div class="dropdown">
                <button class="nav-link text-white p-0 dropdown-toggle bg-transparent border-0" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        type="button">

                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37
                            C3.242 11.226 4.805 10 8 10
                            s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <ul class="dropdown-menu dropdown-menu-end">

                    <?php if (Auth::isLoggedIn()): ?>
                        <!-- If we are logged in, this menu will appear when we click on the user icon.
                        It will give us the option of Administration Panel or My Profile, depending on our active role-->
                        <li>
                            <p class="dropdown-item">Bienvenido, <?=htmlspecialchars($_SESSION['nombre']) ?></p>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <!-- If it's an admin or employee, an administration panel is opened that only those roles can access. -->
                        <?php if (Auth::hasAnyRole(['admin', 'empleado'])): ?>
                            <li>    
                                <a class="dropdown-item" href="<?= BASE_URL ?>/public/admin/dashboard.php">
                                    Panel de administración
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/public/user/dashboard.php">
                                Panel de usuario
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/includes/logout.php">Cerrar sesión</a>
                        </li>
                    <?php else: ?>
                    <!-- When we are not logged in, only the login option will appear. -->
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/public/login.php">Iniciar sesión</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Notifications -->
            <a class="nav-link text-white p-0 position-relative d-flex align-items-center gap-2" 
                href="<?= BASE_URL ?>/public/user/notifications.php">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                    </svg>

                    <?php if ($unreadNotifications > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle
                                    badge rounded-pill bg-danger"
                            style="font-size: 0.65rem;">
                            <?= $unreadNotifications ?>
                        </span>
                    <?php endif; ?>
                </div>

            </a>
        </div>

        <!-- Hamburger menu, navigation links -->
        <div class="collapse navbar-collapse order-lg-1 text-center" id="navbarDropdown">
            <ul class="navbar-nav mx-lg-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/books.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>about-us.php">Sobre nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact.php">Contacto</a></li>
            </ul>
        </div>
    </div>
</nav>