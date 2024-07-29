<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Officer Dashboard</title>
    <link rel="stylesheet" href="css/Security-dash.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<style>
     nav ul li a.active {
            color: yellow;
        }
</style>
<body>
    <header>
        <div class="container">
            <h1>Security Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="Security-dash.php"  class="active">Home</a></li>
                    <li><a href="Security-park.php">Manage Parking</a></li>
                    <li><a href="Security-visitor.php">Visitor Logs</a></li>
                    <li><a href="Security-prof.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                <p>Monitor and manage parking, visitors, and ensure the safety of residents.</p>
                <a href="#" class="btn">Get Started</a>
            </div>
            <img src="https://i.ibb.co/6ggCW2r/side-Img2-copy.png" alt="Security Image">
        </section>

        <section class="features">
            <div class="feature">
                <a href="Security-park.html"><img src="images/LandCruiser.jpg" alt="Manage Parking"></a>
                <h3>Manage Parking</h3>
                <p>Keep track of all vehicles entering and exiting the premises.</p>
            </div>
            <div class="feature">
                <img src="https://i.ibb.co/Jqnd9X5/larg-Img1-1.jpg" alt="Visitor Logs">
                <h3>Visitor Logs</h3>
                <p>Maintain detailed records of all visitors for security purposes.</p>
            </div>
            <div class="feature">
                <img src="https://i.ibb.co/fDfPsZt/img-a1-copy.png" alt="Alerts">
                <h3>Alerts</h3>
                <p>Receive real-time alerts for any security breaches or incidents.</p>
            </div>
            <div class="feature">
                <a href="Security-prof.html"><img src="https://i.ibb.co/h8mr3h9/img-a4.png" alt="Profile"></a>
                <h3>Profile</h3>
                <p>Update your profile and manage personal settings.</p>
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
