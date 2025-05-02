<?php
require_once __DIR__ . '/includes/db_connect.php';
$pageTitle = "Enroll New Student";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;
    $year = $_POST['year'];
    $major = $_POST['major'];

    try {
        $stmt = $conn->prepare("INSERT INTO Students (first_name, last_name, email, phone, year, major) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $phone, $year, $major]);
        $success = "Student added successfully!";
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
        <label>Year: 
            <select name="year" required>
                <option value="Freshman">Freshman</option>
                <option value="Sophomore">Sophomore</option>
                <option value="Junior">Junior</option>
                <option value="Senior">Senior</option>
            </select>
        </label>
    </div>
    <div class="form-group">
        <label>Major: <input type="text" name="major" required></label>
    </div>
    <button type="submit" class="btn">Enroll Student</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>