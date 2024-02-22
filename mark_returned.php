<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "rentals";

// Check if orderId is provided in the GET request
if(isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    // Create connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to update vehicle_returned to 1 for the specified order
    $sql = "UPDATE order_details SET vehicle_returned = 1 WHERE order_id = $orderId";

    if ($conn->query($sql) === TRUE) {
        echo "Order marked as returned successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close connection
    $conn->close();
} else {
    echo "Order ID not provided";
}
?>
