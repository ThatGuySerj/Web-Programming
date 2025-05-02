<?php
$host = "localhost";
$user = "root";      
$password = "";        
$dbname = "CourseRegistration";

// Create connection
$conn = new mysqli($host, $user, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db($dbname);

// Create Students table
$sql = "CREATE TABLE IF NOT EXISTS Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname NVARCHAR(50) NOT NULL,
    lastname NVARCHAR(50) NOT NULL,
    year NVARCHAR(20) NOT NULL,
    major NVARCHAR(50) NOT NULL,
    email NVARCHAR(100) NOT NULL
)";
$conn->query($sql);

// Create Instructors table
$sql = "CREATE TABLE IF NOT EXISTS Instructors (
    instructor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name NVARCHAR(50) NOT NULL,
    last_name NVARCHAR(50) NOT NULL,
    department NVARCHAR(50) NOT NULL,
    email NVARCHAR(100) NOT NULL
)";
$conn->query($sql);

// Create Courses table
$sql = "CREATE TABLE IF NOT EXISTS Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    prefix NVARCHAR(10) NOT NULL,
    course_number NVARCHAR(10) NOT NULL,
    section NVARCHAR(10) NOT NULL,
    name NVARCHAR(100) NOT NULL,
    days NVARCHAR(20) NOT NULL,
    time NVARCHAR(20) NOT NULL,
    room NVARCHAR(20) NOT NULL,
    credit_hours INT NOT NULL,
    enrollment_cap INT NOT NULL,
    instructor_id INT,
    FOREIGN KEY (instructor_id) REFERENCES Instructors(instructor_id)
)";
$conn->query($sql);

// Create Registration table
$sql = "CREATE TABLE IF NOT EXISTS Registration (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES Students(student_id),
    FOREIGN KEY (course_id) REFERENCES Courses(course_id),
    UNIQUE(student_id, course_id)
)";
$conn->query($sql);

echo "All tables created successfully";

$conn->close();
?>