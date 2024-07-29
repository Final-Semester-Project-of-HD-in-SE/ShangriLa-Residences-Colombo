<?php
session_start();
require_once('inc/connection.php');

$issue_id = $suggestion_id = ""; 
$issue_error = $suggestion_error = ""; 
$success_message = ""; 
$dp = $_SESSION['Rpic'];
$user_id = $_SESSION['rid']; // Ensure this session variable is set when the user logs in

function generate_random_id() {
    return mt_rand(10000, 99999); 
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_issue'])) {
    $apartment_number_issue = sanitize_input($_POST['apartment-number-issue']);
    $issue = sanitize_input($_POST['issue']);
    $email_issue = sanitize_input($_POST['email-issue']);

    if (empty($apartment_number_issue)) {
        $issue_error = "Apartment number is required";
    } elseif (empty($issue)) {
        $issue_error = "Issue description is required";
    } elseif (empty($email_issue)) {
        $issue_error = "Email is required";
    } else {
        $issue_id = generate_random_id();
        $sql = "INSERT INTO issues (Issue_id, Apt_no, Issue, Remail, Rid) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('isssi', $issue_id, $apartment_number_issue, $issue, $email_issue, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Issue submitted successfully.";
            $success_message = $_SESSION['success_message'];
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_suggestion'])) {
    $apartment_number_suggestion = sanitize_input($_POST['apartment-number-suggestion']);
    $suggestion = sanitize_input($_POST['suggestion']);
    $email_suggestion = sanitize_input($_POST['email-suggestion']);

    if (empty($apartment_number_suggestion)) {
        $suggestion_error = "Apartment number is required";
    } elseif (empty($suggestion)) {
        $suggestion_error = "Suggestion description is required";
    } elseif (empty($email_suggestion)) {
        $suggestion_error = "Email is required";
    } else {
        $suggestion_id = generate_random_id();
        $sql = "INSERT INTO suggestions (Suggestion_Id, Apt_no, Suggestion, Remail, Rid) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('isssi', $suggestion_id, $apartment_number_suggestion, $suggestion, $email_suggestion, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Suggestion submitted successfully.";
            $success_message = $_SESSION['success_message'];
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch issues and suggestions for the user
$issues = [];
$suggestions = [];

$sql = "SELECT * FROM issues WHERE Rid = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $issues[] = $row;
}
$stmt->close();

$sql = "SELECT * FROM suggestions WHERE Rid = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row;
}
$stmt->close();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-top: 10px;
        }

        .error-message {
            color: red;
            font-size: 0.875em;
        }

        .form-box input[type="email"]  {
            padding: 15px;
            margin-bottom: 15px;
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            width: 100%;
            transition: border-bottom 0.3s ease;
        }
        .form-box input[type="email"]:focus {
            border-bottom: 2px solid #004d7a;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        .issue-table {
            border: 2px solid #007bff;
        }

        .suggestion-table {
            border: 2px solid #28a745;
        }
        nav ul li a.active {
            color: yellow;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>OneGalleFace Apartments</h1>
            <nav>
                <ul>
                <li><a href="user-dash.php">Home</a></li>
                <li><a href="user-issues.php" class="active">Issues</a></li>
                <li><a href="product.php">Payments</a></li>
                <li><a href="user-visitors.php">Visitors</a></li>
                <li><a href="user-parkings.php">Parkings</a></li>
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
        <section class="forms-container">
            <div class="form-box">
                <h2>Report an Issue</h2>
                <form id="issue-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="apartment-number-issue">Apartment Number</label>
                    <input type="text" id="apartment-number-issue" name="apartment-number-issue" required>
                    <span class="error-message"><?php echo $issue_error; ?></span>

                    <label for="issue">Issue</label>
                    <textarea id="issue" name="issue" required></textarea>
                    <span class="error-message"><?php echo $issue_error; ?></span>

                    <label for="email-issue">Email</label>
                    <input type="email" id="email-issue" name="email-issue" required>
                    <span class="error-message"><?php echo $issue_error; ?></span>
                    
                    <button type="submit" name="submit_issue">Submit Issue</button>
                </form>
                <?php if (!empty($success_message) && isset($_POST['submit_issue'])): ?>
                    <div class="success-message">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-box">
                <h2>Submit a Suggestion</h2>
                <form id="suggestion-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="apartment-number-suggestion">Apartment Number</label>
                    <input type="text" id="apartment-number-suggestion" name="apartment-number-suggestion" required>
                    <span class="error-message"><?php echo $suggestion_error; ?></span>

                    <label for="suggestion">Suggestion</label>
                    <textarea id="suggestion" name="suggestion" required></textarea>
                    <span class="error-message"><?php echo $suggestion_error; ?></span>

                    <label for="email-suggestion">Email</label>
                    <input type="email" id="email-suggestion" name="email-suggestion" required>
                    <span class="error-message"><?php echo $suggestion_error; ?></span>
                    
                    <button type="submit" name="submit_suggestion">Submit Suggestion</button>
                </form>
                <?php if (!empty($success_message) && isset($_POST['submit_suggestion'])): ?>
                    <div class="success-message">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="tables-container">
            <div class="form-box">
                <h2>Your Issues</h2>
                <table class="issue-table">
                    <thead>
                        <tr>
                            <th>Issue ID</th>
                            <th>Issue Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($issues)): ?>
                            <?php foreach ($issues as $issue): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($issue['Issue_id']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['Issue']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No issues found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="form-box">
                <h2>Your Suggestions</h2>
                <table class="suggestion-table">
                    <thead>
                        <tr>
                            <th>Suggestion ID</th>
                            <th>Suggestion Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suggestions)): ?>
                            <?php foreach ($suggestions as $suggestion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($suggestion['Suggestion_Id']); ?></td>
                                    <td><?php echo htmlspecialchars($suggestion['Suggestion']); ?></td>
                                    <td><?php echo htmlspecialchars($suggestion['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No suggestions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
