<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة مصمم لتقديم خدمات الصيانة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-indigo-500">نظام إدارة مصمم لتقديم خدمات الصيانة</h1>
            <button class="bg-slate-700 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-indigo-500">مرحباً</h2>
            <p class="text-gray-600">أهلاً بك في نظام إدارة مصمم لتقديم خدمات الصيانة</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            $stats = array(
                array('title' => 'فواتير', 'value' => 100),
                array('title' => 'جداول التقدم', 'value' => 50),
                array('title' => 'مراقبة الوقت', 'value' => 200),
            );
            foreach ($stats as $stat) {
                ?>
                <div class="glassmorphism-card p-4 bg-white rounded shadow-md">
                    <h3 class="text-lg font-bold text-indigo-500"><?= $stat['title'] ?></h3>
                    <p class="text-gray-600"><?= $stat['value'] ?></p>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-indigo-500">روابط سريعة</h2>
            <ul class="list-none mb-0">
                <li class="mb-2">
                    <a href="#" class="text-gray-600 hover:text-indigo-500">فواتير</a>
                </li>
                <li class="mb-2">
                    <a href="#" class="text-gray-600 hover:text-indigo-500">جداول التقدم</a>
                </li>
                <li class="mb-2">
                    <a href="#" class="text-gray-600 hover:text-indigo-500">مراقبة الوقت</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.querySelector('.stats-grid');
                data.forEach(stat => {
                    const statCard = document.createElement('div');
                    statCard.classList.add('glassmorphism-card', 'p-4', 'bg-white', 'rounded', 'shadow-md');
                    statCard.innerHTML = `
                        <h3 class="text-lg font-bold text-indigo-500">${stat.title}</h3>
                        <p class="text-gray-600">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statCard);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: You'll need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, make sure to update the backend files to handle the API requests and return the stats data in the expected format.