**list_موارد.php**

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
    <title>موارد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .bg-emerald-600 {
            background-color: #0d9488;
        }
        .text-teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body class="bg-emerald-600 text-teal-500">
    <div class="container mx-auto p-4">
        <header class="flex justify-between mb-4">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
            </div>
        </header>
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold">موارد</h1>
            <a href="create_موارد.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="w-full p-2 mr-2" placeholder="بحث...">
            <button id="search-btn" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">الاسم</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const recordsTable = document.getElementById('records');

        searchBtn.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/موارد.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>
                                <a href="edit_موارد.php?id=${record.id}" class="text-teal-500 hover:text-teal-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/موارد.php')
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>
                                <a href="edit_موارد.php?id=${record.id}" class="text-teal-500 hover:text-teal-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            }
        });

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/موارد.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }
    </script>
</body>
</html>


**backend/موارد.php**

<?php
// Database connection
$conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $conn->prepare('SELECT * FROM موارد WHERE اسم LIKE :search');
    $stmt->bindParam(':search', $searchQuery);
    $stmt->execute();
    $data = $stmt->fetchAll();
} else {
    $stmt = $conn->prepare('SELECT * FROM موارد');
    $stmt->execute();
    $data = $stmt->fetchAll();
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM موارد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Output data
echo json_encode($data);