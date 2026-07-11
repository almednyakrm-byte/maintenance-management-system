**create_مراقبة-الوقت.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $start_time = trim($_POST['start_time']);
    $end_time = trim($_POST['end_time']);

    if (empty($name) || empty($description) || empty($start_time) || empty($end_time)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO مراقبة_الوقت (name, description, start_time, end_time) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $name, $description, $start_time, $end_time);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_مراقبة-الوقت.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-indigo-500 mb-4">Create New مراقبة_الوقت</h2>
    <form action="" method="post" class="space-y-4">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    Name
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" name="name" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                    Description
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" type="text" name="description" required>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start_time">
                    Start Time
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="start_time" type="datetime-local" name="start_time" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end_time">
                    End Time
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="end_time" type="datetime-local" name="end_time" required>
            </div>
        </div>
        <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit" name="submit">
            Create
        </button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_مراقبة-الوقت.js**
javascript
$(document).ready(function() {
    $('#create_مراقبة-الوقت_form').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/مراقبة-الوقت.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_مراقبة-الوقت.php';
                } else {
                    alert('Error creating new مراقبة_الوقت');
                }
            },
            error: function(xhr, status, error) {
                alert('Error creating new مراقبة_الوقت');
            }
        });
    });
});


**Note:** Make sure to replace `../backend/مراقبة-الوقت.php` with the actual URL of your backend script that handles the form submission. Also, make sure to include the necessary JavaScript libraries (e.g. jQuery) in your HTML file.