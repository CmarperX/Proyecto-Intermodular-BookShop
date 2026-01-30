<?php
// Access control
class Auth {

    public static function login($email, $password, $pdo) {

        // We selected only the fields required for login
        $sql = "SELECT dni, clave, nombre, rol, activo 
                FROM usuarios 
                WHERE email = ? 
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // The entered username does not match the registered users
        if (!$user) {
            return "El usuario introducido es incorrecto.";
        }

        // Check the user's activity status to see if they are active or inactive
        if ($user['activo'] !== 'activo') {
            return "El usuario esta inactivo";
        }

        // The entered key does not match the entered user's key
        if (!password_verify($password, $user['clave'])) {
            return "La clave introducida es incorrecta";
        }

        // Login successful, we saved user data in the session
        session_start();
        $_SESSION['dni'] = $user['dni'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];

        return true;
    }

    // Control by role
    // We check if the user is logged in
    public static function isLoggedIn() {
        return isset($_SESSION['dni']);
    }

    // We checked the user's role
    public static function hasRole($role) {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === $role;
    }

    // We checked several roles
    public static function hasAnyRole(array $roles) {
        return isset($_SESSION['rol']) && in_array($_SESSION['rol'], $roles);
    }

    // we close the session
    public static function logout() {
        session_destroy();
    }
}
?>