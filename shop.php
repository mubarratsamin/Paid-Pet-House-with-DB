<?php
// Database connection
$servername = "localhost:3307"; // Change if necessary
$username = "root";               // Your MySQL username
$password = "105435";            // Your MySQL password
$dbname = "paidpethouse";        // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if they don't exist
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('accessory', 'food') NOT NULL,
    price FLOAT NOT NULL,
    image VARCHAR(255) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    payment_method ENUM('bkash', 'nagad', 'rocket') NOT NULL,
    transaction_id VARCHAR(255) NOT NULL,
    sender_number VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    total FLOAT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
)");

// Insert sample products if the table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("INSERT INTO products (name, description, type, price, image) VALUES
    ('Dog Collar', 'A stylish collar for your dog.', 'accessory', 500, 'images/dog-collar.jpg'),
    ('Cat Toy', 'A fun toy for your cat.', 'accessory', 200, 'images/cat-toy.jpg'),
    ('Dog Food', 'Premium dog food for all breeds.', 'food', 1500, 'images/dog-food.jpg'),
    ('Cat Food', 'Healthy cat food for a balanced diet.', 'food', 1200, 'images/cat-food.jpg')");
}

// Handle form submission (purchase process)
if (isset($_POST['submit_purchase'])) {
    $product_id = $_POST['product_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $quantity = intval($_POST['quantity']);
    $total = floatval($_POST['product_price']) * $quantity;
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $transaction_id = $conn->real_escape_string($_POST['transaction_id']);
    $sender_number = $conn->real_escape_string($_POST['sender_number']);

    // Insert the data into the purchase table
    $sql = "INSERT INTO purchases (product_id, name, payment_method, transaction_id, sender_number, quantity, total) 
            VALUES ('$product_id', '$name', '$payment_method', '$transaction_id', '$sender_number', '$quantity', '$total')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Purchase recorded successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Fetch products based on search input
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR type LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furry Friends Lodge - Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ede8d0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 50px;
        }
        h1 {
            text-align: center;
            color: #954535;
        }
        .home-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #954535;
            color: white;
            padding: 10px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 24px;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .search-input {
            padding: 10px;
            width: 300px;
            border: 1px solid #954535;
            border-radius: 4px 0 0 4px;
        }
        .search-button {
            background-color: #954535;
            border: none;
            padding: 10px 15px;
            color: white;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-item {
            background-color: #fff;
            border: 1px solid #954535;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        .product-item img {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .product-item h3 {
            margin: 0;
        }
        .product-item span {
            font-weight: bold;
            color: #954535;
        }
        .buy-btn {
            background-color: #954535;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            position: absolute;
            bottom: 15px;
            right: 15px;
        }
        .buy-box {
            display: none;
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
        }
        .buy-box-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
        }
        .buy-box-content label, .buy-box-content input, .buy-box-content select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        .buy-box-content input[type="submit"] {
            background-color: #954535;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Home button -->
<a class="home-button" href="index.html" title="Home"><i class="fas fa-home"></i></a>

<div class="container">
    <h1>Furry Friends Lodge - Shop</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form action="shop.php" method="GET">
            <input type="text" name="search" class="search-input" placeholder="Search by name or type" value="<?php if(isset($_GET['search'])) { echo $_GET['search']; } ?>">
            <button type="submit" class="search-button">&#x1F50D;</button> <!-- Search icon -->
        </form>
    </div>

    <div class="product-grid">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-item">';
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<img src="' . $row["image"] . '" alt="' . $row["name"] . '">';
            echo '<p>' . $row["description"] . '</p>';
            echo '<span>Price: ৳' . $row["price"] . '</span>';
            echo '<button class="buy-btn" onclick="openBuyBox(' . $row["id"] . ', ' . $row["price"] . ')">Buy</button>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found.</p>';
    }
    ?>
    </div> <!-- End of product grid -->

    <!-- Buy Box Modal -->
    <div id="buy-box" class="buy-box">
        <div class="buy-box-content">
            <form action="shop.php" method="POST">
                <input type="hidden" id="product_id" name="product_id">
                <input type="hidden" id="product_price" name="product_price">

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required oninput="updateTotal()">

                <label>Total Amount: ৳<span id="total-amount">0</span></label>

                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="payment_method">Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="rocket">Rocket</option>
                </select>

                <label for="transaction_id">Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id" required>

                <label for="sender_number">Sender Number:</label>
                <input type="text" id="sender_number" name="sender_number" required>

                <input type="submit" name="submit_purchase" value="Confirm Purchase">
                <button type="button" onclick="closeBuyBox()">Cancel</button>
            </form>
        </div>
    </div> <!-- End of buy box modal -->

</div> <!-- End of container -->

<script>
function openBuyBox(productId, productPrice) {
    document.getElementById('buy-box').style.display = 'flex';
    document.getElementById('product_id').value = productId;
    document.getElementById('product_price').value = productPrice;
    document.getElementById('quantity').value = 1; // Set default quantity
    updateTotal(); // Update total amount when the box opens
}

function closeBuyBox() {
    document.getElementById('buy-box').style.display = 'none';
}

function updateTotal() {
    const quantity = document.getElementById('quantity').value;
    const price = document.getElementById('product_price').value;
    const total = quantity * price;
    document.getElementById('total-amount').textContent = total.toFixed(2);
}
</script>

</body>
</html>

<?php
$conn->close();
?>
