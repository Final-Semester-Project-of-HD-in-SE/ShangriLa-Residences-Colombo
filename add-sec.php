<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$dp = $_SESSION['Hpic'];
require_once('inc/connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = null; // Initialize message variable

// Handle adding new security officer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $officer_name = htmlspecialchars(trim($_POST['officer_name']));
    $officer_id = htmlspecialchars(trim($_POST['officer_id']));
    $officer_password = htmlspecialchars(trim($_POST['officer_password']));
    $contact_number_sl = htmlspecialchars(trim($_POST['contact_number_sl']));

    if (!empty($officer_name) && !empty($officer_id) && !empty($officer_password) && !empty($contact_number_sl)) {
        $check_query = "SELECT COUNT(*) FROM secuirtyoff WHERE SecId = ?";
        if ($check_stmt = $connection->prepare($check_query)) {
            $check_stmt->bind_param('s', $officer_id);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                $message = ["type" => "error", "text" => "Error: Officer ID already exists."];
            } else {
                $query = "INSERT INTO secuirtyoff (Sname, SecId, Spass, Scon) VALUES (?, ?, ?, ?)";
                if ($stmt = $connection->prepare($query)) {
                    $stmt->bind_param('ssss', $officer_name, $officer_id, $officer_password, $contact_number_sl);

                    if ($stmt->execute()) {
                        $message = ["type" => "success", "text" => "New security officer added successfully."];
                    } else {
                        $message = ["type" => "error", "text" => "Error executing query: " . $stmt->error];
                    }

                    $stmt->close();
                } else {
                    $message = ["type" => "error", "text" => "Error preparing statement: " . $connection->error];
                }
            }
        } else {
            $message = ["type" => "error", "text" => "Error preparing duplicate check query: " . $connection->error];
        }
    } else {
        $message = ["type" => "error", "text" => "Please fill in all fields."];
    }

    $connection->close();
}

// Handle deleting security officer
if (isset($_GET['delete_id'])) {
    $delete_id = htmlspecialchars(trim($_GET['delete_id']));
    $delete_query = "DELETE FROM secuirtyoff WHERE SecId = ?";
    if ($delete_stmt = $connection->prepare($delete_query)) {
        $delete_stmt->bind_param('s', $delete_id);
        if ($delete_stmt->execute()) {
            header('Location: add-sec.php'); 
            exit();
        } else {
            $message = ["type" => "error", "text" => "Error deleting record: " . $delete_stmt->error];
        }
        $delete_stmt->close();
    } else {
        $message = ["type" => "error", "text" => "Error preparing delete statement: " . $connection->error];
    }
    $connection->close();
}

// Handle updating security officer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $update_id = htmlspecialchars(trim($_POST['update_id']));
    $update_name = htmlspecialchars(trim($_POST['update_name']));
    $update_password = htmlspecialchars(trim($_POST['update_password']));
    $update_contact_number = htmlspecialchars(trim($_POST['update_contact_number']));

    if (!empty($update_id) && !empty($update_name) && !empty($update_password) && !empty($update_contact_number)) {
        $update_query = "UPDATE secuirtyoff SET Sname = ?, Spass = ?, Scon = ? WHERE SecId = ?";
        if ($update_stmt = $connection->prepare($update_query)) {
            $update_stmt->bind_param('ssss', $update_name, $update_password, $update_contact_number, $update_id);
            if ($update_stmt->execute()) {
                $message = ["type" => "success", "text" => "Security officer details updated successfully."];
            } else {
                $message = ["type" => "error", "text" => "Error executing update query: " . $update_stmt->error];
            }
            $update_stmt->close();
        } else {
            $message = ["type" => "error", "text" => "Error preparing update statement: " . $connection->error];
        }
    } else {
        $message = ["type" => "error", "text" => "Please fill in all fields."];
    }

    $connection->close();
}

// Search functionality
$search_query = '';
if (isset($_POST['search'])) {
    $search_term = htmlspecialchars(trim($_POST['search_term']));
    $search_query = "SELECT * FROM secuirtyoff WHERE Sname LIKE ? OR SecId LIKE ?";
    $search_term = "%$search_term%";
} else {
    $search_query = "SELECT * FROM secuirtyoff";
}

$officers = [];
if ($search_stmt = $connection->prepare($search_query)) {
    if (isset($_POST['search'])) {
        $search_stmt->bind_param('ss', $search_term, $search_term);
    }
    $search_stmt->execute();
    $result = $search_stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $officers[] = $row;
    }
    $result->free();
    $search_stmt->close();
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Officers Management - Dashboard</title>
    <link rel="stylesheet" href="css/add-sec.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Existing CSS code */
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

        .message {
            color: green;
            font-size: 16px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid green;
            background-color: #d4edda; 
            border-radius: 5px;
        }

        .error {
            color: red;
            font-size: 16px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid red;
            background-color: #f8d7da; 
            border-radius: 5px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Roboto', sans-serif;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .delete-btn, .update-btn {
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            color: #ff6f6f;
        }

        .delete-btn:hover {
            color: #ff3d3d;
        }

        .update-btn {
            color: #4a90e2;
        }

        .update-btn:hover {
            color: #2a80d2;
        }

        .search-container {
            margin-top: 20px;
        }

        .search-container input[type="text"] {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        .search-container button {
            padding: 8px 12px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        .form-update {
            margin-top: 20px;
        }

        .form-update input[type="text"],
        .form-update input[type="password"] {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .form-update button {
            padding: 8px 12px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-update button:hover {
            background-color: #0056b3;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }

            .delete-btn, .update-btn {
                font-size: 16px;
            }
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
                    <li><a href="add-sec.php" class="nav-payments">Add Security Officers</a></li>
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
        <section class="add-security-officer">
            <div class="container">
                <h2>Register New Security Officer</h2>
                <form id="officer-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="form-group">
                        <label for="officer-name">Officer Name:</label>
                        <input type="text" id="officer-name" name="officer_name" placeholder="Enter officer's name" required>
                    </div>
                    <div class="form-group">
                        <label for="officer-id">Officer ID:</label>
                        <input type="text" id="officer-id" name="officer_id" placeholder="Enter officer's ID" required>
                    </div>
                    <div class="form-group">
                        <label for="officer-password">Officer Password:</label>
                        <input type="password" id="officer-password" name="officer_password" placeholder="Enter officer's password" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-number-sl">Contact Number (SL):</label>
                        <input type="text" id="contact-number-sl" name="contact_number_sl" placeholder="Enter contact number (SL)" required>
                    </div>
                    <button type="submit" name="add" class="btn">Register Officer</button>
                </form>

                <?php if (isset($message)) { ?>
                    <div class="<?php echo htmlspecialchars($message['type']); ?>">
                        <?php echo htmlspecialchars($message['text']); ?>
                    </div>
                <?php } ?>

                <h2>Search Security Officers</h2>
                <form class="search-container" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <input type="text" name="search_term" placeholder="Search by name or ID" value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
                    <button type="submit" name="search">Search</button>
                </form>

                <h2>Security Officers List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Password</th>
                            <th>Contact Number (SL)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($officers as $officer) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($officer['Sname']); ?></td>
                                <td><?php echo htmlspecialchars($officer['SecId']); ?></td>
                                <td><?php echo htmlspecialchars($officer['Spass']); ?></td>
                                <td><?php echo htmlspecialchars($officer['Scon']); ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="update-btn" onclick="openUpdateForm('<?php echo htmlspecialchars($officer['SecId']); ?>', '<?php echo htmlspecialchars($officer['Sname']); ?>', '<?php echo htmlspecialchars($officer['Spass']); ?>', '<?php echo htmlspecialchars($officer['Scon']); ?>')">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="delete-btn" onclick="confirmDelete('<?php echo htmlspecialchars($officer['SecId']); ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div id="update-form" class="form-update" style="display:none;">
                    <h2>Update Security Officer</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <input type="hidden" id="update-id" name="update_id">
                        <div class="form-group">
                            <label for="update-name">Officer Name:</label>
                            <input type="text" id="update-name" name="update_name" placeholder="Enter officer's name" required>
                        </div>
                        <div class="form-group">
                            <label for="update-password">Officer Password:</label>
                            <input type="password" id="update-password" name="update_password" placeholder="Enter officer's password" required>
                        </div>
                        <div class="form-group">
                            <label for="update-contact-number">Contact Number (SL):</label>
                            <input type="text" id="update-contact-number" name="update_contact_number" placeholder="Enter contact number (SL)" required>
                        </div>
                        <button type="submit" name="update" class="btn">Update Officer</button>
                        <button type="button" class="btn" onclick="closeUpdateForm()">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 OneGalleFace Apartments. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this officer?")) {
                window.location.href = "?delete_id=" + encodeURIComponent(id);
            }
        }

        function openUpdateForm(id, name, password, contactNumber) {
            document.getElementById('update-id').value = id;
            document.getElementById('update-name').value = name;
            document.getElementById('update-password').value = password;
            document.getElementById('update-contact-number').value = contactNumber;
            document.getElementById('update-form').style.display = 'block';
        }

        function closeUpdateForm() {
            document.getElementById('update-form').style.display = 'none';
        }
    </script>
</body>
</html>
