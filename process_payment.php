<?php
// payment_process.php

// Database connection parameters
$servername = "localhost";
$username = "your_username"; // Replace with your database username
$password = "your_password"; // Replace with your database password
$dbname = "paidpethouse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $petName = $petType = "";
$duration = $total_amount = 0;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get payment details
    $transaction_id = htmlspecialchars($_POST['transaction_id']);
    $sender_number = htmlspecialchars($_POST['sender_number']);
    $name = htmlspecialchars($_POST['name']);
    $petName = htmlspecialchars($_POST['petName']);
    $petType = htmlspecialchars($_POST['petType']);
    $duration = intval($_POST['duration']);
    $total_amount = floatval($_POST['total_amount']);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO payments (transaction_id, sender_number, name, pet_name, pet_type, duration, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssids", $transaction_id, $sender_number, $name, $petName, $petType, $duration, $total_amount);

    // Execute the statement
    if ($stmt->execute()) {
        // Send amount to "01321868891"
        $recipientNumber = "01321868891";
        $response = sendPayment($recipientNumber, $total_amount);

        if ($response['status'] === 'success') {
            echo "<p>Payment recorded and sent successfully to $recipientNumber!</p>";
        } else {
            echo "<p>Payment recorded but failed to send: " . $response['error'] . "</p>";
        }
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close statement
    $stmt->close();
}

// If the page is accessed via POST, show the payment form
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['name'])) {
    // If there is no data from the previous page, set defaults
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $petName = isset($_POST['petName']) ? htmlspecialchars($_POST['petName']) : '';
    $petType = isset($_POST['petType']) ? htmlspecialchars($_POST['petType']) : '';
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;

    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #ede8d0, #954535);
            position: relative;
        }

        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            background-color: rgba(149, 53, 53, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #fff;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Information</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Pet Name:</strong> $petName</p>
        <p><strong>Pet Type:</strong> $petType</p>
        <p><strong>Duration of Stay:</strong> $duration days</p>
        <p><strong>Total Amount:</strong> $total_amount</p>

        <form action="" method="POST">
            <input type="hidden" name="name" value="$name">
            <input type="hidden" name="petName" value="$petName">
            <input type="hidden" name="petType" value="$petType">
            <input type="hidden" name="duration" value="$duration">
            <input type="hidden" name="total_amount" value="$total_amount">

            <label for="method">Payment Method:</label>
            <select id="method" name="method" required>
                <option value="bkash">Bkash</option>
                <option value="nogod">Nogod</option>
                <option value="rocket">Rocket</option>
            </select>

            <label for="transaction_id">Transaction ID:</label>
            <input type="text" id="transaction_id" name="transaction_id" required>

            <label for="sender_number">Sender's Number:</label>
            <input type="text" id="sender_number" name="sender_number" required>

            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>
HTML;
}

$conn->close();

// Dummy function to simulate sending payment
function sendPayment($recipientNumber, $amount) {
    // Here you would implement the API call to send money
    // For demonstration, we'll simulate a successful response
    return ['status' => 'success', 'error' => ''];
}
?>
