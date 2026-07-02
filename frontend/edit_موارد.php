**edit_موارد.php**

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

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/موارد.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title
$page_title = 'Edit مورد';

// Include header
include 'header.php';

?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-emerald-600 mb-4"><?= $page_title ?></h1>

    <form id="edit-muawad-form" class="bg-white p-8 rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-600 focus:border-emerald-600" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-600 focus:border-emerald-600"><?= $data['description'] ?></textarea>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-600 focus:border-emerald-600">
                    <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/موارد.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('status').value = data.status;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-muawad-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/موارد.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                status: document.getElementById('status').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to list page
            window.location.href = 'list_<?= $mod_slug ?>.php';
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <h1 class="text-3xl font-bold"><?= $page_title ?></h1>
            <ul class="flex items-center space-x-4">
                <li><a href="login.php" class="text-white hover:text-emerald-400">Login</a></li>
                <li><a href="register.php" class="text-white hover:text-emerald-400">Register</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <?= $content ?>
    </main>
</body>
</html>


**footer.php**

<footer class="bg-emerald-600 text-white p-4">
    <p>&copy; <?= date('Y') ?> <?= $page_title ?></p>
</footer>


**backend/موارد.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set.';
    exit;
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Prepare query
$stmt = $conn->prepare('SELECT * FROM muawad WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();

// Fetch data
$data = $stmt->fetch();

// Return data as JSON
echo json_encode($data);

// Close connection
$conn = null;
?>


Note: Replace `database`, `username`, and `password` with your actual database credentials. Also, make sure to update the `backend/موارد.php` file to match your database schema and table name.