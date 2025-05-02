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

// Fetch students and instructors for dropdowns
$students_result = $conn->query("SELECT student_id, firstname, lastname FROM Students");
$instructors_result = $conn->query("SELECT instructor_id, first_name, last_name FROM Instructors");

// Initialize output
$courses = [];
$type = $_POST['type'] ?? '';
$selected_id = $_POST['person_id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && $type && $selected_id) {
    if ($type === "student") {
        $sql = "
            SELECT c.*, CONCAT(i.first_name, ' ', i.last_name) AS instructor_name
            FROM Courses c
            JOIN Registration r ON c.course_id = r.course_id
            LEFT JOIN Instructors i ON c.instructor_id = i.instructor_id
            WHERE r.student_id = ?
        ";
    } elseif ($type === "instructor") {
        $sql = "
            SELECT * FROM Courses
            WHERE instructor_id = ?
        ";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Courses</title>
    <link rel="stylesheet" href="NicoStyles.css">
    <script>
        function toggleDropdown(type) {
            document.getElementById("studentDropdown").style.display = type === "student" ? "block" : "none";
            document.getElementById("instructorDropdown").style.display = type === "instructor" ? "block" : "none";
        }
    </script>
</head>
<body>
    <h2>View Courses</h2>
    <form method="POST" action="view_course.php">
        <label>
            <input type="radio" name="type" value="student" onclick="toggleDropdown('student')" required> By Student
        </label>
        <label>
            <input type="radio" name="type" value="instructor" onclick="toggleDropdown('instructor')" required> By Instructor
        </label>

        <div id="studentDropdown" style="display:none; margin-top: 10px;">
            <label>Select Student:</label>
            <select name="person_id">
                <?php while ($row = $students_result->fetch_assoc()): ?>
                    <option value="<?= $row['student_id'] ?>"><?= $row['firstname'] . " " . $row['lastname'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div id="instructorDropdown" style="display:none; margin-top: 10px;">
            <label>Select Instructor:</label>
            <select name="person_id">
                <?php while ($row = $instructors_result->fetch_assoc()): ?>
                    <option value="<?= $row['instructor_id'] ?>"><?= $row['first_name'] . " " . $row['last_name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <br>
        <button type="submit">View Courses</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <h3>Courses Found:</h3>
        <?php if (count($courses) > 0): ?>
            <table>
                <tr>
                    <th>Prefix</th>
                    <th>Course #</th>
                    <th>Section</th>
                    <th>Name</th>
                    <th>Days</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Credit Hours</th>
                    <th>Enrollment Cap</th>
                    <?php if ($type === "student"): ?>
                        <th>Instructor</th>
                    <?php endif; ?>
                </tr>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['prefix'] ?></td>
                        <td><?= $course['course_number'] ?></td>
                        <td><?= $course['section'] ?></td>
                        <td><?= $course['name'] ?></td>
                        <td><?= $course['days'] ?></td>
                        <td><?= $course['time'] ?></td>
                        <td><?= $course['room'] ?></td>
                        <td><?= $course['credit_hours'] ?></td>
                        <td><?= $course['enrollment_cap'] ?></td>
                        <?php if ($type === "student"): ?>
                            <td><?= $course['instructor_name'] ?? 'N/A' ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No courses found for the selected <?= $type ?>.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
