<?php
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

		if ($statusKorisnika == '1') {?>

    	<div class="unos">

			<div class="unos-form-container">

				<div id="form1" class="show">

					<h1 class="center-text white-text">NARUDŽBENICA</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="narudzbenica-general">
							<div class="left-3row">
								<div>
									<input type="text" class="center-text input-small" size="5" name="" placeholder="ID" required>
									<input type="date" class="center-text input-small" size="9" name="" placeholder="" required>
								</div>
								
								<input type="text" class="center-text input-field" name="" placeholder="Mest" required>
								<input type="text" class="center-text input-field" name="" placeholder="Saradnik" required>
							</div>

							<div class="center-3row">
								<input type="text" class="center-text input-field" name="" placeholder="Ime i Prezime" required>
								<input type="text" class="center-text input-field" name="" placeholder="Adresa" required>
								<input type="text" class="center-text input-field" name="" placeholder="Prevoznik" required>
							</div>

							<div class="right-3row">
								<?php
								$magacini = $getData->get_magacini_by_korisnik($id_korisnika);
								?>
								<select class="center-text input-field" >
									<option value="" disabled selected>Izaberi magacin</option>
								<?php
								foreach ($magacini as $magacin) {
									echo "
									<option value=".$magacin['id_magacina'].">".$magacin['naziv_magacina']."</option>
									";	
								}
								?>									
								</select>
								<input type="text" class="center-text input-field" name="" placeholder="Telefon" required>
								<input type="text" class="center-text input-field" name="" placeholder="Broj Pošiljke" required>
							</div>

						</div>

						<div class="porudzbenica-artikli">
							<input type="text" class="center-text input-small" name="" size="35" placeholder="Proizvod" required>
							<input type="text" class="center-text input-small" name="" size="5" placeholder="kolicina" required>
							<input type="text" class="center-text input-small" name="" size="5" placeholder="cena" required>
							<input type="text" class="center-text input-small" name="" size="5" placeholder="stanje" required>
						</div>

						<div class="unos-button">
					        <input type="submit" class="submit button-full" name="submit-proizvod" value="Submit"><br><br>
						</div>

					</form>

				</div>

			</div>

		</div> <!--END Unos-->

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