<?php

// Check if the username exists in the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $username = $_POST['username'];

    // Your database connection code (replace with your actual database connection details)
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "rentals";

    // Create connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username exists in the database
    $checkUsernameQuery = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($checkUsernameQuery);
    if ($result->num_rows > 0) {
        // Username already exists
        echo "exists";
    } else {
        // Username is available
        echo "available";
    }

    $conn->close(); // Close the database connection
} else {
    // Invalid request
    echo "invalid";
}
?>
