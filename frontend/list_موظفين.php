**list_موظفين.php**

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
    <title>موظفين</title>
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
        <a href="index.php">الرئيسية</a>
        <span class="ml-4">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="ml-4">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">موظفين</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_موظفين.php'">اضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الموظف</th>
                    <th>وظيفة</th>
                    <th>تاريخ الميلاد</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['اسم الموظف']; ?></td>
                        <td><?php echo $record['وظيفة']; ?></td>
                        <td><?php echo $record['تاريخ الميلاد']; ?></td>
                        <td>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                        <td>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_موظفين.php?id=<?php echo $record['id']; ?>'">تعديل</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/موظفين.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record['اسم الموظف']}</td>
                            <td>${record['وظيفة']}</td>
                            <td>${record['تاريخ الميلاد']}</td>
                            <td>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                            </td>
                            <td>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_موظفين.php?id=${record['id']}'">تعديل</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا الموظف؟')) {
                fetch('../backend/موظفين.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الموظف بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الموظف');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/موظفين.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/موظفين.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'اسم الموظف' => 'محمد',
    'وظيفة' => 'مدير',
    'تاريخ الميلاد' => '1990-01-01'
);
$records[] = array(
    'id' => 2,
    'اسم الموظف' => 'عبد الله',
    'وظيفة' => 'مدير',
    'تاريخ الميلاد' => '1995-01-01'
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['اسم الموظف'], $search) !== false || strpos($record['وظيفة'], $search) !== false || strpos($record['تاريخ الميلاد'], $search) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
}

// Output records
echo json_encode(array('records' => $records));
?>

Note: This is a basic example and you should replace the backend code with your actual database logic.