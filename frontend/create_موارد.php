**create_موارد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO موارد (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);

    // Check if query was successful
    if ($result) {
        // Redirect back to list page
        header('Location: list_موارد.php');
        exit;
    } else {
        echo 'Error inserting data';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create new مورد form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New مورد</h2>
    <form id="create-muawad-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"></textarea>
        </div>
        <button type="submit" id="submit-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-muawad-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/موارد.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_موارد.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/موارد.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO موارد (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);

    // Check if query was successful
    if ($result) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return error response
        echo json_encode(['error' => 'Error inserting data']);
    }
}