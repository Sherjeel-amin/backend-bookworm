<?php

require_once '/opt/lampp/htdocs/bookworm/backend/config/dbconn.php';

class AddressModel {

    public function deleteAddress($addressId) {
        // Connect to the database
        $database = new Database();
        $conn = $database->getConnection();
        
        // SQL query to delete address from the database
        $sql = "DELETE FROM Address WHERE id = ?";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(1, $addressId, PDO::PARAM_INT);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the execution was successful
        if ($result) {
            return true; // Address deleted successfully
        } else {
            return false; // Failed to delete address
        }
    }
    
    public function editAddress($addressId, $addressLine1, $addressLine2, $city, $pincode, $country) {
        // Connect to the database
        $database = new Database();
        $conn = $database->getConnection();
        
        // SQL query to update address in the database
        $sql = "UPDATE Address SET address_line1 = ?, address_line2 = ?, city = ?, pin = ?, country = ?, updated_at = current_timestamp() WHERE id = ?";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(1, $addressLine1, PDO::PARAM_STR);
        $stmt->bindParam(2, $addressLine2, PDO::PARAM_STR);
        $stmt->bindParam(3, $city, PDO::PARAM_STR);
        $stmt->bindParam(4, $pincode, PDO::PARAM_STR);
        $stmt->bindParam(5, $country, PDO::PARAM_STR);
        $stmt->bindParam(6, $addressId, PDO::PARAM_INT);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the execution was successful
        if ($result) {
            return true; // Address edited successfully
        } else {
            return false; // Failed to edit address
        }
    }
    
    
    public function getUserAddress($userId) {
        // Connect to the database
        $database = new Database();
        $conn = $database->getConnection();
        
        // Query to fetch user addresses based on user ID
        $query = "SELECT * FROM Address WHERE user_id = :userId";
        
        // Prepare the statement
        $stmt = $conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':userId', $userId);
        
        // Execute the query
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Close the statement
        $stmt->closeCursor();
        
        // Initialize an array to store addresses
        $addresses = [];
        
        // Iterate through results and format each address
        foreach ($results as $result) {
            $address = array(
                "addressId" => $result['id'],
                "addressline_1" => $result['address_line1'],
                "addressline_2" => $result['address_line2'],
                "city" => $result['city'],
                "pin" => $result['pin'],
                "country"=> $result['country']
            );
            // Add the address to the addresses array
            $addresses[] = $address;
        }
        
        return $addresses;
    }

    public function addAddress($userId, $addressLine1, $addressLine2, $city, $pincode, $country) {
        // Connect to the database
        $database = new Database();
        $conn = $database->getConnection();
        
        // Example SQL query to insert address into the database
        $sql = "INSERT INTO Address (user_id, address_line1, address_line2, city, pin, country, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, current_timestamp(), current_timestamp())";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters with data types
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $addressLine1, PDO::PARAM_STR);
        $stmt->bindParam(3, $addressLine2, PDO::PARAM_STR);
        $stmt->bindParam(4, $city, PDO::PARAM_STR);
        $stmt->bindParam(5, $pincode, PDO::PARAM_STR);
        $stmt->bindParam(6, $country, PDO::PARAM_STR);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the execution was successful
        if ($result) {
            return true; // Address added successfully
        } else {
            return false; // Failed to add address
        }
    }
}




?>
