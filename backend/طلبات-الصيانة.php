<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define table name
$table_name = 'طلبات الصيانة';

// Define columns
$columns = array('id', 'title', 'description', 'status', 'created_at', 'updated_at');

// Define validation rules
$validation_rules = array(
    'title' => 'required',
    'description' => 'required',
);

// Validate input data
foreach ($validation_rules as $column => $rule) {
    if (!isset($input[$column]) || !$input[$column]) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
}

// Sanitize input data
$input = array_map('trim', $input);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all records
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return records
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Insert record
    $stmt = $pdo->prepare("INSERT INTO $table_name (title, description, status, created_at, updated_at) VALUES (:title, :description, :status, NOW(), NOW())");
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':status', $input['status']);
    $stmt->execute();

    // Return inserted record
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update record
    $stmt = $pdo->prepare("UPDATE $table_name SET title = :title, description = :description, status = :status, updated_at = NOW() WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':status', $input['status']);
    $stmt->execute();

    // Return updated record
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $input['id']));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete record
    $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Return deleted record
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $input['id']));
    exit;
}

// Return error response
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;