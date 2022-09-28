<?php
$ukupno = 0;
$artikliKomadi = "";
$today = date("Y-m-d");

include "dbconfig.php";
include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {
	if (isset($_GET['id_magacina'])) {
		$id_magacina = strip_tags($_GET['id_magacina']);
	} else {
		header("Location: index.php");
	}

/*Podaci USER-a*/
	$useID = $_SESSION['sess_user_id'];
	$username = $_SESSION['sess_user_name'];
	$id_korisnika = $_SESSION['sess_id_korisnika'];
	$statusUser = $_SESSION['sess_user_status'];

/*Podaci KORISNIKA*/
	$korisnik = $_SESSION['sess_korisnik_name'];

	if ($korisnik != "") {
		$korisnikRow = $getData->get_korisnik($id_korisnika);

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

		if ($statusKorisnika == '1') {
			$naziv_magacina = $getData->get_magacin_by_id($id_magacina)["naziv_magacina"];

			$porudzbine = $getData->get_porudzbine_by_magacin($id_korisnika, $id_magacina);

			?>
		<div id="alert"></div>

		<div><h1 class="center-text">Magacin <?php echo $naziv_magacina; ?></h1></div>

		<div class="unos">

			<div class="unos-form-container">

				<table class="unos-table" id="magacin-tabela">
					<tr>
						<th>ID</th>
						<th>DATUM</th>
						<th>IME I PREZIME</th>
						<th>SARADNIK</th>
						<th>BROJ POSILJKE</th>
						<th>IZNOS</th>
						<th>Napomena</th>
					</tr>
			<?php

			foreach ($porudzbine as $porudzbina) {
				$id_saradnika = $porudzbina["id_saradnika"];
				$saradnik = $getData->get_saradnik_by_id($id_korisnika, $id_saradnika);
				echo "
					<tr>
						<td><button class='broj center-text input-small plus'>" . $porudzbina['id_narudzbine'] . "</button></td>
						<td>" . $porudzbina['datum'] . "</td>
						<td>" . $porudzbina['ime_i_prezime'] . "</td>
						<td>" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . "</td>
						<td>" . $porudzbina['broj_posiljke'] . "</td>
						<td>" . $porudzbina['ukupno'] . "</td>
						<td>" . $porudzbina['napomena'] . "</td>
					</tr>
					";
			}
			?>
				</table>

			</div><!--END unos-form-container-->
		</div><!--END unos-->


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
} else {
	header("Location: index.php");
}

include "assets/footer.php";
?>

<script type="text/javascript">
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

</script>