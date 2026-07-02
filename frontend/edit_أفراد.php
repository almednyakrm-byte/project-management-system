**edit_أفراد.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/أفراد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل أفراد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">تعديل أفراد</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">بريد إلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">هاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
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
                    url: '../backend/أفراد.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/أفراد.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$sql = "SELECT * FROM أفراد WHERE id = '$id'";
$result = $conn->query($sql);

// Fetch data
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Output data
echo json_encode($data);
?>


**backend/أفراد.php (update)**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Update record
$sql = "UPDATE أفراد SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo 'success';
} else {
    echo 'Error: ' . $sql . '<br>' . $conn->error;
}

// Close connection
$conn->close();
?>