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

// Process registration if the form is submitted
if (isset($_POST['register-button'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        // Removed the status message
    } else {
        // Check if the email already exists
        $stmt = $conn->prepare("SELECT Email FROM registrants WHERE Email = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error . " SQL: SELECT Email FROM registrants WHERE Email = ?");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Removed the status message
        } else {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO registrants (Name, Email, Password, registration_date) VALUES (?, ?, ?, NOW())");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error . " SQL: INSERT INTO registrants (Name, Email, Password, registration_date) VALUES (?, ?, ?, NOW())");
            }
            $stmt->bind_param("sss", $name, $email, $password); // Store plain password

            if ($stmt->execute()) {
                header('Location: login.php'); // Redirect to login page after successful registration
                exit; // Stop further execution
            } else {
                // Removed the status message
            }
        }
    }

    // Redirect back to the registration page
    header('Location: registration.php');
    exit; // Stop further execution
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    /* Geometric circle pattern background */
    background-image: radial-gradient(circle at 1px 1px, #ede8d0, transparent 50%), 
                      radial-gradient(circle at 5px 5px, #954535, transparent 50%);
    background-size: 10px 10px; /* Adjust size of the dots */
    margin: 0;
    padding: 0;
    color: #333; /* Default text color */
}


        .container {
            max-width: 400px; /* Limit container width for better appearance */
            padding: 40px; /* Increased padding for better spacing */
            background-color: #954535; /* Set container background color to #954535 */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slightly elevated container */
            text-align: center; /* Center-align content */
            margin: 100px auto; /* Center the container on the page */
            color: #fff; /* Change text color to white for contrast */
        }

        h2 {
            margin-bottom: 20px; /* Spacing below the heading */
        }

        form {
            display: flex; /* Flexbox for better layout */
            flex-direction: column; /* Stack form elements vertically */
            gap: 15px; /* Space between form elements */
            align-items: stretch; /* Make inputs stretch to fill the container */
        }

        label {
            text-align: left; /* Left-align labels */
            font-weight: bold; /* Make labels bold */
        }

        input {
            padding: 10px; /* Padding for input fields */
            border: 1px solid #ccc; /* Border style */
            border-radius: 5px; /* Rounded corners for inputs */
            font-size: 16px; /* Font size for input text */
            width: 100%; /* Make inputs take full width */
            box-sizing: border-box; /* Include padding and border in width calculation */
        }

        button {
            padding: 10px; /* Padding for the button */
            background-color: #ede8d0; /* Green background */
            color: #954535; /* White text */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size for button text */
            width: 100%; /* Make button take full width */
            box-sizing: border-box; /* Include padding and border in width calculation */
        }

        button:hover {
            background-color: #a96d53; /* Darker green on hover */
        }

        p {
            margin-top: 20px; /* Space above the paragraph */
        }

        a {
            color: #4CAF50; /* Green link color */
            text-decoration: none; /* Remove underline */
        }

        a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Your Account</h2>
        <form action="registration.php" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit" name="register-button">Step Inside</button>
        </form>
        <?php
            // Removed success message display
            // Removed status message display
        ?>
        <p>Already have an account? <a href="login.php">Sign in here</a>.</p>
    </div>
</body>
</html>
