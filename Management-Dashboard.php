<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}
$username = $_SESSION['username'];
$dp = $_SESSION['Hpic'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Team Dashboard</title>
    <link rel="stylesheet" href="css/ManagementDash.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
   * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
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
<body>
    <header>
        <div class="container">
            <h1>Management Team Dashboard</h1>
            <nav>
                <ul>
                <li><a href="Management-Dashboard.php"  class="nav-payments">Home</a></li>
                    <li><a href="Management-complain.php">Complaints</a></li>
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
        <div class="container">
            <section class="overview">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p>Monitor and manage resident complaints, payments, visitors, and more.</p>
                <img src="https://i.ibb.co/fDfPsZt/img-a1-copy.png" alt="About Image">
            </section>

            <section class="cards">
                <div class="card">
                    <img src="https://i.ibb.co/Z269gzP/city-view-copy.jpg" alt="Complaints">
                    <h3>Complaints</h3>
                    <p>View and manage resident complaints and service requests.</p>
                </div>
                <div class="card">
                    <img src="https://i.ibb.co/8xtYCB4/largImg4.jpg" alt="Payments">
                    <h3>Payments</h3>
                    <p>Track and manage service and security payments.</p>
                </div>
                <div class="card">
                    <img src="https://i.ibb.co/GW6yNX5/ogfrnewimg6.jpg" alt="Visitors">
                    <h3>Visitors</h3>
                    <p>Monitor visitor entries and exits, manage visitor logs.</p>
                </div>
                <div class="card">
                    <img src="https://i.ibb.co/Z269gzP/city-view-copy.jpg" alt="Reports">
                    <h3>Reports</h3>
                    <p>Generate and view various management reports.</p>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 OneGalleFace Management. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
