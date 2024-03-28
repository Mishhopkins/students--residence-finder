<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$errMsg = ''; // Initialize error message

if (isset($_POST['register'])) {
    // Get data from FORM
    $username = $_POST['username'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    // Additional checks before registration
    if (trim($password) == '') {
        $errMsg = 'Password cannot be empty.';
    } elseif (trim($password) != trim($c_password)) {
        $errMsg = 'Passwords do not match.';
    } else {
        // Combine first name and last name to create full name
        $fullname = $firstName . ' ' . $lastName;

        try {
            $stmt = $connect->prepare('INSERT INTO users (fullname, mobile, username, email, password) VALUES (:fullname, :mobile, :username, :email, :password)');
            $stmt->execute(array(
                ':fullname' => $fullname,
                ':username' => $username,
                ':password' => $password, // Note: It's a good practice to hash passwords before storing them in the database for security.
                ':email' => $email,
                ':mobile' => $mobile,
            ));
            header('Location: register.php?action=joined');
            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'joined') {
    $errMsg = 'Registration successful. Now you can login';
}
?>

<?php include '../include/header.php';?>
	<!-- <nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="../index.php">WebSiteName</a>
	    </div>
	    <ul class="nav navbar-nav navbar-right">
			<li><a href="login.php">Login</a></li>
			<li><a href="register.php">Register</a></li>
	    </ul>
	  </div>
	</nav> -->
	<!-- Services -->
	<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
      <div class="container">

	  <a class="navbar-brand js-scroll-trigger" href="#page-top">
  <img src="../assets/img/logo1.png" alt="Logo" style="width: 70px; height: 70px;  border-radius: 40%;">
</a>
        <a class="navbar-brand js-scroll-trigger" href="../index.php"> /Home</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
			  <a href="forget.php" class="forgot-password-link">Forgot Password?</a>
            </li>
            <li class="nav-item">
              <!-- <a class="nav-link" href="register.php">Register</a> -->
            </li>
          </ul>
        </div>
      </div>
    </nav>
<!-- <section> --><br>
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="alert alert-info" role="alert">
                <?php
                if (isset($errMsg) && !empty($errMsg)) {
                    echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
                }
                ?>
			  		<h2 class="text-center">Register <div style="color:red;text-align:center;font-size:17px;"><em>(For landlords/owners only)</em></div></h2>
				  	<form action="" method="post" onsubmit="return validateForm()">
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label for="first_name">First Name</label>
									<input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" required>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label for="last_name">Last Name</label>
									<input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" required>
								</div>
							</div>
						</div>
				  		<div class="row">
							<div class="col-6">
							  <div class="form-group">
							    <label for="username">User Name</label>
							    <input type="text" class="form-control" id="username" placeholder="User Name" name="username" required>
							  </div>
						    </div>
					   </div>
					   <div class="row">
					  	    <div class="col-6">
							  <div class="form-group">
							    <label for="mobile">Mobile</label>
							    <input type="text" class="form-control" pattern="^(\d{10})$" id="mobile" title="10 digit mobile number" placeholder="10 digit mobile number" name="mobile" required>
							  </div>
							 </div>
							<div class="col-6">					  
							  <div class="form-group">
							    <label for="email">Email</label>
							    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
							  </div>
							 </div>
						</div>

						<div class="form-group">
					    <label for="password">Password</label>
					    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
					  </div>

					  <div class="form-group">
					    <label for="c_password">Confirm Password</label>
					    <input type="password" class="form-control" id="c_password" placeholder="Confirm Password" name="c_password" required>
                        <div id="password-error" style="color:red;"></div>
					  </div>

					  <button type="submit" class="btn btn-primary" name='register' value="register">Submit</button>
					</form>				
				</div>
			</div>
		</div>
	</div>
<!-- </section> -->
<?php include '../include/footer.php';?>

<script>
function validateForm() {
    var password = document.getElementById("password").value;
    var c_password = document.getElementById("c_password").value;
    
    if (password !== c_password) {
        document.getElementById("password-error").innerHTML = "Passwords do not match.";
        return false;
    } else {
        document.getElementById("password-error").innerHTML = "";
        return true;
    }
}
</script>

