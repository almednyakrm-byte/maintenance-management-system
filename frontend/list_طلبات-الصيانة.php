**list_طلبات-الصيانة.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الصيانة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-white">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">طلبات الصيانة</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_طلبات-الصيانة.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" placeholder="بحث" id="search-input">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>تاريخ الطلب</th>
                    <th>وصف الطلب</th>
                    <th>حالة الطلب</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['description']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_طلبات-الصيانة.php?id=<?php echo $record['id']; ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            const response = await fetch('../backend/طلبات-الصيانة.php', { method: 'GET' });
            const data = await response.json();
            return data.records;
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetchRecords().then(records => {
                const recordsTable = document.getElementById('records-table');
                recordsTable.innerHTML = '';
                records.forEach(record => {
                    if (record.description.includes(searchInput) || record.date.includes(searchInput)) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.date}</td>
                            <td>${record.description}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_طلبات-الصيانة.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/طلبات-الصيانة.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id }) });
            if (response.ok) {
                alert('تم حذف السجل بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف السجل');
            }
        }
    </script>
</body>
</html>

<?php
// Function to fetch records from backend
function fetchRecords() {
    $records = array();
    // Fetch records from backend
    $response = file_get_contents('../backend/طلبات-الصيانة.php');
    $data = json_decode($response, true);
    $records = $data['records'];
    return $records;
}
?>

**backend/طلبات-الصيانة.php**

<?php
// Fetch records from database
$records = array();
// Fetch records from database
$records = array(
    array('id' => 1, 'date' => '2022-01-01', 'description' => 'وصف السجل 1', 'status' => 'مكتمل'),
    array('id' => 2, 'date' => '2022-01-02', 'description' => 'وصف السجل 2', 'status' => 'مكتمل'),
    array('id' => 3, 'date' => '2022-01-03', 'description' => 'وصف السجل 3', 'status' => 'مكتمل'),
);
// Return records as JSON
header('Content-Type: application/json');
echo json_encode(array('records' => $records));
?>

Note: This code assumes that you have a backend script (`backend/طلبات-الصيانة.php`) that fetches records from a database and returns them as JSON. You should replace this script with your own implementation.