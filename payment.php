<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 1.2em;
            color: #555;
            text-align: left;
        }

        input[type="text"] {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "rentals";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);
    $totalRent = isset($_SESSION['totalPrice']) ? $_SESSION['totalPrice'] : 0;

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;

    if ($orderId !== null) {
        $sql = "SELECT
                vd.rent_per_day,
                od.order_date,
                od.return_date,
                od.user_id
            FROM
                vehicle_details vd
            JOIN
                order_details od ON vd.vehicle_id = od.vehicle_id
            WHERE
                od.order_id = $orderId
        ";
        $result = $conn->query($sql);

        if (!$result) {
            die("Error in SQL query: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rentPerDay = $row["rent_per_day"];
            $orderDate = $row["order_date"];
            $returnDate = $row["return_date"];
            $userId = $row["user_id"];
            $orderDateObj = new DateTime($orderDate);
            $returnDateObj = new DateTime($returnDate);
            $daysDiff = $returnDateObj->diff($orderDateObj)->days;
            $totalRent = $rentPerDay * $daysDiff;
        } else {
            echo "Error: No data found.";
        }
    } else {
        echo "Error: Order ID not provided.";
    }

    if (isset($_POST['submitPayment'])) {
        $orderDate = $_POST['orderDate'];
        $checkUserQuery = "SELECT user_id FROM user WHERE user_id = $userId";
        $userExists = $conn->query($checkUserQuery);

        if ($userExists->num_rows > 0) {
            $sqlInsertPayment = "INSERT INTO payment_details (order_id, amount, pay_date) VALUES ('$orderId', '$totalRent', '$orderDate')";

            if ($conn->query($sqlInsertPayment) === TRUE) {
                echo '<script>alert("Payment successful!");</script>';
                echo '<script>window.location.href = "userdashboard.php?userId=' . $userId . '";</script>';
                exit();
            } else {
                echo "Error: " . $sqlInsertPayment . "<br>" . $conn->error;
            }
        } else {
            echo "Error: User with ID $userId does not exist.";
        }
    }

    $conn->close();
    ?>

    <button class="logout-btn" onclick="logout()">Logout</button>

    <div class="container">
        <h1>Payment Page</h1>

        <form method="post" action="">
            <label for="orderDate">Order Date:</label>
            <input type="text" id="orderDate" name="orderDate" value="<?php echo $orderDate; ?>" readonly>

            <label for="returnDate">Return Date:</label>
            <input type="text" id="returnDate" name="returnDate" value="<?php echo $returnDate; ?>" readonly>

            <label for="totalRent">Total Rent:</label>
            <input type="text" id="totalRent" name="totalRent" value="<?php echo $totalRent; ?>" readonly>

            <button type="submit" name="submitPayment">Submit Payment</button>
        </form>
    </div>

    <script>
        function logout() {
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "payment.php");

            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "logout");
            input.setAttribute("value", "true");

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
