<?php
require_once __DIR__ . '/includes/db_connect.php';
$pageTitle = "Course Registration";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id'];
    
    try {
        $check = $conn->prepare("SELECT * FROM Registration WHERE student_id = ? AND course_id = ?");
        $check->execute([$studentId, $courseId]);
        
        if ($check->rowCount() > 0) {
            throw new Exception("Student is already registered for this course");
        }
        
        $stmt = $conn->prepare("INSERT INTO Registration (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$studentId, $courseId]);
        $success = "Registration successful!";
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

$students = $conn->query("SELECT * FROM Students ORDER BY last_name")->fetchAll();
$courses = $conn->query("SELECT c.*, i.first_name, i.last_name 
                       FROM Courses c
                       JOIN Instructors i ON c.instructor_id = i.instructor_id
                       ORDER BY c.prefix, c.number")->fetchAll();

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
        <label>Select Student:
            <select name="student_id" required>
                <?php foreach ($students as $student): ?>
                    <option value="<?= $student['student_id'] ?>">
                        <?= $student['last_name'] ?>, <?= $student['first_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    
    <div class="form-group">
        <label>Select Course:
            <select name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['course_id'] ?>">
                        <?= $course['prefix'] ?> <?= $course['number'] ?>: <?= $course['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    
    <button type="submit" class="btn">Register Student</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>