<?php

//SEO
$page_title = "Kartica Firme";
$keywords = "mont,kartica,firme,dobavljac,faktura";
$description = "Knjigovodstvena aplikacija za izlistavanje kartice firme i unos faktura";

$today = date("Y-m-d");

$msg = $artikliKomadi = $cena = $ukupno = $broj_otpremnice = $broj_prijemnice = $br_predracuna = $firma = "";

include "dbconfig.php";

/*KLIKNUTO JE DUGME LOGIN NA LOGIN FORMI*/
if (isset($_POST['submitBtnLogin'])) {

	$username = strip_tags($_POST['username']);
	$password = sha1(strip_tags($_POST['password']));

	if ($username != "" && $password != "") {
		$user->login($username, $password);
	} else {
		$msg = "Oba polja moraju biti popunjena!";
	}
}

include "assets/header.php";

/*AKO JE USER ULOGOVAN (ako postoji sesija sess_user_id*/
if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {

/*Podaci USER-a*/
	$useID = $_SESSION['sess_user_id'];
	$username = $_SESSION['sess_user_name'];
	$idKorisnika = $_SESSION['sess_id_korisnika'];
	$statusUser = $_SESSION['sess_user_status'];

/*Podaci KORISNIKA*/
	$korisnik = $_SESSION['sess_korisnik_name'];

	if ($korisnik != "") {
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
	} else {
		$statusKorisnika = 0;
	}

	if ($statusUser !== "0") {
		/*user koji ima status 0 je blokiran*/

		if ($statusKorisnika == '1') {?>

      <div class="main-menu">
        <div class="main-menu-block">
          <a href=""><button class="submit">Biznis soft</button></a>
          <a href=""><button class="submit">Blagajna</button></a>
        </div>

        <div class="main-menu-block">
          <a href="unosi.php"><button class="submit">Unosi</button></a>
          <a href="izbor-magacina.php"><button class="submit">Porudžbine</button></a>
        </div>
      </div>

    <?php
} elseif ($statusKorisnika == '0') {
			echo "<h1 class='centerText'>ZBOG NEIZMIRENIH OBAVEZA STE PRIVREMENO ISKLJUCENI!</h1><br><br><br><br><br><br><br><br>";
		} else {
			// ako status korisnika nije '1' ili '2' vec je '0'
			echo "<h1 class='centerText'>TRENOTNO SE RADI NA POBOLJSANJU APLIKACIJE!</h1><br><br><br><br><br><br><br><br>";
		}

	} else {
		// ako je status usera "0" (blokirani user)
		echo "<div class='centerText'>
            <h1>IZ NEKOG RAZLOGA STE BLOKIRANI!</h1>
            <h2>Proverite svoju email poštu</h2>
          </div>";
	}
}

/*AKO NIJE ULOGOVAN PRIKAZUJE SE LOGIN FORMA*/
else {
	include "assets/login_form.php";
}

include "assets/footer.php";
?>

<script>
    $("#n1").css({"color": "#415a2d", "padding":"5px", "border": "solid 1px", "border-radius":"4px", "font-weight":"bold"});

    function mediaSize(){
    if (window.matchMedia('(max-device-width: 768px)').matches){
      $("body").css("background-image", "url('images/background_mobile.webp')");
      $(".header").css("border-bottom", "none");
    }
    else{
      $("body").css("background-image", "url('images/background.webp')");
    }
  }

  mediaSize();
  window.addEventListener('resize', mediaSize, false);
  $("#headerTop").css("background-color", "rgba(10,10,10,0)");
  $("#pc-code-logo").css("display", "none");
</script>