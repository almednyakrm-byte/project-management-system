**edit_مواد.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مواد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit مواد</h2>
        <form id="edit-material-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-md" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-md"><?= $data['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-md" value="<?= $data['price'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-material-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مواد.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get existing record details
$query = "SELECT * FROM materials WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Record not found']);
}

// Close connection
$conn->close();
?>


**backend/edit_material.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Get data from form
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to update record
$query = "UPDATE materials SET name = '$name', description = '$description', price = '$price' WHERE id = '$id'";
$conn->query($query);

// Check if update was successful
if ($conn->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Update failed']);
}

// Close connection
$conn->close();
?>