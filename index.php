<?php
require 'config/config.php';
$data = [];

if (isset($_POST['search'])) {
    // Get data from FORM
    $location = $_POST['location'];
    $property_type = $_POST['property_type'];
    $min_budget = $_POST['min_budget'];
    $max_budget = $_POST['max_budget'];

    // Check if all fields are filled
    if (empty($location) || empty($property_type) || empty($min_budget) || empty($max_budget)) {
        $errMsg = 'Please fill in all details for the search.';
    } else {
        // Location based search
        $locations = explode(',', $location);
        $loc = "(" . implode(",", array_map(function ($value) {
            return "'$value'";
        }, $locations)) . ")";

        try {
            $stmt = $connect->prepare("SELECT 
                id, fullname, mobile, alternat_mobile, email, country, location, city, landmark, 
                property_type, rent, deposit, plot_number, rooms, address, accommodation, description, 
                image, open_for_sharing, other, vacant, created_at, updated_at, user_id
                FROM room_rental_registrations
                WHERE (location IN $loc OR city IN $loc OR address IN $loc)
                AND property_type = :property_type
                AND rent BETWEEN :min_budget AND :max_budget 
                AND deposit BETWEEN :min_budget AND :max_budget
                UNION
                SELECT 
                id, fullname, mobile, alternat_mobile, email, country, location, city, landmark, 
                property_type, rent, deposit, plot_number, rooms, address, accommodation, description, 
                image, open_for_sharing, other, vacant, created_at, updated_at, user_id
                FROM room_rental_registrations_apartment
                WHERE (location IN $loc OR city IN $loc OR address IN $loc)
                AND property_type = :property_type
                AND rent BETWEEN :min_budget AND :max_budget 
                AND deposit BETWEEN :min_budget AND :max_budget");
            $stmt->bindParam(':min_budget', $min_budget, PDO::PARAM_INT);
            $stmt->bindParam(':max_budget', $max_budget, PDO::PARAM_INT);
            $stmt->bindParam(':property_type', $property_type, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($data)) {
                // Set the error message when no houses are found
                $errMsg = 'No house found.';
            }
        } catch (PDOException $e) {
            $errMsg = $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Msavi Campus Residence Finder</title>

    <!-- Favicons -->
    <link rel="shortcut icon" href="assets/img/logo1.png" />

    <!-- Bootstrap core CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="assets/css/rent.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: linear-gradient(to right, #007BFF, #00BFFF); box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="#page-top">
                <img src="assets/img/logo1.png" alt="Logo" style="width: 60px; height: 60px; border-radius: 40%;">
            </a>
            <a class="navbar-brand js-scroll-trigger" href="#page-top"> / Home</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#search">Search</a>
                    </li>
                    <?php
                    if (empty($_SESSION['username'])) {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="./auth/login.php">Login</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="./auth/dashboard.php">Home</a>';
                        echo '</li>';
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./auth/register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="masthead">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in">Students' Residence Finder!</div>
                <div class="intro-heading text-uppercase">It's Nice To See You<br></div>
            </div>
        </div>
    </header>

    <!-- Search -->
    <section id="search">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading text-uppercase">Search</h2>
                    <h3 class="section-subheading text-muted">Search rooms or Find a rental house near you.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-16">
                    <form action="" method="POST" class="center" novalidate>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" id="location" type="text" name="location" placeholder="Location" required data-validation-required-message="Please enter location">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control" id="property_type" name="property_type">
                                        <option value="">Property Type</option>
                                        <option value="bedsitter">Bedsitter</option>
                                        <option value="single_room">Single Room</option>
                                        <option value="1_bedroom">1 Bedroom</option>
                                        <option value="2_bedroom">2 Bedroom</option>
                                        <option value="3_bedroom">3 Bedroom</option>
                                        <option value="4_bedroom">4 Bedroom</option>
                                        <option value="5_bedroom">5 Bedroom</option>
                                        <option value="6_bedroom">6 Bedroom</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" id="min_budget" name="min_budget" type="number" placeholder="Min Budget" required data-validation-required-message="Please enter min budget">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" id="max_budget" name="max_budget" type="number" placeholder="Max Budget" required data-validation-required-message="Please enter max budget">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <button id="" class="btn btn-success btn-md text-uppercase" name="search" value="search" type="submit">Search</button>
                                    <button id="clearSearch" class="btn btn-secondary btn-md text-uppercase" type="button">Clear Search</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($errMsg)) {
                        echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
                    }

                    if (count($data) !== 0) {
                        echo "<h2 class='text-center'>List of Rental Houses Details</h2>";
                        foreach ($data as $key => $value) {
                            echo '<div class="card card-inverse card-info mb-3" style="padding:1%;">          
                                        <div class="card-block">';
                            echo   '<div class="row">
                                                    <div class="col-4">
                                                        <h4 class="text-center">Owner Details</h4>';
                            echo '<p><b>Owner Name: </b>' . $value['fullname'] . '</p>';
                            echo '<p><b>Mobile Number: </b>' . $value['mobile'] . '</p>';
                            echo '<p><b>Alternate Number: </b>' . $value['alternat_mobile'] . '</p>';
                            echo '<p><b>Email: </b>' . $value['email'] . '</p>';
                            echo '<p><b>Country: </b>' . $value['country'] . '</p><p><b> Location: </b>' . $value['location'] . '</p><p><b> City: </b>' . $value['city'] . '</p>';
                            if ($value['image'] !== 'uploads/') {
                                # code...
                                echo '<img src="app/' . $value['image'] . '" width="100">';
                            }
                            echo '</div>
                                                    <div class="col-4">
                                                        <h4 class="text-center">Room Details</h4>';
                            echo '<p><b>Property Type: </b>' . $value['property_type'] . '</p>'; // Added property_type
                            echo '<p><b>Plot Number: </b>' . $value['plot_number'] . '</p>';

                            if (isset($value['sale'])) {
                                echo '<p><b>Sale: </b>' . $value['sale'] . '</p>';
                            }

                            if (isset($value['apartment_name']))
                                echo '<div class="alert alert-success" role="alert"><p><b>Apartment Name: </b>' . $value['apartment_name'] . '</p></div>';

                            if (isset($value['ap_number_of_plats']))
                                echo '<div class="alert alert-success" role="alert"><p><b>Plat Number: </b>' . $value['ap_number_of_plats'] . '</p></div>';

                            echo '<p><b>Rent: </b>' . $value['rent'] . '</p>'; // Added rent
                            echo '<p><b>Deposit: </b>' . $value['deposit'] . '</p>'; // Added deposit
                            echo '<p><b>Available Rooms: </b>' . $value['rooms'] . '</p>';
                            echo '<p><b>Address: </b>' . $value['address'] . '</p><p><b> Landmark: </b>' . $value['landmark'] . '</p>';

                            echo '<form action="payment/payments.php" method="POST">';
                            echo '<input type="hidden" name="property_id" value="' . $value['id'] . '">';
                            echo '<input type="hidden" name="property_type" value="' . $value['property_type'] . '">';
                            echo '<input type="hidden" name="rent" value="' . $value['rent'] . '">';
                            echo '<input type="hidden" name="deposit" value="' . $value['deposit'] . '">';
                            echo '<input type="hidden" name="location" value="' . $value['location'] . '">';

                            echo '<a href="payment/payments.php?id=' . (isset($value['id']) ? $value['id'] : '') . '">book Now</a>';
                            // echo '<button type="submit" class="btn btn-primary">Book Now</button>';
                            echo '</form>';

                            echo '</div>
                                                    <div class="col-4">
                                                        <h4>Other Details</h4>';
                            echo '<p><b>Facilities: </b>' . $value['accommodation'] . '</p>';
                            echo '<p><b>Description: </b>' . $value['description'] . '</p>';
                            if ($value['vacant'] == 0) {
                                echo '<div class="alert alert-danger" role="alert"><p><b>Occupied</b></p></div>';
                            } else {
                                echo '<div class="alert alert-success" role="alert"><p><b>Vacant</b></p></div>';
                            }
                            echo '</div>
                                                </div>              
                                            </div>
                                        </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <br><br><br><br><br><br>
    </section>

    <script>
        // Clear Search Functionality
        document.getElementById("clearSearch").addEventListener("click", function() {
            document.getElementById("location").value = "";
            document.getElementById("property_type").value = "";
            document.getElementById("min_budget").value = "";
            document.getElementById("max_budget").value = "";
        });
    </script>

    <!-- Footer -->
    <footer style="background-color: #ccc;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; designed by Mishael Momanyi 2024</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li class="list-inline-item">
                            <a href="https://x.com/in/MishaelMomanyi?t=4acV0yuJMOh6WpuChKRseQ&S=09">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://linkedin.com/in/mishael-momanyi-483b312a8">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="assets/plugins/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="assets/js/jqBootstrapValidation.js"></script>
    <script src="assets/js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="assets/js/rent.js"></script>
</body>

</html>