<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}
$username = $_SESSION['username'];
$dp = $_SESSION['Hpic'];

require_once('inc/connection.php');

function createMailtoLink($username, $balance, $month) {
    $subject = "Payment Reminder";
    $body = "Dear " . urlencode($username) . ",%0D%0A%0D%0A" .
            "This is a friendly reminder to please make your payment before the 25th of " . urlencode($month) . ".%0D%0A%0D%0A" .
            "Here are the details:%0D%0A" .
            "Balance: $" . urlencode(number_format($balance, 2)) . "%0D%0A" .
            "Month: " . urlencode($month) . "%0D%0A%0D%0A" .
            "Please ensure that your payment is completed on time to avoid any late fees.%0D%0A%0D%0A" .
            "Thank you for your attention to this matter.%0D%0A" .
            "Best regards,%0D%0A" .
            "Management%0D%0A" .
            "Shangri-La Residences";
    
    $mailtoLink = "mailto:" . urlencode($username) . "?subject=" . urlencode($subject) . "&body=" . $body;
    return $mailtoLink;
}

$sql = "SELECT Rname, Rid, Balance, RMonth FROM payment";
$result = $connection->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Owners Dashboard</title>
    <link rel="stylesheet" href="css/user-dash.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .table-container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        h2 {
            text-align: center; 
            margin-bottom: 20px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        .send-reminder-btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
        }

        .send-reminder-btn:hover {
            background-color: #218838;
        }
        .nav-payments {
            color: yellow; 
        }

        
        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: absolute;
            top: 30px; 
            right: 30px; 
        }

        .profile-image {
            width: 100px; 
            height: 100px; 
            overflow: hidden; 
            border-radius: 50%; 
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd; 
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
        }

        .logout-link:hover {
            text-decoration: underline; 
        }

        .logout-link i {
            font-size: 20px; 
        }

        header {
            position: relative;
        }

    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Shangri-La Residences</h1>

            <nav>
                <ul>
                <li><a href="Management-Dashboard.php">Home</a></li>
                    <li><a href="Management-complain.php">Complaints</a></li>
                    <li><a href="Management-payment.php" class="nav-payments">Payments</a></li>
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
        <section class="table-container">
            <h2>Payment Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Resident Name</th>
                        <th>Resident ID</th>
                        <th>Balance</th>
                        <th>Month</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $recipient = $row['Rname']; 
                            $balance = $row['Balance'];
                            $month = $row['RMonth'];
                            $mailtoLink = createMailtoLink($recipient, $balance, $month);
                            echo "<tr>
                                    <td>{$row['Rname']}</td>
                                    <td>{$row['Rid']}</td>
                                    <td>$" . number_format($balance, 2) . "</td>
                                    <td>{$month}</td>
                                    <td><a href=\"{$mailtoLink}\" class=\"send-reminder-btn\">Send Reminder</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No payments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Shangri-La Residences. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$connection->close();
?>
