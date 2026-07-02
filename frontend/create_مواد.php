<?php
// create_مواد.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'مواد';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create <?= $mod_slug ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-slate-900 text-indigo-500">
        <h1 class="text-3xl font-bold mb-4">Create <?= $mod_slug ?></h1>
        <form id="create-<?= $mod_slug ?>-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium mb-2">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 pl-10 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium mb-2">Unit Price</label>
                <input type="number" id="unit_price" name="unit_price" step="0.01" class="block w-full p-2 pl-10 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 hover:bg-indigo-700 hover:text-slate-100 rounded-md">Create</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-<?= $mod_slug ?>-form').submit(function(e) {
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