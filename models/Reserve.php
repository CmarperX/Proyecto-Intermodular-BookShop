<?php 

// Reserve entity
class Reserve {

    // We declare a private property that stores the connection to the DB
    private PDO $pdo;

    // Constrtuctor receives the connection from the connect function and stores it in the variable $pdo
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // function to create reservation
    public function create(
        string $dni, 
        string $fecha, 
        $horaInicio, 
        $horaFin, 
        string $recurso, 
        int $codRecurso, 
        string $fechaDevolucion
    ) {
        
        $sql = "INSERT INTO reservas 
            (codUsuario, fecha, horaInicio, horaFin, recurso, codRecurso, fechaDevolucion)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $dni, 
            $fecha, 
            $horaInicio, 
            $horaFin, 
            $recurso, 
            $codRecurso, 
            $fechaDevolucion
        ]);
}

    // Finish reservation, return book
    public function finish(int $codigo) {

        // get book reservation
        $sql = "SELECT codRecurso 
                FROM reservas 
                WHERE codigo = ? AND estado != 'finalizada'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigo]);

        $codLibro = $stmt->fetchColumn();

        // Reservation it was already finished or not exists
        if (!$codLibro) {
            return false;
        } 

        // Change status as completed
        $sql = "UPDATE reservas 
                SET estado = 'finalizada' 
                WHERE codigo = ?";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigo]);

        // increase book stock
        $sql = "UPDATE libros 
                SET stock = stock + 1 
                WHERE codigo = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codLibro]);

        return true;
    }

    // get user reservations by DNI
    public function getUserReserves(string $dni, string $search = '') {

        $sql = "SELECT r.*, l.titulo
                FROM reservas r JOIN libros l 
                ON l.codigo = r.codRecurso
                WHERE r.codUsuario = :dni";

        // Array of parameters
        $params = [':dni' => $dni];

        // if we type something into the search engine
        if ($search !== '') {

            // add filter to the SQL 
            $sql .= " AND (r.codigo LIKE :search
                        OR l.titulo LIKE :search)";

            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // refund all reservations
        return $stmt->fetchAll();
    }

    // pagination
    public function count(string $search = '') {

        if ($search) {
            $sql = "SELECT COUNT(*) 
                    FROM reservas r JOIN usuarios u 
                    ON u.dni = r.codUsuario
                    WHERE (
                    r.codigo LIKE :search
                    OR u.nombre LIKE :search
                    OR r.codRecurso LIKE :search)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            $stmt->execute();

        } else {

            $sql = "SELECT COUNT(*) FROM reservas";

            $stmt = $this->pdo->query($sql);
        }

        return (int)$stmt->fetchColumn();
    }

    // Search and paginated reservations for admin
    public function getAll(
        string $search,
        string $order,
        int $limit,
        int $offset
    ) {

        $order = ($order === 'ASC') ? 'ASC' : 'DESC';

        if ($search !== '') {
            $sql = "SELECT r.*, u.nombre, l.titulo
                    FROM reservas r JOIN usuarios u 
                    ON u.dni = r.codUsuario
                    LEFT JOIN libros
                    ON l.codigo = r.codRecurso
                    WHERE (
                        r.codigo LIKE :search
                        OR u.nombre LIKE :search
                        OR l.titulo LIKE :search
                    )
                    ORDER BY r.fecha $order
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);

        } else {
            $sql = "SELECT r.*, u.nombre, l.titulo
                    FROM reservas r JOIN usuarios u 
                    ON u.dni = r.codUsuario
                    LEFT JOIN libros l ON l.codigo = r.codRecurso
                    ORDER BY r.fecha $order
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // update status
    public function updateEstado(int $codigo, string $estado): bool {

        $sql = "UPDATE reservas 
                SET estado = ? 
                WHERE codigo = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$estado, $codigo]);
    }

    // Obtain ID
    public function getById(int $codigo): array|false {

        $sql = "SELECT * FROM reservas 
                WHERE codigo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigo]);

        return $stmt->fetch();
    }

    public function cancel(int $codigoReserva) {

        $reserve = $this->getById($codigoReserva);

        // only allow cancellation if it exists and is in confirmed status
        if (!$reserve || $reserve['estado'] !== 'confirmada') {
            return false;
        }

        // Update status and increase stock
        $sql = "UPDATE reservas 
                SET estado = 'cancelada' 
                WHERE codigo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codigoReserva]);
        
        $sql = "UPDATE libros 
                SET stock = stock + 1 
                WHERE codigo = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$reserve['codRecurso']]);

    }
}