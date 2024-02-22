
<?php
// Assuming you have a database connection established

$usernameError = "";
$passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Login form submission
    if (isset($_POST['login'])) {
        $username = $_POST["username"];
        $UserPassword = $_POST["password"];

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND UserPassword = ?");
        $stmt->bind_param("ss", $username, $UserPassword);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // User login successful
            $user = $result->fetch_assoc();
            // Redirect to index1.php with the car model included in the URL
            header("Location: index1.php?userId=" . $user['user_id']);
            exit(); // Ensure subsequent code is not executed after redirection
        } else {
            // Invalid credentials
            echo "Invalid Username or Password";
        }
        $stmt->close();
    }

    // Signup form submission
    elseif (isset($_POST['signup'])) {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $phone = $_POST["phone"];
        $newUserPassword = $_POST["newUserPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        // Validate password match
        if ($newUserPassword !== $confirmPassword) {
            echo "Passwords do not match";
            exit();
        }

        // Auto-generate username
        $username = ucfirst(strtolower($firstName)) . "@gearup";

        // Check if the username already exists
        $checkUsernameQuery = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($checkUsernameQuery);
        if ($result->num_rows > 0) {
            // Username already exists, set error message
            $usernameError = "Username already exists. Please choose a different username.";
        }
        if (strlen($newUserPassword) < 6) {
            // Password length is less than 6, set error message
            $passwordError = "Password must contain at least 6 characters.";
        }
        // Sanitize input to prevent SQL injection (you should use prepared statements instead)
        $firstName = mysqli_real_escape_string($conn, $firstName);
        $lastName = mysqli_real_escape_string($conn, $lastName);
        $phone = mysqli_real_escape_string($conn, $phone);
        $newUserPassword = mysqli_real_escape_string($conn, $newUserPassword);

        // Query to insert new user into the database (assuming plain text passwords)
        $sql = "INSERT INTO user (username, firstName, lastName, phone, UserPassword) VALUES ('$username', '$firstName', '$lastName', '$phone', '$newUserPassword')";

        if ($conn->query($sql) === TRUE) {
            // Provide feedback that signup is successful
            echo '<script>alert("Signup is Successful"); window.location.href = "main.php";</script>';
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Bike and Car Rentals - Login</title>
</head>
<body>
<div class="container">
    <img src="images/gearupbgno.png" alt="Rental Logo" class="logo">
    <div id="loginFormContainer">
        <h2>Login</h2>
        <form id="loginForm" action="main.php?carModel=<?php echo urlencode($_GET['carModel'] ?? ''); ?>" method="POST">
            <input type="hidden" name="carModel" value="<?php echo urlencode($_GET['carModel'] ?? ''); ?>">
            <label for="username">Username:</label>
            <!-- Use the placeholder attribute for watermark -->
            <input type="text" name="username" id="username" placeholder="firstname@gearup" required>

            <label for="password">Password:</label>
            <div class="password-input">
                <input type="password" name="password" id="loginPassword" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('loginPassword')">Show</span>
            </div>

            <button type="submit" name="login">Login</button>
        </form>
        <p id="signupLink">Don't have an account? <a href="#" onclick="showSignupForm()">Signup here</a></p>
    </div>

    <div id="signupFormContainer" style="display: none;">
        <h2>Signup</h2>

        <form id="signupForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" id="signupFirstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" id="signupLastName" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" id="signupPhone" pattern="[0-9]{10}" required>

            <label for="username">Username:</label>
            <input type="text" name="username" id="signupUsername" required>
            <span id="usernameError" style="color: red;"><?php echo $usernameError; ?></span>

            <div class="password-input">
                <label for="newUserPassword">New Password:</label>
                <input type="password" name="newUserPassword" id="signupPassword" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('signupPassword')">Show</span>
            </div>
            <div id="passwordError" style="color: red;"><?php echo $passwordError; ?></div>

            <div class="password-input">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" name="confirmPassword" id="confirmPassword" required>
            </div>
            <div id="passwordError" style="color: red;"></div>

            <button type="submit" name="signup">Signup</button>
        </form>
        <p id="loginLink">Already have an account? <a href="#" onclick="showLoginForm()">Login here</a></p>
    </div>
</div>
<script src="script.js"></script>

<script>
    function showSignupForm() {
        var signupFormContainer = document.getElementById("signupFormContainer");
        signupFormContainer.style.display = "block";

        var loginFormContainer = document.getElementById("loginFormContainer");
        loginFormContainer.style.display = "none";
    }

    function showLoginForm() {
        var signupFormContainer = document.getElementById("signupFormContainer");
        signupFormContainer.style.display = "none";

        var loginFormContainer = document.getElementById("loginFormContainer");
        loginFormContainer.style.display = "block";
    }

    function togglePasswordVisibility(inputId) {
        var passwordInput = document.getElementById(inputId);
        var toggleIcon = passwordInput.nextElementSibling;
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.textContent = "Hide";
        } else {
            passwordInput.type = "password";
            toggleIcon.textContent = "Show";
        }
    }

    document.getElementById("signupPassword").addEventListener("input", function() {
        var confirmPassword = document.getElementById("signupPassword");
        var toggleIcon = confirmPassword.nextElementSibling;

        // If the user starts typing, make the password visible
        if (confirmPassword.type === "password") {
            confirmPassword.type = "text";
            toggleIcon.textContent = "hide";
        }
    });

    // Automatically append "@gearup" to the signup username input
    document.getElementById("signupFirstName").addEventListener("input", function() {
        var firstNameInput = document.getElementById("signupFirstName");
        var usernameInput = document.getElementById("signupUsername");
        var currentValue = firstNameInput.value;
        // Capitalize the first letter of the first name
        var capitalizedFirstName = currentValue.charAt(0).toUpperCase() + currentValue.slice(1);
        // Append "@gearup" to the capitalized first name
        var username = capitalizedFirstName.toLowerCase() + "@gearup";
        usernameInput.value = username;
    });

    function validatePasswordLength() {
        var passwordInput = document.getElementById("signupPassword");
        var passwordError = document.getElementById("passwordError");
        
        if (passwordInput.value.length < 6) {
            passwordError.textContent = "Password must contain at least 6 characters.";
            return false;
        } else {
            passwordError.textContent = "";
            return true;
        }
    }

    document.getElementById("signupForm").addEventListener("submit", function(event) {
        if (!validatePasswordLength()) {
            event.preventDefault(); // Prevent form submission if password is invalid
        }
    });

    function validateForm() {
        var passwordInput = document.getElementById("signupPassword");
        var confirmPasswordInput = document.getElementById("confirmPassword");
        var passwordError = document.getElementById("passwordError");
        
        if (passwordInput.value.length < 6) {
            passwordError.textContent = "Password must contain at least 6 characters.";
            return false;
        } else if (passwordInput.value !== confirmPasswordInput.value) {
            passwordError.textContent = "Passwords do not match.";
            return false;
        } else {
            passwordError.textContent = "";
            return true;
        }
    }

    document.getElementById("signupForm").addEventListener("submit", function(event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });

    // Automatically append "@gearup" to the username input
    document.getElementById("signupFirstName").addEventListener("input", function() {
    var firstNameInput = document.getElementById("signupFirstName");
    var usernameInput = document.getElementById("signupUsername");
    var currentValue = firstNameInput.value;
    // Capitalize the first letter of the first name
    var capitalizedFirstName = currentValue.charAt(0).toUpperCase() + currentValue.slice(1);
    // Append "@gearup" to the capitalized first name
    var username = capitalizedFirstName + "@gearup";
    usernameInput.value = username;
});


    // Check if the entered username already exists
    document.getElementById("signupUsername").addEventListener("change", function() {
        var usernameInput = document.getElementById("signupUsername");
        var username = usernameInput.value;
        
        // AJAX request to check username availability
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "check_username.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                if (response === "exists") {
                    // Username already exists, show error message
                    document.getElementById("usernameError").textContent = "Username already exists. Please choose a different username.";
                } else {
                    // Username is available, clear error message
                    document.getElementById("usernameError").textContent = "";
                }
            }
        };
        xhr.send("username=" + username);
    });
</script>