<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['username'];
$dp = $_SESSION['Rpic'];
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
