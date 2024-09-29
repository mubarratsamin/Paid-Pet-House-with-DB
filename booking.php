<?php
// booking.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO bookings (name, address, phone, petName, petType, breed, duration, petPhoto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Display error message if preparation fails
    }
    
    // Set parameters
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $petName = $_POST['petName'];
    $petType = $_POST['petType'];
    $breed = $_POST['breed'];
    $duration = $_POST['duration'];
    $petPhoto = NULL; // Initialize petPhoto variable

    // Handle file upload for pet photo
    if (isset($_FILES['petPhoto']) && $_FILES['petPhoto']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['petPhoto']['tmp_name'];
        $fileName = $_FILES['petPhoto']['name'];
        $fileSize = $_FILES['petPhoto']['size'];
        $fileType = $_FILES['petPhoto']['type'];
        
        // Specify the directory where the photo will be saved
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        // Create the uploads directory if it doesn't exist
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        // Move the file to the desired location
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $petPhoto = $dest_path; // Save the file path to the database
        } else {
            echo "There was an error moving the uploaded file.";
        }
    }

    // Bind parameters and execute
    $stmt->bind_param("ssssssss", $name, $address, $phone, $petName, $petType, $breed, $duration, $petPhoto);

    if ($stmt->execute()) {
        // Redirect to billing page upon successful insert with relevant data
        header("Location: billing.php?name=" . urlencode($name) . "&petName=" . urlencode($petName) . "&petType=" . urlencode($petType) . "&duration=" . urlencode($duration));
        exit();
    } else {
        echo "Error: " . $stmt->error; // Display error message if execution fails
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #ede8d0, #954535); /* Base gradient */
            position: relative; /* For the pseudo-element */
        }

        /* Adding texture effect */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2); /* White overlay for texture effect */
            pointer-events: none; /* Allow clicks to go through */
            background-image: radial-gradient(circle, rgba(255, 255, 255, 0.1) 20%, transparent 20%),
                              radial-gradient(circle, rgba(255, 255, 255, 0.1) 20%, transparent 20%); /* Textured pattern */
            background-size: 50px 50px; /* Size of the texture circles */
            opacity: 0.3; /* Adjust the texture visibility */
        }

        .container {
            max-width: 100%;
            padding: 40px;
            background-color: rgba(149, 53, 53, 0.9); /* Semi-transparent brown for contrast */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #fff; /* White text for contrast */
            margin-top: 100px;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            width: 90%;
            max-width: 400px;
            text-align: left;
        }

        label {
            display: inline-block;
            margin-bottom: 5px;
            color: #fff; /* White label text */
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px 20px;
            background-color: #ede8d0            ; /* Green button */
            color: #954535; /* White button text */
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #a96d53; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book Your Pet's Stay</h2>
        <form id="bookingForm" method="POST" action="booking.php" enctype="multipart/form-data">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{11}" required>
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="petName">Pet Name:</label>
            <input type="text" id="petName" name="petName" required>
            
            <label for="petType">Pet Type:</label>
            <select id="petType" name="petType" required>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
            </select>
            
            <label for="breed">Pet Breed:</label>
            <input type="text" id="breed" name="breed" required>
            
            <label for="duration">Duration of Stay (in days):</label>
            <input type="number" id="duration" name="duration" min="1" required>

            <label for="petPhoto">Upload Pet Photo:</label>
            <input type="file" id="petPhoto" name="petPhoto" accept="image/*">

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
