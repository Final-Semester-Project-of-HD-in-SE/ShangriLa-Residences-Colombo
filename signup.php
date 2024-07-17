<?php
require_once('inc/connection.php');

$nicError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Rname = $_POST['Rname'];
    $Rid = $_POST['Rid'];
    $RaptNum = $_POST['RaptNum'];
    $Rpassword = $_POST['Rpassword']; 
    $Remail = $_POST['Remail'];

    $query = "SELECT * FROM resident WHERE Rid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $Rid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $nicError = 'Error: Duplicate NIC.';
    } else {
        // Insert new resident into the table
        $query = "INSERT INTO resident (Rname, Rid, RaptNum, Rpassword, Remail) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $Rname, $Rid, $RaptNum, $Rpassword, $Remail);

        if ($stmt->execute()) {
            header('Location: index.html');
            exit();
        } else {
            $nicError = 'Error: Could not register.';
        }
    }

    $stmt->close();
    $conn->close();
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
      <div class="error" id="nic-error"></div> <!-- Error message placeholder -->
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
      <input type="submit" value="Submit">
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
          <div class="title">Management Signup</div>
        <form action="#">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Enter your name" required>
              </div>
              <div class="input-box">
                <i class="fa fa-barcode"></i>
                <input type="text" placeholder="Enter your NIC" required>
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" placeholder="Enter your email" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Enter your password" required>
              </div>
              <div class="button input-box">
                <input type="submit" value="Sumbit">
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