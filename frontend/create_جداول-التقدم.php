**create_جداول-التقدم.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Check if all fields are filled
    if (!empty($name) && !empty($description) && !empty($status)) {
        // Insert data into database
        $query = "INSERT INTO جداول_التقدم (name, description, status) VALUES ('$name', '$description', '$status')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_جدول التقدم.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill all fields';
    }
}

// Include header
require_once '../backend/header.php';

// Include form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-indigo-500 mb-4">Create جداول_التقدم</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700">Description:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter description"></textarea>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-slate-700">Status:</label>
            <select id="status" name="status" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../backend/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/جداول-التقدم.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_جدول التقدم.php';
                    } else {
                        alert('Error creating جداول_التقدم');
                    }
                }
            });
        });
    });
</script>


**backend/جداول-التقدم.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status'])) {
    // Insert data into database
    $query = "INSERT INTO جداول_التقدم (name, description, status) VALUES ('".$_POST['name']."', '".$_POST['description']."', '".$_POST['status']."')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating جداول_التقدم';
    }
}
?>


Note: This code assumes that you have a database connection established and a table named `جداول_التقدم` with columns `name`, `description`, and `status`. You should replace the database connection and table name with your actual database credentials and table structure.