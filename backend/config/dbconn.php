<?php

class Database {
    
    private $host = 'localhost';
    private $dbname = 'bookworm';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        try {
            // Allow cross-origin requests
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");

            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn; // Return the PDO connection object
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

$database = new Database();
$conn = $database->getConnection();

?>
