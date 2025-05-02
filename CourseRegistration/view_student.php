<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "CourseRegistration";

// Connect to the database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all courses for dropdown
$courses_result = $conn->query("
    SELECT course_id, prefix, course_number, section, name 
    FROM Courses
    ORDER BY prefix, course_number, section
");

$students = [];
$selected_course_id = $_POST['course_id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && $selected_course_id) {
    $sql = "
        SELECT s.firstname, s.lastname, s.year, s.major, s.email
        FROM Students s
        JOIN Registration r ON s.student_id = r.student_id
        WHERE r.course_id = ?
        ORDER BY s.lastname, s.firstname
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="NicoStyles.css">
</head>
<body>
    <h2>View Students Enrolled in a Course</h2>
    <form method="POST" action="view_student.php">
        <label>Select Course:</label>
        <select name="course_id" required>
            <option value="">-- Select a Course --</option>
            <?php while ($row = $courses_result->fetch_assoc()): ?>
                <option value="<?= $row['course_id'] ?>" <?= $selected_course_id == $row['course_id'] ? 'selected' : '' ?>>
                    <?= $row['prefix'] . " " . $row['course_number'] . "-" . $row['section'] . ": " . $row['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">View Students</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <h3>Enrolled Students:</h3>
        <?php if (count($students) > 0): ?>
            <table>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Year</th>
                    <th>Major</th>
                    <th>Email</th>
                </tr>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['firstname']) ?></td>
                        <td><?= htmlspecialchars($student['lastname']) ?></td>
                        <td><?= htmlspecialchars($student['year']) ?></td>
                        <td><?= htmlspecialchars($student['major']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No students enrolled in the selected course.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
