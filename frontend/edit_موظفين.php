**edit_موظفين.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/موظفين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    // Populate form fields
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
} else {
    echo "Error fetching data";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit موظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-slate-900">Edit موظفين</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" value="<?= $name ?>" class="block w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
                <input type="email" id="email" name="email" value="<?= $email ?>" class="block w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?= $phone ?>" class="block w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/موظفين.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating record');
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/موظفين.php**

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    // Fetch existing record details
    $id = $_GET['id'];
    $query = "SELECT * FROM موظفين WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
} else {
    // Handle invalid ID
    echo 'Invalid ID';
}
?>

Note: This code assumes you have a `mysqli` connection established in your backend script. You should replace `../backend/موظفين.php` with the actual path to your backend script. Also, make sure to validate user input and implement proper security measures to prevent SQL injection and other security vulnerabilities.