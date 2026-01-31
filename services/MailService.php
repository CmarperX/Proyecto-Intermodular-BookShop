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
    ): bool {

        $mail = new PHPMailer(true);

        try {
            // SMTP config
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->Port       = SMTP_PORT;
            $mail->SMTPSecure = SMTP_SECURE === 'tls'
                ? PHPMailer::ENCRYPTION_STARTTLS
                : PHPMailer::ENCRYPTION_SMTPS;

            // Sender & recipient
            $mail->setFrom(SMTP_USER, 'Savia Nexus');
            $mail->addAddress($email, $nombre);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de reserva';
            $mail->Body = "
                <h3>Reserva confirmada</h3>
                <p><strong>Libro:</strong> {$libro}</p>
                <p><strong>Recogida:</strong> {$fechaInicio}</p>
                <p><strong>Devolución:</strong> {$fechaFin}</p>
                <p>Gracias por confiar en <strong>Savia Nexus</strong></p>
            ";

            return $mail->send();

        } catch (Exception $e) {
            return false;
        }
    }
}