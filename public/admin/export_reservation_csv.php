<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../models/Reserve.php';

// Only admin
if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin'])) {
    header('Location: ../index.php');
    exit;
}

// instantiate reservation model
$reserveModel = new Reserve($pdo);

// Get all bookings
$reserves = $reserveModel->getAll('', 'ASC', 1000, 0); 

// HTTP headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reserves.csv');

// opening an exit for writing
$output = fopen('php://output', 'w');

// CSV header
fputcsv($output, ['Código', 'Usuario', 'Título', 'Fecha', 'Estado']);

// fill out CSV
foreach ($reserves as $r) {

    $titulo = $r['titulo'] ?? '';

    fputcsv($output, [
        $r['codigo'],
        $r['codUsuario'],
        $r['titulo'] ?? '',
        $r['fecha'],
        $r['estado']
    ]);
}

fclose($output);
exit;
?>