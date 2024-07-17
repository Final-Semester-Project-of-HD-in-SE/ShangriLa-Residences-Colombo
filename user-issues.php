<?php
session_start();
require_once('inc/connection.php');

$issue_id = $suggestion_id = ""; 
$issue_error = $suggestion_error = ""; 
$success_message = ""; 

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

    if (empty($apartment_number_issue)) {
        $issue_error = "Apartment number is required";
    } elseif (empty($issue)) {
        $issue_error = "Issue description is required";
    } else {
 
        $issue_id = generate_random_id();

        $sql = "INSERT INTO issues (Issue_id, Apt_no, Issue) VALUES ($issue_id, $apartment_number_issue, '$issue')";
        
        if ($connection->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Issue submitted successfully.";
            $success_message = $_SESSION['success_message'];
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_suggestion'])) {
    $apartment_number_suggestion = sanitize_input($_POST['apartment-number-suggestion']);
    $suggestion = sanitize_input($_POST['suggestion']);

    if (empty($apartment_number_suggestion)) {
        $suggestion_error = "Apartment number is required";
    } elseif (empty($suggestion)) {
        $suggestion_error = "Suggestion description is required";
    } else {
      
        $suggestion_id = generate_random_id();

        $sql = "INSERT INTO suggestions (Suggestion_Id, Apt_no, Suggestion) VALUES ($suggestion_id, '$apartment_number_suggestion', '$suggestion')";
        
        if ($connection->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Suggestion submitted successfully.";
            $success_message = $_SESSION['success_message'];
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
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
    <link rel="stylesheet" href="css/user-issues.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-top: 10px;
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
                    <li><a href="user-issues.php">Issues</a></li>
                    <li><a href="product.html">Payments</a></li>
                    <li><a href="user-visitors.html">Visitors</a></li>
                    <li><a href="user-prof.html">Profile</a></li>
                </ul>
            </nav>
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
                    
                    <button type="submit" name="submit_suggestion">Submit Suggestion</button>
                </form>
                <?php if (!empty($success_message) && isset($_POST['submit_suggestion'])): ?>
                    <div class="success-message">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
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
