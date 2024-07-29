<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['username'];
$dp = $_SESSION['Rpic'];

// Database connection
require_once('inc/connection.php');

try {
    // Fetch parking data
    $query = "SELECT slot, Pstatus, Pday, Ptime FROM parking";
    $result = $connection->query($query);
    if (!$result) {
        throw new Exception("Error fetching data: " . $connection->error);
    }
    $parkings = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
} catch (Exception $e) {
    echo '<p>Error: ' . $e->getMessage() . '</p>';
    exit();
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Owners Dashboard</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-color: #f9f9f9;
        color: #333;
    }

    header {
        background-color: #4CAF50;
        color: #fff;
        padding: 15px 0;
        position: relative;
    }

    .container {
        width: 90%;
        margin: 0 auto;
        max-width: 1200px;
    }

    nav ul {
        list-style: none;
        display: flex;
        justify-content: space-around;
    }

    nav ul li {
        display: inline;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        padding: 10px 15px;
        font-weight: 600;
    }

    .profile-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
    }

    .profile-image img {
        width: 100%;
        height: auto;
    }

    .logout-link {
        color: yellow;
        text-decoration: none;
        margin-top: 10px;
        font-weight: bold;
        font-size: 16px;
    }

    .logout-link:hover {
        text-decoration: underline;
    }

    .logout-link i {
        font-size: 20px;
    }

    .parking-section {
        margin: 30px 0;
    }

    h2 {
        font-size: 24px;
        color: #4CAF50;
        margin-bottom: 20px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #4CAF50;
        color: #fff;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 15px 0;
    }
    nav ul li a.active {
            color: yellow;
        }
</style>

    <link rel="stylesheet" href="css/user-dash.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Shangri-La Residences</h1>
        <nav>
            <ul>
                    <li><a href="Management-Dashboard.php">Home</a></li>
                    <li><a href="Management-complain.php"  class="nav-payments">Complaints</a></li>
                    <li><a href="Management-payment.php">Payments</a></li>
                    <li><a href="Management-visitors.php">Visitors</a></li>
                    <li><a href="add-sec.php">Add Security Officers</a></li> 
                    <li><a href="Management-parking.php">Parkings</a></li> 
                    <li><a href="hr-prof.php">Profile</a></li>
            </ul>
        </nav>
        <div class="profile-container">
            <div class="profile-image">
                <img id="profile-pic" src="<?php echo htmlspecialchars('./uploads/' . $dp); ?>" alt="Profile Picture">
            </div>
            <a href="index.html" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</header>

<main>
    <section class="parking-section">
        <div class="container">
            <h2>Parking Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Slot</th>
                        <th>Status</th>
                        <th>Until</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parkings as $parking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($parking['slot']); ?></td>
                            <td><?php echo htmlspecialchars($parking['Pstatus']); ?></td>
                            <td><?php echo htmlspecialchars($parking['Pday']); ?></td>
                            <td><?php echo htmlspecialchars($parking['Ptime']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 OneGalleFace Apartments. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
