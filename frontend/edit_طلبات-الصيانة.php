**edit_طلبات-الصيانة.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/طلبات-الصيانة.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit طلبات الصيانة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="max-w-md mx-auto p-4 bg-slate-100 rounded-md shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit طلبات الصيانة</h2>

    <form id="edit-form" class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['title'] ?>">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
        </div>

        <button type="submit" class="w-full p-2 text-sm text-white bg-indigo-500 hover:bg-indigo-700 rounded-md">Save Changes</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#edit-form').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'PUT',
                url: '../backend/طلبات-الصيانة.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>

</body>
</html>


**backend/طلبات-الصيانة.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'title' => 'Example Title',
    'description' => 'Example Description'
);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);