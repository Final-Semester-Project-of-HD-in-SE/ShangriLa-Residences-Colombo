<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$dp = $_SESSION['Hpic'];

require_once('inc/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['status'], $_POST['comment'], $_POST['type'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $comment = $_POST['comment'];
        $type = $_POST['type'];

        if ($type === 'issue') {
            $table = 'issues';
            $idColumn = 'Issue_id';
        } elseif ($type === 'suggestion') {
            $table = 'suggestions';
            $idColumn = 'Suggestion_Id';
        } else {
            die('Invalid type');
        }

        $stmt = $connection->prepare("UPDATE $table SET status = ?, comment = ? WHERE $idColumn = ?");
        $stmt->bind_param('ssi', $status, $comment, $id);

        if ($stmt->execute()) {
            $emailStmt = $connection->prepare("SELECT Remail FROM $table WHERE $idColumn = ?");
            $emailStmt->bind_param('i', $id);
            $emailStmt->execute();
            $result = $emailStmt->get_result();
            $emailRow = $result->fetch_assoc();
            $remail = $emailRow['Remail'];

            $subject = "Status Update: $type ID $id";
            $message = "Dear User,%0D%0A%0D%0A" .
                       "Your $type has been updated.%0D%0A" .
                       "Current Status: " . urlencode($status) . "%0D%0A" .
                       "Comment: " . urlencode($comment) . "%0D%0A" .
                       "%0D%0A" .
                       "Best regards,%0D%0AThe Team";
            $mailtoLink = "mailto:" . urlencode($remail) . "?subject=" . urlencode($subject) . "&body=" . $message;

            echo json_encode([
                'status' => 'success',
                'message' => 'Data updated successfully. You can now send an email using the following link:',
                'mailtoLink' => $mailtoLink
            ]);

            $emailStmt->close();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $stmt->error
            ]);
        }

        $stmt->close();
        $connection->close();
        exit();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input'
        ]);
        exit();
    }
}

$issuesQuery = "SELECT * FROM issues ORDER BY Issue_id ASC";
$issuesResult = $connection->query($issuesQuery);
$issuesData = [];
if ($issuesResult && $issuesResult->num_rows > 0) {
    while ($row = $issuesResult->fetch_assoc()) {
        $issuesData[] = $row;
    }
}

$suggestionsQuery = "SELECT * FROM suggestions ORDER BY Suggestion_Id ASC";
$suggestionsResult = $connection->query($suggestionsQuery);
$suggestionsData = [];
if ($suggestionsResult && $suggestionsResult->num_rows > 0) {
    while ($row = $suggestionsResult->fetch_assoc()) {
        $suggestionsData[] = $row;
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Owners Dashboard</title>
    <link rel="stylesheet" href="css/user-dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .data-table th, .data-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .data-table th {
            background-color: #f4f4f4;
        }

        .status-dropdown, .comment-input, .save-button {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .save-button {
            width: auto;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            text-align: center;
        }

        .save-button:hover {
            background-color: #45a049;
        }
        .nav-payments {
            color: yellow; 
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
                    <li><a href="Management-complain.php"  class="nav-payments">Complaints</a></li>
                    <li><a href="Management-payment.php">Payments</a></li>
                    <li><a href="Management-visitors.php">Visitors</a></li>
                    <li><a href="add-sec.php">Add Security Officers</a></li> 
                    <li><a href="Management-parking.php">Parkings</a></li> 
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
        <section class="data">
            <div class="container">
                <h2>Issues</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Issue ID</th>
                            <th>Apartment No</th>
                            <th>Issue</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($issuesData as $issue): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($issue['Issue_id']); ?></td>
                            <td><?php echo htmlspecialchars($issue['Apt_no']); ?></td>
                            <td><?php echo htmlspecialchars($issue['Issue']); ?></td>
                            <td>
                                <select class="status-dropdown" data-id="<?php echo htmlspecialchars($issue['Issue_id']); ?>" data-type="issue">
                                    <option value="To check" <?php echo $issue['status'] == 'To check' ? 'selected' : ''; ?>>To check</option>
                                    <option value="In progress" <?php echo $issue['status'] == 'In progress' ? 'selected' : ''; ?>>In progress</option>
                                    <option value="Done" <?php echo $issue['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="comment-input" data-id="<?php echo htmlspecialchars($issue['Issue_id']); ?>" data-type="issue" value="<?php echo htmlspecialchars($issue['comment']); ?>">
                            </td>
                            <td>
                                <button class="save-button" data-id="<?php echo htmlspecialchars($issue['Issue_id']); ?>" data-type="issue">Save</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h2>Suggestions</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Suggestion ID</th>
                            <th>Apartment No</th>
                            <th>Suggestion</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suggestionsData as $suggestion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($suggestion['Suggestion_Id']); ?></td>
                            <td><?php echo htmlspecialchars($suggestion['Apt_no']); ?></td>
                            <td><?php echo htmlspecialchars($suggestion['Suggestion']); ?></td>
                            <td>
                                <select class="status-dropdown" data-id="<?php echo htmlspecialchars($suggestion['Suggestion_Id']); ?>" data-type="suggestion">
                                    <option value="To check" <?php echo $suggestion['status'] == 'To check' ? 'selected' : ''; ?>>To check</option>
                                    <option value="In progress" <?php echo $suggestion['status'] == 'In progress' ? 'selected' : ''; ?>>In progress</option>
                                    <option value="Done" <?php echo $suggestion['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="comment-input" data-id="<?php echo htmlspecialchars($suggestion['Suggestion_Id']); ?>" data-type="suggestion" value="<?php echo htmlspecialchars($suggestion['comment']); ?>">
                            </td>
                            <td>
                                <button class="save-button" data-id="<?php echo htmlspecialchars($suggestion['Suggestion_Id']); ?>" data-type="suggestion">Save</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.save-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var type = this.getAttribute('data-type');
                var row = this.parentElement.parentElement;
                var statusDropdown = row.querySelector('.status-dropdown');
                var status = statusDropdown ? statusDropdown.value : null;
                var commentInput = row.querySelector('.comment-input');
                var comment = commentInput ? commentInput.value : null;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '', true); 
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            window.location.href = response.mailtoLink;
                        } else {
                            alert(response.message);
                        }
                    } else {
                        alert('Failed to update data');
                    }
                };
                xhr.onerror = function () {
                    alert('Request failed');
                };
                xhr.send('id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status) + '&comment=' + encodeURIComponent(comment) + '&type=' + encodeURIComponent(type));
            });
        });
    });
    </script>
</body>
</html>
