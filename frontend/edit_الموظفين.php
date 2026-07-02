**edit_الموظفين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/الموظفين.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الموظف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل الموظف</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900">اسم الموظف:</label>
                <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="email" class="text-slate-900">بريد إلكتروني:</label>
                <input type="email" id="email" name="email" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $record['email'] ?>">
            </div>
            <div>
                <label for="phone" class="text-slate-900">رقم الهاتف:</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $record['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الموظفين.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_الموظفين.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/الموظفين.php**

<?php
// Get id from URL
$id = $_GET['id'];

// Fetch existing record details from database
$record = // fetch record from database using $id

// Return record as JSON
echo json_encode($record);


**Note:** Replace `// fetch record from database using $id` with your actual database query to fetch the record.