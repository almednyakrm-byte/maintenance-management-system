**list_مراقبة-الوقت.php**

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
    <title>مراقبة الوقت</title>
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
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مراقبة الوقت</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة مراقبة الوقت</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مراقبة-الوقت.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-700 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تاريخ</th>
                    <th>وقت</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مراقبة-الوقت.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    recordsContainer.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>${record.تاريخ}</td>
                            <td>${record.وقت}</td>
                            <td>${record.حالة}</td>
                            <td>
                                <a href="edit_مراقبة-الوقت.php?id=${record.id}" class="bg-slate-700 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsContainer.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/مراقبة-الوقت.php')
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    recordsContainer.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>${record.تاريخ}</td>
                            <td>${record.وقت}</td>
                            <td>${record.حالة}</td>
                            <td>
                                <a href="edit_مراقبة-الوقت.php?id=${record.id}" class="bg-slate-700 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsContainer.appendChild(row);
                    });
                });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مراقبة-الوقت.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

This code creates a premium Tailwind UI with a header navigation, a table showing a list of records, and a search bar. The search bar filters the records in real-time using AJAX. The table includes actions for editing and deleting records. The delete action uses an AJAX call to the backend to delete the record.