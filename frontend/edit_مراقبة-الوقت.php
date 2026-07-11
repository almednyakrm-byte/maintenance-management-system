**edit_مراقبة-الوقت.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مراقبة-الوقت.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مراقبة الوقت</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-indigo-500 mb-4">تعديل مراقبة الوقت</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">اسم المراقب</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-700 bg-white rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="time" class="block text-sm font-medium text-slate-700">الوقت</label>
                <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-slate-700 bg-white rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['time'] ?>">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-slate-700">التاريخ</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-slate-700 bg-white rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['date'] ?>">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-600">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مراقبة-الوقت.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_مراقبة-الوقت.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مراقبة-الوقت.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'ID not set'));
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$query = "SELECT * FROM مراقبة_الوقت WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('success' => false, 'message' => 'Record not found'));
}

// Close connection
$conn->close();
?>


**backend/edit_مراقبة-الوقت.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'ID not set'));
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$query = "SELECT * FROM مراقبة_الوقت WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Update record
    $name = $_POST['name'];
    $time = $_POST['time'];
    $date = $_POST['date'];

    $query = "UPDATE مراقبة_الوقت SET name = '$name', time = '$time', date = '$date' WHERE id = '$id'";
    $conn->query($query);

    // Check if update was successful
    if ($conn->affected_rows > 0) {
        echo json_encode(array('success' => true, 'message' => 'Record updated successfully'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error updating record'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Record not found'));
}

// Close connection
$conn->close();
?>