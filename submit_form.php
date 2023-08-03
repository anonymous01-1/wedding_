<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form data
    $name = $_POST["name"];
    $guest_count = isset($_POST["guest"]) ? (int)$_POST["guest"] : 0;
    $attendance = $_POST["attendance"];

    // Validate the required fields (name and attendance)
    if (empty($name) || (!isset($_POST["attendance"]))) {
        die("Please fill in all required fields.");
    }

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wedding_guests";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query using prepared statements
    $sql = "INSERT INTO guests (name, guest_count, attendance) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $name, $guest_count, $attendance);

    if ($stmt->execute()) {
		if ($attendance === "1") {
			echo "Form submitted successfully! Welcome to the party. نتشرف بحضوركم ونرحب بكم في الحفل.";
		} elseif ($attendance === "0") {
			echo "Form submitted successfully! We are sorry that you won't be able to attend the party. نأسف على عدم تمكنكم من الحضور في الحفل.";
		}
		else {
            echo "Form submitted successfully!";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and the database connection
    $stmt->close();
    $conn->close();
}
?>