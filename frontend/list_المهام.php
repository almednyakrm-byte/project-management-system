**list_المهام.php**

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
    <title>المهام</title>
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
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900">المهام</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_المهام.php'">إضافة جديد</button>
        <div class="flex justify-center mt-4">
            <input type="search" class="search-bar" placeholder="بحث..." id="search-input">
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>اسم المهمة</th>
                    <th>تاريخ الإضافة</th>
                    <th>تاريخ النهاية</th>
                    <th>حالة المهمة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $url = '../backend/المهام.php';
                $searchInput = $_GET['search'] ?? '';
                $params = http_build_query(['search' => $searchInput]);
                $response = file_get_contents($url . '?' . $params);
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['added_at']; ?></td>
                        <td><?php echo $item['end_at']; ?></td>
                        <td><?php echo $item['status']; ?></td>
                        <td>
                            <a href="edit_المهام.php?id=<?php echo $item['id']; ?>" class="text-indigo-500">تعديل</a>
                            <button class="text-red-500" onclick="deleteItem(<?php echo $item['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const tableBody = document.getElementById('table-body');

        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.trim();
            const url = '../backend/المهام.php';
            const params = http_build_query(['search' => searchValue]);
            fetch(url + '?' + params)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.name}</td>
                            <td>${item.added_at}</td>
                            <td>${item.end_at}</td>
                            <td>${item.status}</td>
                            <td>
                                <a href="edit_المهام.php?id=${item.id}" class="text-indigo-500">تعديل</a>
                                <button class="text-red-500" onclick="deleteItem(${item.id})">حذف</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
        });

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف المهمة؟')) {
                fetch('../backend/المهام.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المهمة بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المهمة');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, current user info, and logout.
3. Table showing list of records: Fetches data from '../backend/المهام.php' using Fetch API and displays it in a table.
4. Search bar: Filters elements in real-time using the search input value.
5. AJAX delete request: Sends a DELETE request to '../backend/المهام.php' to delete a record.
6. Premium Tailwind UI: Uses the slate-900 and indigo-500 color palette.

Note: This code assumes that the backend API is implemented and returns data in JSON format.