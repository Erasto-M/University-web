<?php
// Start session to access session variables
session_start();

// Check if user is logged in and is a faculty member
if (!isset($_SESSION['userId']) || !isset($_SESSION['facultyFirstName']) || !isset($_SESSION['facultyLastName'])) {
    // Redirect to login page if not logged in
    header('Location: ./login.html');
    exit();
}

include 'db_connection.php';  // Include your database connection file

// Fetch the Faculty ID from the session
$facultyId = $_SESSION['userId'];

// Fetch faculty-specific data from the database (example: course assignments, advisees)
$faculty_sql = "SELECT * FROM Faculty WHERE FacultyId = ?";
$stmt = $conn->prepare($faculty_sql);
$stmt->bind_param("s", $facultyId);
$stmt->execute();
$faculty_result = $stmt->get_result();

if ($faculty_result->num_rows > 0) {
    $faculty = $faculty_result->fetch_assoc();
} else {
    // If faculty record is not found, redirect to login page
    header('Location: ./login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Console</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
</head>
<body>

    <!-- Faculty Navigation Bar -->
    <nav>
        <ul>
            <li><a href="faculty_home.php">Home</a></li>
            <li><a href="view_advisees.php">View Advisees</a></li>
            <li><a href="view_schedule.php">View Schedule</a></li>
            <li><a href="view_grades.php">View Grades</a></li>
            <li><a href="update_info.php">Update Personal Information</a></li>
            <li><a href="assign_course.php">Assign Course</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Faculty Console Content -->
    <section>
        <h1>Welcome, <?php echo $faculty['FirstName']; ?> <?php echo $faculty['LastName']; ?>!</h1>
        <p><strong>Faculty ID:</strong> <?php echo $faculty['FacultyId']; ?></p>
        <p><strong>Full Name:</strong> <?php echo $faculty['FirstName']; ?> <?php echo $faculty['LastName']; ?></p>

        <!-- Navigation Section -->
        <h2>Faculty Console</h2>
        <p>Welcome to your Faculty Console. You can manage your academic tasks here:</p>
        <ul>
            <li><a href="view_advisees.php">View Your Advisees</a></li>
            <li><a href="view_schedule.php">View Your Semester Schedule</a></li>
            <li><a href="view_grades.php">View and Update Course Grades</a></li>
            <li><a href="update_info.php">Update Your Personal Information</a></li>
            <li><a href="assign_course.php">Assign Course Sections</a></li>
        </ul>

        <h3>Your Assigned Courses</h3>
        <?php
        // Query to fetch the course sections assigned to the faculty
        $courses_sql = "SELECT c.CourseName, cs.SectionId 
                        FROM Course c
                        JOIN CourseSection cs ON cs.FacultyId = ?
                        WHERE cs.FacultyId = ?";
        $courses_stmt = $conn->prepare($courses_sql);
        $courses_stmt->bind_param("ss", $facultyId, $facultyId);
        $courses_stmt->execute();
        $courses_result = $courses_stmt->get_result();

        if ($courses_result->num_rows > 0) {
            echo "<ul>";
            while ($course = $courses_result->fetch_assoc()) {
                echo "<li>Course: " . $course['CourseName'] . " | Section: " . $course['SectionId'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>You are not assigned to any course sections.</p>";
        }
        ?>

        <h3>Your Advisees</h3>
        <?php
        // Query to fetch advisees
        $advisees_sql = "SELECT s.StudentId, s.FirstName, s.LastName
                         FROM Student s
                         JOIN Advisor a ON a.FacultyId = ?
                         WHERE a.FacultyId = ?";
        $advisees_stmt = $conn->prepare($advisees_sql);
        $advisees_stmt->bind_param("ss", $facultyId, $facultyId);
        $advisees_stmt->execute();
        $advisees_result = $advisees_stmt->get_result();

        if ($advisees_result->num_rows > 0) {
            echo "<ul>";
            while ($advisee = $advisees_result->fetch_assoc()) {
                echo "<li><a href='view_advisee.php?studentId=" . $advisee['StudentId'] . "'>" . $advisee['FirstName'] . " " . $advisee['LastName'] . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>You have no assigned advisees.</p>";
        }
        ?>
    </section>

</body>
</html>
