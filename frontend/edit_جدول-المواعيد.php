**edit_جدول-المواعيد.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/جدول-المواعيد.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل جدول المواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل جدول المواعيد</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900">العنوان:</label>
                <input type="text" id="title" name="title" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900">الوصف:</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $existingRecord['description'] ?></textarea>
            </div>
            <div>
                <label for="date" class="text-slate-900">التاريخ:</label>
                <input type="date" id="date" name="date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['date'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/جدول-المواعيد.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/جدول-المواعيد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = array(
    'title' => 'عنوان جدول المواعيد',
    'description' => 'وصف جدول المواعيد',
    'date' => '2022-01-01'
);

echo json_encode($existingRecord);