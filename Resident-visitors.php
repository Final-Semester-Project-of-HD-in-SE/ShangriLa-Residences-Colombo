<?php
session_start();
require_once('inc/connection.php');

// Fetch visitor data
$query = "SELECT Vname, Vid, Vcontact, Vtime, Apt_no, approval, reason FROM visitors";
$result = $connection->query($query);

$visitors = [];
while ($row = $result->fetch_assoc()) {
    $visitors[] = $row;
}

$result->free();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Officer Profile</title>
    <link rel="stylesheet" href="css/Security-prof.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Inline CSS for table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #0044cc;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Security Officer Profile</h1>
            <nav>
                <ul>
                    <li><a href="Security-dash.php">Home</a></li>
                    <li><a href="Security-park.php">Parking Management</a></li>
                    <li><a href="Security-visitor.php"  class="active">Visitor Logs</a></li>
                    <li><a href="Security-prof.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Visitor Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>ID</th>
                        <th>Contact</th>
                        <th>Time</th>
                        <th>Apartment No</th>
                        <th>Approval</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($visitors)): ?>
                        <?php foreach ($visitors as $visitor): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($visitor['Vname']); ?></td>
                                <td><?php echo htmlspecialchars($visitor['Vid']); ?></td>
                                <td><?php echo htmlspecialchars($visitor['Vcontact']); ?></td>
                                <td><?php echo htmlspecialchars($visitor['Vtime']); ?></td>
                                <td><?php echo htmlspecialchars($visitor['Apt_no']); ?></td>
                                <td><?php echo htmlspecialchars($visitor['approval']); ?></td>
                                <td><?php echo htmlspecialchars($visitor['reason']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No visitors found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 OneGalleFace Apartments. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
