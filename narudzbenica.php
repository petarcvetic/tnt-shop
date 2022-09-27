<?php
$ukupno = 0;
include "dbconfig.php";

include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {
	if (isset($_GET['id_magacina'])) {
		$id_magacina = strip_tags($_GET['id_magacina']);
	} else {
		header("Location: izbor-magacina.php");
	}

	$naziv_magacina = $getData->get_magacin_by_id($id_magacina)["naziv_magacina"];


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

/* KLIKNUTO JE "SUBMIT" */
			if(isset($_POST['submit-narudzbenica'])){
				$datum = strip_tags($_POST['datum']);
				$mesto = strip_tags($_POST['mesto']);
				$id_saradnika = strip_tags($_POST['id_saradnika']);
				$ime_i_prezime = strip_tags($_POST['ime_i_prezime']);
				$adresa = strip_tags($_POST['adresa']);
				$prevoznik = strip_tags($_POST['prevoznik']);
				$id_magacina = strip_tags($_POST['id_magacina']);
				$telefon = strip_tags($_POST['telefon']);
				$broj_posiljke = strip_tags($_POST['broj_posiljke']);
				$broj_artikala = strip_tags($_POST['broj_artikala']);

				for($i=1; $i<=$broj_artikala; $i++){
					if(isset($_POST["proizvod".$i]) && $_POST["proizvod".$i]!=""){
						$id_proizvoda = strip_tags($_POST['proizvod'.$i]);
			      $kolicina = strip_tags($_POST["kolicina".$i]);
			      $cena = strip_tags($_POST["cena_proizvoda".$i]);
echo $id_proizvoda . "/" . $id_magacina . "/" . $id_saradnika . "/" . $datum;
			      if($getData->if_proizvod_id_exists($id_korisnika, $id_proizvoda, $id_magacina) != 0){
			      	$proizvod = get_proizvod_by_id($id_korisnika, $id_proizvoda, $id_magacina);

			      	if($id_proizvoda!="" && $kolicina!=""){
			          $artikalKomada = $id_proizvoda."/".$kolicina."/".$cena;  //par "artikal"/"komada"/"cena"
			          $artikliKomadi .=  $artikalKomada.",";  //------String koji sadrzi parove "artikal"/"komada"---------------//
			        }
			        else{
			          $artikalKomada = "";
			        }
			      }
			      else{
			      	echo "<script>alert('Poroizvod ".$id_proizvoda." ne poztoji u izabranom magacinu');</script>";
			      }

			      $zbirno = (float)$kolicina * (float)$cena;
      			$ukupno = (float)$ukupno+(float)$zbirno;  //------Ukupna cena------//
					}
				}
			}

/* END "SUBMIT" */
			if($getData->get_poslednja_posiljka($id_korisnika)){
				$id_narudzbine = $getData->get_poslednja_posiljka($id_korisnika)["id_narudzbine"] + 1;
			}
			else{
				$id_narudzbine = 1;
			} 
			
			?>
			<div id="alert"></div>

    	<div class="unos">

			<div class="unos-form-container">

				<div id="form1" class="show">

					<h1 class="center-text white-text">NARUDŽBENICA</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="narudzbenica-general">
							<div class="left-3row">
								<div>
									<input type="text" class="center-text input-small" size="5" name="id" value="<?php echo $id_narudzbine; ?>" >
									<input type="date" class="center-text input-small" size="9" name="datum" required>
								</div>

								<input type="text" class="awesomplete center-text input-field" name="mesto" list="gradovi" placeholder="Mesto" required>
									<datalist id="gradovi">
										<?php

			$mesta = $getData->get_gradovi();
			foreach ($mesta as $mesto) {
				echo "<option value='" . $mesto['ime_grada'] . "'>" . $mesto['ime_grada'] . " " . $mesto['postanski_broj'] . '</option>';
			}

			?>
									</datalist>

								<input type="text" class="awesomplete center-text input-small" name="id_saradnika" list="saradnici" value="" placeholder="Izaberi Saradnika">
									<datalist id="saradnici">
										<?php

			$saradnici = $getData->get_saradnici($id_korisnika);
			foreach ($saradnici as $saradnik) {
				echo "<option value='" . $saradnik['id_saradnika'] . "'>" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . '</option>';
			}

			?>
									</datalist>
							</div>

							<div class="center-3row">
								<input type="text" class="center-text input-field" name="ime_i_prezime" placeholder="Ime i Prezime" required>
								<input type="text" class="center-text input-field" name="adresa" placeholder="Adresa" required>
								<input type="text" class="center-text input-field" name="prevoznik" placeholder="Prevoznik" required>
							</div>

							<div class="right-3row">
								<input type="text" class="center-text input-field" name="magacin" value="<?php echo $naziv_magacina ?>" disabled>
								<input type="hidden" name="id_magacina" value="<?php echo $id_magacina ?>">
								<input type="text" class="center-text input-field" name="telefon" placeholder="Telefon" required>
								<input type="text" class="center-text input-field" name="broj_posiljke" placeholder="Broj Pošiljke" required>
							</div>

						</div>
						<?php $i = 1;?>
						<div class="porudzbenica-artikli">
							<?php echo $i . ". "; ?>
							<input type="text" class="awesomplete center-text input-small" name="proizvod<?php echo $i; ?>" list="proizvodi" value="" size="36" placeholder="Izaberi Artikal" required onChange="autofillProizvoda(this,'<?php echo $i; ?>','narudzbenica',<?php echo $id_magacina ?>)">
							<datalist id="proizvodi">
								<?php

								$proizvodi = $getData->get_proizvodi_from_magacin($id_korisnika, $id_magacina);
								foreach ($proizvodi as $proizvod) {
									echo "<option value='".$proizvod['id_proizvoda']."'>" . $proizvod['naziv_proizvoda'] . "</option>";
								}

								?>
							</datalist>

							<input type="text" class="center-text input-small" name="kolicina<?php echo $i; ?>" size="5" placeholder="kolicina" required>
							<input type="text" class="center-text input-small" name="cena_proizvoda<?php echo $i; ?>" id="cena-proizvoda<?php echo $i; ?>" size="5" placeholder="cena" required>
							<input type="text" class="center-text input-small" name="stanje<?php echo $i; ?>" id="stanje<?php echo $i; ?>" size="5" placeholder="stanje" disabled>
						</div>

						<input type="hidden" name="broj_artikala" value="<?php echo $i; ?>">
						
						<div class="unos-button">
					        <input type="submit" class="submit button-full" name="submit-narudzbenica" value="Submit"><br><br>
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