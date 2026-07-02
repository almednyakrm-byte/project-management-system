**list_الموظفين.php**

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
    <title>الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
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
            background-color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            font-weight: bold;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl text-slate-900">الموظفين</h1>
            <a href="create_الموظفين.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>اسم الموظف</th>
                    <th>وظيفة</th>
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
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach((record, index) => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/الموظفين.php', { method: 'GET' });
                const data = await response.json();
                const records = data.records;
                records.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_الموظف}</td>
                        <td>${record.وظيفة}</td>
                        <td>
                            <a href="edit_الموظفين.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/الموظفين.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    const records = Array.from(recordsTable.children);
                    records.forEach((record) => {
                        if (record.children[0].textContent.includes(id)) {
                            record.remove();
                        }
                    });
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP file `الموظفين.php` that handles GET and DELETE requests for retrieving and deleting records, respectively. The `loadRecords` function fetches the records from the backend and populates the table, while the `deleteRecord` function sends a DELETE request to the backend to delete a record.