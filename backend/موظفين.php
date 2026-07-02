<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    $stmt = $pdo->prepare('SELECT * FROM موظفين');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST request
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Insert data into database
    $stmt = $pdo->prepare('INSERT INTO موظفين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee created successfully'));
}

// Handle PUT request
if (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Update data in database
    $stmt = $pdo->prepare('UPDATE موظفين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee updated successfully'));
}

// Handle DELETE request
if (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete data from database
    $stmt = $pdo->prepare('DELETE FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee deleted successfully'));
}
?>



// Enable PUT and DELETE methods
if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    $_PUT = $_POST;
    $_DELETE = $_POST;
}