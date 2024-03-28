<?php 
require '../config/config.php';

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if property_id is set in the URL
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Fetch bookings for the specified property
    $stmt = $connect->prepare('SELECT students.*, payments.amount_paid, payments.status AS payment_status, 
                                IFNULL(registrations.fullname, apartments.fullname) AS property_name
                                FROM students 
                                LEFT JOIN payments ON students.id = payments.student_id 
                                LEFT JOIN room_rental_registrations registrations ON students.property_id = registrations.id
                                LEFT JOIN room_rental_registrations_apartment apartments ON students.property_id = apartments.id
                                WHERE students.property_id = :property_id');
    $stmt->execute(array(':property_id' => $property_id));
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include '../include/header.php'; ?>

<!-- Header nav -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            <img src="../assets/img/logo1.png" alt="Logo" style="width: 50px; height: 50px;  border-radius: 40%;">
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if ($_SESSION['role'] == 'admin') {
                                                            echo "(Admin)";
                                                        } ?></a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- end header nav -->

<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar -->
        <?php include '../include/side-nav.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="mt-4 mb-1">
            <div class="text-right d-print-none">
                <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light"><i class="fas fa-print mr-1"></i> Print Students' List</a>
            </div>
        </div>
        
            <?php
            // Display the list of bookings and update status
            if (count($bookings) > 0) {
                echo '<h2 class="mb-4">Bookings for Property: ' . $bookings[0]['property_name'] . '</h2>';
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-striped">';
                echo '<thead class="thead-dark">';
                echo '<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Gender</th><th>Email</th><th>Amount Paid</th><th>Payment Status</th><th>Action</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($bookings as $booking) {
                    echo '<tr>';
                    echo '<td>' . $booking['id'] . '</td>';
                    echo '<td>' . $booking['first_name'] . '</td>';
                    echo '<td>' . $booking['last_name'] . '</td>';
                    echo '<td>' . $booking['gender'] . '</td>';
                    echo '<td>' . $booking['email'] . '</td>';
                    echo '<td>' . $booking['amount_paid'] . '</td>';
                    echo '<td>' . $booking['payment_status'] . '</td>';
                    echo '<td><a href="updatestatus.php?id=' . $booking['id'] . '" class="btn btn-info btn-sm">Update Status</a></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p class="lead">No bookings for this property.</p>';
            }
            ?>
        </main>
    </div>
</div>

<?php include '../include/footer.php'; ?>
