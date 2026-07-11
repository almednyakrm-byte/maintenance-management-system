<?php
// Start the session to store user data
session_start();

// Import the database connection file
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user data
        $userData = array(
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username']
        );
        echo json_encode($userData);
    } else {
        // User is not logged in, return a message
        echo json_encode(array('message' => 'Not logged in'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the action parameter
    if (isset($_POST['action'])) {
        // Handle login action
        if ($_POST['action'] === 'login') {
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Prepare the SQL query to select the user data
                $stmt = $db->prepare('SELECT user_id, username, password FROM users WHERE username = ?');
                $stmt->bind_param('s', $_POST['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                $userData = $result->fetch_assoc();

                // Check if the user exists and the password is correct
                if ($userData && password_verify($_POST['password'], $userData['password'])) {
                    // Login successful, store the user data in the session
                    $_SESSION['user_id'] = $userData['user_id'];
                    $_SESSION['username'] = $userData['username'];
                    echo json_encode(array('message' => 'Login successful'));
                } else {
                    // Login failed, return an error message
                    echo json_encode(array('message' => 'Invalid username or password'));
                }
            } else {
                // Missing fields, return an error message
                echo json_encode(array('message' => 'Missing fields'));
            }
        } 
        // Handle register action
        elseif ($_POST['action'] === 'register') {
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Check if the username and email are valid
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
                    // Prepare the SQL query to insert the user data
                    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt->bind_param('sss', $_POST['username'], $_POST['email'], $passwordHash);
                    if ($stmt->execute()) {
                        // Registration successful, return a success message
                        echo json_encode(array('message' => 'Registration successful'));
                    } else {
                        // Registration failed, return an error message
                        echo json_encode(array('message' => 'Registration failed'));
                    }
                } else {
                    // Invalid username or email, return an error message
                    echo json_encode(array('message' => 'Invalid username or email'));
                }
            } else {
                // Missing fields, return an error message
                echo json_encode(array('message' => 'Missing fields'));
            }
        } 
        // Handle logout action
        elseif ($_POST['action'] === 'logout') {
            // Unset the user data from the session
            unset($_SESSION['user_id']);
            unset($_SESSION['username']);
            // Destroy the session
            session_destroy();
            echo json_encode(array('message' => 'Logged out'));
        }
    } else {
        // Missing action parameter, return an error message
        echo json_encode(array('message' => 'Missing action parameter'));
    }
} else {
    // Invalid request method, return an error message
    echo json_encode(array('message' => 'Invalid request method'));
}