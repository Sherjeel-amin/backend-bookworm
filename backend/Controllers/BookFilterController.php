<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '/opt/lampp/htdocs/bookworm/backend/config/dbconn.php';
require_once '/opt/lampp/htdocs/bookworm/backend/Model/BookFilterModel.php';

try {
    // Initialize the database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Retrieve query parameters
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Create a BookSearch instance
    $bookSearch = new BookSearch($conn);

    // Perform the search
    $response = $bookSearch->searchBooks($query, $page);

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Close the connection
    if (isset($conn)) {
        $conn = null; // For PDO, we set the connection to null to close it.
    }
}
?>
