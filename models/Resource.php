<?php

// Use Resource as a generic model for various resource
// This prevents duplication in the code
// It cannot be instantiated directly
class Resource {

    protected PDO $pdo;
    protected string $table;

    // Constrtuctor receives the connection from the connect function and stores it in the variable $pdo
    public function __construct (PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtain a resource by code
    // If it exists returns array
    // If it does not exist, it returns nul
    public function getById(int $codigo) {
        
        $sql = "SELECT * FROM {$this->table}
                WHERE codigo = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigo]);

        // returns array or null
        return $stmt->fetch();
    }

    // ToogleActive 
    public function toggleActive(int $codigo) {
        $sql = "UPDATE {$this->table}
                SET activo = IF(activo= 'activo', 'inactivo', 'activo')
                WHERE codigo = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$codigo]);
    }

    public function count(string $search) {
        $sql = "SELECT COUNT(*) FROM {$this->table}
                WHERE titulo LIKE :search";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);
        return (int)$stmt->fetchColumn();
    }
        
}