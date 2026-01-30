<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MailService {

    public static function sendReservationConfirmation(
        string $email,
        string $nombre,
        string $libro,
        string $fechaInicio,
        string $fechaFin
    ) {

        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP (ejemplo Gmail)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tucorreo@gmail.com';
            $mail->Password = 'CLAVE_DE_APP';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom('noreply@savianexus.com', 'Savia Nexus');
            $mail->addAddress($email, $nombre);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de reserva';
            $mail->Body = "
                <h3>Reserva confirmada</h3>
                <p><strong>Libro:</strong> $libro</p>
                <p><strong>Recogida:</strong> $fechaInicio</p>
                <p><strong>Devolución:</strong> $fechaFin</p>
                <p>Gracias por usar Savia Nexus</p>
            ";

            return $mail->send();

        } catch (Exception $e) {
            return false;
        }
    }
}