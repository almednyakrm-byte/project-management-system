**edit_المشاريع.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$project = json_decode(file_get_contents('../backend/المشاريع.php?id=' . $id), true);

// Check if project exists
if (empty($project)) {
    echo 'Project not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Project';
$mod_slug = 'projects';

// Include header and navigation
include 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-project-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
            <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $project['title'] ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $project['description'] ?></textarea>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" <?= $project['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $project['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Project</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/المشاريع.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
            document.getElementById('status').value = data.status;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-project-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const id = <?= $id ?>;

        fetch('../backend/المشاريع.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': <?= json_encode($_SESSION['csrf_token']) ?>
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_' + <?= $mod_slug ?> + '.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/المشاريع.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Check if project exists
$project = get_project($id);

if (empty($project)) {
    echo json_encode(['error' => 'Project not found']);
    exit;
}

// Update project via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);

    // Update project in database
    update_project($id, $data);

    echo json_encode(['success' => true]);
    exit;
}

// Get project details
function get_project($id) {
    // Database query to get project details
    // ...
}

// Update project in database
function update_project($id, $data) {
    // Database query to update project
    // ...
}
?>