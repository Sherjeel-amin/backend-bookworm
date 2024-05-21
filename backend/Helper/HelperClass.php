<?php

class HelperClass {
    protected function respondWithSuccess($statusCode, $message) {
        http_response_code($statusCode);
        echo json_encode(["success" => true, "message" => $message]);
        exit;
    }

    protected function respondWithError($statusCode, $message) {
        http_response_code($statusCode);
        echo json_encode(["success" => false, "message" => $message]);
        exit;
    }
}
