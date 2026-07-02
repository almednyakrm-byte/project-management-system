<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المشاريع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold">مرحباً <?php echo $_SESSION['username']; ?></h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white bg-opacity-10 rounded shadow-md p-4">
                <h2 class="text-lg font-bold mb-2">إجمالي المشاريع</h2>
                <p id="total-projects" class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded shadow-md p-4">
                <h2 class="text-lg font-bold mb-2">إجمالي المهام</h2>
                <p id="total-tasks" class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded shadow-md p-4">
                <h2 class="text-lg font-bold mb-2">إجمالي الموظفين</h2>
                <p id="total-employees" class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded shadow-md p-4">
                <h2 class="text-lg font-bold mb-2">المشاريع النشطة</h2>
                <p id="active-projects" class="text-3xl font-bold">0</p>
            </div>
        </div>
        <div class="flex flex-wrap justify-center mt-4">
            <a href="projects.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mx-2">إدارة المشاريع</a>
            <a href="tasks.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mx-2">إدارة المهام</a>
            <a href="employees.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mx-2">إدارة الموظفين</a>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        // Fetch stats dynamically via Javascript API calls
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-projects').innerHTML = data.totalProjects;
                document.getElementById('total-tasks').innerHTML = data.totalTasks;
                document.getElementById('total-employees').innerHTML = data.totalEmployees;
                document.getElementById('active-projects').innerHTML = data.activeProjects;
            });
    </script>
</body>
</html>