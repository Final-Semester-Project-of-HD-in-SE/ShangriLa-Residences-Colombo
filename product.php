<?php
session_start();
require_once('inc/connection.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['username'];
$dp = $_SESSION['Rpic'];
$currentMonth = date('F'); 

$sql = "SELECT SUM(Balance) FROM payment WHERE Rname = ? AND RMonth = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $username, $currentMonth);
$stmt->execute();
$stmt->bind_result($totalBalance);
$stmt->fetch();
$stmt->close();

$requiredBalance = 763;
$remainingBalance = $requiredBalance - ($totalBalance ?: 0);

$shouldDisplayNotification = $remainingBalance > 0;

$isButtonDisabled = ($totalBalance == $requiredBalance);

if (isset($_POST['print_all_payments'])) {
    $sql = "SELECT RMonth, SUM(Balance) AS total FROM payment WHERE Rname = ? GROUP BY RMonth ORDER BY RMonth DESC";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    $stmt->close();
    $connection->close();

    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>All Payment History</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <h1>All Payment History</h1>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Payment</th>
                </tr>
            </thead>
            <tbody>";
    
    foreach ($payments as $payment) {
        echo "<tr>
            <td>" . htmlspecialchars($payment['RMonth']) . "</td>
            <td>$" . htmlspecialchars(number_format($payment['total'], 2)) . "</td>
        </tr>";
    }
    
    echo "</tbody>
        </table>
        <script>
            window.print();
        </script>
    </body>
    </html>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
    <link rel="website icon" type="jpg" href="ProductImg/title.png">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
       * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }
    .profile-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: absolute;
        top: 30px; 
        right: 50px; 
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

    .notification {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background-color: #444;
        color: #fff;
        padding: 30px 35px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 1000;
        max-width: 550px;
        font-size: 16px;
        width: 550px;
    }

    .notification button {
        background: none;
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        padding: 0 5px;
        width: 50px;
        height: 50px;
    }
    </style>
</head>
<body>
    <header>
        <div class="container" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <div class="header-row" style="display: flex; flex-direction: row; padding-top: 10px;">
                <h1>Shangri-La Residences</h1>
            </div>
            <div class="nav-row" style="display: flex; flex-direction: row; padding-bottom: 35px;">
                <nav>
                    <ul>
                        <li><a href="user-dash.php">Home</a></li>
                        <li><a href="user-issues.php">Issues</a></li>
                        <li><a href="product.php">Payments</a></li>
                        <li><a href="user-visitors.php">Visitors</a></li>
                        <li><a href="user-prof.php">Profile</a></li>
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
        </div>
    </header>
    <div class="container">
        <div id="root"></div>
        <div class="sidebar">
            <div class="head"><p>Payments</p></div>
            <div id="cartItem">Your payment list is empty</div>
            <div class="foot">
                <h3>Total</h3>
                <h2 id="total">$ 0.00</h2>
            </div>
        </div>
    </div>
    <div class="delivery-form">
        <h2>Resident Information</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Resident Name:</label>
                <input type="name" id="name" name="Name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="address">Apartment No:</label>
                <input type="address" id="address" name="address">
            </div>
            <div class="form-buttons">
                <button id="printAllPaymentsBtn" type="button" class="btn print">Print my all payments</button>
                <button id="clearOrderBtn" class="btn clear">Clear Payment</button>
                <button type="submit" class="btn confirm" <?php echo $isButtonDisabled ? 'disabled' : ''; ?>>Proceed to Payment</button>
            </div>
        </form>
    </div>
    <div class="end-text">
        <p>2024 OneGalleFace Apartments. All rights reserved.</p>
    </div>
    <?php if ($shouldDisplayNotification): ?>
    <div class="notification" id="notification">
        <span>Please do the remaining balance of $<?php echo htmlspecialchars(number_format($remainingBalance, 2)); ?> for <?php echo htmlspecialchars($currentMonth); ?>.</span>
        <button onclick="closeNotification()">✖</button>
    </div>
    <?php endif; ?>
    <script>
        function closeNotification() {
            document.getElementById('notification').style.display = 'none';
        }

        document.getElementById('printAllPaymentsBtn').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.open();
                    printWindow.document.write(xhr.responseText);
                    printWindow.document.close();
                } else {
                    alert('Failed to retrieve data.');
                }
            };
            xhr.send('print_all_payments=true');
        });
    </script>
    <script src="prod.js"></script>
</body>
</html>
