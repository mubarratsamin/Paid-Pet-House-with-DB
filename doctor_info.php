<!-- doctor_info.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furry Friends Lodge - Doctor's Information</title>
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
        .form-container {
            background-color: transparent; /* No background for form container */
            padding: 20px;
            margin: 20px 0;
            display: flex;
            justify-content: center; /* Center the content */
        }
        input[type="text"] {
            width: 40%;
            padding: 10px;
            margin-right: 10px; /* Add margin to separate from button */
            border: 1px solid #954535;
            border-radius: 4px;
            background-color: #fff; /* White background for search bar */
        }
        input[type="submit"] {
            background-color: #954535;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #954535;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #954535;
            color: #fff;
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
    </style>
</head>
<body>

<a class="home-button" href="index.html" title="Home"><i class="fas fa-home"></i></a>

<div class="container">
    <h1>Doctor Information</h1>

    <div class="form-container">
        <form action="doctor_info.php" method="GET" style="display: flex; align-items: center;">
            <input type="text" id="search" name="search" placeholder="Search Doctor by Name or Location...">
            <input type="submit" value="Search">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Doctor's Name</th>
                <th>Number</th>
                <th>Location</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
        <?php
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

        // Search functionality
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

        // Fetch doctors based on search
        $sql = "SELECT * FROM doctors WHERE name LIKE '%$search%' OR location LIKE '%$search%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row["name"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["number"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["location"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["start_time"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["end_time"]) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">No doctors found.</td></tr>';
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
