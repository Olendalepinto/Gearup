<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Rental Vehicle Order</title>
    <style>
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

        .dashboard-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .dashboard-btn:hover {
            background-color: #45a049;
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
    ?>

    <button class="logout-btn" onclick="logout()">Logout</button>
    <a href="userdashboard.php?userId=<?php echo $_GET['userId']; ?>"><button class="dashboard-btn">Go to Dashboard</button></a>

    <div class="container">
        <div class="form-container">
            <h2>Rental Vehicle Order</h2>
            <form id="orderForm" method="post" action="processOrder.php?userId=<?php echo $_GET['userId']; ?>" action="payment.php">
                <label for="orderDate">Order Date:</label>
                <input type="date" id="orderDate" name="orderDate" required>

                <label for="returnDate">Return Date:</label>
                <input type="date" id="returnDate" name="returnDate" required>

                <label>Select Vehicle Type:</label>
                <div class="radio-container">
                    <?php
                    $servername = "localhost";
                    $dbUsername = "root";
                    $dbPassword = "";
                    $dbName = "rentals";
                    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

                    $sqlTypes = "SELECT DISTINCT vehicle_type FROM vehicle_details";
                    $resultTypes = $conn->query($sqlTypes);

                    while ($row = $resultTypes->fetch_assoc()) {
                        $vehicleType = $row["vehicle_type"];
                        echo "<input type='radio' id='$vehicleType' name='vehicleType' value='$vehicleType' required>";
                        echo "<label for='$vehicleType'>$vehicleType</label>";
                    }
                
                    $conn->close();
                    ?>
                </div>

                <label for="model">Select Vehicle Model:</label>
                <select id="model" name="model" required>
                </select>

                <button type="button" onclick="calculateTotal()">Calculate Total</button>
                <button type="submit">Book Now</button>
            </form>
        </div>

        <div class="result-container">
            <h2>Order Summary</h2>
            <p><strong>Price per Day:</strong> ₹<span id="pricePerDay">0</span></p>
            <p><strong>Total Price:</strong> ₹<span id="totalPrice">0.00</span></p>
        </div>
    </div>

    <script src="script.js"></script>

    <script>
        var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth() + 1;
        var year = currentDate.getFullYear();

        if (day < 10) {
            day = '0' + day;
        }
        if (month < 10) {
            month = '0' + month;
        }
        var today = year + '-' + month + '-' + day;

        document.getElementById('orderDate').setAttribute('min', today);
        document.getElementById('returnDate').setAttribute('min', today);

        document.getElementById('returnDate').addEventListener('change', function() {
            var orderDate = new Date(document.getElementById('orderDate').value);
            var returnDate = new Date(this.value);

            if (returnDate <= orderDate) {
                var nextDay = new Date(orderDate);
                nextDay.setDate(nextDay.getDate() + 1);
                var nextDayFormatted = nextDay.toISOString().substr(0, 10);
                this.value = nextDayFormatted;
            }
        });

        function logout() {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'index.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'logout';
            input.value = 'true';

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
