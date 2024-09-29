<?php
// reviews.php
// Database connection parameters
$servername = "localhost:3307"; // Adjust if necessary
$username = "root";
$password = "105435";
$dbname = "paidpethouse"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for reviews
$petName = '';
$review = '';

// Check if the review form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitReview'])) {
    $petName = $_POST['petName'];
    $review = $_POST['review'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reviews (petName, review) VALUES (?, ?)");
    $stmt->bind_param("ss", $petName, $review);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Successfully added the review
        echo "<script>alert('Your review has been submitted!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch existing reviews from the database
$sql = "SELECT petName, review, created_at FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ede8d0 50%, #954535 50%);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #954535; /* Brown background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white; /* Text color for the container */
        }

        h2 {
            text-align: center;
            color: #ede8d0; /* Light color for the heading */
        }

        .review-card {
            background-color: rgba(255, 255, 255, 0.2); /* Slightly transparent background for reviews */
            border: 1px solid #ede8d0; /* Light border color */
            border-radius: 8px; /* Rounded corners */
            padding: 15px; /* Padding for review cards */
            margin-bottom: 15px; /* Space between reviews */
        }

        .review-card h3 {
            margin: 0;
            font-size: 1.2em; /* Slightly larger font for pet name */
            color: #ede8d0; /* Light color for the pet name */
        }

        .review-card p {
            margin: 5px 0;
            color: #ede8d0; /* Light color for the review text */
        }

        .review-form {
            margin-top: 20px;
        }

        .review-form input, .review-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .review-form button {
            padding: 10px;
            background-color: #ede8d0; /* Green button */
            color: black; /* White button text */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .review-form button:hover {
            background-color: #a96d53; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reviews</h2>

        <!-- Review Form -->
        <div class="review-form">
            <h3>Your Review</h3>
            <form method="POST" action="reviews.php">
                <input type="text" name="petName" placeholder="Pet Name" required value="<?php echo htmlspecialchars($petName); ?>">
                <textarea name="review" rows="5" placeholder="Write your review here..." required><?php echo htmlspecialchars($review); ?></textarea>
                <button type="submit" name="submitReview">Submit Review</button>
            </form>
        </div>

        <h3>All Reviews</h3>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <h3><?php echo htmlspecialchars($row['petName']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['review'])); ?></p>
                    <p><em><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></em></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
