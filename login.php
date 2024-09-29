<?php
session_start(); // Start session handling

$servername = "localhost:3307"; // Adjust if necessary
$username = "root";
$password = "105435";
$dbname = "paidpethouse"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login if the form is submitted
if (isset($_POST['login-button'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT Password FROM registrants WHERE Email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error . " SQL: SELECT Password FROM registrants WHERE Email = ?");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password (you may want to hash passwords when storing them in the database)
        if ($password === $hashed_password) {
            // Password is correct
            $_SESSION['email'] = $email; // Set session variable
            header('Location: index.html'); // Redirect to index page
            exit; // Stop further execution
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }

    // Clear the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    /* Concentric circle pattern background */
    background-image: radial-gradient(circle, #ede8d0 10%, transparent 10%, transparent 20%, #ede8d0 20%, #ede8d0 30%, transparent 30%, transparent 40%);
    background-color: #954535; /* Fallback background color */
    background-size: 50px 50px; /* Adjust size of circles */
    margin: 0;
    padding: 0;
    color: #333; /* Default text color */
}



        .container {
            max-width: 400px; /* Limit container width */
            padding: 40px; /* Padding for spacing */
            background-color: #954535; /* Set container background color */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slight shadow */
            text-align: center; /* Center-align content */
            margin: 100px auto; /* Center the container on the page */
            color: #fff; /* Change text color to white */
        }

        h2 {
            margin-bottom: 20px; /* Spacing below the heading */
        }

        form {
            display: flex; /* Flexbox for better layout */
            flex-direction: column; /* Stack form elements vertically */
            gap: 15px; /* Space between form elements */
            align-items: stretch; /* Stretch inputs */
        }

        label {
            text-align: left; /* Left-align labels */
            font-weight: bold; /* Make labels bold */
        }

        input {
            padding: 10px; /* Padding for input fields */
            border: 1px solid #ccc; /* Border style */
            border-radius: 5px; /* Rounded corners for inputs */
            font-size: 16px; /* Font size */
            width: 100%; /* Make inputs take full width */
            box-sizing: border-box; /* Include padding in width */
        }

        button {
            padding: 10px; /* Padding for the button */
            background-color: #ede8d0; /* Button background color */
            color: #954535; /* Button text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            font-size: 16px; /* Font size */
            width: 100%; /* Button takes full width */
            box-sizing: border-box; /* Include padding in width */
        }

        button:hover {
            background-color: #a96d53; /* Darker button on hover */
        }

        p {
            margin-top: 20px; /* Space above the paragraph */
        }

        a {
            color: #4CAF50; /* Link color */
            text-decoration: none; /* No underline */
        }

        a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login-button">Login</button>
        </form>
        <?php
            // Display error message if there is one
            if (isset($error_message)) {
                echo "<p style='color:red;'>" . htmlspecialchars($error_message) . "</p>"; // Sanitize output
            }
        ?>
        <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
    </div>
</body>
</html>
