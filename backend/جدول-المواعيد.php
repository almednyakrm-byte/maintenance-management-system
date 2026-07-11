<?php
require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'];
$authenticated = $_SESSION['authenticated'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($authenticated) {
        // Get all records
        $stmt = $pdo->prepare('SELECT * FROM جدول_المواعيد');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } else {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);
    
    // Validate input data
    if (!isset($inputData['date']) || !isset($inputData['time']) || !isset($inputData['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    
    // Sanitize input data
    $date = filter_var($inputData['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($inputData['time'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Insert record
    $stmt = $pdo->prepare('INSERT INTO جدول_المواعيد (date, time, description) VALUES (:date, :time, :description)');
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);
    
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['date']) || !isset($inputData['time']) || !isset($inputData['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    
    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $date = filter_var($inputData['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($inputData['time'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Update record
    $stmt = $pdo->prepare('UPDATE جدول_المواعيد SET date = :date, time = :time, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);
    
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    
    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Delete record
    $stmt = $pdo->prepare('DELETE FROM جدول_المواعيد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}