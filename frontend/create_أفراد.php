**create_أفراد.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';

?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold mb-4 text-emerald-600">إضافة أفراد جديد</h1>

    <form id="create-form" class="bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">اسم</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">العنوان</label>
            <textarea id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/أفراد.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_أفراد.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**أفراد.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Database connection
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');

    // Check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // SQL query
    $sql = "INSERT INTO أفراد (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo 'Error: No data submitted';
}
?>