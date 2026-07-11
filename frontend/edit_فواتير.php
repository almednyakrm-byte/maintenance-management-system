**edit_فواتير.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/فواتير.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title
$pageTitle = 'Edit فواتير';

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl font-bold text-indigo-500 mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-slate-700 border border-slate-300 rounded" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-slate-700 border border-slate-300 rounded"><?= $existingRecord['description'] ?></textarea>
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">Amount</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 text-sm text-slate-700 border border-slate-300 rounded" value="<?= $existingRecord['amount'] ?>">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 text-sm text-slate-700 border border-slate-300 rounded" value="<?= $existingRecord['date'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/فواتير.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
            document.getElementById('amount').value = data.amount;
            document.getElementById('date').value = data.date;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/فواتير.php', {
            method: 'PUT',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_فواتير.php';
            })
            .catch(error => console.error(error));
    });
</script>


**backend/فواتير.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Invalid request';
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
$stmt = $conn->prepare("SELECT * FROM فواتير WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch record details
$record = $result->fetch_assoc();

// Close connection
$conn->close();

// Return record details as JSON
echo json_encode($record);


**backend/فواتير.php (update)**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Invalid request';
    exit;
}

// Get id
$id = $_GET['id'];

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$amount = $_POST['amount'];
$date = $_POST['date'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update record
$stmt = $conn->prepare("UPDATE فواتير SET title = ?, description = ?, amount = ?, date = ? WHERE id = ?");
$stmt->bind_param("sssid", $title, $description, $amount, $date, $id);
$stmt->execute();

// Close connection
$conn->close();

// Return success message
echo 'Record updated successfully';