<?php
date_default_timezone_set('Europe/Belgrade');
session_start();

/*
//KUCA
$dbHost = "localhost";
$dbName = "tnt_shop";
$dbUser = "root";
$dbPassword = "";
$root_path = "localhost/mmont-fakture";
*/

//POSAO
$dbHost = "localhost";
$dbName = "tnt_shop";
$dbUser = "root";
$dbPassword = "pepa9917";
$root_path ="localhost/mmont-fakture";


//PHPMailer info
//Sender info
define('EMAIL', 'info@petarcvetic.com');
define('PASS', 'Pep@9917');
//SMTP info
define('HOST', 'mailcluster.loopia.se');
define('AUTH', 'true');
define('SSL', 'tls');
define('PORT', '587');

define("ROOT_PATH", $root_path);

try {
	$DB_con = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8mb4', $dbUser, $dbPassword);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$DB_con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo "Connection failed : " . $e->getMessage();
}

include 'classes/class.user.php';
include 'classes/class.insertdata.php';
include 'classes/class.getdata.php';

$user = new USER($DB_con);
$getData = new GetData($DB_con);
$insertData = new InsertData($DB_con);

//MySQLi konekcija
//$conn = mysqli_connect("localhost","root","pepa9917","moje_poslovanje");
$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
mysqli_query($conn, 'SET NAMES utf8');
if (!$conn) {
	echo "Konekcija nije uspela!";
}

//require_once($path.'connect.inc.php');
?>