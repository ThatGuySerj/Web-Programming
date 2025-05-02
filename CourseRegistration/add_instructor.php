<?php
require_once __DIR__ . '/includes/db_connect.php';
$pageTitle = "Add Instructor";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;
    $department = $_POST['department'];

    try {
        $stmt = $conn->prepare("INSERT INTO Instructors (first_name, last_name, email, phone, department) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $phone, $department]);
        $success = "Instructor added successfully!";
    } catch(PDOException $e) {
        $error = str_contains($e->getMessage(), 'Duplicate') 
               ? "Email already exists" 
               : "Error: " . $e->getMessage();
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if (isset($success)): ?>
    <div class="alert success"><?= $success ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert error"><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="form">
    <div class="form-group">
        <label>First Name: <input type="text" name="first_name" required></label>
    </div>
    <div class="form-group">
        <label>Last Name: <input type="text" name="last_name" required></label>
    </div>
    <div class="form-group">
        <label>Email: <input type="email" name="email" required></label>
    </div>
    <div class="form-group">
        <label>Phone: <input type="tel" name="phone"></label>
    </div>
    <div class="form-group">
        <label>Department: <input type="text" name="department" required></label>
    </div>
    <button type="submit" class="btn">Add Instructor</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>