<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate input
    if (!isset($input['project_id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $project_id = intval($input['project_id']);

    // Prepare SQL query
    $sql = 'SELECT * FROM مشاريع WHERE project_id = :project_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch();

    // Check if data exists
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Project not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($input['project_name']) || !isset($input['project_description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $project_name = trim($input['project_name']);
    $project_description = trim($input['project_description']);

    // Prepare SQL query
    $sql = 'INSERT INTO مشاريع (project_name, project_description) VALUES (:project_name, :project_description)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':project_description', $project_description);
    $stmt->execute();

    // Get last inserted ID
    $last_inserted_id = $pdo->lastInsertId();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('project_id' => $last_inserted_id));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input
    if (!isset($input['project_id']) || !isset($input['project_name']) || !isset($input['project_description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $project_id = intval($input['project_id']);
    $project_name = trim($input['project_name']);
    $project_description = trim($input['project_description']);

    // Prepare SQL query
    $sql = 'UPDATE مشاريع SET project_name = :project_name, project_description = :project_description WHERE project_id = :project_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':project_description', $project_description);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() == 1) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Project updated successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Project not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input
    if (!isset($input['project_id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $project_id = intval($input['project_id']);

    // Prepare SQL query
    $sql = 'DELETE FROM مشاريع WHERE project_id = :project_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->rowCount() == 1) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Project deleted successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Project not found'));
    }
}