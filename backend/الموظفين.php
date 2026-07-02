<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get') {
    $stmt = $pdo->prepare('SELECT * FROM الموظفين');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($_GET['action']) && $_GET['action'] == 'getById') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM الموظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST request
elseif (isset($_POST['action']) && $_POST['action'] == 'create') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $email = htmlspecialchars($input['email']);
    $phone = htmlspecialchars($input['phone']);

    // Insert data into database
    $stmt = $pdo->prepare('INSERT INTO الموظفين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Employee created successfully']);
}

// Handle PUT request
elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);
    $name = htmlspecialchars($input['name']);
    $email = htmlspecialchars($input['email']);
    $phone = htmlspecialchars($input['phone']);

    // Update data in database
    $stmt = $pdo->prepare('UPDATE الموظفين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Employee updated successfully']);
}

// Handle DELETE request
elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);

    // Delete data from database
    $stmt = $pdo->prepare('DELETE FROM الموظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Employee deleted successfully']);
}

// Handle invalid requests
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}