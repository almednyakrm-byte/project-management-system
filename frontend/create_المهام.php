**create_المهام.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-12 2xl:p-16">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12 2xl:p-16">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة مهمة جديدة</h2>
        <form id="create-task-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="task_name">اسم المهمة</label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="task_name" type="text" placeholder="اسم المهمة">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="task_description">وصف المهمة</label>
                    <textarea class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="task_description" rows="4" placeholder="وصف المهمة"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="task_deadline">تاريخ الاستحقاق</label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="task_deadline" type="date" placeholder="تاريخ الاستحقاق">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="task_status">حالة المهمة</label>
                    <select class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="task_status">
                        <option value="">اختر حالة المهمة</option>
                        <option value="مفتوح">مفتوح</option>
                        <option value="مكتمل">مكتمل</option>
                        <option value="مؤجل">مؤجل</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-task-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/المهام.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_المهام.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**backend/المهام.php**

<?php
// Include database connection
include 'database.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_deadline = $_POST['task_deadline'];
    $task_status = $_POST['task_status'];

    // Insert data into database
    $query = "INSERT INTO المهام (task_name, task_description, task_deadline, task_status) VALUES ('$task_name', '$task_description', '$task_deadline', '$task_status')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>