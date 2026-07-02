**edit_المهام.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$record = json_decode(file_get_contents('../backend/المهام.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit Record</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900 text-sm font-bold">Title:</label>
                <input type="text" id="title" name="title" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md" value="<?= $record['title'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 text-sm font-bold">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Save Changes</button>
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
                    url: '../backend/المهام.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_المهام.php';
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


**backend/المهام.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Connect to database
$conn = new PDO('dsn', 'username', 'password');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM المهام WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$record = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if record exists
if (empty($record)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Update record via PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    $stmt = $conn->prepare('UPDATE المهام SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Output record details
echo json_encode($record);