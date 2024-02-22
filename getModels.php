<?php
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

// Fetch available vehicle models based on the selected vehicle type
$vehicleType = $_POST['vehicleType'];
$sql = "SELECT vehicle_name FROM vehicle_details WHERE vehicle_type = '$vehicleType' AND available = 1";
$result = $conn->query($sql);

$models = array();

if ($result === false) {
    die("Error in SQL query: " . $conn->error);
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $models[] = $row["vehicle_name"];
    }
}

$conn->close();

// Return the available models in JSON format
header('Content-Type: application/json');
echo json_encode($models);
?> 
