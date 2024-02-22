<?php
session_start();
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "rentals";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_GET['userId'];

$orderDate = $_POST['orderDate'];
$returnDate = $_POST['returnDate'];
$vehicleType = $_POST['vehicleType'];
$model = $_POST['model'];

$sqlVehicleId = "SELECT vehicle_id FROM vehicle_details WHERE vehicle_type = '$vehicleType' AND vehicle_name = '$model' AND available = 1";
$resultVehicleId = $conn->query($sqlVehicleId);

if ($resultVehicleId === false) {
    die("Error in SQL query: " . $conn->error);
}

if ($resultVehicleId->num_rows > 0) {
    $rowVehicleId = $resultVehicleId->fetch_assoc();
    $vehicleID = $rowVehicleId["vehicle_id"];

    $sqlInsertOrder = "INSERT INTO order_details (user_id, order_date, return_date, vehicle_type, vehicle_id) VALUES ('$userID', '$orderDate', '$returnDate', '$vehicleType', '$vehicleID')";
    $resultInsertOrder = $conn->query($sqlInsertOrder);

    if ($resultInsertOrder === false) {
        die("Error in SQL query: " . $conn->error);
    }

    $sqlRentPerDay = "SELECT rent_per_day FROM vehicle_details WHERE vehicle_id = '$vehicleID' AND available = 1";
    $resultRentPerDay = $conn->query($sqlRentPerDay);

    if ($resultRentPerDay === false) {
        die("Error in SQL query: " . $conn->error);
    }

    if ($resultRentPerDay->num_rows > 0) {
        $rowRentPerDay = $resultRentPerDay->fetch_assoc();
        $rentPerDay = $rowRentPerDay["rent_per_day"];

        $orderDateObj = new DateTime($orderDate);
        $returnDateObj = new DateTime($returnDate);
        $daysDiff = $returnDateObj->diff($orderDateObj)->days;
        $totalPrice = $rentPerDay * $daysDiff;

        $_SESSION['totalPrice'] = $totalPrice;

        $sqlGetLargestOrderId = "SELECT MAX(order_id) AS largest_order_id FROM order_details";
        $resultGetLargestOrderId = $conn->query($sqlGetLargestOrderId);

        if ($resultGetLargestOrderId === false) {
            die("Error in SQL query: " . $conn->error);
        }

        $rowGetLargestOrderId = $resultGetLargestOrderId->fetch_assoc();

        $largestOrderId = $rowGetLargestOrderId["largest_order_id"];

        $conn->close();

        header("Location: payment.php?orderId=$largestOrderId");
    } else {
        $conn->close();
        echo "Error: No rent per day found.";
    }
} else {
    $conn->close();
    echo "Error: No matching vehicle found.";
}
?>
