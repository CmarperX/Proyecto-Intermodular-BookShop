<?php

// specific book model
require_once 'Resource.php';

class Book extends Resource {

    protected string $table = 'libros';

    // list with search, order and pagination - no category
    public function getAll(
        string $search,
        string $column,
        string $order,
        int $limit,
        int $offset

    ) {

        // prevent SQL injection
        $columnsBook = ['codigo', 'titulo', 'autor', 'isbn', 'stock', 'activo'];
        if (!in_array($column, $columnsBook)) {
            $column = 'codigo';

        }

        $order = ($order === 'DESC') ? 'DESC' : 'ASC';

        $sql = "SELECT * from libros
                WHERE titulo LIKE :search
                    or autor LIKE :search
                    or isbn LIKE :search
                ORDER BY $column $order
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // count the books according to the search
    public function count(string $search) {
        $sql = "SELECT COUNT(*) FROM libros
                WHERE titulo LIKE :search
                   OR autor LIKE :search
                   OR isbn LIKE :search";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);

        return (int)$stmt->fetchColumn();
    }

    // Add new book
    public function create(array $data) {

        $sql = "INSERT INTO libros
                (titulo, isbn, autor, editorial, descripcion, stock, codCategoria, imagen)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['isbn'],
            $data['autor'],
            $data['editorial'],
            $data['descripcion'],
            $data['stock'],
            $data['codCategoria'],
            $data['imagen']
        ]);
    }

    // Update book
    public function update(int $codigo, array $data) {
        $sql = "UPDATE libros
                SET titulo=?, isbn=?, autor=?, editorial=?, descripcion=?, stock=?, codCategoria=?, imagen=?
                WHERE codigo=?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['isbn'],
            $data['autor'],
            $data['editorial'],
            $data['descripcion'],
            $data['stock'],
            $data['codCategoria'],
            $data['imagen'],
            $codigo
        ]);
    }

    // Decrease Stock
    public function decreaseStock(int $codigo) {
        
        $sql = "UPDATE libros SET stock = stock - 1 WHERE codigo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigo]);
    }

    // Show only active books
    public function getAllActive() {
        $sql = "SELECT l.*, c.nombre AS categoria
                FROM libros l
                LEFT JOIN categorias c ON c.codigo = l.codCategoria
                WHERE l.activo = 'activo'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // filtered by categories
    public function getByCategory(int $codCategoria) {

        $sql = "SELECT l.*, c.nombre AS categoria
            FROM libros l
            JOIN categorias c ON c.codigo = l.codCategoria
            WHERE l.activo = 'activo'
            AND l.codCategoria = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codCategoria]);
        return $stmt->fetchAll();
    }

    // get all categories
    public function getCategories() {

        $sql = "SELECT codigo, nombre 
                FROM categorias 
                ORDER BY nombre ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
}
}
?>