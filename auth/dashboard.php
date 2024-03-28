<?php
    require '../config/config.php';
    if(empty($_SESSION['username']))
        header('Location: login.php');

    if($_SESSION['role'] == 'admin'){
        $stmt = $connect->prepare('SELECT count(*) as register_user FROM users');
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $connect->prepare('SELECT count(*) as total_rent FROM room_rental_registrations');
        $stmt->execute();
        $total_rent = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $connect->prepare('SELECT count(*) as total_rent_apartment FROM room_rental_registrations_apartment');
        $stmt->execute();
        $total_rent_apartment = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $stmt = $connect->prepare('SELECT count(*) as total_auth_user_rent FROM room_rental_registrations WHERE user_id = :user_id');
    $stmt->execute(array(
        ':user_id' => $_SESSION['id']
    ));
    $total_auth_user_rent = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $connect->prepare('SELECT count(*) as total_auth_user_rent_ap FROM room_rental_registrations_apartment WHERE user_id = :user_id');
    $stmt->execute(array(
        ':user_id' => $_SESSION['id']
    ));
    $total_auth_user_rent_ap = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Assuming you have a column named user_id in both tables
    $user_id = $_SESSION['id'];

    // Fetch properties posted by the logged-in user (landlord/owner)
    $stmt = $connect->prepare('SELECT * FROM room_rental_registrations WHERE user_id = :user_id');
    $stmt->execute(array(':user_id' => $user_id));
    $user_properties_1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment WHERE user_id = :user_id');
    $stmt->execute(array(':user_id' => $user_id));
    $user_properties_2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge the results from both tables
    $user_properties = array_merge($user_properties_1, $user_properties_2);
?>

<?php include '../include/header.php';?>
    <!-- Header nav -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
      <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top">
  <img src="../assets/img/logo1.png" alt="Logo" style="width: 50px; height: 50px;  border-radius: 40%;">
</a>
<!-- <a class="navbar-brand js-scroll-trigger" href="../index.php"> /Home</a> -->

        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if($_SESSION['role'] == 'admin'){ echo "(Admin)"; } ?></a>
            </li>
            <li class="nav-item">
              <a href="logout.php" class="nav-link">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- end header nav -->
<?php include '../include/side-nav.php';?>
    <section class="wrapper" style="margin-left: 16%;margin-top: -11%;">
        <div class="col-md-12">
            <h1>Dash board</h1>
            <div class="row">
                <?php 
                    if($_SESSION['role'] == 'admin'){ 
                        echo '<div class="col-md-3">';
                        echo '<a href="../app/users.php"><div class="alert alert-warning" role="alert">';
                        echo '<b>Registered Users: <span class="badge badge-pill badge-success">'.$count['register_user'].'</span></b>';
                        echo '</div></a>';
                        echo '</div>';
                    } 
                ?>  
                <?php 
                    if($_SESSION['role'] == 'admin'){ 
                        echo '<div class="col-md-3">';
                        echo '<a href="../app/list.php"><div class="alert alert-warning" role="alert">';
                        echo '<b>Rooms for Rent: <span class="badge badge-pill badge-success">'.(intval($total_rent['total_rent'])+intval($total_rent_apartment['total_rent_apartment'])).'</span></b>';
                        echo '</div></a>';
                        echo '</div>';
                    } 
                ?>
                <?php 
                    if($_SESSION['role'] == 'user'){ 
                        echo '<div class="col-md-3">';
                        echo '<a href="../app/list.php"><div class="alert alert-warning" role="alert">';
                        echo '<b>Registered Rooms: <span class="badge badge-pill badge-success">'.$total_auth_user_rent['total_auth_user_rent'].'</span></b>';
                        echo '</div></a>';
                        echo '</div>';
                    } 
                ?>

<?php 
                    if($_SESSION['role'] == 'user'){ 
                        echo '<div class="col-md-3">';
                        echo '<a href="../app/list.php"><div class="alert alert-warning" role="alert">';
                        echo '<b>Registered Apartments: <span class="badge badge-pill badge-success">'.$total_auth_user_rent_ap['total_auth_user_rent_ap'].'</span></b>';
                        echo '</div></a>';
                        echo '</div>';
                    } 
                ?>
            </div>

            <?php
            // Display the list of properties posted by the logged-in user (landlord/owner)
            if($_SESSION['role'] == 'user' && count($user_properties) > 0){
                echo '<h2>Your Properties</h2>';
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">Property ID</th>';
                echo '<th scope="col">Property Name</th>';
                echo '<th scope="col">Status</th>';
                echo '<th scope="col">Actions</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
				foreach ($user_properties as $property) {
					echo '<tr>';
					echo '<th scope="row">' . (isset($property['id']) ? $property['id'] : '') . '</th>';
					echo '<th scope="row">' . (isset($property['fullname']) ? $property['fullname'] : '') . '</th>';
					// Add logic to display the status of the property (occupied or not)
					echo '<td>' . (isset($property['vacant']) ? ($property['vacant'] == 1 ? 'Available' : 'Occupied') : '') . '</td>';

					echo '<td>';
					// Add logic to display the list of students who have booked and paid for this property
					echo '<a href="view_bookings.php?id=' . (isset($property['id']) ? $property['id'] : '') . '">View Bookings</a>';
					// Add logic to mark the property as "occupied" (update the database)
					echo ' | <a href="mark_occupied.php?id=' . (isset($property['id']) ? $property['id'] : '') . '">Mark as Occupied</a>';
					echo '</td>';
					echo '</tr>';
				}
			}				
            ?>
        </div>
    </section>
<?php include '../include/footer.php';?>
