<?php
require 'db_connection.php';
session_start(); // Start a session to store user data (e.g., login status)

// Check if the user is already logged in, redirect to a dashboard if necessary
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace these values with your actual database credentials
    $db_server = "localhost";
    $db_username = "admin"; // Your MySQL username
    $db_password = "admin";     // Your MySQL password (if any)
    $db_name = "web_app";  // Your database name

    // Create a database connection
    $conn = new mysqli($db_server, $db_username, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get input from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to check if the provided username and password match a record in the database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Login successful
        $_SESSION['username'] = $username; // Store the username in the session
        header("Location: dashboard.php"); // Redirect to the dashboard page
        exit;
    } else {
        // Login failed
        $error_message = "Invalid username or password. Please try again.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('bg.png'); /* Update with the correct path */
            background-size: center; 
            color: #333; /* Dark text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border: 2px solid #e8dba0; /* Light beige border */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            max-width: 400px;
            width: 100%;
            text-align: center; /* Center the text in the container */
        }

        h2 {
            color: #6d4c41; /* Darker brown for heading */
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #5d4037; /* Soft brown label color */
            text-align: left; /* Align labels to the left */
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px); /* Full width minus padding */
            padding: 12px;
            border: 2px solid #e8dba0; /* Light beige border */
            border-radius: 5px;
            background-color: #fff; /* White input background */
            color: #000; /* Black text in input */
            outline: none;
            transition: border-color 0.3s;
            margin: 0 auto; /* Center the inputs */
            display: block; /* Ensure block display for centering */
        }

        input[type="submit"] {
            background-color: #e0c8a0; /* Beige button */
            color: #000; /* Black text on button */
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%; /* Full width for button */
        }

        input[type="submit"]:hover {
            background-color: #c7b49b; /* Darker beige on hover */
            transform: scale(1.05); /* Slightly enlarge button on hover */
        }

        .error {
            color: red; /* Error message color */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Beige Bliss</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
