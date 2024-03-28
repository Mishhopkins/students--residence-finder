<?php
require '../config/config.php';

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if student_id is set in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch payment details for the specified student_id
    $stmtPayment = $connect->prepare('SELECT payments.*, students.property_id, 
                                      IFNULL(registrations.fullname, apartments.fullname) AS property_name
                                      FROM payments 
                                      LEFT JOIN students ON payments.student_id = students.id
                                      LEFT JOIN room_rental_registrations registrations ON students.property_id = registrations.id
                                      LEFT JOIN room_rental_registrations_apartment apartments ON students.property_id = apartments.id
                                      WHERE payments.student_id = :id');
    $stmtPayment->execute(array(':id' => $student_id));
    $payment = $stmtPayment->fetch(PDO::FETCH_ASSOC);

    if ($payment) {
        // Check if the form is submitted for status update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = isset($_POST['status']) ? $_POST['status'] : null;

            // Update the status in the payments table
            $updateStmt = $connect->prepare('UPDATE payments SET status = :status WHERE student_id = :id');
            $updateStmt->execute(array(':status' => $status, ':id' => $student_id));

            // Redirect back to view_bookings.php after updating status
            header("Location: view_bookings.php?id={$payment['property_id']}");
            exit();
        }

        ?>

        <!-- Include header and navigation here -->
        <?php include '../include/header.php'; ?>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
            <!-- Navigation content here -->
        </nav>
        <?php include '../include/side-nav.php'; ?>

        <!-- Display the form for updating status -->
        <div class="container-fluid mt-5">
            <div class="row">
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <h2 class="mb-4">Update Booking Status for <?php echo $payment['property_name']; ?></h2>
                    <form method="post">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status:</label>
                            <select class="form-select" name="status" required>
                                <option value="pending" <?php echo ($payment['status'] === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="booked" <?php echo ($payment['status'] === 'booked' ? 'selected' : ''); ?>>Booked</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </main>
            </div>
        </div>

        <!-- Include footer here -->
        <?php include '../include/footer.php'; ?>
    <?php
    } else {
        echo 'Invalid student ID.';
    }
} else {
    echo 'Student ID not provided.';
}
?>
