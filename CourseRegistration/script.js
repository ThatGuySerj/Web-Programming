document.addEventListener("DOMContentLoaded", function () {
    const enrollForm = document.querySelector('.enrollstud');
    const instructorForm = document.querySelector('.instructor');
    const addCourseForm = document.querySelector('.addcourse');
    const dropCourseForm = document.querySelector('.dropcourse');
    const registerForm = document.querySelector('.registercourse');
    const daysDropdown = document.getElementById("days");
    const timeDropdown = document.getElementById("time");

    if (enrollForm) {
        enrollForm.addEventListener('submit', validateEnrollForm);
    }
    if (instructorForm) {
        instructorForm.addEventListener('submit', validateInstructorForm);
    }
    if (addCourseForm) {
        addCourseForm.addEventListener('submit', validateAddCourseForm);
    }
    if (dropCourseForm) {
        dropCourseForm.addEventListener('submit', validateDropCourseForm);
    }
    if (registerForm) {
        registerForm.addEventListener('submit', validateRegisterForm);
    }
    if (daysDropdown && timeDropdown) {
        daysDropdown.addEventListener("change", function () {
            updateTimeOptions(daysDropdown.value, timeDropdown);
        });
    }
});

function validateEnrollForm(event) {
    event.preventDefault(); // Prevent default form submission

    const firstName = document.getElementById('firstname').value.trim();
    const lastName = document.getElementById('lastname').value.trim();
    const year = document.getElementById('year').value;
    const major = document.getElementById('major').value.trim();
    const email = document.getElementById('email').value.trim();

    if (!/^[A-Za-z\s]+$/.test(firstName)) {
        alert("First Name must contain only letters and spaces.");
        return;
    }
    if (!/^[A-Za-z\s]+$/.test(lastName)) {
        alert("Last Name must contain only letters and spaces.");
        return;
    }
    if (!/^[A-Za-z]{3}\d{3}@marietta\.edu$/i.test(email)) {
        alert("Email must follow the format: abc123@marietta.edu");
        return;
    }

    // Send data to the server using fetch
    fetch('enroll_student.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            firstname: firstName,
            lastname: lastName,
            year: year,
            major: major,
            email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Enrollment successful!");
            document.getElementById('results').style.display = 'block';
            document.getElementById('display-name').textContent = `${firstName} ${lastName}`;
            document.getElementById('display-year').textContent = year;
            document.getElementById('display-major').textContent = major || "Undeclared";
            document.getElementById('display-email').textContent = email;
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred while enrolling the student.");
    });
}

function validateInstructorForm(event) {
    event.preventDefault(); // Prevent default form submission

    const firstName = document.getElementById('firstname').value.trim();
    const lastName = document.getElementById('lastname').value.trim();
    const department = document.getElementById('department').value.trim();
    const email = document.getElementById('email').value.trim();

    if (!/^[A-Za-z\s]+$/.test(firstName)) {
        alert("First Name must contain only letters and spaces.");
        return;
    }
    if (!/^[A-Za-z\s]+$/.test(lastName)) {
        alert("Last Name must contain only letters and spaces.");
        return;
    }
    if (!/^[A-Za-z]{3}\d{3}@marietta\.edu$/i.test(email)) {
        alert("Email must follow the format: abc123@marietta.edu");
        return;
    }
    if (department === "") {
        alert("Department must be entered.");
        return;
    }

    alert(`Instructor Added:\nName: ${firstName} ${lastName}\nDepartment: ${department}\nEmail: ${email}`);
}

function validateAddCourseForm(event) {
    event.preventDefault(); // Prevent default form submission

    const semester = document.getElementById("semester").value;
    const year = document.getElementById("year").value;
    const coursePrefix = document.getElementById("course_prefix").value.trim();
    const courseNumber = document.getElementById("course_number").value.trim();
    const courseSection = document.getElementById("course_section").value.trim();
    const courseName = document.getElementById("course_name").value.trim();
    const room = document.getElementById("room").value.trim();
    const days = document.getElementById("days").value;
    const time = document.getElementById("time").value;
    const creditHours = document.getElementById("credit_hours").value.trim();
    const instructorFirst = document.getElementById("instructor_first_name").value.trim();
    const instructorLast = document.getElementById("instructor_last_name").value.trim();
    const enrollmentCap = document.getElementById("enrollment_cap").value.trim();

    const errors = [];

    if (!/^[A-Z]{3,4}$/.test(coursePrefix)) {
        alert("Course Prefix must be 3 or 4 capital letters.");
    }
    if (!/^\d{3}$/.test(courseNumber) || parseInt(courseNumber) > 499) {
        alert("Course Number must be exactly 3 digits and ≤ 499.");
    }
    if (!/^\d{2}$/.test(courseSection)) {
        alert("Course Section must be exactly 2 digits.");
    }
    if (room && !/^[A-Za-z\s]+ \d{2,3}[A-Za-z]?$/.test(room)) {
        alert("Room must be like: Selby 101 or Rickey 220A.");
    }
    if (!/^[0-4](\.\d+)?$/.test(creditHours)) {
        alert("Credit Hours must be a number between 0 and 4.");
    }
    if (!/^[A-Za-z\s]+$/.test(instructorFirst)) {
        alert("Instructor First Name must contain only letters/spaces.");
    }
    if (!/^[A-Za-z\s]+$/.test(instructorLast)) {
        alert("Instructor Last Name must contain only letters/spaces.");
    }
    if (!/^\d{2}$/.test(enrollmentCap)) {
        alert("Enrollment Cap must be a two-digit number.");
    }
    if (!/^\d{4}$/.test(year) || parseInt(year) < 2025) {
        alert("Year must be four digits and 2025 or later.");
        return;
    }

    if (errors.length > 0) {
        alert("Please fix these errors:\n\n" + errors.join("\n"));
        return;
    }

    alert(`Course Added:\n\n` +
        `Semester: ${semester}\n` +
        `Course: ${coursePrefix} ${courseNumber} - Section ${courseSection}\n` +
        `Course Name: ${courseName}\n` +
        `Room: ${room}\n` +
        `Days: ${days}\n` +
        `Time: ${time}\n` +
        `Credit Hours: ${creditHours}\n` +
        `Instructor: ${instructorFirst} ${instructorLast}\n` +
        `Enrollment Cap: ${enrollmentCap}`);
}

function validateDropCourseForm(event) {
    event.preventDefault(); // Prevent default form submission

    const firstName = document.getElementById("drop_first_name").value.trim();
    const lastName = document.getElementById("drop_last_name").value.trim();
    const semester = document.getElementById("drop_semester").value.trim();
    const year = document.getElementById("drop_year").value.trim();
    const coursePrefix = document.getElementById("drop_course_prefix").value.trim();
    const courseNumber = document.getElementById("drop_course_number").value.trim();
    const courseSection = document.getElementById("drop_course_section").value.trim();

    const errors = [];

    if (!/^[A-Za-z\s]+$/.test(firstName)) {
        alert("First name is required and must contain only letters and spaces.");
        return;
    }
    if (!/^[A-Za-z\s]+$/.test(lastName)) {
        alert("Last name is required and must contain only letters and spaces.");
        return;
    }
    if (!semester) {
        alert("Semester required.");
        return;
    }
    if (!/^\d{4}$/.test(year) || parseInt(year) < 2025) {
        alert("Year must be four digits and 2025 or later.");
        return;
    }
    if (!/^[A-Z]{3,4}$/.test(coursePrefix)) {
        alert("Course prefix must be 3 or 4 uppercase letters.");
        return;
    }
    if (!/^\d{3}$/.test(courseNumber)) {
        alert("Course number must be exactly 3 digits.");
        return;
    }
    if (!/^\d{2}$/.test(courseSection)) {
        alert("Course section must be exactly 2 digits.");
        return;
    }

    if (errors.length > 0) {
        alert(errors.join("\n"));
        return;
    }

    alert(`Course Dropped Successfully:\n\nStudent Name: ${firstName} ${lastName}\nSemester: ${semester}\nCourse: ${coursePrefix} ${courseNumber} - Section ${courseSection}`);
}

function validateRegisterCourseForm(event) {
    event.preventDefault(); // Prevent default form submission

    const firstName = document.getElementById("first_name").value.trim();
    const lastName = document.getElementById("last_name").value.trim();
    const semester = document.getElementById("semester").value.trim();
    const year = document.getElementById("year").value.trim();
    const coursePrefix = document.getElementById("course_prefix").value.trim();
    const courseNumber = document.getElementById("course_number").value.trim();
    const courseSection = document.getElementById("course_section").value.trim();

    const errors = [];

    if (!/^[A-Za-z\s]+$/.test(firstName)) {
        alert("First name is required and must contain only letters and spaces.");
        return;
    }
    if (!/^[A-Za-z\s]+$/.test(lastName)) {
        alert("Last name is required and must contain only letters and spaces.");
        return;
    }
    if (!/^\d{4}$/.test(year) || parseInt(year) < 2025) {
        alert("Year must be four digits and 2025 or later.");
        return;
    }
    if (!/^[A-Z]{3,4}$/.test(coursePrefix)) {
        alert("Course prefix must be 3 or 4 uppercase letters.");
        return;
    }
    if (!/^\d{3}$/.test(courseNumber) || parseInt(courseNumber) > 499) {
        alert("Course number must be exactly 3 digits and not exceed 499.");
        return;
    }
    if (!/^\d{2}$/.test(courseSection)) {
        alert("Course section must be exactly 2 digits.");
        return;
    }

    if (errors.length > 0) {
        alert(errors.join("\n"));
        return;
    }

    alert(`Student Name: ${firstName} ${lastName}\nYear: ${year}\nCourse: ${coursePrefix} ${courseNumber} - Section ${courseSection}`);
}

function updateTimeOptions(selectedDays, timeDropdown) {
    // Clear existing options
    timeDropdown.innerHTML = "";

    // Define time options based on selected days
    const timeOptions = {
        MWF: [
            "8:00 AM - 8:50 AM",
            "9:00 AM - 9:50 AM",
            "10:00 AM - 10:50 AM",
            "11:00 AM - 11:50 AM",
            "12:00 PM - 12:50 PM",
            "1:00 PM - 1:50 PM",
            "2:00 PM - 2:50 PM",
            "3:00 PM - 3:50 PM",
        ],
        MW: [
            "8:00 AM - 8:50 AM",
            "9:00 AM - 9:50 AM",
            "10:00 AM - 10:50 AM",
            "11:00 AM - 11:50 AM",
            "12:00 PM - 12:50 PM",
            "1:00 PM - 1:50 PM",
            "2:00 PM - 2:50 PM",
            "3:00 PM - 3:50 PM",
        ],
        TR: [
            "8:00 AM - 9:15 AM",
            "9:30 AM - 10:45 AM",
            "11:00 AM - 12:15 PM",
            "1:00 PM - 2:15 PM",
            "2:30 PM - 3:45 PM",
        ],
        M: ["9:00 AM - 10:50 AM", "1:00 PM - 4:00 PM","2:00 PM - 5:00 PM","7:00 PM - 9:30 PM"],
        T: ["9:00 AM - 10:50 AM", "1:00 PM - 4:00 PM","2:00 PM - 5:00 PM","7:00 PM - 9:30 PM"],
        W: ["9:00 AM - 10:50 AM", "1:00 PM - 4:00 PM","2:00 PM - 5:00 PM","7:00 PM - 9:30 PM"],
        R: ["9:00 AM - 10:50 AM", "1:00 PM - 4:00 PM", "2:00 PM - 5:00 PM","7:00 PM - 9:30 PM"],
    };

    // Get the appropriate options for the selected days
    const options = timeOptions[selectedDays] || [];

    // Populate the time dropdown with the new options
    options.forEach((time) => {
        const option = document.createElement("option");
        option.value = time;
        option.textContent = time;
        timeDropdown.appendChild(option);
    });

    // If no options are available, add a default message
    if (options.length === 0) {
        const option = document.createElement("option");
        option.value = "";
        option.textContent = "No available times for selected days";
        timeDropdown.appendChild(option);
    }
}