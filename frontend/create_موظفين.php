**create_موظفين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Create form validation rules
$rules = [
    'name' => 'required',
    'email' => 'required|email',
    'phone' => 'required|numeric',
    'position' => 'required',
];

// Validate form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    foreach ($rules as $field => $rule) {
        if (empty($_POST[$field])) {
            $errors[] = "$field is required";
        } elseif ($field === 'email' && !filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "$field must be a valid email address";
        } elseif ($field === 'phone' && !ctype_digit($_POST[$field])) {
            $errors[] = "$field must be a numeric value";
        }
    }

    if (empty($errors)) {
        // Insert data into database
        $stmt = $conn->prepare('INSERT INTO موظفين (name, email, phone, position) VALUES (?, ?, ?, ?)');
        $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['position']]);
        header('Location: list_موظفين.php');
        exit;
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New موظفين</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-slate-900 text-sm font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-slate-900 text-sm font-bold mb-2">Position:</label>
            <input type="text" id="position" name="position" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // Send form data via AJAX
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/موظفين.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_موظفين.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

**backend/موظفين.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert data into database
    $stmt = $conn->prepare('INSERT INTO موظفين (name, email, phone, position) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['position']]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}