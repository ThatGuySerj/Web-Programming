<?php
require_once __DIR__ . '/includes/db_connect.php';
$pageTitle = "Drop Course";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registration_id'])) {
    $registrationId = $_POST['registration_id'];
    
    try {
        $registration = $conn->query("
            SELECT s.first_name, s.last_name, c.prefix, c.number, c.name
            FROM Registration r
            JOIN Students s ON r.student_id = s.student_id
            JOIN Courses c ON r.course_id = c.course_id
            WHERE r.registration_id = $registrationId
        ")->fetch();
        
        $stmt = $conn->prepare("DELETE FROM Registration WHERE registration_id = ?");
        $stmt->execute([$registrationId]);
        $success = "Course dropped successfully for {$registration['first_name']} {$registration['last_name']}";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['student_id'])) {
    $studentId = $_GET['student_id'];
    $courses = $conn->query("
        SELECT r.registration_id, c.prefix, c.number, c.name
        FROM Registration r
        JOIN Courses c ON r.course_id = c.course_id
        WHERE r.student_id = $studentId
        ORDER BY c.prefix, c.number
    ")->fetchAll();
    
    header('Content-Type: application/json');
    echo json_encode($courses);
    exit;
}

$students = $conn->query("SELECT * FROM Students ORDER BY last_name")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<?php if (isset($success)): ?>
    <div class="alert success"><?= $success ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert error"><?= $error ?></div>
<?php endif; ?>

<div class="form">
    <div class="form-group">
        <label>Select Student:
            <select id="studentSelect" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?= $student['student_id'] ?>">
                        <?= $student['last_name'] ?>, <?= $student['first_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    
    <div id="courseList"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#studentSelect').change(function() {
        const studentId = $(this).val();
        if (!studentId) {
            $('#courseList').html('');
            return;
        }
        
        $.get('drop_course.php', { student_id: studentId }, function(data) {
            let html = '<h3>Registered Courses:</h3>';
            if (data.length > 0) {
                data.forEach(function(course) {
                    html += `
                    <div class="course-item">
                        <form method="POST">
                            <input type="hidden" name="registration_id" value="${course.registration_id}">
                            ${course.prefix} ${course.number} - ${course.name}
                            <button type="submit" class="btn danger">Drop</button>
                        </form>
                    </div>`;
                });
            } else {
                html += '<p>No registered courses found.</p>';
            }
            $('#courseList').html(html);
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>