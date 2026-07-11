**create_فواتير.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-lg font-bold text-indigo-500">إضافة فاتورة جديدة</h2>
        <form id="create-form" class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date" class="block text-sm font-bold text-slate-700">تاريخ الفاتورة</label>
                    <input type="date" id="date" name="date" class="block w-full px-4 py-2 mt-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="customer_name" class="block text-sm font-bold text-slate-700">اسم العميل</label>
                    <input type="text" id="customer_name" name="customer_name" class="block w-full px-4 py-2 mt-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="total" class="block text-sm font-bold text-slate-700">المجموع</label>
                    <input type="number" id="total" name="total" class="block w-full px-4 py-2 mt-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-bold text-slate-700">الحالة</label>
                    <select id="status" name="status" class="block w-full px-4 py-2 mt-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">اختر الحالة</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة فاتورة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/فواتير.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_فواتير.php';
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
include 'footer.php';
?>


**Note:** This code assumes that you have jQuery and Bootstrap installed in your project. Also, make sure to replace `../backend/فواتير.php` with the actual URL of your backend script that handles the form submission.