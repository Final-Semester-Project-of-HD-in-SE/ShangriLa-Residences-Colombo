<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Owners Dashboard</title>
    <link rel="stylesheet" href="css/user-dash.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Shangri-La Residences</h1>
            <nav>
                <ul>
                    <li><a href="user-dash.php">Home</a></li>
                    <li><a href="user-issues.php">Issues</a></li>
                    <li><a href="product.html">Payments</a></li>
                    <li><a href="user-visitors.php">Visitors</a></li>
                    <li><a href="user-prof.html">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
                <p>Manage your apartment issues, payments, visitors, and more with ease.</p>
                <a href="#" class="btn">Get Started</a>
            </div>
            <img src="https://i.ibb.co/L6J480y/header-img3-2.png" alt="Hero Image">
        </section>

        <section class="features">
            <div class="feature">
                <a href="user-issues.html"><img src="https://i.ibb.co/HpGbxnx/about-copy.png" alt="Issues"></a>
                <h3>Report Issues</h3>
                <p>Quickly report any issues related to electricity, water, repairs, and more.</p>
            </div>
            <div class="feature">
                <a href="product.html"><img src="https://i.ibb.co/Z269gzP/city-view-copy.jpg" alt="Payments"></a>
                <h3>Make Payments</h3>
                <p>Pay your monthly service charges and yearly security charges online.</p>
            </div>
            <div class="feature">
                <a href="user-visitors.html"><img src="https://i.ibb.co/GW6yNX5/ogfrnewimg6.jpg" alt="Visitors"></a>
                <h3>Manage Visitors</h3>
                <p>Keep track of visitors and provide access permissions.</p>
            </div>
            <div class="feature">
                <a href="user-prof.html"><img src="https://i.ibb.co/8xtYCB4/largImg4.jpg" alt="Profile"></a>
                <h3>Update Profile</h3>
                <p>Manage your personal information and apartment details.</p>
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
