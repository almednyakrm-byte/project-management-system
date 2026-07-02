<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get inputs
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// GET method: Read all materials
if ($method == 'GET') {
    // Validate and sanitize inputs
    $search = isset($input['search']) ? trim($input['search']) : '';
    $limit = isset($input['limit']) ? (int) $input['limit'] : 10;
    $offset = isset($input['offset']) ? (int) $input['offset'] : 0;

    // SQL query structure: Select all materials with pagination and search
    $stmt = $pdo->prepare('SELECT * FROM مواد WHERE name LIKE :search LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Output processing: Return materials in JSON format
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($materials);
}

// POST method: Create new material
elseif ($method == 'POST') {
    // Validate and sanitize inputs
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Insert new material
    $stmt = $pdo->prepare('INSERT INTO مواد (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Output processing: Return created material in JSON format
    $material_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare('SELECT * FROM مواد WHERE id = :id');
    $stmt->bindParam(':id', $material_id, PDO::PARAM_INT);
    $stmt->execute();
    $material = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($material);
}

// PUT method: Update existing material
elseif ($method == 'PUT') {
    // Validate and sanitize inputs
    $id = isset($input['id']) ? (int) $input['id'] : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Update existing material
    $stmt = $pdo->prepare('UPDATE مواد SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Output processing: Return updated material in JSON format
    $stmt = $pdo->prepare('SELECT * FROM مواد WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $material = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($material);
}

// DELETE method: Delete existing material
elseif ($method == 'DELETE') {
    // Validate and sanitize inputs
    $id = isset($input['id']) ? (int) $input['id'] : 0;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Delete existing material
    $stmt = $pdo->prepare('DELETE FROM مواد WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Output processing: Return success message in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Material deleted successfully']);
}

// Invalid request method
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}