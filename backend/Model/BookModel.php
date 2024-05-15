<?php
require '/opt/lampp/htdocs/bookworm/backend/config/dbconn.php';

class BooksModel {
    public static function getBooks($conn) {
        try {
            // Define the SQL query
            $query = "SELECT b.*, a.name AS author_name, c.name AS category_name
                FROM Books b
                JOIN Author a ON b.author_id = a.id
                JOIN Categories c ON b.category_id = c.id";

            // Prepare and execute the statement
            $statement = $conn->prepare($query);
            $statement->execute();

            // Fetch all rows as associative array
            $books = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $books;
        } catch (PDOException $e) {
            // Handle exceptions
            die("Error fetching books: " . $e->getMessage());
        }
    }
    
}
