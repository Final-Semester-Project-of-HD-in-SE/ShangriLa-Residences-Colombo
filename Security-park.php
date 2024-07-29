<?php
session_start();
require_once('inc/connection.php');

$message = ""; // Variable to store messages

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot = $_POST['slot'];
    $status = $_POST['status'];
    $until = $_POST['until'];
    $time = $_POST['time'];
    $action = $_POST['action'];

    if ($action == 'insert') {
        $query = "INSERT INTO parking (slot, Pstatus, Pday, Ptime) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssss", $slot, $status, $until, $time);
        
        if ($stmt->execute()) {
            $message = "Record successfully inserted";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action == 'update') {
        $query = "UPDATE parking SET Pstatus = ?, Pday = ?, Ptime = ? WHERE slot = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssss", $status, $until, $time, $slot);
        
        if ($stmt->execute()) {
            $message = "Record successfully updated";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch parking data
$query = "SELECT slot, Pstatus, Pday, Ptime FROM parking";
$result = $connection->query($query);

$slots = [];
while ($row = $result->fetch_assoc()) {
    $slots[$row['slot']] = $row;
}

$result->free();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parking - Security Officer</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            padding-top: 60px; 
        }
        header {
            background-color: #0044cc;
            color: #fff;
            padding: 10px 0;
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%; 
            z-index: 1000;
            display: flex;
            flex-direction: column; 
        }
        header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column; 
            align-items: center;
        }
        header h1 {
            margin: 0;
            padding-bottom: 10px; 
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        nav ul li {
            margin-right: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: 700;
        }
        nav ul li a.active {
            color: yellow;
        }
        main {
            padding: 20px;
        }
        .parking-management {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }
        .parking-lot {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .parking-slot {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px);
            box-sizing: border-box;
        }
        .slot-info {
            padding: 15px;
        }
        .slot-info h3 {
            margin-top: 0;
            color: #333;
        }
        .slot-info label {
            display: block;
            margin-bottom: 5px;
            font-weight: 700;
        }
        .slot-info select,
        .slot-info input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .slot-info button.btn-clear {
            background-color: #0044cc;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .slot-info button.btn-clear:hover {
            background-color: #0033aa;
        }
        .insert-form {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .insert-form label,
        .insert-form input,
        .insert-form select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        .insert-form input,
        .insert-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .insert-form button {
            background-color: #0044cc;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .insert-form button:hover {
            background-color: #0033aa;
        }
        footer {
            background-color: #0044cc;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Manage Parking</h1>
            <nav>
                <ul>
                    <li><a href="Security-dash.php">Home</a></li>
                    <li><a href="Security-park.php" class="active">Parking Management</a></li>
                    <li><a href="Security-visitor.php">Visitor Logs</a></li>
                    <li><a href="Security-prof.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="parking-management">
            <div class="container">
                <h2>Parking Management</h2>
                <?php if ($message): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <!-- Insert Slot Form -->
                <div class="insert-form">
                    <h3>Insert New Slot</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="action" value="insert">
                        <label for="slot">Slot Number:</label>
                        <input type="number" id="slot" name="slot" min="1" max="8" required>
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="available">Available</option>
                            <option value="not_available">Not available</option>
                        </select>
                        <label for="until">Until:</label>
                        <input type="date" id="until" name="until" required>
                        <label for="time">Time Slot:</label>
                        <input type="time" id="time" name="time" required>
                        <button class="btn btn-clear" type="submit">Insert Slot</button>
                    </form>
                </div>

                <div class="parking-lot">
                    <?php foreach ($slots as $key => $slot): ?>
                        <div class="parking-slot">
                            <div class="slot-info">
                                <h3>Slot <?php echo $key; ?></h3>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="slot" value="<?php echo $key; ?>">
                                    <label for="status<?php echo $key; ?>">Status:</label>
                                    <select id="status<?php echo $key; ?>" name="status">
                                        <option value="available" <?php echo ($slot['Pstatus'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="not_available" <?php echo ($slot['Pstatus'] == 'not_available') ? 'selected' : ''; ?>>Not available</option>
                                    </select>
                                    <label for="until<?php echo $key; ?>">Until:</label>
                                    <input type="date" id="until<?php echo $key; ?>" name="until" value="<?php echo $slot['Pday']; ?>" required>
                                    <label for="time<?php echo $key; ?>">Time Slot:</label>
                                    <input type="time" id="time<?php echo $key; ?>" name="time" value="<?php echo $slot['Ptime']; ?>" required>
                                    <button class="btn btn-clear" type="submit">Update Slot</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Parking Management System. All rights reserved.</p>
    </footer>
</body>
</html>
