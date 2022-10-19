<!DOCTYPE html>
<html>
<head>
	<title>TNT Shop</title>
	<link rel='shortcut icon' href='images/favicon-32.jpg' type='image/x-icon'/ >
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="description" content="">
	<meta name="KEYWORDS" content="">
	<meta name="distribution" content="global">

	<!-- Clearing cache -->
	<meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>

	<!--FONT-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!--CSS-->
	<link href="css/awesomplete.css" type="text/css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet" type="text/css">

	<!--JavaScript-->
	<script type="text/javascript" src="js/functionsJS.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="js/awesomplete.js"></script>

</head>

<body>


	<div class="header">



		<?php
if (isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
	$username = $_SESSION['sess_user_name'];
	$idKorisnika = $_SESSION['sess_id_korisnika'];
	$statusUser = $_SESSION['sess_user_status'];
	$statusKorisnika = $_SESSION['sess_korisnik_status'];
	?>

		<?php
if ($_SESSION['sess_korisnik_status'] > 0) {?>
			<div class="logo">
				<a href="index.php"><img src="images/logo-medium-transparent.webp" height="150" alt="pc-code_logo"></a>
			</div>
		<?php }?>

			<div class="login-header">
				<a href="logout.php"><button class="submit yellow-button" name="logout"> Izloguj se </button></a>
				<a href="index.php"><button class="submit">HOME</button></a>
			</div>

			<?php
if ($statusUser !== "4" && $_SESSION['sess_korisnik_status'] > 0) {
		?>
			<!-- MENU Desktop -->
			<!--
			<div class="menu">
				<ul class="menulist">
					<li><a id="n1" href="index.php">UNOS</a></li>
					<li><a id="n2" href="kartica-dobavljaca.php">KARTICA DOBAVLJAČA</a></li>

				<?php
if ($_SESSION['sess_user_status'] !== "0") {
			echo '<li><a id="n3" href="settings.php">UNOS DOBAVLJAČA</a></li>';
		}
		?>
				</ul>
			</div>
			-->
			<!-- END Menu Desktop -->

		<?php
}
}

?>

	</div> <!-- END header -->

	<div class="container">

