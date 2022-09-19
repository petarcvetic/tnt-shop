<?php
session_start();
$_SESSION['sess_user_id'] = "";
$_SESSION['sess_username'] = "";
$_SESSION['sess_name'] = "";

$_SESSION['sess_id_korisnika'] = "";
$_SESSION['sess_user_status'] = "";

$_SESSION['sess_korisnik_name'] = "";
$_SESSION['sess_korisnik_status'] = "";

if(empty($_SESSION['sess_user_id'])){
	session_destroy();
	header("location: index.php");
}
?> 