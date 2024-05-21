<?php
require_once '/opt/lampp/htdocs/bookworm/backend/config/dbconn.php';

class BookSearch {
    private $conn;
    private $itemsPerPage;

    public function __construct($dbConnection, $itemsPerPage = 8) {
        $this->conn = $dbConnection;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function searchBooks($query, $page) {
        try {
            $offset = ($page - 1) * $this->itemsPerPage;

            // Prepare the SQL statement with placeholders
            $sql = "SELECT SQL_CALC_FOUND_ROWS Books.* 
             FROM Books 
            JOIN Author ON Books.author_id = Author.id 
            WHERE 
            Books.title LIKE :searchTerm OR 
            Author.name LIKE :searchTerm
            LIMIT :offset, :itemsPerPage";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->errorInfo()[2]);
            }

            // Bind the parameters
            $searchTerm = "%$query%";
            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':itemsPerPage', $this->itemsPerPage, PDO::PARAM_INT);

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->errorInfo()[2]);
            }

            // Fetch the results
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get the total number of items
            $resultTotal = $this->conn->query("SELECT FOUND_ROWS() as total");
            if (!$resultTotal) {
                throw new Exception("Error fetching total items: " . $this->conn->errorInfo()[2]);
            }

            $totalItems = $resultTotal->fetch(PDO::FETCH_ASSOC)['total'];
            $totalPages = ceil($totalItems / $this->itemsPerPage);

            // Prepare the response
            return [
                'books' => $books,
                'totalPages' => $totalPages,
            ];

        } catch (Exception $e) {
            // Handle any errors
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }
}
?>
