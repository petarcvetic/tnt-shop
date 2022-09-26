<?php

//SEO
$page_title = "Kartica Dobavljaca";
$keywords = "dobavljac,kartica,firma,stanje,potraznja uplata";
$description = "Izlistavanje kartica firmi iz baze i pregled naloga za uplatu i isplatu";

$msg = $artikliKomadi = $cena = $ukupno = $broj_otpremnice = $broj_prijemnice = $br_predracuna = $kupac_id = $od_datuma = $do_datuma = "";

$i = 1;
$ukupna_uplata = 0;
$ukupna_potraznja = 0;

$today = date("Y-m-d");

$time = strtotime("2017-01-01");
$startDate = date("Y-m-d", $time);

include "dbconfig.php";
include "assets/header.php";

/*AKO JE USER ULOGOVAN (ako postoji sesija sess_user_id*/
if ($user->is_loggedin() != "") {

/*Podaci USER-a*/
	$userID = $_SESSION['sess_user_id'];
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

	//IZMENA FAKTURE/IZVODA
	if (isset($_POST["izmena-fakture"]) || isset($_POST["izmena-izvoda"])) {

		$id_naloga_izmena = strip_tags($_POST["id-naloga"]);
		$br_naloga_izmena = strip_tags($_POST["br-naloga"]);
		$iznos_naloga_izmena = strip_tags($_POST["iznos-naloga"]);
		$datum_naloga_izmena = strip_tags($_POST["datum-naloga"]);
		if (isset($_POST["valuta-naloga"])) {
			$valuta_naloga_izmena = strip_tags($_POST["valuta-naloga"]);
		} else { $valuta_naloga_izmena = 0;}

		//KLIKNUTO JE IZMENI FAKTURU
		if (isset($_POST["izmena-fakture"])) {
			$insertData->edit_faktura_by_id($br_naloga_izmena, $datum_naloga_izmena, $valuta_naloga_izmena, $iznos_naloga_izmena, $username, $id_naloga_izmena);
		}

		//KLIKNUTO JE IZMENI NALOG
		if (isset($_POST["izmena-izvoda"])) {
			$insertData->edit_izvod_by_id($br_naloga_izmena, $datum_naloga_izmena, $valuta_naloga_izmena, $iznos_naloga_izmena, $username, $id_naloga_izmena);
		}
	}

	if ($statusUser !== "0") {
		/*user koji ima status 0 je blokiran*/

		if ($statusKorisnika == '1') {
			?> <!--AKO KORISNIK NIJE BLOKIRAN PRIKAZUJE SE SLEDECE-->

      <div id="alert"></div> <!--DIV u kome AJAX upisuje jQuery koji popunjava polja-->
      <div id="popup"></div> <!--DIV u kome jQuery ispisuje popup za editovanje fakture-->


      <div class="dobavljac-box fix-position-left non-printable">
        <form class="" method="post" action="">
          <fieldset>
          <legend>&nbsp; <b> DOBAVLJAČ </b> &nbsp; </legend>
          <div class="input-row">
            DOBAVLJAČ: <input type="text" class="awesomplete input-right input-kupac" onblur="autofill(this,'faktura')" name="firma" id="firma" list="kupci" value=""  autofocus required><br>
            <datalist id="kupci">
              <?php

			$kupci = $getData->get_kupci($idKorisnika);
			foreach ($kupci as $row) {
				echo "<option>" . $row['naziv_kupca'] . '</option>';
			}

			?>
            </datalist>
          </div>
          <div class="input-row">
            OD DATUMA<input type="date" name="od_datuma" class="input-right"><br>
          </div>
          <div class="input-row">
            DO DATUMA<input type="date" name="do_datuma" class="input-right"><br>
          </div class="input-row">
            <input type="submit" name="pretrazi" class="submit" value="PRETRAŽI">
          </fieldset>
        </form>
      </div>

      <?php
//KLIKNUTO JE "PRETRAZI"
			if (isset($_POST["pretrazi"]) || isset($_GET["page"])) {
				include "assets/kartica-dobavljaca.inc.php";
			} else {
				include "assets/svi-dobavljaci.inc.php";
			}
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

/*AKO NIJE ULOGOVAN redirektuje se na index stranicu*/
else {
	header("Location: index.php");
}

include "assets/footer.php";
?>

<script>
    $("#n2").css({"color": "#415a2d", "padding":"5px", "border": "solid 1px", "border-radius":"4px", "font-weight":"bold"});

    function printArhiva(){
      $("#kartica-klijenta").css("width", "180mm");
      window.print();
    }
</script>
