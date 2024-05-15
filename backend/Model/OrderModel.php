<?php
require '/opt/lampp/htdocs/bookworm/backend/config/dbconn.php';

class OrderModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insertOrder($userId, $addressData, $cartItems,$totalAmount) {
        try {
            // Prepare INSERT statement
            $stmt = $this->pdo->prepare("INSERT INTO Orders (user_id, address_data, cart_items, total_amount) VALUES (?, ?, ?, ?)");
            // Bind parameters and execute statement
            $stmt->execute([$userId, $addressData, $cartItems, $totalAmount]);
            return true;
        } catch (PDOException $e) {
            // Return error message
            return "Error placing order: " . $e->getMessage();
        }
    }

    public function getOrders($userId) {
        try {
            // Prepare SELECT statement to fetch orders
            $stmt = $this->pdo->prepare("SELECT * FROM Orders WHERE user_id={$userId}");
            // Execute statement
            $stmt->execute();
            // Fetch all orders as associative array
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $orders;
        } catch (PDOException $e) {
            // Return error message
            return "Error fetching orders: " . $e->getMessage();
        }
    }
}




