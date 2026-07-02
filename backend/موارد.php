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
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $params = json_decode(file_get_contents('php://input'), true);
    if (empty($params)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مورد WHERE 1=1');
    $params = array_filter($params);
    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Validate required fields
    $requiredFields = ['name', 'description'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO مورد (name, description, created_by) VALUES (:name, :description, :created_by)');
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':created_by', $userID);

    // Execute query and return ID
    $stmt->execute();
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Validate required fields
    $requiredFields = ['id', 'name', 'description'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE مورد SET name = :name, description = :description, updated_by = :updated_by WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':updated_by', $userID);

    // Execute query
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Resource updated successfully']);
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Validate required fields
    $requiredFields = ['id'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM مورد WHERE id = :id AND deleted_by = :deleted_by');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':deleted_by', $userID);

    // Execute query
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Resource deleted successfully']);
    exit;
}

// Return error response for unsupported methods
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;