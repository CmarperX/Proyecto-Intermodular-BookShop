<?php 
// Notification model

class Notification {

    private PDO $pdo;

    // / Constructor receives the PDO connection
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Create a new notification for a user
    public function create(string $codUsuario, string $mensaje) {

        $sql = "INSERT INTO notificaciones (codUsuario, mensaje)
                VALUES (?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $codUsuario,
            $mensaje
        ]);
    }

    // Get all notifications of a user
    public function getUserNotifications(string $codUsuario) {

        $sql = "SELECT * FROM notificaciones
                WHERE codUsuario = ?
                ORDER BY fecha_creacion DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codUsuario]);

        return $stmt->fetchAll();
    }

    // Count unread notifications of a user
    public function countUnread(string $codUsuario) {

        $sql = "SELECT COUNT(*) 
                FROM notificaciones
                WHERE codUsuario = ? AND leida = 'no leida'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codUsuario]);

        return (int) $stmt->fetchColumn();
    }

    // Mark a notificatio as read
    public function markAsRead(int $codigo, string $codUsuario) {

        $sql = "UPDATE notificaciones
                SET leida = 'leida'
                WHERE codigo = ? AND codUsuario = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$codigo, $codUsuario]);
    }

    // Mark all notifications as read for a user
    public function markAllAsRead(string $codUsuario) {
    
        $sql = "UPDATE notificaciones
                SET leida = 'leida'
                WHERE codUsuario = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$codUsuario]);
    }
}