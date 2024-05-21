<?php

require '/opt/lampp/htdocs/bookworm/backend/config/dbconn.php';

class UserModel {
    public static function userExistsByEmail($conn, $email) {
        try {
            // Prepare the SQL statement
            $query = "SELECT * FROM Users WHERE email = ?";
            $statement = $conn->prepare($query);
            $statement->bindParam(1, $email);

            // Execute the query
            $statement->execute();

            // Fetch the result
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            return $user; // Return user data or null if user does not exist
        } catch (PDOException $e) {
            // Handle exceptions
            throw new Exception("Error checking user existence: " . $e->getMessage());
        }
    }

    public static function createUser($conn, $username, $email, $password) {
        try {
            // Check if the user already exists
            if (self::userExistsByEmail($conn, $email)) {
                return false; // User already exists
            }

            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement
            $query = "INSERT INTO Users (username, email, password_hash) VALUES (?, ?, ?)";
            $statement = $conn->prepare($query);
            $statement->bindParam(1, $username);
            $statement->bindParam(2, $email);
            $statement->bindParam(3, $hashedPassword);

            // Execute the query
            if ($statement->execute()) {
                return true; // User created successfully
            } else {
                return false; // Error creating user
            }
        } catch (PDOException $e) {
            // Handle exceptions
            throw new Exception("Error creating user: " . $e->getMessage());
        }
    }

    public static function authenticateUser($conn, $email, $password) {
        try {
            // Prepare the SQL statement
            $query = "SELECT * FROM Users WHERE email = ?";
            $statement = $conn->prepare($query);
            $statement->bindParam(1, $email);

            // Execute the query
            $statement->execute();

            // Fetch the user data
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if ($user && password_verify($password, $user['password_hash'])) {
                return true; // User authenticated successfully
            }  
                return false; // Invalid email or password
            
        } catch (PDOException $e) {
            // Handle exceptions
            throw new Exception("Error authenticating user: " . $e->getMessage());
        }
    }
}

?>
