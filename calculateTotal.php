<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "rentals";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vehicleType = isset($_POST['vehicleType']) ? $_POST['vehicleType'] : 'car';
$model = isset($_POST['model']) ? $_POST['model'] : '';

$sql = "SELECT rent_per_day FROM vehicle_details WHERE vehicle_type = '$vehicleType' AND vehicle_name = '$model' AND available = 1";
$result = $conn->query($sql);

if ($result === false) {
    die("Error in SQL query: " . $conn->error);
} elseif ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rentPerDay = $row["rent_per_day"];
    echo json_encode(['rentPerDay' => $rentPerDay]);
} else {
    echo json_encode(['error' => 'No results found.']);
}

$conn->close();
?>
