**list_فواتير.php**

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
    <title>فواتير</title>
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
            text-align: center;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="mx-2">|</span>
        <span><?= $_SESSION['username'] ?></span>
        <span class="mx-2">|</span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">فواتير</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_فواتير.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>تاريخ الفاتورة</th>
                    <th>المبلغ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/فواتير.php';
                $response = file_get_contents($url);
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['invoice_number'] ?></td>
                        <td><?= $record['invoice_date'] ?></td>
                        <td><?= $record['amount'] ?></td>
                        <td>
                            <a href="edit_فواتير.php?id=<?= $record['id'] ?>" class="bg-slate-700 hover:bg-slate-900 text-white font-bold py-1 px-2 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const records = recordsTable.getElementsByTagName('tr');
            for (let i = 0; i < records.length; i++) {
                const record = records[i];
                const cells = record.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    record.style.display = '';
                } else {
                    record.style.display = 'none';
                }
            }
        });

        // Delete record
        function deleteRecord(id) {
            const url = '../backend/فواتير.php';
            const method = 'DELETE';
            const headers = new Headers({
                'Content-Type': 'application/json',
            });
            const body = JSON.stringify({
                id: id,
            });
            fetch(url, {
                method: method,
                headers: headers,
                body: body,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف الفاتورة بنجاح');
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف الفاتورة');
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
    </script>
</body>
</html>

**Note:** This code assumes that the backend API is already implemented and returns a JSON response with the list of records. The `delete_فواتير.php` file is also assumed to be implemented and handles the DELETE request.