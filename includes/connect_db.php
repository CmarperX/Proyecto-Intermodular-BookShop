<?php
function connect() {

    try {
        // Data source name: indicates which database to connect to and which driver to use
        // motor: mysql
        // host: DB_HOST
        // DB name: DB_NAME
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        // Create PDO connection
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        // Configure error mode for exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // In production, internal messages should be hidden to avoid exposing sensitive information
        // die("Error de conexión");
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
}    
?>