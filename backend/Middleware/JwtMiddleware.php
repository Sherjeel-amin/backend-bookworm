<?php
require_once '/opt/lampp/htdocs/bookworm/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{

    public static function handleRequest()
    {
        try {
            $secretKey = "M4nytg6yyjU5ch59gzer";
            // Get the JWT token from the Authorization header
            $jwt = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            if (empty($jwt)) {
                self::sendUnauthorizedResponse('No token provided');
            }

            // Decode the JWT token using cached secret key
            $decoded = JWT::decode($jwt, $secretKey);

            // Extract user data from token
            $userData = array_intersect_key((array)$decoded, array_flip(['user_id', 'email', 'username']));

            // Set user data in request
            $_REQUEST = array_merge($_REQUEST, $userData);
        } catch (ExpiredException $e) {
            self::sendUnauthorizedResponse('Token expired');
        } catch (Exception $e) {
            self::sendUnauthorizedResponse($e->getMessage());
        }
    }

    private static function sendUnauthorizedResponse($message)
    {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => $message]);
        exit();
    }
}

// Handle the request using a single instance
JwtMiddleware::handleRequest();
