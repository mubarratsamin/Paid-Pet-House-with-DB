<?php
// Set amounts per day for each pet type
$amount_per_day = [
    'dog' => 200,
    'cat' => 250
];

// Retrieve booking details from the URL parameters
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Guest';
$petName = isset($_GET['petName']) ? htmlspecialchars($_GET['petName']) : 'Pet';
$petType = isset($_GET['petType']) ? htmlspecialchars($_GET['petType']) : 'dog';
$duration = isset($_GET['duration']) ? intval($_GET['duration']) : 1;

// Calculate total amount
$total_amount = $amount_per_day[$petType] * $duration;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing</title>
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

        p {
            margin: 10px 0;
        }

        .total {
            font-size: 24px;
            font-weight: bold;
            color: #2ecc71;
        }

        label, input, select {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        input[type="text"], select {
            background-color: #fff;
            color: #000;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #ede8d0;
            color: #954535;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #a96d53;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Billing Summary</h2>
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Pet Name:</strong> <?php echo $petName; ?></p>
        <p><strong>Pet Type:</strong> <?php echo ucfirst($petType); ?></p>
        <p><strong>Duration of Stay:</strong> <?php echo $duration; ?> days</p>
        <p class="total">Total Amount: <?php echo number_format($total_amount, 2); ?> Taka</p>
        
        <form action="billing.php" method="POST">
            <input type="hidden" name="name" value="<?php echo $name; ?>">
            <input type="hidden" name="petName" value="<?php echo $petName; ?>">
            <input type="hidden" name="petType" value="<?php echo $petType; ?>">
            <input type="hidden" name="duration" value="<?php echo $duration; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

            <label for="recipient-number"><strong>Recipient's Number:</strong></label>
            <input type="text" id="recipient-number" name="recipient_number" value="01321868891" readonly>

            <label for="payment-method"><strong>Payment Method:</strong></label>
            <select id="payment-method" name="payment_method" required>
                <option value="" disabled selected>Select Payment Method</option>
                <option value="bkash">Bkash</option>
                <option value="nogod">Nogod</option>
                <option value="rocket">Rocket</option>
            </select>

            <label for="transaction-id"><strong>Transaction ID:</strong></label>
            <input type="text" id="transaction-id" name="transaction_id" placeholder="Enter Transaction ID" required>

            <label for="sender-number"><strong>Sender's Number:</strong></label>
            <input type="text" id="sender-number" name="sender_number" placeholder="Enter Sender's Number" required>

            <button type="submit">Confirm Payment</button>
        </form>
    </div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $servername = "localhost:3307";
    $username = "root";
    $password = "105435";
    $dbname = "paidpethouse";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $petName = isset($_POST['petName']) ? htmlspecialchars($_POST['petName']) : '';
    $petType = isset($_POST['petType']) ? htmlspecialchars($_POST['petType']) : '';
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0.0;
    $recipient_number = isset($_POST['recipient_number']) ? htmlspecialchars($_POST['recipient_number']) : '';
    $payment_method = isset($_POST['payment_method']) ? htmlspecialchars($_POST['payment_method']) : '';
    $transaction_id = isset($_POST['transaction_id']) ? htmlspecialchars($_POST['transaction_id']) : '';
    $sender_number = isset($_POST['sender_number']) ? htmlspecialchars($_POST['sender_number']) : '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO billing (name, petName, petType, duration, total_amount, recipient_number, payment_method, transaction_id, sender_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssidsiss", $name, $petName, $petType, $duration, $total_amount, $recipient_number, $payment_method, $transaction_id, $sender_number);

    if ($stmt->execute()) {
        // Redirect to confirmation.html after successful payment
        header('Location: confirmation.html');
        exit();
    } else {
        echo "<script>alert('Payment failed. Please try again.');</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
