<?php

//SEO 

$page_title = "Kartica Firme";

$keywords = "mont,kartica,firme,dobavljac,faktura";

$description = "Knjigovodstvena aplikacija za izlistavanje kartice firme i unos faktura";


$page = "register";
$msg = ""; 


include("dbconfig.php");  
include("assets/header.php");

if($user->is_loggedin()!=""){
	if($statusUser < 3){
		header("location: index.php");
	}
}


//kliknuto je na verifikacioni link iz mejla
if(isset($_GET["pw"]) && isset($_GET["email"])){
  $verify_password = strip_tags($_GET["pw"]);
  $email = strip_tags($_GET["email"]);

  $user_count = $getData->if_admin_mail_exists($email);

  if($user_count == 1){
    $admin = $getData->get_admin_by_email_and_password($email,$verify_password); 

    $id_admin = $admin["id_admin"];
    $user_name = $admin["username"];

    $insertData->verify_user($id_admin); 
    echo "<script>alert('Poštoveni ".$user_name.", spešno ste završili registraciju');</script>";
    echo '<script>window.location.href = "index.php";</script>';
  }
  elseif($user_count > 1) {
    echo "<script>alert('Email ".$email." je zauzet, ne možete se registrovati sa ovom email adresom');</script>";
    echo '<script>window.location.href = "index.php";</script>';
  }
  else{
    echo "<script>alert('DOŠLO JE DO GREŠKE! Korisnik sa email adresom ".$email." nije pronađen u bazi.');</script>";
    echo '<script>window.location.href = "index.php";</script>';
  }

}


if(isset($_POST['submitBtnRegister'])) {

  $username = strip_tags($_POST['username']);
  $password = sha1(strip_tags($_POST['password']));
  $email = strip_tags($_POST['email']);
  if($user->is_loggedin()!="" and $_SESSION['sess_user_status'] == 3){
  	$id_korisnika = strip_tags($_SESSION['sess_id_korisnika']);
  }
  else{
  	$id_korisnika = strip_tags($_POST['korisnik']);
  }
  $status = 0;

  

  if($username != "" && $password != "" && $email !="" && $id_korisnika !=""){
  
    $user->register($username,$password,$email,$id_korisnika,$status);

    if(isset($_SESSION['error_msg'])){ 
      $msg = $_SESSION['error_msg'];
    } 
  }
  else
  {
    $msg = "Sva polja moraju biti popunjena!";
  } 

}


if($user->is_loggedin()!=""){
?>

  <div class="center-v-h">
        
      <form class="login-form" id="login" method="POST">
        <fieldset>
        <legend>&nbsp; <b> REGISTRUJ SE </b> &nbsp; </legend>
          <br><br>
          Username:&nbsp <input type="text" name="username" id="username" autofocus><br><br>
          Password:&nbsp <input type="password" name="password" id="password"><br><br>
          Email:&nbsp <input type="email" name="email" id="email"><br><br>

          <?php 
    			if($statusUser = 4){
    				echo "Firma:&nbsp <input type='text' name='korisnik' id='korisnik' value=''><br><br>";
    			}
          ?>

          <input type="submit" class="submit" name="submitBtnRegister" value="Register"><br><br>
          <span class="loginMsg"><?php echo @$msg;?></span>
          <br>
        
        </fieldset>
      </form> 
      
  </div>

<?php 
include("assets/footer.php");
}

else{
  echo '<script>window.location.href = "index.php";</script>';
}
?>