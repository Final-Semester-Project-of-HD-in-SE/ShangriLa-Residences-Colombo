<?php
session_start();
require_once('inc/connection.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$rid = $_SESSION['rid'];
$username = $_SESSION['username'];

// Fetch user details from the database
$query = "SELECT * FROM resident WHERE Rid = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $rid);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$stmt->close();

// Initialize variables with default values if data is not found
$name = $userData['Rname'] ?? '';
$apartmentNo = $userData['RaptNum'] ?? '';
$email = $userData['Remail'] ?? '';
$phone = $userData['Rcon'] ?? '';
$address = $userData['Radd'] ?? '';
$profilePic = $userData['Rprof'] ?? ''; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['name'];
    $newApartmentNo = $_POST['apartment_no'];
    $newEmail = $_POST['email'];
    $newPhone = $_POST['phone'];
    $newAddress = $_POST['address'];

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileSize = $_FILES['profile_pic']['size'];
        $fileType = $_FILES['profile_pic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = $rid . '.' . $fileExtension; // Ensure unique file name
            $uploadFileDir = 'C:/xampp/htdocs/Projects/ShangriLa-Residences-Colombo/uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profilePic = $newFileName; // Update profile picture file name
            } else {
                $errorMessage = 'Error moving the uploaded file.';
            }
        } else {
            $errorMessage = 'Invalid file extension.';
        }
    }

    $updateQuery = "UPDATE resident SET Rname = ?, RaptNum = ?, Remail = ?, Rcon = ?, Radd = ?, Rprof = ? WHERE Rid = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("ssssssi", $newName, $newApartmentNo, $newEmail, $newPhone, $newAddress, $profilePic, $rid);

    if ($updateStmt->execute()) {
        $_SESSION['username'] = $newName; // Update the session username
        $username = $newName; // Update the local variable
        $successMessage = "Profile updated successfully.";
    } else {
        $errorMessage = "Error updating profile: " . $updateStmt->error;
    }

    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Profile</title>
    <link rel="stylesheet" href="css/user-prof.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Resident Profile</h1>
            <nav>
                <ul>
                    <li><a href="user-dash.php">Home</a></li>
                    <li><a href="user-issues.php">Issues</a></li>
                    <li><a href="product.php">Payments</a></li>
                    <li><a href="resident-visit.php">Visitors</a></li>
                    <li><a href="user-prof.php" class="active">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="profile">
            <div class="container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img id="profile-pic" src="<?php echo htmlspecialchars('./uploads/' . $profilePic); ?>" alt="Profile Picture">
                        <input type="file" id="profile-pic-input" name="profile_pic" accept="image/*" onchange="loadFile(event)">
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($username); ?></h2>
                        <p>Resident</p>
                    </div>
                </div>
                <?php if (isset($successMessage)): ?>
                    <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>
                <?php if (isset($errorMessage)): ?>
                    <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>
                <form class="profile-form" action="user-prof.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>
                    <div class="form-group">
                        <label for="apartment_no">Apartment No:</label>
                        <input type="tel" id="apartment_no" name="apartment_no" value="<?php echo htmlspecialchars($apartmentNo); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
                    </div>
                    <div class="form-group">
                        <label for="profile-pic-input">Profile Picture:</label>
                        <input type="file" id="profile-pic-input" name="profile_pic" accept="image/*">
                    </div>
                    <button type="submit" class="btn">Save Changes</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 OneGalleFace Apartments. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const loadFile = (event) => {
            const output = document.getElementById('profile-pic');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src); // free memory
            }
        };
    </script>
</body>
</html>