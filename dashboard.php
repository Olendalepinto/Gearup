<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .returned-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            cursor: pointer;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Orders with Vehicles Not Returned</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Date</th>
                <th>Return Date</th>
                <th>Vehicle Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $dbUsername = "root";
            $dbPassword = "";
            $dbName = "rentals";

            $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT order_details.*, vehicle_details.vehicle_name 
                    FROM order_details 
                    INNER JOIN vehicle_details 
                    ON order_details.vehicle_id = vehicle_details.vehicle_id 
                    WHERE order_details.vehicle_returned = 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["user_id"] . "</td>";
                    echo "<td>" . $row["order_date"] . "</td>";
                    echo "<td>" . $row["return_date"] . "</td>";
                    echo "<td>" . $row["vehicle_name"] . "</td>";
                    echo "<td><button class='returned-btn' onclick='markReturned(" . $row["order_id"] . ")'>Returned</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <h2>Orders with Vehicles Returned</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Date</th>
                <th>Return Date</th>
                <th>Vehicle Type</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT order_details.*, vehicle_details.vehicle_name 
                    FROM order_details 
                    INNER JOIN vehicle_details 
                    ON order_details.vehicle_id = vehicle_details.vehicle_id 
                    WHERE order_details.vehicle_returned = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["user_id"] . "</td>";
                    echo "<td>" . $row["order_date"] . "</td>";
                    echo "<td>" . $row["return_date"] . "</td>";
                    echo "<td>" . $row["vehicle_name"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <form action="index.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <script>
        function markReturned(orderId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "mark_returned.php?orderId=" + orderId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload();
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
