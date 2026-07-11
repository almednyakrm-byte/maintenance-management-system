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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM مراقبة_الوقت WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
} elseif (isset($_GET['all'])) {
    $stmt = $pdo->query("SELECT * FROM مراقبة_الوقت");
    $rows = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle POST request
elseif (isset($_POST['id']) && $_POST['id'] == 'new') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);

    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO مراقبة_الوقت (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_POST['id'])) {
    // Validate input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize input data
    $id = $_POST['id'];
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update existing record
    $stmt = $pdo->prepare("UPDATE مراقبة_الوقت SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
elseif (isset($_POST['id'])) {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete existing record
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM مراقبة_الوقت WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}