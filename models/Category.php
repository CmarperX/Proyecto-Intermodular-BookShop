<?php
class Category {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllActive() {
       
        $sql = "SELECT * FROM categorias WHERE activo = 'activo'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll() {

        $sql = "SELECT * FROM categorias";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(string $nombre) {

        $sql = "INSERT INTO categorias (nombre) VALUES (?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre]);
    }

    public function toggle(int $codigo) {

        $sql = "UPDATE categorias
                SET activo = IF(activo='activo','inactivo','activo')
                WHERE codigo = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$codigo]);
    }

    public function update(int $codigo, string $nombre) {

        $sql = "UPDATE categorias SET nombre = ? WHERE codigo = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $codigo]);
    }

    public function getById(int $codigo) {

        $sql = "SELECT * FROM categorias WHERE codigo = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigo]);
        return $stmt->fetch();
    }
}