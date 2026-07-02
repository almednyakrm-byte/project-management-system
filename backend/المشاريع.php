<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    'GET' => [
        '/projects' => 'getProjects',
        '/projects/:id' => 'getProject',
    ],
    'POST' => [
        '/projects' => 'createProject',
    ],
    'PUT' => [
        '/projects/:id' => 'updateProject',
    ],
    'DELETE' => [
        '/projects/:id' => 'deleteProject',
    ],
];

// Get route
$route = $_SERVER['REQUEST_URI'];
$route = explode('/', $route);
$route = end($route);

// Get method
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (!isset($routes[$method][$route])) {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

// Call route function
$func = $routes[$method][$route];
$func();

function getProjects() {
    global $db;
    global $user;

    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $stmt = $db->prepare('SELECT * FROM projects');
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($projects);
}

function getProject() {
    global $db;
    global $user;

    // Get project ID
    $id = $_GET['id'];

    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $stmt = $db->prepare('SELECT * FROM projects WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output
    if ($project) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($project);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}

function createProject() {
    global $db;
    global $user;

    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Sanitize input
    $name = $db->quote($input['name']);
    $description = $db->quote($input['description']);

    // SQL query
    $stmt = $db->prepare('INSERT INTO projects (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Project created successfully']);
}

function updateProject() {
    global $db;
    global $user;

    // Get project ID
    $id = $_GET['id'];

    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Sanitize input
    $name = $db->quote($input['name']);
    $description = $db->quote($input['description']);

    // SQL query
    $stmt = $db->prepare('UPDATE projects SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Project updated successfully']);
}

function deleteProject() {
    global $db;
    global $user;

    // Get project ID
    $id = $_GET['id'];

    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $stmt = $db->prepare('DELETE FROM projects WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Project deleted successfully']);
}