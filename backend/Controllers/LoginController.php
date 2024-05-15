<?php
require_once '/opt/lampp/htdocs/bookworm/backend/Model/UserModel.php';
require_once '/opt/lampp/htdocs/bookworm/vendor/autoload.php'; 

use Firebase\JWT\JWT;

class LoginController {
    private $database;

    public function __construct() {
        // Instantiate the Database class here
        $this->database = new Database();
    }

    public function loginUser($email, $password) {
        try {
            // Establish database connection
            $conn = $this->database->getConnection();

            // Create UserModel instance
            $userModel = new UserModel();

            // Attempt to authenticate the user
            $userAuthenticated = $userModel->authenticateUser($conn, $email, $password);

            // If authentication successful, generate JWT token
            if ($userAuthenticated) {
                // Get user details if needed
                $userData = $userModel->userExistsByEmail($conn, $email);

                // Generate JWT token
                $key = "M4nytg6yyjU5ch59gzer";
                $payload = array(
                    "user_id" => $userData['id'], 
                    "email" => $userData['email'],
                    "username" => $userData['username'],
                    "exp" => time() + 3600
                );
                $algorithm = 'HS256'; // Choose the appropriate algorithm here
                $jwt = JWT::encode($payload, $key, $algorithm);

                // Return JSON response including JWT token and user ID
                http_response_code(200);
                echo json_encode(array("success" => true, "message" => "Login successful", "token" => $jwt, "userId" => $userData['id'], "email" => $userData['email'], "username" => $userData['username']));
            } else {
                // Authentication failed
                // http_response_code(401);
                echo json_encode(array("success" => false, "message" => "Invalid email or password"));
            }
        } catch (Exception $e) {
            // Handle exceptions and errors
            http_response_code(500);
            echo json_encode(array("success" => false, "message" => "An error occurred: " . $e->getMessage()));
        }
    }

    public function handleRequest() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $this->loginUser($email, $password);
            } else {
                http_response_code(400);
                echo json_encode(array("success" => false, "message" => "Missing required fields"));
            }
        } else {
            http_response_code(405);
            echo json_encode(array("success" => false, "message" => "Invalid request method"));
        }
    }
}
