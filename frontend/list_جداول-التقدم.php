**list_جداول-التقدم.php**

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
    <title>جداول التقدم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }
        .header li {
            margin-right: 20px;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .table-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
        .actions a {
            margin-right: 10px;
        }
        .search-bar {
            width: 300px;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <ul>
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="#">حسناً, <?php echo $_SESSION['username']; ?></a></li>
            <li><a href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </div>
    <div class="table-container">
        <h2>جداول التقدم</h2>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_جداول-التقدم.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-700 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>تاريخ</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/جداول-التقدم.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['title'] . '</td>';
                    echo '<td>' . $record['date'] . '</td>';
                    echo '<td>' . $record['status'] . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="edit_جداول-التقدم.php?id=' . $record['id'] . '">تعديل</a>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/جداول-التقدم.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td class="actions">
                                <a href="edit_جداول-التقدم.php?id=${record.id}">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/جداول-التقدم.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI containing a header navigation, a table showing list of records with actions, an 'Add New Item' button, and a search bar filtering elements in real-time. The AJAX Javascript (Fetch API) is used to fetch list records from the backend and handle DELETE requests.