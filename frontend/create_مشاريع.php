<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'مشاريع';

// Page title
$page_title = 'Create مشاريع';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-xl shadow-md">
        <h1 class="text-3xl text-emerald-600 mb-4"><?= $page_title ?></h1>
        <form id="create-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المشروع</label>
                <input type="text" id="name" name="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المشروع</label>
                <textarea id="description" name="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-emerald-600 focus:ring-emerald-600"></textarea>
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">تاريخ البدء</label>
                <input type="date" id="start_date" name="start_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-sm font-medium text-gray-700">تاريخ الانتهاء</label>
                <input type="date" id="end_date" name="end_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">حالة المشروع</label>
                <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-emerald-600 focus:ring-emerald-600">
                    <option value="active">فعال</option>
                    <option value="inactive">غير فعال</option>
                </select>
            </div>
            <button type="submit" class="py-2 px-4 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/<?= $mod_slug ?>.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?= $mod_slug ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>