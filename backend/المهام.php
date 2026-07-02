<?php

require_once 'db.php';

// Get user role and login status from session
$userRole = $_SESSION['userRole'];
$loggedIn = $_SESSION['loggedIn'];

// Check if user is logged in and authorized
if (!$loggedIn || ($userRole != 'admin' && $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE')) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Prepare SQL query to select all tasks
        $stmt = $pdo->prepare('SELECT * FROM tasks');
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return tasks as JSON response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($tasks);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate input data
        if (!isset($inputData['title']) || !isset($inputData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $title = htmlspecialchars($inputData['title']);
        $description = htmlspecialchars($inputData['description']);
        
        // Prepare SQL query to insert new task
        $stmt = $pdo->prepare('INSERT INTO tasks (title, description) VALUES (:title, :description)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return new task as JSON response
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Task created successfully'));
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Validate input data
        if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($inputData['id']);
        $title = htmlspecialchars($inputData['title']);
        $description = htmlspecialchars($inputData['description']);
        
        // Prepare SQL query to update task
        $stmt = $pdo->prepare('UPDATE tasks SET title = :title, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return updated task as JSON response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Task updated successfully'));
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        // Validate input data
        if (!isset($inputData['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($inputData['id']);
        
        // Prepare SQL query to delete task
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return deleted task as JSON response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Task deleted successfully'));
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

?>