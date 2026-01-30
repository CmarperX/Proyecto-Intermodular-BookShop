<?php

class Validation {
    
    // Validation form
    // dni validation
     public static function dni(string $dni) {

        $dni = strtoupper(trim($dni));

        // DNI format
        if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            return false;
        }

        $number = (int) substr($dni, 0, 8);
        $letter = substr($dni, -1);

        $letters = "TRWAGMYFPDXBNJZSQVHLCKE";

        return $letter === $letters[$number % 23];
    }

    // email validation
    public static function email(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // password validation
    public static function password(string $password) {
        return strlen($password) >= 8;
    }

    // phone validation
    public static function telefono(string $telefono) {
        return preg_match('/^[0-9]{9}$/', $telefono);
    }

    // text validation
    public static function text(string $text) {
        return trim($text) !== '';
    }

    // Clean up leftover space and prevent XSS (Cross-Site Scripting) attacks 
    public static function sanitize($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    // Validate numbers in PHP for id, quantities, stock...
    public static function int($value) {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}

?>
