<!-- doctor_input.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furry Friends Lodge - Doctor Input</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ede8d0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding-top: 50px;
        }
        h1 {
            text-align: center;
            color: #954535;
        }
        .form-container {
            background-color: #fff;
            border: 1px solid #954535;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        label {
            font-weight: bold;
            color: #954535;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #954535;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #954535;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .time-period-container {
            display: flex;
            justify-content: space-between;
        }
        .time-period-container select {
            width: 48%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Input Doctor Information</h1>
    <div class="form-container">
        <form action="doctor_input.php" method="POST">
            <label for="doctor_name">Doctor's Name:</label>
            <input type="text" id="doctor_name" name="doctor_name" required>

            <label for="doctor_number">Doctor's Number:</label>
            <input type="text" id="doctor_number" name="doctor_number" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="start_time">Start Time:</label>
            <select id="start_time" name="start_time" required>
                <?php for ($hour = 0; $hour < 24; $hour++): ?>
                    <?php for ($minute = 0; $minute < 60; $minute += 30): ?>
                        <option value="<?php echo sprintf('%02d:%02d', $hour, $minute); ?>">
                            <?php echo sprintf('%02d:%02d', $hour, $minute); ?>
                        </option>
                    <?php endfor; ?>
                <?php endfor; ?>
            </select>

            <label for="end_time">End Time:</label>
            <select id="end_time" name="end_time" required>
                <?php for ($hour = 0; $hour < 24; $hour++): ?>
                    <?php for ($minute = 0; $minute < 60; $minute += 30): ?>
                        <option value="<?php echo sprintf('%02d:%02d', $hour, $minute); ?>">
                            <?php echo sprintf('%02d:%02d', $hour, $minute); ?>
                        </option>
                    <?php endfor; ?>
                <?php endfor; ?>
            </select>

            <input type="submit" value="Submit" name="submit_doctor">
        </form>
    </div>

    <?php
    // Database connection
    if (isset($_POST['submit_doctor'])) {
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

        // Handle form submission
        $doctor_name = $conn->real_escape_string($_POST['doctor_name']);
        $doctor_number = $conn->real_escape_string($_POST['doctor_number']);
        $location = $conn->real_escape_string($_POST['location']);
        $start_time = $conn->real_escape_string($_POST['start_time']);
        $end_time = $conn->real_escape_string($_POST['end_time']);

        // Insert the data into the database
        $sql = "INSERT INTO doctors (name, number, location, start_time, end_time) 
                VALUES ('$doctor_name', '$doctor_number', '$location', '$start_time', '$end_time')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Doctor information submitted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }

        $conn->close();
    }
    ?>
</div>

</body>
</html>
