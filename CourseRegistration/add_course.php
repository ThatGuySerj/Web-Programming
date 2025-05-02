<?php
require_once __DIR__ . '/includes/db_connect.php';
$pageTitle = "Add New Course";

$instructors = $conn->query("SELECT * FROM Instructors ORDER BY last_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prefix = $_POST['prefix'];
    $number = $_POST['number'];
    $section = $_POST['section'];
    $name = $_POST['name'];
    $days = $_POST['days'];
    $time = $_POST['time'];
    $room = $_POST['room'];
    $creditHours = $_POST['credit_hours'];
    $enrollmentCap = $_POST['enrollment_cap'];
    $instructorId = $_POST['instructor_id'];
    $semester = $_POST['semester'];

    try {
        $stmt = $conn->prepare("INSERT INTO Courses (prefix, number, section, name, days, time, room, credit_hours, enrollment_cap, instructor_id, semester) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prefix, $number, $section, $name, $days, $time, $room, $creditHours, $enrollmentCap, $instructorId, $semester]);
        $success = "Course added successfully!";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
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
        <label>Prefix: <input type="text" name="prefix" required placeholder="e.g., CSCI"></label>
    </div>
    <div class="form-group">
        <label>Number: <input type="text" name="number" required placeholder="e.g., 101"></label>
    </div>
    <div class="form-group">
        <label>Section: <input type="text" name="section" required placeholder="e.g., 01"></label>
    </div>
    <div class="form-group">
        <label>Course Name: <input type="text" name="name" required></label>
    </div>
    <div class="form-group">
        <label>Days: <input type="text" name="days" required placeholder="e.g., MWF"></label>
    </div>
    <div class="form-group">
        <label>Time: <input type="text" name="time" required placeholder="e.g., 10:00-11:15"></label>
    </div>
    <div class="form-group">
        <label>Room: <input type="text" name="room" required></label>
    </div>
    <div class="form-group">
        <label>Credit Hours: <input type="number" name="credit_hours" min="1" required></label>
    </div>
    <div class="form-group">
        <label>Enrollment Cap: <input type="number" name="enrollment_cap" min="1" required></label>
    </div>
    <div class="form-group">
        <label>Semester: 
            <select name="semester" required>
                <option value="Spring">Spring</option>
                <option value="Summer">Summer</option>
                <option value="Fall">Fall</option>
                <option value="Winter">Winter</option>
            </select>
        </label>
    </div>
    <div class="form-group">
        <label>Instructor: 
            <select name="instructor_id" required>
                <?php foreach ($instructors as $instructor): ?>
                    <option value="<?= $instructor['instructor_id'] ?>">
                        <?= $instructor['last_name'] ?>, <?= $instructor['first_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <button type="submit" class="btn">Add Course</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>