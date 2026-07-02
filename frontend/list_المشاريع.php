**list_المشاريع.php**

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
    <title>المشاريع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>المشاريع</h1>
        <a href="index.php">الرئيسية</a>
        <a href="profile.php"><?= $_SESSION['username'] ?></a>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">قائمة المشاريع</h2>
            <a href="create_المشاريع.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة مشروع جديد</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="search-bar" placeholder="بحث...">
            <button id="search-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المشروع</th>
                    <th>وصف المشروع</th>
                    <th>تاريخ بداية المشروع</th>
                    <th>تاريخ نهاية المشروع</th>
                    <th>حالة المشروع</th>
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
        const searchBtn = document.getElementById('search-btn');
        const records = document.getElementById('records');

        searchBtn.addEventListener('click', async () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                const response = await fetch('../backend/المشاريع.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                });
                const data = await response.json();
                renderRecords(data);
            } else {
                const response = await fetch('../backend/المشاريع.php', {
                    method: 'GET'
                });
                const data = await response.json();
                renderRecords(data);
            }
        });

        async function renderRecords(data) {
            records.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.name}</td>
                    <td>${record.description}</td>
                    <td>${record.startDate}</td>
                    <td>${record.endDate}</td>
                    <td>${record.status}</td>
                    <td>
                        <a href="edit_المشاريع.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المشروع؟')) {
                const response = await fetch('../backend/المشاريع.php', {
                    method: 'DELETE',
                    params: { id }
                });
                if (response.ok) {
                    alert('تم حذف المشروع بنجاح');
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف المشروع');
                }
            }
        }
    </script>
</body>
</html>

**backend/المشاريع.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM projects WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM projects";
}

// Execute query
$result = $conn->query($query);

// Fetch data
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output data
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM projects WHERE id = '$id'";
    $conn->query($query);
    echo 'Record deleted successfully';
}
?>