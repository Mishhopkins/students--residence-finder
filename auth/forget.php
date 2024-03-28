<?php
    require '../config/config.php';

    if(isset($_POST['forgotpass'])) {
        $errMsg = '';

        // Getting data from FORM
        $username = $_POST['username'];
        $email = $_POST['email'];

        if (empty($username) && empty($email)) {
            $errMsg = 'Please enter your username or email to reset your password.';
        }

        if ($errMsg == '') {
            try {
                $stmt = $connect->prepare('SELECT password, username, email FROM users WHERE username = :username OR email = :email');
                $stmt->execute(array(
                    ':username' => $username,
                    ':email' => $email
                ));
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data) {
                    // You may want to send an email with a secure link to reset the password
                    // For simplicity, let's just display the password here
                    $viewpass = 'Your username: ' . $data['username'] . '<br>Your password is: ' . $data['password'] . '<br><a href="login.php">Login Now</a>';
                } else {
                    $errMsg = 'Username or email not found.';
                }
            } catch(PDOException $e) {
                $errMsg = $e->getMessage();
            }
        }
    }
?>

<?php include '../include/header.php';?>
    <!-- Services -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
      <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top">
  <img src="../assets/img/logo1.png" alt="Logo" style="width: 50px; height: 50px;  border-radius: 40%;">
</a>
<a class="navbar-brand js-scroll-trigger" href="../index.php"> /Home</a>

        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <!-- <a class="nav-link" href="login.php">Login</a> -->
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
              <a href="forgot.php" class="forgot-password-link">Forgot Password?</a> 
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <section id="services">
        <div class="container">
            <div class="row">                
              <div class="col-md-4 mx-auto">
                <div class="alert alert-info" role="alert">
                    <?php
                        if(isset($errMsg)){
                            echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
                        }
                        if(isset($viewpass)){
                            echo '<div style="color:#198E35;text-align:center;font-size:17px;margin-top:5px">'.$viewpass.'</div>';
                        }
                    ?>
                    <body>
    <div align="center">
        <div style="border: solid 1px #006D9C;" align="left">
            <?php
                if(isset($errMsg)){
                    echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
                }
            ?>
            <div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Forgot Password</b></div>
            <div style="margin: 15px">
                <form action="" method="post">
                    <input type="text" name="username" placeholder="Username" autocomplete="off" class="box"/><br />OR<br />
                    <input type="text" name="email" placeholder="Email" autocomplete="off" class="box"/><br /><br />
                    <input type="submit" name='forgotpass' value="Check Credentials" class='submit'/><br />
                </form>
            </div>
        </div>
    </div>     
                 </div>
            </div>
            </div>
        </div>
    </section>
<?php include '../include/footer.php';?>
