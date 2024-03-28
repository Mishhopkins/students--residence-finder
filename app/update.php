<?php
require '../config/config.php';

if (empty($_SESSION['username'])) {
    header('Location: login.php');
}

if (isset($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (isset($_GET['act'])) {
    $active = $_REQUEST['act'];

    try {
        if ($active === 'ap') {
            $stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment WHERE id = :id');
        } else {
            $stmt = $connect->prepare('SELECT * FROM room_rental_registrations WHERE id = :id');
        }

        $stmt->execute(array(':id' => $id));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errMsg = $e->getMessage();
    }
}

if (isset($_POST['register_individuals'])) {
    $errMsg = '';

    // Get data from FORM
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $alternat_mobile = isset($_POST['alternat_mobile']) ? $_POST['alternat_mobile'] : '';
    $plot_number = $_POST['plot_number'];
    $rooms = $_POST['rooms'];
    $country = $_POST['country'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $landmark = $_POST['landmark'];
    $rent = $_POST['rent'];
    $sale = $_POST['sale'];
    $deposit = $_POST['deposit'];
    $description = $_POST['description'];
    $accommodation = $_POST['accommodation'];
    $vacant = $_POST['vacant'];
    $user_id = $_SESSION['id'];
    $property_type = $_POST['property_type'];

    try {
			$stmt = $connect->prepare('UPDATE room_rental_registrations 
		SET fullname = ?, email = ?, mobile = ?, alternat_mobile = ?, 
		plot_number = ?, rooms = ?, country = ?, location = ?, city = ?, 
		address = ?, landmark = ?, rent = ?, sale = ?, deposit = ?, 
		description = ?, accommodation = ?, vacant = ?, user_id = ?, 
		property_type = ? WHERE id = ?');


        $stmt->execute(array(
            $fullname,
            $email,
            $mobile,
            $alternat_mobile,
            $plot_number,
            $rooms,
            $country,
            $location,
            $city,
            $address,
            $landmark,
            $rent,
            $sale,
            $deposit,
            $description,
            $accommodation,
            $vacant,
            $user_id,
            $property_type,
            $id
        ));

        header('Location: update.php?action=reg');
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST['register_apartment'])) {
    $errMsg = '';

    // Get data from FORM
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $alternat_mobile = isset($_POST['alternat_mobile']) ? $_POST['alternat_mobile'] : '';
    $plot_number = $_POST['plot_number'];
    $apartment_name = $_POST['apartment_name'];
    $ap_number_of_plats = $_POST['ap_number_of_plats'];
    $rooms = $_POST['rooms'];
    $country = $_POST['country'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $landmark = $_POST['landmark'];
    $rent = $_POST['rent'];
    $deposit = $_POST['deposit'];
    $description = $_POST['description'];
    $accommodation = $_POST['accommodation'];
    $property_type = $_POST['property_type'];
    $user_id = $_SESSION['id'];
    $floor = $_POST['floor'];
    $ownership = $_POST['own'];
    $area = $_POST['area'];
    $purpose = $_POST['purpose'];
    $vacant = $_POST['vacant'];

    try {
		$stmt = $connect->prepare('UPDATE room_rental_registrations_apartment 
		SET fullname = ?, email = ?, mobile = ?, alternat_mobile = ?, 
		plot_number = ?, apartment_name = ?, ap_number_of_plats = ?, 
		rooms = ?, country = ?, location = ?, city = ?, address = ?, 
		landmark = ?, rent = ?, deposit = ?, description = ?, 
		accommodation = ?, property_type = ?, vacant = ?, user_id = ?, floor = ?, 
		own = ?, area = ?, purpose = ? WHERE id = ?');
	


        $stmt->execute(array(
            $fullname,
            $email,
            $mobile,
            $alternat_mobile,
            $plot_number,
            $apartment_name,
            $ap_number_of_plats,
            $rooms,
            $country,
            $location,
            $city,
            $address,
            $landmark,
            $rent,
            $deposit,
            $description,
            $accommodation,
            $property_type,
            $vacant,
            $user_id,
            $floor,
            $ownership,
            $area,
            $purpose,
            $id
        ));

        header('Location: update.php?action=reg');
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'reg') {
    $errMsg = 'Update successfull. Thank you';
}
?>

<?php include '../include/header.php'; ?>

<!-- Header nav -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="../index.php">Logo/Home</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if ($_SESSION['role'] == 'admin') { echo "(Admin)"; } ?></a>
                </li>
                <li class="nav-item">
                    <a href="../auth/logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- end header nav -->

<?php include '../include/side-nav.php'; ?>

<section class="wrapper" style="margin-left: 16%;margin-top: -11%;">
    <?php
    if (isset($active)) {
        if ($active === 'ap') {
            include 'partials/edit/apartment.php';
        }

        if ($active === 'indi') {
            include 'partials/edit/individaul.php';
        }
    }
    ?>
</section>

<?php include '../include/footer.php'; ?>

<script type="text/javascript">
    var rowCount = 1;

    function addMoreRows(frm) {
        rowCount++;
        var recRow = '<div id="rowCount' + rowCount + '"><tr><td><input name="ap_number_of_plats[]" type="text" size="16%" placeholder="  Plat Number" maxlength="120"/></td><td><input name="rooms[]" type="text"  maxlength="120" placeholder="  2BHK/3BHK/1RK" style="margin: 4px 5px 0 5px;"/></td><td><input name="" type="hidden" maxlength="120" style="margin: 4px 10px 0 0px;"/></td></tr><a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');" class="btn btn-danger btn-sm">Delete</a></div>';
        $('#addedRows').append(recRow);
    }

    function removeRow(removeNum) {
        console.log("hhh");
        console.log(removeNum);
        $('#rowCount' + removeNum).remove();
    }
</script>