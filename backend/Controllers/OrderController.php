<?php

require_once '/opt/lampp/htdocs/bookworm/backend/Model/OrderModel.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class OrderController {
    private $orderModel;

    public function __construct($pdo) {
        $this->orderModel = new OrderModel($pdo);
    }

    public function handleOrderRequest() {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_data = file_get_contents("php://input");

            if (!empty($post_data)) {

                $data = json_decode($post_data, true);
         
                $userId = $data['userId'];
                $addressData = json_encode($data['addressData']);
                $cartItems = json_encode($data['cartItems']);
                $totalAmount = $data['totalAmount'];

                $result = $this->orderModel->insertOrder($userId, $addressData, $cartItems,$totalAmount);

              
                echo $result === true ? "Order placed successfully." : $result;
            } else {
             
                echo "No data received";
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
         
            if(isset($_GET['userId'])) {
                    $userId = intval($_GET['userId']);
                    $orders = $this->orderModel->getOrders($userId);
                } else {
                    echo json_encode(array("success" => false, "message" => "Missing userId parameter"));
                }

            echo json_encode($orders);
        } else {
    
            echo "Invalid request method";
        }
    }
}

$database = new Database();
$pdo = $database->getConnection();



