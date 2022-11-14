<?php
$ukupno = 0;
$artikliKomadi = "";
$today = date("Y-m-d");

include "dbconfig.php";
include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0" && $_SESSION['sess_user_status'] > 2) {

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

		/*KLIKNUTO JE SUBMIT*/
		if (isset($_POST['submit-proizvod'])) {
			$id_proizvoda = strip_tags($_POST['id-proizvoda']);
			$naziv_proizvoda = strip_tags($_POST['naziv-proizvoda']);
			$cena_proizvoda = strip_tags($_POST['cena-proizvoda']);
			$tezina_proizvoda = strip_tags($_POST['tezina-proizvoda']);
			$cena_saradnika = strip_tags($_POST['cena-saradnika']);
			$id_magacina = strip_tags($_POST['id-magacina']);
			$kolicina_u_magacinu = strip_tags($_POST['kolicina-u-magacinu']);
			$sifra_proizvoda = strip_tags($_POST['sifre-proizvoda']);
			$broj_paketa = strip_tags($_POST['broj-paketa']);

			if ($naziv_proizvoda != "" && $cena_proizvoda != "" && $tezina_proizvoda != "" && $cena_saradnika != "" && $id_magacina != "" && $kolicina_u_magacinu != "") {
				$insertData->update_proizvoda($naziv_proizvoda, $cena_proizvoda, $tezina_proizvoda, $cena_saradnika, $id_magacina, $kolicina_u_magacinu, $sifra_proizvoda, $broj_paketa, $id_proizvoda, $id_korisnika);
				header('Location: edit-usera.php');
			}
		}

		if ($statusKorisnika == '1') {
			if (isset($_GET['id-usera'])) {
				$id_usera = strip_tags($_GET['id-usera']);
			} else {
				$id_usera = -1;
			}?>


				<div class="unos">
					<div class="unos-form-container">

					<?php
if ($id_usera <= 0) {
				$useri = $getData->get_all_users($id_korisnika);
				?>
						<input type="text" class="center-text input-small proizvod-input" list="datalist_edit" size="34" onChange="redirect_to(this,'edit-usera')" placeholder="Odaberi user-a">

						<datalist id="datalist_edit">

						<?php
foreach ($useri as $user) {
					echo "<option id='" . $user['id_admin'] . "' value='" . $user['user_ime'] . " " . $user['user_prezime'] . "'>";
				}
				echo "</datalist>";
			} elseif ($getData->if_user_id_exists_for_korisnik($id_korisnika, $id_proizvoda) > 0) {
				$proizvod = $getData->get_user_by_id_and_korisnik($id_korisnika, $id_proizvoda);
				?>
						<!--Forma PROIZVODI-->
						<div id="form1" class="show">

							<h1 class="center-text white-text">EDIT User-a</h1>

							<form action="" method="post" class="forme-unosa">

								<div class="form-inputs">
									<div class="left-row">
										<input type="text" class="center-text input-field" name="user-ime" placeholder="Ime" required>
										<input type="text" class="center-text input-field" name="user-prezime" placeholder="Prezime" required>
										<input type="text" class="center-text input-field" name="user-username" placeholder="Username" required>
										<input type="password" class="center-text input-field" name="user-password" id="user-password" placeholder="Password" required>
										<label><input type="checkbox" onclick="showPassword()">Show Password</label>
									</div>

									<div class="right-row">
										<input type="email" class="center-text input-field" name="user-email" placeholder="email" required>
										<select class="center-text input-field" name="user-rola" placeholder="Privilegija" required>
											<option value="" disabled selected>Privilegija</option>
											<option value="1">Saradnik</option>
											<option value="2">Zaposleni</option>
											<option value="3">Admin</option>
										</select>
									</div>
								</div>

								<div class="unos-button">
					        <input type="submit" class="submit button-full" name="submit-user" value="Submit"><br><br>
					      </div>

							</form>

						</div>
					<?php } else {
				echo "<script>alert('User sa zadatim ID-jem ne postoji u bazi')</script>";
			}?>
					</div><!--END unos-form-container-->
				</div><!--END unos -->


	<?php	} elseif ($statusKorisnika == '0') {
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