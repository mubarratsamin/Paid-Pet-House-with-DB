<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furry Friends Lodge - Shop</title>
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
        }
        h1 {
            text-align: center;
            color: #954535;
        }
        .product-category {
            margin: 20px 0;
            display: flex; /* Use flexbox to arrange categories */
            flex-wrap: wrap; /* Allow wrapping of items */
            justify-content: center; /* Center items */
        }
        .product-category h2 {
            color: #954535;
            width: 100%; /* Full width for the category heading */
            text-align: center; /* Center the heading */
        }
        .product-item {
            background-color: #fff;
            border: 1px solid #954535;
            border-radius: 8px;
            padding: 15px;
            margin: 15px; /* Add margin around each item */
            max-width: 300px; /* Set a max width for the product item */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
            display: inline-block; /* Allow items to sit next to each other */
            vertical-align: top; /* Align items at the top */
        }
        .product-item h3 {
            margin: 0;
        }
        .product-item p {
            margin: 5px 0;
            max-height: 60px; /* Limit the height for description */
            overflow: hidden; /* Hide overflow text */
            text-overflow: ellipsis; /* Show ellipsis for overflow text */
            white-space: nowrap; /* Prevent text wrapping */
        }
        .product-item span {
            font-weight: bold;
            color: #954535;
        }
        .product-item img {
            max-width: 100%; /* Adjust image to fit within item */
            height: auto; /* Maintain aspect ratio */
            margin-bottom: 10px;
        }
        .form-container {
            background-color: #fff;
            border: 1px solid #954535;
            padding: 20px;
            border-radius: 10px;
            margin: 40px 0;
        }
        .form-container h1 {
            color: #954535;
        }
        label {
            font-weight: bold;
            color: #954535;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #954535;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Products</h1>

    <?php
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection
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

    // Handle form submission for adding a new product
    if (isset($_POST['submit'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $price = $conn->real_escape_string($_POST['price']);
        $type = $conn->real_escape_string($_POST['type']);
        $description = $conn->real_escape_string($_POST['description']);
        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $allowedTypes)) {
            if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $sql = "INSERT INTO products (name, type, price, description, image) 
                            VALUES ('$name', '$type', '$price', '$description', '$target_file')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<p style='color: green;'>Product added successfully!</p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error moving uploaded file.</p>";
                }
            } else {
                echo "<p style='color: red;'>File upload error: " . $_FILES["image"]["error"] . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Only JPG, JPEG, PNG & GIF files are allowed.</p>";
        }
    }

    // Handle product deletion
    if (isset($_POST['delete'])) {
        $productId = $conn->real_escape_string($_POST['product_id']);
        $sql = "DELETE FROM products WHERE id = '$productId'";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Product deleted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error deleting product: " . $conn->error . "</p>";
        }
    }

    // Display form to add new product
    echo '
    <div class="form-container">
        <h1>Add New Product</h1>
        <form action="products.php" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <label for="type">Product Type</label>
            <select id="type" name="type" required>
                <option value="Accessory">Accessory</option>
                <option value="Food">Food</option>
            </select>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <input type="submit" value="Add Product" name="submit">
        </form>
    </div>';

    // Fetch and display Accessories
    $sql = "SELECT * FROM products WHERE type = 'Accessory'";
    $result = $conn->query($sql);
    echo '<div class="product-category">';
    echo '<h2>Accessories</h2>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-item">';
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<img src="' . $row["image"] . '" alt="' . $row["name"] . '">';
            echo '<p>' . $row["description"] . '</p>';
            echo '<span>Price: ৳' . $row["price"] . '</span>';
            echo '<form action="products.php" method="POST" style="margin-top: 10px;">';
            echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
            echo '<input type="submit" value="Delete Product" name="delete" style="background-color: red; color: white;">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No accessories available.</p>';
    }
    echo '</div>';

    // Fetch and display Food items
    $sql = "SELECT * FROM products WHERE type = 'Food'";
    $result = $conn->query($sql);
    echo '<div class="product-category">';
    echo '<h2>Food</h2>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-item">';
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<img src="' . $row["image"] . '" alt="' . $row["name"] . '">';
            echo '<p>' . $row["description"] . '</p>';
            echo '<span>Price: ৳' . $row["price"] . '</span>';
            echo '<form action="products.php" method="POST" style="margin-top: 10px;">';
            echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
            echo '<input type="submit" value="Delete Product" name="delete" style="background-color: red; color: white;">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No food items available.</p>';
    }
    echo '</div>';

    $conn->close();
    ?>
</div>

</body>
</html>
