<?php
// visit.php
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

// Initialize variables for search
$searchName = '';
$searchBreed = '';

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchName = isset($_POST['petName']) ? $_POST['petName'] : '';
    $searchBreed = isset($_POST['breed']) ? $_POST['breed'] : '';
}

// Prepare SQL query based on search inputs
$sql = "SELECT petName, breed, petPhoto FROM bookings WHERE 1=1";
if (!empty($searchName)) {
    $sql .= " AND petName LIKE '%" . $conn->real_escape_string($searchName) . "%'";
}
if (!empty($searchBreed)) {
    $sql .= " AND breed LIKE '%" . $conn->real_escape_string($searchBreed) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Page</title>
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

        .search-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px; /* Space between the input fields */
        }

        .search-form input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 30%; /* Adjust width */
        }

        .search-form button {
            padding: 10px;
            background-color: #ede8d0; /* Green button */
            color: black; /* White button text */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #a96d53; /* Darker green on hover */
        }

        .pet-card {
            display: flex;
            align-items: center;
            border: 1px solid #ede8d0; /* Light border color */
            border-radius: 8px; /* Rounded corners */
            padding: 20px; /* More padding for better spacing */
            margin-bottom: 15px;
            background-color: rgba(255, 255, 255, 0.2); /* More opaque background */
            transition: transform 0.2s; /* Animation effect on hover */
        }

        .pet-card:hover {
            transform: scale(1.02); /* Slightly enlarge on hover */
        }

        .pet-photo {
            width: 120px; /* Slightly larger width for images */
            height: auto; /* Maintain aspect ratio */
            border-radius: 8px; /* Match the card's corners */
            margin-right: 20px;
            border: 2px solid #ede8d0; /* Light border around the image */
        }

        .pet-info {
            flex-grow: 1; /* Allow the info section to grow */
        }

        .pet-info h3 {
            margin: 0;
            font-size: 1.5em; /* Larger font for the pet name */
            color: #ede8d0; /* Light color for the pet name */
        }

        .pet-info p {
            margin: 5px 0;
            font-size: 1.2em; /* Larger font for the breed */
            color: #ede8d0; /* Light color for the breed */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pets Visit Information</h2>
        
        <!-- Search Form -->
        <form class="search-form" method="POST" action="visit.php">
            <input type="text" name="petName" placeholder="Pet Name" value="<?php echo htmlspecialchars($searchName); ?>">
            <input type="text" name="breed" placeholder="Breed" value="<?php echo htmlspecialchars($searchBreed); ?>">
            <button type="submit">Search</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="pet-card">
                    <?php if (!empty($row['petPhoto'])): ?>
                        <img src="<?php echo htmlspecialchars($row['petPhoto']); ?>" alt="<?php echo htmlspecialchars($row['petName']); ?>" class="pet-photo">
                    <?php else: ?>
                        <img src="placeholder.jpg" alt="No photo available" class="pet-photo"> <!-- Placeholder image -->
                    <?php endif; ?>
                    <div class="pet-info">
                        <h3><?php echo htmlspecialchars($row['petName']); ?></h3>
                        <p>Breed: <?php echo htmlspecialchars($row['breed']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pets found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
