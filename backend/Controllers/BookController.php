<?php
require_once '/opt/lampp/htdocs/bookworm/backend/Model/BookModel.php';

class BookController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getBooks() {
        $booksModel = new BooksModel();
        $books = $booksModel->getBooks($this->conn);

        if ($books) {
            http_response_code(200); // OK
            header('Content-Type: application/json');
            echo json_encode($books);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("success" => false, "message" => "Failed to retrieve books"));
        }
    }
}
