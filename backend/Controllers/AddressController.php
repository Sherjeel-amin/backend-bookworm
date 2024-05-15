<?php
require_once '/opt/lampp/htdocs/bookworm/backend/Model/AddressModel.php';

class AddressController {
    
    public function __construct() {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }

    public function getUserAddress($userId) {
        $userModel = new AddressModel();
        $userAddress = $userModel->getUserAddress($userId);
        
        if ($userAddress) {
            http_response_code(200); // OK
            echo json_encode(array("success" => true, "address" => $userAddress));
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("success" => false, "message" => "User address not found"));
        }
    }

    public function addAddress($userId, $addressLine1, $addressLine2, $city, $pincode, $country) {
        $userModel = new AddressModel();
        $success = $userModel->addAddress($userId, $addressLine1, $addressLine2, $city, $pincode, $country);
        
        if ($success) {
            http_response_code(201); // Created
            echo json_encode(array("success" => true, "message" => "Address added successfully"));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("success" => false, "message" => "Failed to add address"));
        }
    }

    public function deleteAddress($addressId) {
        $userModel = new AddressModel();
        $success = $userModel->deleteAddress($addressId);
        
        if ($success) {
            http_response_code(200); // OK
            echo json_encode(array("success" => true, "message" => "Address deleted successfully"));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("success" => false, "message" => "Failed to delete address"));
        }
    }

    public function editAddress($addressId, $addressLine1, $addressLine2, $city, $pincode, $country) {
        $userModel = new AddressModel();
        $success = $userModel->editAddress($addressId, $addressLine1, $addressLine2, $city, $pincode, $country);
        
        if ($success) {
            http_response_code(200); // OK
            echo json_encode(array("success" => true, "message" => "Address edited successfully"));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("success" => false, "message" => "Failed to edit address"));
        }
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            if(isset($_GET['userId'])) {
                $userId = intval($_GET['userId']);
                $this->getUserAddress($userId);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array("success" => false, "message" => "Missing userId parameter"));
            }
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
            if(isset($_POST['userId'], $_POST['addressLine1'], $_POST['addressLine2'], $_POST['city'], $_POST['pincode'], $_POST['country'])) {
                $userId = intval($_POST['userId']);
                $addressLine1 = $_POST['addressLine1'];
                $addressLine2 = $_POST['addressLine2'];
                $city = $_POST['city'];
                $pincode = $_POST['pincode'];
                $country = $_POST['country'];
        
                $this->addAddress($userId, $addressLine1, $addressLine2, $city, $pincode, $country);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array("success" => false, "message" => "Missing data parameters"));
            }
        }
        elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
            // Check if the Content-Type header is set to application/json
            $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if ($content_type !== 'application/json') {
                http_response_code(400); // Bad Request
                echo json_encode(array("success" => false, "message" => "Content-Type header must be application/json"));
                exit;
            }
        
            // Get the JSON data from the request body
            $request_body = file_get_contents('php://input');
            // Decode the JSON data
            $data = json_decode($request_body, true);
        
            // Check if addressId is present in the JSON data
            if(isset($data['addressId'])) {
                // Extract the addressId from the data
                $addressId = intval($data['addressId']);
                // Call your deleteAddress function with the extracted addressId
                $this->deleteAddress($addressId);
            } else {
                // If addressId is missing in the request body, return a 400 Bad Request response
                http_response_code(400);
                echo json_encode(array("success" => false, "message" => "Missing addressId parameter"));
            }
        }
        
        elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {
            // Check if the Content-Type header is set to application/json
            $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if ($content_type !== 'application/json') {
                http_response_code(400); // Bad Request
                echo json_encode(array("success" => false, "message" => "Content-Type header must be application/json"));
                exit;
            }
        
            // Get the JSON data from the request body
            $request_body = file_get_contents('php://input');
            // Decode the JSON data
            $data = json_decode($request_body, true);
        
            // Check if all required parameters are present in the JSON data
            if(isset($data['addressId'], $data['addressLine1'], $data['addressLine2'], $data['city'], $data['pincode'], $data['country'])) {
                // Extract parameters from the data
                $addressId = intval($data['addressId']);
                $addressLine1 = $data['addressLine1'];
                $addressLine2 = $data['addressLine2'];
                $city = $data['city'];
                $pincode = $data['pincode'];
                $country = $data['country'];
        
                // Call your editAddress function with the extracted parameters
                $this->editAddress($addressId, $addressLine1, $addressLine2, $city, $pincode, $country);
            } else {
                // If any parameter is missing in the request body, return a 400 Bad Request response
                http_response_code(400); // Bad Request
                echo json_encode(array("success" => false, "message" => "Missing data parameters"));
            }
        } else {
            // If the request method is not PUT, return a 405 Method Not Allowed response
            http_response_code(405); // Method Not Allowed
            echo json_encode(array("success" => false, "message" => "Unsupported request method"));
        }
    }
}
        