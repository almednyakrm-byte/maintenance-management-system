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

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : null;

    // SQL query structure: Select all or by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM طلبات_العملاء WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM طلبات_العملاء');
    }

    // Execute query and process output
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check user role for create permission
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $required_fields = ['customer_name', 'order_date', 'total'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // SQL query structure: Insert new record
    $stmt = $pdo->prepare('INSERT INTO طلبات_العملاء (customer_name, order_date, total) VALUES (:customer_name, :order_date, :total)');
    $stmt->bindParam(':customer_name', $data['customer_name']);
    $stmt->bindParam(':order_date', $data['order_date']);
    $stmt->bindParam(':total', $data['total']);

    // Execute query and process output
    $stmt->execute();
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check user role for update permission
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $required_fields = ['id', 'customer_name', 'order_date', 'total'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // SQL query structure: Update existing record
    $stmt = $pdo->prepare('UPDATE طلبات_العملاء SET customer_name = :customer_name, order_date = :order_date, total = :total WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':customer_name', $data['customer_name']);
    $stmt->bindParam(':order_date', $data['order_date']);
    $stmt->bindParam(':total', $data['total']);

    // Execute query and process output
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check user role for delete permission
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    if (!isset($data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required field: id']);
        exit;
    }

    // SQL query structure: Delete existing record
    $stmt = $pdo->prepare('DELETE FROM طلبات_العملاء WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);

    // Execute query and process output
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}