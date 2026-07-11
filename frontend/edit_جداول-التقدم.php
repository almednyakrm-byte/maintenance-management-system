**edit_جداول-التقدم.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/جداول-التقدم.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if response is successful
if ($data['status'] !== 'success') {
    echo 'Error fetching record details';
    exit;
}

// Set form data
$form_data = $data['data'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل جدول التقدم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center text-2xl font-bold mb-4">تعديل جدول التقدم</h2>
        <form id="edit-form">
            <div class="form-group">
                <label for="name">اسم الجدول</label>
                <input type="text" id="name" name="name" value="<?= $form_data['name'] ?>">
            </div>
            <div class="form-group">
                <label for="description">وصف الجدول</label>
                <textarea id="description" name="description"><?= $form_data['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">حالة الجدول</label>
                <select id="status" name="status">
                    <option value="active" <?= $form_data['status'] === 'active' ? 'selected' : '' ?>>نشط</option>
                    <option value="inactive" <?= $form_data['status'] === 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="تعديل">
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/جداول-التقدم.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating record');
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/جداول-التقدم.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
$query = "SELECT * FROM جداول_التقدم WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if record exists
if (!$data) {
    echo json_encode(array('status' => 'error', 'message' => 'Record not found'));
    exit;
}

// Return record details as JSON
echo json_encode(array('status' => 'success', 'data' => $data));
?>


Note: This code assumes that you have a database connection established in the `backend/جداول-التقدم.php` file. You should replace the database connection code with your own. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.