<?php
require '../config/config.php';

// Initialize variables
$property_id = isset($_GET['id']) ? $_GET['id'] : null;
$property_type = isset($_POST['property_type']) ? $_POST['property_type'] : null;
$rent = isset($_POST['rent']) ? $_POST['rent'] : null;
$deposit = isset($_POST['deposit']) ? $_POST['deposit'] : null;
$location = isset($_POST['location']) ? $_POST['location'] : null;

// Track the current step
$current_step = isset($_GET['step']) ? $_GET['step'] : 1;

// Handle form submissions based on the step
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($current_step == 1) {
            // Step 1: Collect student details
            $telephone_number = $_POST['telephone_number'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $gender = $_POST['gender'];
            $email = $_POST['email'];

            // Store student details in the session for later use
            $_SESSION['student_details'] = [
                'telephone_number' => $telephone_number,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'gender' => $gender,
                'email' => $email,
                'rent' => $rent, // Include the rent in the session
            ];

            // Insert data into 'students' table
            $stmt = $connect->prepare("INSERT INTO students (property_id, telephone_number, first_name, last_name, gender, email) 
                                VALUES (:property_id, :telephone_number, :first_name, :last_name, :gender, :email)");
            $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
            $stmt->bindParam(':telephone_number', $telephone_number, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // Get the last inserted student_id
            $student_id = $connect->lastInsertId();

            // Store the student_id in the session for later use in step 2
            $_SESSION['student_id'] = $student_id;

            // Move to the next step
            header("Location: payments.php?id=$property_id&step=2");
            exit();
        }

        if ($current_step == 2) {
            // Step 2: Collect payment details
            $amount_paid = $_POST['amount_paid'];
            $sender = $_POST['sender'];

            // Retrieve student_id from the session
            $student_id = $_SESSION['student_id'];

            // Retrieve total rent from the session
            $total_rent = $_SESSION['student_details']['rent'];

            // Calculate the balance
            $balance = (int)$total_rent - (int)$amount_paid;

            // Store payment details in the session for later use
            $_SESSION['payment_details'] = [
                'amount_paid' => $amount_paid,
                'sender' => $sender,
                'balance' => $balance,
            ];

            // Insert data into 'payments' table
            $stmt = $connect->prepare("INSERT INTO payments (student_id, amount_paid, sender, balance, status) 
                                       VALUES (:student_id, :amount_paid, :sender, :balance, 'Pending')");
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':amount_paid', $amount_paid, PDO::PARAM_STR);
            $stmt->bindParam(':sender', $sender, PDO::PARAM_STR);
            $stmt->bindParam(':balance', $balance, PDO::PARAM_STR);
            $stmt->execute();

            // Set a confirmation message in the session
            $_SESSION['confirmation_message'] = "Booking completed successfully!";

            // Redirect to confirmation.php after processing step 2
            if (isset($_POST['complete_booking'])) {
                header("Location: confirmation.php?id=$property_id");
                exit();
            }
        }
    } catch (PDOException $e) {
        // Handle the exception appropriately (log, display an error message, etc.)
        echo "Error: " . $e->getMessage();
    }
}

// Clean up the session after displaying the confirmation message
$confirmation_message = isset($_SESSION['confirmation_message']) ? $_SESSION['confirmation_message'] : null;
unset($_SESSION['confirmation_message']);
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
			  <a href="forget.php" class="forgot-password-link">Forgot Password?</a> 
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container"> <br>
    <a href="../index.php" class="btn btn-secondary">Back to Home</a>
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="section-heading text-uppercase">Booking and Payment</h2>
                <p class="text-muted">Step <?= $current_step ?> of 2</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php if ($current_step == 1): ?>
                    <form action="payments.php?id=<?= $property_id ?>&step=1" method="post">
                        <input type="hidden" name="property_type" value="<?= $property_type ?>">
                        <input type="hidden" name="rent" value="<?= $rent ?>">
                        <input type="hidden" name="deposit" value="<?= $deposit ?>">
                        <input type="hidden" name="location" value="<?= $location ?>">

                        <div class="form-group">
                            <label for="telephone_number">Telephone Number</label>
                            <input type="text" class="form-control" id="telephone_number" name="telephone_number" required>
                        </div>

                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                <?php endif; ?>

                <?php if ($current_step == 2): ?>
                    <form action="payments.php?id=<?= $property_id ?>&step=2" method="post">
                        <input type="hidden" name="property_id" value="<?= $property_id ?>">
                        <input type="hidden" name="rent" value="<?= $rent ?>">
                        <input type="hidden" name="deposit" value="<?= $deposit ?>">
                        <input type="hidden" name="location" value="<?= $location ?>">

                        <div class="form-group">
                            <label for="amount_paid">Amount Paid</label>
                            <input type="number" class="form-control" id="amount_paid" name="amount_paid" required>
                        </div>

                        <div class="form-group">
                            <label for="sender">Sender</label>
                            <input type="text" class="form-control" id="sender" name="sender" required>
                        </div>

                        <button type="submit" class="btn btn-primary" name="complete_booking" onclick="return confirm('Are you sure you want to complete the booking?')">Complete Booking</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($confirmation_message): ?>
        <div class="container mt-3">
            <div class="alert alert-success" role="alert">
                <?= $confirmation_message ?>
            </div>
        </div>
    <?php endif; ?>

    <?php include '../include/footer.php';?>

