<?php
require_once('inc/connection.php');

$error = "";
$success = "";

// Set SMTP configuration dynamically
ini_set('SMTP', 'smtp.gmail.com'); // Replace with your SMTP server
ini_set('smtp_port', 587); // Replace with your SMTP port number

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitorName = $_POST['visitorName'];
    $visitorId = $_POST['visitorId'];
    $contactDetails = $_POST['contactDetails'];
    $apartmentNumber = $_POST['apartmentNumber'];
    $stayTime = $_POST['stayTime'];

    $sql = "INSERT INTO visitors (Vname, Vid, Vcontact, Vtime, Apt_no) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssss", $visitorName, $visitorId, $contactDetails, $stayTime, $apartmentNumber);

    if ($stmt->execute()) {
        $success = "Visitor information inserted successfully!";
   
        $emailBody = "Visitor Name: $visitorName\n"
                   . "Visitor ID Number: $visitorId\n"
                   . "Visitor Contact Details: $contactDetails\n"
                   . "Apartment Number: $apartmentNumber\n"
                   . "Stay Time at Apartment: $stayTime\n";
        
        $to = "ThathsaraniB@99x.io";
        $subject = "Visitor Information";
        $headers = "From: webmaster@example.com"; 

        if (mail($to, $subject, $emailBody, $headers)) {
            $success .= " Email sent successfully!";
        } else {
            $error = "Error sending email.";
        }
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$connection->close();
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
                    <li><a href="user-dash.html">Home</a></li>
                    <li><a href="user-issues.html">Issues</a></li>
                    <li><a href="Card.html">Payments</a></li>
                    <li><a href="user-visitors.html">Visitors</a></li>
                    <li><a href="#">Profile</a></li>
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
