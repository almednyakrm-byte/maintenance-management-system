<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/fواتير' => array('GET' => 'getFواتير', 'POST' => 'createFواتير'),
    '/fواتير/{id}' => array('GET' => 'getFواتيرById', 'PUT' => 'updateFواتير', 'DELETE' => 'deleteFواتير'),
);

// Route request
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '{id}') !== false && !isset($input['id'])) {
        continue;
    }
    if (strpos($route, '{id}') !== false && isset($input['id'])) {
        $route = str_replace('{id}', $input['id'], $route);
    }
    if (strpos($route, '/') === 0) {
        $route = substr($route, 1);
    }
    if (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], $methods)) {
        $match = true;
        break;
    }
}

if (!$match) {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
    exit;
}

// Get method
$method = $_SERVER['REQUEST_METHOD'];

// Get route
$route = $route;

// Get input data
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = $_POST;
}

// Validate input
if ($method === 'POST') {
    $requiredFields = array('name', 'description', 'amount');
} elseif ($method === 'PUT') {
    $requiredFields = array('name', 'description', 'amount');
} else {
    $requiredFields = array();
}

foreach ($requiredFields as $field) {
    if (!isset($input[$field])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required field: ' . $field));
        exit;
    }
}

// Sanitize input
$input['name'] = htmlspecialchars($input['name']);
$input['description'] = htmlspecialchars($input['description']);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Get user role
$userRole = $_SESSION['user_role'];

// Perform operation
switch ($method) {
    case 'GET':
        $result = getFواتير($db, $route);
        break;
    case 'POST':
        $result = createFواتير($db, $input);
        break;
    case 'PUT':
        $result = updateFواتير($db, $input);
        break;
    case 'DELETE':
        $result = deleteFواتير($db, $input);
        break;
}

// Close database connection
$db = null;

// Output result
if ($result !== false) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    http_response_code(500);
    echo json_encode(array('error' => 'Internal Server Error'));
}

// Functions
function getFواتير(PDO $db, $route) {
    if ($route === 'fواتير') {
        $stmt = $db->prepare('SELECT * FROM فواتير');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->prepare('SELECT * FROM فواتير WHERE id = :id');
        $stmt->bindParam(':id', $route);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $result;
}

function createFواتير(PDO $db, $input) {
    $stmt = $db->prepare('INSERT INTO فواتير (name, description, amount) VALUES (:name, :description, :amount)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();
    return array('message' => 'Fواتير created successfully');
}

function updateFواتير(PDO $db, $input) {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }
    $stmt = $db->prepare('UPDATE فواتير SET name = :name, description = :description, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();
    return array('message' => 'Fواتير updated successfully');
}

function deleteFواتير(PDO $db, $input) {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }
    $stmt = $db->prepare('DELETE FROM فواتير WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    return array('message' => 'Fواتير deleted successfully');
}