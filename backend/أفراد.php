<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if user is logged in
if ($userRole !== 'admin' && $userRole !== 'user') {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input parameters
    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);
    $limit = isset($params['limit']) ? intval($params['limit']) : 10;
    $offset = isset($params['offset']) ? intval($params['offset']) : 0;

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM أفراد LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST request
elseif ($method === 'POST') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $requiredFields = array('name', 'email', 'phone');
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO أفراد (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $inputData['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $inputData['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $inputData['phone'], PDO::PARAM_STR);
    $stmt->execute();

    // Return ID of newly inserted record
    $lastID = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $lastID));
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $requiredFields = array('id', 'name', 'email', 'phone');
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }

    // Check if user is admin to perform edit operation
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE أفراد SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $inputData['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $inputData['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $inputData['phone'], PDO::PARAM_STR);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record updated successfully'));
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $requiredFields = array('id');
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }

    // Check if user is admin to perform delete operation
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM أفراد WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id'], PDO::PARAM_INT);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record deleted successfully'));
}

// Return error message for unsupported methods
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}