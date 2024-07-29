<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['username'];
$dp = $_SESSION['Hpic'];
require_once('inc/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $vid = $_POST['vid'];
    $approval = $_POST['approval'];
    $reason = $_POST['reason'];

    $updateSql = "UPDATE visitors SET approval = ?, reason = ? WHERE Vid = ?";
    $stmt = $connection->prepare($updateSql);
    $stmt->bind_param('ssi', $approval, $reason, $vid);
    $stmt->execute();
    $stmt->close();

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT * FROM visitors";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: 'Roboto', sans-serif;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .whatsapp-button {
            display: inline-flex;
            align-items: center;
            background-color: #25D366;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .whatsapp-button:hover {
            background-color: #128C7E;
        }

        .whatsapp-button i {
            margin-right: 5px;
        }

        .checkbox-cell {
            text-align: center;
        }

        select {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        input[type="text"] {
            width: 100%;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .update-button {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
        }

        .update-button:hover {
            background-color: #0056b3;
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

.nav-payments {
            color: yellow; 
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
                    <li><a href="Management-payment.php">Payments</a></li>
                    <li><a href="Management-visitors.php">Visitors</a></li>
                    <li><a href="add-sec.php">Add Security Officers</a></li> 
                    <li><a href="Management-parking.php"  class="nav-payments">Parkings</a></li> 
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
        <div class="container">
            <h2>Visitor Details</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Contact</th>
                            <th>Time</th>
                            <th>Apartment No</th>
                            <th>Approval</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="checkbox-cell"><input type="checkbox" class="visitor-checkbox" data-name="<?php echo htmlspecialchars($row['Vname']); ?>" data-contact="<?php echo htmlspecialchars($row['Vcontact']); ?>" data-approval="<?php echo htmlspecialchars($row['approval']); ?>"></td>
                                <td><?php echo htmlspecialchars($row['Vname']); ?></td>
                                <td><?php echo htmlspecialchars($row['Vid']); ?></td>
                                <td><?php echo htmlspecialchars($row['Vcontact']); ?></td>
                                <td><?php echo htmlspecialchars($row['Vtime']); ?></td>
                                <td><?php echo htmlspecialchars($row['Apt_no']); ?></td>
                                <td>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                        <select name="approval">
                                            <option value="Pending" <?php echo ($row['approval'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Approved" <?php echo ($row['approval'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                            <option value="Declined" <?php echo ($row['approval'] == 'Declined') ? 'selected' : ''; ?>>Declined</option>
                                        </select>
                                        <input type="hidden" name="vid" value="<?php echo htmlspecialchars($row['Vid']); ?>">
                                </td>
                                <td><input type="text" name="reason" value="<?php echo htmlspecialchars($row['reason']); ?>"></td>
                                <td><button type="submit" name="update" class="update-button">Update</button></td>
                                    </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="#" id="whatsapp-button" class="whatsapp-button">
                    <i class="fab fa-whatsapp"></i> Send Approval Update
                </a>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 OneGalleFace Apartments. All rights reserved.</p>
        </div>
    </footer>
    <script>
   document.getElementById('whatsapp-button').addEventListener('click', function(event) {
    event.preventDefault();
    const checkboxes = document.querySelectorAll('.visitor-checkbox:checked');
    let urls = [];

    checkboxes.forEach((checkbox) => {
        const name = checkbox.getAttribute('data-name');
        const contact = checkbox.getAttribute('data-contact');
        const row = checkbox.closest('tr');
        const approval = row.querySelector('select[name="approval"]').value;
        const reason = row.querySelector('input[name="reason"]').value;

        let message = `Hello ${name}, your visit request at our apartment has been ${approval}.`;

       document.getElementById('whatsapp-button').addEventListener('click', function(event) {
    event.preventDefault();
    const checkboxes = document.querySelectorAll('.visitor-checkbox:checked');
    let urls = [];

    checkboxes.forEach((checkbox) => {
        const name = checkbox.getAttribute('data-name');
        const contact = checkbox.getAttribute('data-contact');
        const row = checkbox.closest('tr');
        const approval = row.querySelector('select[name="approval"]').value;
        const reason = row.querySelector('input[name="reason"]').value;

        let message = `Hello ${name}, your visit request at our apartment has been ${approval}.`;

        if (approval === 'Declined' && reason) {
            message += ` Reason: ${reason}.`;
        }

        if (approval === 'Approved' && reason) {
            message += ` Reason: ${reason}.`;
        }

        const encodedMessage = encodeURIComponent(message);
        const whatsappUrl = `https://wa.me/${contact}?text=${encodedMessage}`;
        urls.push(whatsappUrl);
    });

    if (urls.length) {
        urls.forEach(url => window.open(url, '_blank'));
    }
});


        const encodedMessage = encodeURIComponent(message);
        const whatsappUrl = `https://wa.me/${contact}?text=${encodedMessage}`;
        urls.push(whatsappUrl);
    });

    if (urls.length) {
        urls.forEach(url => window.open(url, '_blank'));
    }
});

</script>

</body>
</html>
