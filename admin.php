<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Bike and Car Rentals - Admin Login</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $adminUsername = $_POST["adminUsername"];
        $adminPassword = $_POST["adminPassword"];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "rentals";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $adminUsername = mysqli_real_escape_string($conn, $adminUsername);
        $adminPassword = mysqli_real_escape_string($conn, $adminPassword);

        $sql = "SELECT * FROM admin WHERE adminUsername = '$adminUsername' AND adminPassword = '$adminPassword'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid Admin Username or Password";
        }

        $conn->close();
    }
    ?>
    
    <div class="container">
        <img src="images/gearupbgno.png" alt="Rental Logo" class="logo">
        <div id="adminLoginFormContainer">
            <h2>Admin Login</h2>
            <form id="adminLoginForm" action="admin.php" method="POST">
                <label for="adminUsername">Admin Username:</label>
                <input type="text" name="adminUsername" required>

                <label for="adminPassword">Admin Password:</label>
                <input type="password" name="adminPassword" required>

                <button type="submit">Login as Admin</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
