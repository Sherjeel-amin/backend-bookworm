<?php
$url = $_SERVER['REQUEST_URI'];

$keywords = array('/books', '/address', '/login', '/order', '/signup');

foreach ($keywords as $keyword) {
    if (strpos($url, $keyword) !== false) {

        switch ($keyword) {
            case '/books':

                require_once '/opt/lampp/htdocs/bookworm/backend/Controllers/BookController.php';
                $bookController = new BookController($conn);
                $bookController->getBooks();
                break;
            case '/address':
        
                require_once '/opt/lampp/htdocs/bookworm/backend/Controllers/AddressController.php';
                $addressController = new AddressController();
                $addressController->handleRequest();
                break;
            case '/login':

                require_once '/opt/lampp/htdocs/bookworm/backend/Controllers/LoginController.php';
                $loginController = new LoginController();
                $loginController->handleRequest();
                break;
            case '/order':

                require_once '/opt/lampp/htdocs/bookworm/backend/Controllers/OrderController.php';
                $orderController = new OrderController($pdo);
                $orderController->handleOrderRequest();
                break;
            case '/signup':
    
                require_once '/opt/lampp/htdocs/bookworm/backend/Controllers/SignupController.php';
                $signupController = new SignupController();
                $signupController->handleRequest();
                break;
            default:
                echo "No action defined for keyword '$keyword' <br>";
                break;
        }
        break;
    }
}

