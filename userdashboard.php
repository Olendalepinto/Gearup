<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .user-details {
            margin-top: 20px;
        }

        .user-details h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .user-details p {
            margin: 5px 0;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Dashboard</h1>

        <?php
        // Establish connection to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "rentals";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user ID from URL parameter
        $user_id = $_GET['userId']; // Fetch user ID from URL parameter

        // Query to fetch user details where user_id is $user_id
        $sql = "SELECT * FROM user WHERE user_id = $user_id";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                // Fetch user details
                $user = $result->fetch_assoc();
        ?>

        <div class="user-details">
            <h2>User Details</h2>
            <!-- Display user details -->
            <p><strong>First Name:</strong> <?php echo $user['firstName']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $user['lastName']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['username']; ?></p>
            <!-- Add more user details as needed -->
        </div>

        <?php
                // Query to fetch user orders with car name
                $orderSql = "SELECT order_details.order_id, order_details.order_date, order_details.return_date, payment_details.amount, vehicle_details.vehicle_name, order_details.vehicle_type, order_details.vehicle_id 
                             FROM order_details
                             JOIN payment_details ON order_details.order_id = payment_details.order_id
                             JOIN vehicle_details ON order_details.vehicle_id = vehicle_details.vehicle_id
                             WHERE order_details.user_id = $user_id"; // Assuming the orders table has a column user_id
                $orderResult = $conn->query($orderSql);
                if ($orderResult) {
        ?>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Return Date</th>
                    <th>Price</th>
                    <th>Vehicle Name</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop through each order and display details
                    if ($orderResult->num_rows > 0) {
                        while ($order = $orderResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$order['order_id']}</td>";
                            echo "<td>{$order['order_date']}</td>";
                            echo "<td>{$order['return_date']}</td>";
                            echo "<td>{$order['amount']}</td>"; // Price retrieved from payment_details
                            echo "<td>{$order['vehicle_name']}</td>";
                            echo "<td>{$order['vehicle_type']}</td>";
                            echo "<td>{$order['vehicle_id']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No orders found for this user.</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <?php
                } else {
                    echo "Error fetching user orders: " . $conn->error;
                }
            } else {
                echo "No user found with user_id $user_id";
            }
        } else {
            echo "Error fetching user details: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <!-- Back Button -->
    <button class="back-btn" onclick="window.location.href='index1.php?userId=<?php echo $user_id; ?>'">Back</button>

    <!-- Logout button -->
    <form method="post" action="index.php">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>
</body>
</html>
