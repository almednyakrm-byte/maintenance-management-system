<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/get' => 'get',
    '/post' => 'post',
    '/put/:id' => 'put',
    '/delete/:id' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
foreach ($routes as $pattern => $method) {
    if (preg_match('/^' . preg_quote($pattern, '/') . '$/', $route, $matches)) {
        $route = $method;
        break;
    }
}

// Handle route
switch ($route) {
    case 'get':
        get();
        break;
    case 'post':
        post();
        break;
    case 'put':
        put($matches['id']);
        break;
    case 'delete':
        delete($matches['id']);
        break;
}

// Functions
function get() {
    global $db;
    global $user;
    
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $db->prepare('SELECT * FROM جداول_التقدم');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

function post() {
    global $db;
    global $user;
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    
    // SQL query
    $stmt = $db->prepare('INSERT INTO جداول_التقدم (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    // Output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

function put($id) {
    global $db;
    global $user;
    
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    
    // SQL query
    $stmt = $db->prepare('UPDATE جداول_التقدم SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete($id) {
    global $db;
    global $user;
    
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $db->prepare('DELETE FROM جداول_التقدم WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}
?>