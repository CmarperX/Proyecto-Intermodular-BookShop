<?php
// 
class User {

    private PDO $pdo;

    // received a PDO connection and saved
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // READ - User list and search
    public function getAllUsers(
        string $search,
        string $column,
        string $order,
        int $limit,
        int $offset
    ) : array {

        $sql = "SELECT dni, nombre, email, rol, activo
                FROM usuarios
                WHERE dni LIKE :search
                OR nombre LIKE :search
                OR email LIKE :search
                ORDER BY $column $order
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);

        // Search --> We are looking for the text entered by the user %(text entered)%
        $stmt->bindValue(':search', "%$search%");

        // Limit and offset 
        // The limit will always be 5, while the offset increases by 5 for each page
        // PARAM_INT because they must be integers
        // maximum number of rows returned by the query
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        // return results from the record depending on the page we are on
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        // PDO::FETCH_ASSOC -> We prevent access by numerical index
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // counts users depending on the search, return a number
    public function count(string $search) {

        $sql = "SELECT COUNT(*) FROM usuarios
                WHERE dni LIKE :search
                OR nombre LIKE :search
                OR email LIKE :search";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);

        return (int) $stmt->fetchColumn();
    }

    // Obtain users with search, sorting, and pagination
    public function getByDni(string $dni) {

        $sql = "SELECT dni, nombre, apellido, direccion, email, telefono, rol, activo 
                FROM usuarios
                WHERE dni = ?";

        $stmt = $this->pdo->prepare($sql);

        // receive the data we selected from the SQL query
        $stmt->execute([$dni]);
        return $stmt->fetch();
    }

    // Get user by dni only 1 similar dni
    public function dniExists(string $dni): bool {

        $sql = "SELECT COUNT(*) 
                FROM usuarios 
                WHERE dni = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dni]);

        return (int)$stmt->fetchColumn() > 0;
    }

    // Get user by email only 1 similar email
    public function emailExists(string $email) {

        $sql = "SELECT COUNT(*) FROM usuarios
                WHERE email = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        return (int)$stmt->fetchColumn() > 0;
    }

    // CREATE - Create new user
    public function create(array $data) {

        // We hashed the password 
        $hash = password_hash($data['clave'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios
                (dni, nombre, apellido, direccion, email, telefono, clave, rol)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->pdo->prepare($sql)->execute([
            $data['dni'],
            $data['nombre'],
            $data['apellido'],
            $data['direccion'],
            $data['email'],
            $data['telefono'],
            $hash,
            $data['rol']
        ]);
    }

    // UPDATE - Update user
    public function updateUserData(string $dni, array $data) {

        $sql = "UPDATE usuarios
                SET nombre = ?, apellido = ?, direccion = ?, telefono = ?
                WHERE dni = ?";

        return $this->pdo->prepare($sql)->execute([
            $data['nombre'],
            $data['apellido'],
            $data['direccion'],
            $data['telefono'],
            $dni
        ]);
    }

    // Change password
    public function updatePassword(string $dni, string $clave) {

        $hash = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios
                SET clave = ?
                WHERE dni = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$hash, $dni]);
    }

    // Change role 
    public function changeRole(string $dni, string $rol) {

        $roles = ['cliente', 'empleado', 'admin'];

        // check if the value is within the array
        if (!in_array($rol, $roles)) {
            return false;
        }

        $sql = "UPDATE usuarios 
                SET rol = ? 
                WHERE dni = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$rol, $dni]);
    }

    // DELETE - change the user's status, active or inactive
    public function toggleActive(string $dni) {

        $sql = "UPDATE usuarios
                SET activo = IF(activo='activo','inactivo','activo')
                WHERE dni = ?";

        // $activo = $activo == 'activo' ? 'inactivo' : 'activo'
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$dni]);
    }
}