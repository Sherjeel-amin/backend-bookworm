<?php
require_once '/opt/lampp/htdocs/bookworm/backend/Model/UserModel.php';

class SignupController {
    private $database;

    public function __construct() {
        // Instantiate the Database class here
        $this->database = new Database();
    }

    public function createUser($username, $email, $password) {
        try {
            // Establish database connection
            $conn = $this->database->getConnection();

            // Create UserModel instance
            $userModel = new UserModel();

            // Attempt to create the user
            $userCreated = $userModel->createUser($conn, $username, $email, $password);

            // Return JSON response indicating success or failure
            if ($userCreated) {
                http_response_code(201); // HTTP status code for resource created
                return array("success" => true, "message" => "User created successfully");
            } else {
                http_response_code(400); // HTTP status code for bad request
                return array("success" => false, "message" => "Error creating user. User might already exist.");
            }
        } catch (Exception $e) {
            http_response_code(500); // HTTP status code for internal server error
            // Return JSON response indicating failure with the error message
            return array("success" => false, "message" => "An error occurred: " . $e->getMessage());
        }
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Ensure all required fields are provided
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Call createUser method
                $signupResult = $this->createUser($username, $email, $password);

                // Return JSON response
                echo json_encode($signupResult);
            } else {
                http_response_code(400); // HTTP status code for bad request
                // Respond with JSON indicating missing fields
                echo json_encode(array("success" => false, "message" => "Missing required fields"));
            }
        } else {
            http_response_code(405); // HTTP status code for method not allowed
            // Respond with JSON indicating invalid request method
            echo json_encode(array("success" => false, "message" => "Invalid request method"));
        }
    }
}

