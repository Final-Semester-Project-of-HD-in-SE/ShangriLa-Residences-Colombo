<?php
require_once('inc/connection.php');

$nicError = '';
$hrError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['resident_signup'])) {
        $Rname = $_POST['Rname'];
        $Rid = $_POST['Rid'];
        $RaptNum = $_POST['RaptNum'];
        $Rpassword = $_POST['Rpassword'];
        $Remail = $_POST['Remail'];

        $query = "SELECT * FROM resident WHERE Rid = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $Rid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $nicError = 'Error: Duplicate NIC.';
        } else {
            $query = "INSERT INTO resident (Rname, Rid, RaptNum, Rpassword, Remail) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("ssiss", $Rname, $Rid, $RaptNum, $Rpassword, $Remail);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header('Location: index.html');
                exit();
            } else {
                $nicError = 'Error: Could not register.';
            }
        }

        $stmt->close();
    } elseif (isset($_POST['hr_signup'])) {
        $Hname = $_POST['Hname'];
        $Hid = $_POST['Hid'];
        $Hemail = $_POST['Hemail'];
        $Hpassword = $_POST['Hpassword'];

        $query = "SELECT * FROM hr WHERE Hid = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $Hid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $hrError = 'Error: Duplicate NIC.';
        } else {
            $query = "INSERT INTO hr (Hname, Hid, Hemail, Hpassword) VALUES (?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("ssss", $Hname, $Hid, $Hemail, $Hpassword);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header('Location: index.html');
                exit();
            } else {
                $hrError = 'Error: Could not register.';
            }
        }

        $stmt->close();
    }
    $connection->close();
}
?>
<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com-->
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Registration forms</title>
    <link rel="stylesheet" href="css/signup.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
        <div class="front">
            <img src="images/shangri-la-colombo.jpg" alt="">
        </div>
        <div class="back">
            <!--<img class="backImg" src="images/backImg.jpg" alt="">-->
            <div class="text">
                <span class="text-1">Complete miles of journey <br> with one step</span>
                <span class="text-2">Let's get started</span>
            </div>
        </div>
    </div>
    <div class="forms">
        <div class="form-content">
            <div class="login-form">
                <div class="title">Resident Signup</div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fa fa-user"></i>
                            <input type="text" name="Rname" placeholder="Enter your name" required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-barcode"></i>
                            <input type="text" name="Rid" placeholder="Enter your NIC" required>
                            <div class="error" id="nic-error"><?php echo $nicError; ?></div> <!-- Display error message -->
                        </div>
                        <div class="input-box">
                            <i class="fas fa-home"></i>
                            <input type="text" name="RaptNum" placeholder="Enter your house number" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="Rpassword" placeholder="Enter your password" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="Remail" placeholder="Enter your email" required>
                        </div>
                        <div class="button input-box">
                            <input type="submit" name="resident_signup" value="Submit">
                        </div>
                        <div class="text sign-up-text"><label for="flip">I need to register as a resident</label></div>
                        <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                    </div>
                </form>
                <style>
                    .error {
                        color: red;
                        font-size: 14px;
                        margin-top: 5px;
                    }
                </style>
            </div>
            <div class="signup-form">
                <div class="title">HR Signup</div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" name="Hname" placeholder="Enter your name" required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-barcode"></i>
                            <input type="text" name="Hid" placeholder="Enter your NIC" required>
                            <div class="error" id="hr-error"><?php echo $hrError; ?></div> <!-- Display error message -->
                        </div>
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="text" name="Hemail" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="Hpassword" placeholder="Enter your password" required>
                        </div>
                        <div class="button input-box">
                            <input type="submit" name="hr_signup" value="Submit">
                        </div>
                        <div class="text sign-up-text"><label for="flip">I need to register as a resident</label></div>
                        <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
