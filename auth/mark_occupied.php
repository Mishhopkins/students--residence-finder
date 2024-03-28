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

    // Fetch property details for houses
    $stmtHouses = $connect->prepare("
        SELECT *
        FROM room_rental_registrations 
        WHERE id = :id
    ");
    $stmtHouses->execute(array(':id' => $property_id));
    $house = $stmtHouses->fetch(PDO::FETCH_ASSOC);

    // Fetch property details for apartments
    $stmtApartments = $connect->prepare("
        SELECT *
        FROM room_rental_registrations_apartment 
        WHERE id = :id
    ");
    $stmtApartments->execute(array(':id' => $property_id));
    $apartment = $stmtApartments->fetch(PDO::FETCH_ASSOC);

    if ($house || $apartment) {
        // Check if the form is submitted to mark the property as occupied
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assuming you have a field named 'vacant' in your table to represent property status
            $vacant = 0; // Mark as occupied

            // Update the status in the appropriate table
            if ($house) {
                $updateStmt = $connect->prepare("UPDATE room_rental_registrations SET vacant = :vacant WHERE id = :id");
            } elseif ($apartment) {
                $updateStmt = $connect->prepare("UPDATE room_rental_registrations_apartment SET vacant = :vacant WHERE id = :id");
            }

            $updateStmt->execute(array(':vacant' => $vacant, ':id' => $property_id));

            // Redirect back to the dashboard after updating status
            header("Location: dashboard.php");
            exit();
        }

        // Display the form for marking the property as occupied
        include '../include/header.php';
        ?>

        <!-- Add necessary head elements here -->
        <title>Mark Property as Occupied - Msavi Campus Residence Finder</title>

        <!-- Add necessary styles if needed -->

        <?php
        include '../include/side-nav.php';
        ?>

        <section class="wrapper" style="margin-left: 16%; margin-top: -11%;">
            <div class="col-md-12">
                <h2>Mark Property as Occupied</h2>
                <p>Are you sure you want to mark the property as occupied?</p>
                <form method="post">
                    <button type="submit" class="btn btn-primary">Mark as Occupied</button>
                </form>
            </div>
        </section>

        <?php
        include '../include/footer.php';
    } else {
        echo 'Invalid property ID.';
    }
} else {
    echo 'Property ID not provided.';
}
?>
