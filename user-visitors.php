<?php
require_once('inc/connection.php');

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitorName = $_POST['visitorName'];
    $visitorId = $_POST['visitorId'];
    $contactDetails = $_POST['contactDetails'];
    $apartmentNumber = $_POST['apartmentNumber'];
    $stayTime = $_POST['stayTime'];

    $sql = "INSERT INTO visitors (Vname, Vid, Vcontact, Vtime, Apt_no) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $connection->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $visitorName, $visitorId, $contactDetails, $stayTime, $apartmentNumber);

        if ($stmt->execute()) {
            $success = "Visitor information inserted successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Error preparing statement: " . $connection->error;
    }

    $connection->close();

    if (empty($error)) {
        // Prepare the mailto link
        $mailtoLink = "mailto:ThathsaraniB@99x.io?subject=Visitor%20Information&body=" .
                      "Visitor%20Name:%20" . urlencode($visitorName) . "%0D%0A" .
                      "Visitor%20ID%20Number:%20" . urlencode($visitorId) . "%0D%0A" .
                      "Visitor%20Contact%20Details:%20" . urlencode($contactDetails) . "%0D%0A" .
                      "Apartment%20Number:%20" . urlencode($apartmentNumber) . "%0D%0A" .
                      "Stay%20Time%20at%20Apartment:%20" . urlencode($stayTime);
        // Redirect to the mailto link
        header("Location: " . $mailtoLink);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Owners Dashboard</title>
    <link rel="stylesheet" href="css/user-issues.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="js/random-number-generator.js" defer></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>OneGalleFace Apartments</h1>
            <nav>
                <ul>
                <li><a href="user-dash.php">Home</a></li>
                <li><a href="user-issues.php">Issues</a></li>
                <li><a href="product.php">Payments</a></li>
                <li><a href="user-visitors.php">Visitors</a></li>
                <li><a href="user-parkings.php">Parkings</a></li>
                <li><a href="user-prof.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="forms-container">
            <div class="form-box">
                <h2>Visitor Information</h2>
                <form id="visitorForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label for="visitorName">Visitor Name</label>
                    <input type="text" id="visitorName" name="visitorName" required>

                    <label for="visitorId">Visitor ID Number</label>
                    <input type="text" id="visitorId" name="visitorId" required>

                    <label for="contactDetails">Visitor Contact Details</label>
                    <input type="text" id="contactDetails" name="contactDetails" required>

                    <label for="apartmentNumber">Apartment Number</label>
                    <input type="text" id="apartmentNumber" name="apartmentNumber" required>

                    <label for="stayTime">Stay Time at Apartment</label>
                    <input type="text" id="stayTime" name="stayTime" required>

                    <button type="submit">Send Request</button>
                </form>
                
                <?php
                if (!empty($success)) {
                    echo '<p style="color: green;">' . htmlspecialchars($success) . '</p>';
                }
                if (!empty($error)) {
                    echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
                }
                ?>
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
