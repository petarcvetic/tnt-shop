<?php
$ukupno = 0;
$artikliKomadi = "";
$today = date("Y-m-d");

include "dbconfig.php";
include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {

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

			/*KLIKNUTO JE EDIT*/
			if (isset($_POST['edit-trosak'])) {
				$id_troska_e = strip_tags($_POST['id-troska']);
				$datum_troska_e = strip_tags($_POST['datum-troska']);
				$namena_troska_e = strip_tags($_POST['namena-troska']);
				$iznos_troska_e = strip_tags($_POST['iznos-troska']);

				if ($id_troska_e != "" && $datum_troska_e != "" && $namena_troska_e != "" && $iznos_troska_e != "") {
					$insertData->update_troska($datum_troska_e, $namena_troska_e, $iznos_troska_e, $id_troska_e, $id_korisnika);
					header('Location:troskovi.php');
				}
			}

			/*END EDIT*/

			if (isset($_GET['id_troska'])) {
				$id_troska = strip_tags($_GET['id_troska']);
				$trosak = $getData->get_trosak_by_id($id_korisnika, $id_troska);
				$datum_troska = $trosak['datum_troska'];
				$namena_troska = $trosak['namena_troska'];
				$iznos_troska = $trosak['iznos_troska'];
			}

			?>
		<div id="alert"></div>
		<div class="unos">

			<div class="unos-form-container">
				<div><h1 class="center-text">EDIT Troska</h1></div>
				<form action="" method="post" class="forme-unosa">

					<div class="form-inputs">
						<div class="center-row">
							<input type="hidden" name="id-troska" value="<?php echo $id_troska; ?>">
							<input type="date" class="center-text input-field" name="datum-troska" value="<?php echo $datum_troska; ?>" required>
							<input type="text" class="center-text input-field" name="namena-troska" value="<?php echo $namena_troska; ?>" placeholder="Namena" required>
							<input type="text" class="center-text input-field" name="iznos-troska" value="<?php echo $iznos_troska; ?>" placeholder="Iznos" required>
						</div>
					</div>

					<div class="unos-button">
		        <input type="submit" class="submit button-full" name="edit-trosak" id="edit-trosak" value="EDIT"><br><br>
		      </div>

				</form>

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