**create_الموظفين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة موظف جديد</h2>
        <form id="create-employee-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm mb-2">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-slate-900 text-sm mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-slate-900 text-sm mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="department" class="block text-slate-900 text-sm mb-2">القسم</label>
                <select id="department" name="department" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">اختر قسم</option>
                    <option value="sales">مبيعات</option>
                    <option value="marketing">تسويق</option>
                    <option value="finance">مالية</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-employee-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/الموظفين.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_الموظفين.php';
                    } else {
                        alert('Error adding employee');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/الموظفين.php**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['department'])) {
    // Prepare SQL query
    $sql = "INSERT INTO employees (name, email, phone, department) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['department']);
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error adding employee';
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


Note: This code assumes that you have a database connection established in `db.php` and a table named `employees` with columns `name`, `email`, `phone`, and `department`. You should replace the placeholder values with your actual database credentials and table structure.