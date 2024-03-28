<?php
require '../config/config.php'; // Adjust the path as needed

// Retrieve property details from the query parameters
$property_id = isset($_GET['id']) ? $_GET['id'] : null;

$student_details = isset($_SESSION['student_details']) ? $_SESSION['student_details'] : null;
$payment_details = isset($_SESSION['payment_details']) ? $_SESSION['payment_details'] : null;

// Check if both student and payment details are available
if (!$student_details || !$payment_details) {
    // Redirect to the initial page if data is not available
    header("Location: payments.php?id=$property_id&step=1");
    exit();
}

// Clear the session variables as they are no longer needed
unset($_SESSION['student_details']);
unset($_SESSION['payment_details']);
?>


<?php include '../include/header.php';?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="section-heading text-uppercase">Confirmation</h2>
                <p class="text-muted">Thank you for your booking! Please review the details below:</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- Display student details -->
                <h4>Student Details:</h4>
                <?php
                    // Display student details
                    echo "<p><strong>First Name:</strong> {$student_details['first_name']}</p>";
                    echo "<p><strong>Last Name:</strong> {$student_details['last_name']}</p>";
                    echo "<p><strong>Gender:</strong> {$student_details['gender']}</p>";
                    echo "<p><strong>Email:</strong> {$student_details['email']}</p>";
                ?>

                <!-- Display payment details -->
                <h4>Payment Details:</h4>
                <?php
                    // Fetch payment details using student's ID
                    $stmt = $connect->prepare("SELECT amount_paid, sender FROM payments WHERE student_id IN (SELECT id FROM students WHERE property_id = ?)");
                    $stmt->execute([$property_id]);
                    $payment_details = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Display payment details
                    if ($payment_details) {
                        echo "<p><strong>Amount Paid:</strong> {$payment_details['amount_paid']}</p>";
                        echo "<p><strong>Sender:</strong> {$payment_details['sender']}</p>";
                    }
                ?>
            </div>
        </div>
    </div>

    <!-- Add necessary scripts and styles if needed -->
    <script>
        // Redirect back to the main index page after 5 seconds
        setTimeout(function() {
            window.location.href = '../index.php'; // Adjust the path as needed
        }, 5000); // 5000 milliseconds (5 seconds)
    </script>
</body>
</html>
