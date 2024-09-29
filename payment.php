<?php
// payment.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the booking details from the previous page
    $name = htmlspecialchars($_POST['name']);
    $petName = htmlspecialchars($_POST['petName']);
    $petType = htmlspecialchars($_POST['petType']);
    $duration = intval($_POST['duration']);
    $total_amount = floatval($_POST['total_amount']);
}

?>

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
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Pet Name:</strong> <?php echo $petName; ?></p>
        <p><strong>Pet Type:</strong> <?php echo ucfirst($petType); ?></p>
        <p><strong>Duration of Stay:</strong> <?php echo $duration; ?> days</p>
        <p><strong>Total Amount:</strong> $<?php echo number_format($total_amount, 2); ?></p>

        <form action="process_payment.php" method="POST">
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
