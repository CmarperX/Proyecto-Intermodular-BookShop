<?php
// I enter dirname(__DIR__) to go to the project root 
// C:\xampp\htdocs\savia_nexus\public
define('PROJECT_ROOT', dirname(__DIR__));

// Control the sessions
// We save dni, name and role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// I check if we are on localhost or in production
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require 'config.local.php';
} else {
    require 'config.production.php';
}

// Connect to the database
require_once 'connect_db.php';
$pdo = connect();

// Load validation and authentication libraries
require_once 'Auth.php';
require_once 'Validation.php';
?>