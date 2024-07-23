<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Team Dashboard</title>
    <link rel="stylesheet" href="css/ManagementDash.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Management Team Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="Management-Dashboard.php">Home</a></li>
                    <li><a href="Management-complain.html">Complaints</a></li>
                    <li><a href="Management-payment.html">Payments</a></li>
                    <li><a href="Management-visitors.html">Visitors</a></li>
                    <li><a href="add-sec.html">Add Security Officers</a></li> 
                    <li><a href="Management-reports.html">Reports</a></li>
                    <li><a href="hr-prof.php">Profile</a></li>
                </ul>
            </nav>
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
