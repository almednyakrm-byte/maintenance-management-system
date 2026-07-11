**create_طلبات-الصيانة.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Include form validation library
include 'form_validation.php';

// Define form validation rules
$validation_rules = array(
    'title' => 'required',
    'description' => 'required',
    'status' => 'required',
    'priority' => 'required',
    'assigned_to' => 'required',
    'due_date' => 'required|date'
);

// Validate form data
if (isset($_POST['submit'])) {
    $validation = new FormValidation();
    $validation->validate($_POST, $validation_rules);
    if ($validation->get_errors()) {
        $errors = $validation->get_errors();
    } else {
        // Prepare data for insertion
        $data = array(
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'status' => $_POST['status'],
            'priority' => $_POST['priority'],
            'assigned_to' => $_POST['assigned_to'],
            'due_date' => $_POST['due_date']
        );

        // Insert data into database
        $result = insert_data($data);

        if ($result) {
            // Redirect back to list page
            header('Location: list_طلبات-الصيانة.php');
            exit;
        } else {
            $errors[] = 'Error inserting data';
        }
    }
}

// Include form view
include 'view/create_طلبات-الصيانة.php';
?>

<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $('#create-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/طلبات-الصيانة.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_طلبات-الصيانة.php';
                    } else {
                        alert('Error creating record');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error creating record');
                }
            });
        });
    });
</script>


**view/create_طلبات-الصيانة.php**

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-slate-900">Create New Request</h2>
            <form id="create-form" method="post">
                <div class="grid grid-cols-1 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
                        <input type="text" name="title" id="title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea name="description" id="description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="priority" class="block text-sm font-medium text-slate-700">Priority</label>
                        <select name="priority" id="priority" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="assigned_to" class="block text-sm font-medium text-slate-700">Assigned To</label>
                        <select name="assigned_to" id="assigned_to" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select User</option>
                            <!-- List of users will be populated here -->
                        </select>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="due_date" class="block text-sm font-medium text-slate-700">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                </div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
            </form>
        </div>
    </div>
</div>


**backend/طلبات-الصيانة.php**

<?php
// Include database connection
include 'db_connection.php';

// Prepare data for insertion
$data = array(
    'title' => $_POST['title'],
    'description' => $_POST['description'],
    'status' => $_POST['status'],
    'priority' => $_POST['priority'],
    'assigned_to' => $_POST['assigned_to'],
    'due_date' => $_POST['due_date']
);

// Insert data into database
$result = insert_data($data);

if ($result) {
    echo 'success';
} else {
    echo 'error';
}
?>

Note: This code assumes that you have a `db_connection.php` file that establishes a connection to your database, and a `insert_data` function that inserts data into the database. You will need to modify the code to match your specific database schema and requirements.