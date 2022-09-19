<?php 



//SEO 

$page_title = "Kartica Firme";

$keywords = "mont,kartica,firme,dobavljac,faktura";

$description = "Knjigovodstvena aplikacija za izlistavanje kartice firme i unos faktura";



$today = date("Y-m-d");



$msg = $artikliKomadi = $cena = $ukupno = $broj_otpremnice = $broj_prijemnice = $br_predracuna = $firma = ""; 



include("dbconfig.php");  





/*KLIKNUTO JE DUGME LOGIN NA LOGIN FORMI*/

if(isset($_POST['submitBtnLogin'])) {



  $username = strip_tags($_POST['username']);

  $password = sha1(strip_tags($_POST['password']));



  if($username != "" && $password != ""){

    $user->login($username,$password);  

  }

  else

  {

    $msg = "Oba polja moraju biti popunjena!";

  } 



}



include("assets/header.php");







//-------KLIKNUTO JE "UNESI FAKTURU" ili "UNESI IZVOD"--------------------------------

if(isset($_POST["unos-fakture"]) or isset($_POST["unos-izvoda"])){

  $firma = strip_tags($_POST["firma"]);

  $adresa = strip_tags($_POST["adresa"]);

  $mesto = strip_tags($_POST["mesto"]);

  $pib = strip_tags($_POST["pib"]);

  $matBr = strip_tags($_POST["matBr"]);

  if($matBr == ""){$matBr = ".";}

  $email = strip_tags($_POST["email"]);

  $ziro_racun = strip_tags($_POST["ziro-racun"]);

  $status = strip_tags($_POST["status"]);





  //Upis kupca u bazu ukoliko ga nema u bazi  

  $find = $getData->if_kupac_exists($idKorisnika,$firma);



  if($find==0){

    $insertData->insert_new_kupac($idKorisnika,$firma,$adresa,$mesto,$pib,$matBr,$ziro_racun,$email);

  }

  

  $kupacRow = $getData->get_kupac($idKorisnika,$firma);

  

  $kupac_id = $kupacRow["id_kupca"];

 

  //KLIKNUTO JE "UNESI FAKTURU"

  if(isset($_POST["unos-fakture"])){

    $broj_fakture = strip_tags($_POST["br-fakture"]);

    $iznos_fakture = strip_tags($_POST["iznos-fakture"]);

    $datum_fakture = strip_tags($_POST["datum-fakture"]);

    if(isset($_POST["valuta-fakture"])){
      $valuta_fakture = strip_tags($_POST["valuta-fakture"]);
    }
    else{
      $valuta_fakture = 0;
    }
    

    if(!is_int($valuta_fakture)){
      $valuta_fakture = 0;
    }



    $faktura_exists = $getData->if_faktura_exists($idKorisnika,$broj_fakture,$kupac_id);

    if($faktura_exists == 0){

      $insertData->insert_new_faktura($broj_fakture,$idKorisnika,$kupac_id,$datum_fakture,$valuta_fakture,$iznos_fakture,$username);

    }

    else{

      echo "<script>alert('Ova faktura je već uneta u bazu podataka');</script>";

    }



  }

  



  //KLIKNUTO JE "UNESI IZVOD"

  if(isset($_POST["unos-izvoda"])){

    $broj_izvoda = strip_tags($_POST["br-izvoda"]);

    $datum_uplate = strip_tags($_POST["datum-uplate"]);

    $iznos_uplate = (float)strip_tags($_POST["iznos-uplate"]);



    $izvod_exists = $getData->if_izvod_exists($idKorisnika,$broj_izvoda,$kupac_id);

    if($izvod_exists == 0){

    //  echo "<script>alert('".$broj_izvoda."/".$idKorisnika."/".$kupac_id."/".$username."/".$iznos_uplate."/".$datum_uplate."');</script>";

      $insertData->insert_new_izvod($broj_izvoda,$idKorisnika,$kupac_id,$username,$iznos_uplate,$datum_uplate);

    }

    else{

      echo "<script>alert('Ovj izvod je već unet u bazu podataka');</script>";

    }

  }

  



//  header("Refresh:0");



} // END "PRINT"











/*AKO JE USER ULOGOVAN (ako postoji sesija sess_user_id*/

if($user->is_loggedin()!=""){



/*Podaci USER-a*/

  $useID = $_SESSION['sess_user_id'];

  $username = $_SESSION['sess_user_name'];

  $idKorisnika = $_SESSION['sess_id_korisnika'];

  $statusUser = $_SESSION['sess_user_status'];



/*Podaci KORISNIKA*/

  $korisnik = $_SESSION['sess_korisnik_name'];



  $korisnikRow = $getData->get_korisnik($idKorisnika);



  $adresaKorisnika = $korisnikRow["adresa"];

  $mestoKorisnika = $korisnikRow["mesto"];

  $maticniBrojKorisnika = $korisnikRow["maticni_broj"];

  $pibKorisnika = $korisnikRow["pib"];

  $sifraDelatnostiKorisnika = $korisnikRow["sifra_delatnosti"];

  $telefon = $korisnikRow["telefon"];

  $fax = $korisnikRow["fax"];

  $tekuciRacun = $korisnikRow["tekuci_racun"];

  $banka = $korisnikRow["banka"];

  $logoKorisnika = $korisnikRow["logo"];

  $dodatakBroju = $korisnikRow["dodatak_broju"];

  $statusKorisnika = $korisnikRow['status'];





  if($statusUser !== "0"){ /*user koji ima status 0 je blokiran*/

    



    if($statusKorisnika =='1'){

      include("assets/unos.php");

    }

    elseif ($statusKorisnika == '0') {

      echo "<h1 class='centerText'>ZBOG NEIZMIRENIH OBAVEZA STE PRIVREMENO ISKLJUCENI!</h1><br><br><br><br><br><br><br><br>";

    }

    else{ // ako status korisnika nije '1' ili '2' vec je '0'

      echo "<h1 class='centerText'>TRENOTNO SE RADI NA POBOLJSANJU APLIKACIJE!</h1><br><br><br><br><br><br><br><br>";

    }



  }

  else{ // ako je status usera "0" (blokirani user)

    echo "<div class='centerText'>

            <h1>IZ NEKOG RAZLOGA STE BLOKIRANI!</h1>

            <h2>Proverite svoju email poštu</h2>

          </div>";

  }



} 



/*AKO NIJE ULOGOVAN PRIKAZUJE SE LOGIN FORMA*/

else { 

  include("assets/login_form.php"); 

}



include("assets/footer.php"); 

 ?>



<script>

    $("#n1").css({"color": "#415a2d", "padding":"5px", "border": "solid 1px", "border-radius":"4px", "font-weight":"bold"});

</script>







