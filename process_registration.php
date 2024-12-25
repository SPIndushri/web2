<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize form data
    $student_id = htmlspecialchars(trim($_POST['student_id']));
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password'])); // Note: Hash passwords in production
    $dob = htmlspecialchars(trim($_POST['dob']));
    $course = htmlspecialchars(trim($_POST['course']));
    $year = htmlspecialchars(trim($_POST['year']));
    $family_income = htmlspecialchars(trim($_POST['family_income']));
    $reason = htmlspecialchars(trim($_POST['reason']));

    // Basic validation
    if (empty($student_id) || empty($full_name) || empty($email) || empty($dob) || empty($course) || empty($year) || empty($family_income) || empty($reason)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (!is_numeric($family_income) || $family_income < 0) {
        die("Family income must be a valid number.");
    }

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password_db = ""; // Default password for XAMPP is blank
    $dbname = "scholarship_db";

    // Create a connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check the database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query with prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO scholarship_applications (student_id, full_name, email, dob, course, year, family_income, reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }
    $stmt->bind_param("ssssssis", $student_id, $full_name, $email, $dob, $course, $year, $family_income, $reason);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
    exit;
}
?>
