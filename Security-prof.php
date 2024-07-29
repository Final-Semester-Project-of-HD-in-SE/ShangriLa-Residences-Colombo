<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}
$username = $_SESSION['username'];
$userID = $_SESSION['secid'];
require_once('inc/connection.php');

// Fetch security officer data
$query = "SELECT Sname, SecId, Spass, Scon FROM securityoff WHERE SecId = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $userID);
$stmt->execute();
$result = $stmt->get_result();
$securityOfficer = $result->fetch_assoc();
$stmt->close();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Officer Profile</title>
    <link rel="stylesheet" href="css/Security-prof.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Inline CSS for non-editable fields styling */
        .profile-form {
            display: grid;
            gap: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
        .btn {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Security Officer Profile</h1>
            <nav>
                <ul>
                    <li><a href="Security-dash.php">Home</a></li>
                    <li><a href="Security-park.php">Parking Management</a></li>
                    <li><a href="Security-visitor.php">Visitor Logs</a></li>
                    <li><a href="Security-prof.php" class="active">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="profile">
            <div class="container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img id="profile-pic" src="https://i.ibb.co/Jqnd9X5/larg-Img1-1.jpg" alt="Profile Picture">
                        <input type="file" id="profile-pic-input" accept="image/*" onchange="loadFile(event)">
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($securityOfficer['Sname']); ?></h2>
                        <p>Security Officer</p>
                    </div>
                </div>
                <form class="profile-form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" value="<?php echo htmlspecialchars($securityOfficer['Sname']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" value="<?php echo htmlspecialchars($securityOfficer['Scon']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="shift">Shift:</label>
                        <input type="text" id="shift" value="Night" readonly>
                    </div>
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
