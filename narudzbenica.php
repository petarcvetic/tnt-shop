<?php
$ukupno = $ukupna_tezina = $ukupan_broj_paketa = 0;

$artikliKomadi = $regular = $postarina = "";
$today = date("Y-m-d");

include "dbconfig.php";
include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {
	if (isset($_GET['id_magacina'])) {
		$id_magacina = strip_tags($_GET['id_magacina']);
	} else {
		$id_magacina = -1;
	}

//	$naziv_magacina = $getData->get_magacin_by_id($id_magacina)["naziv_magacina"];

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
			if (isset($_POST['submit-narudzbenica'])) {
				$datum = strip_tags($_POST['datum']);
//				$datum = date("d-m-Y", strtotime($datum));

				$mesto = strip_tags($_POST['mesto']);

				$id_saradnika = strip_tags($_POST['saradnik']);
				$saradnik = $getData->get_saradnik_by_id($id_korisnika, $id_saradnika);
				$ime_saradnika = $saradnik['ime_saradnika'];
				$prezime_saradnika = $saradnik['prezime_saradnika'];

				$ime_i_prezime = strip_tags($_POST['ime_i_prezime']);
				$adresa = strip_tags($_POST['adresa']);
				$prevoznik = strip_tags($_POST['prevoznik']);
				$id_magacina = strip_tags($_POST['id_magacina']);
				$telefon = strip_tags($_POST['telefon']);
				$napomena = strip_tags($_POST['napomena']);
				$broj_artikala = strip_tags($_POST['broj_artikala']);

				$magacin = $getData->get_magacin_by_id($id_magacina);
				$tip_magacina = $magacin['tip_magacina'];
				if ($tip_magacina == 1) {
					$postarina = "3";
				} elseif ($tip_magacina == 2) {
					$postarina = "1";
				}

				for ($i = 1; $i <= $broj_artikala; $i++) {
					if (isset($_POST["proizvod" . $i]) && $_POST["proizvod" . $i] != "") {
						$naziv_proizvoda = strip_tags($_POST['proizvod' . $i]);
						$kolicina = strip_tags($_POST["kolicina" . $i]);
						$cena = strip_tags($_POST["cena_proizvoda" . $i]);
						$tezina_proizvoda = strip_tags($_POST['tezina' . $i]);
						$broj_paketa = strip_tags($_POST['broj-paketa' . $i]);

						if ($getData->if_proizvod_exists($id_korisnika, $naziv_proizvoda, $id_magacina) != 0) {
							$proizvod = $getData->get_proizvod_by_name($id_korisnika, $naziv_proizvoda, $id_magacina);
							$id_proizvoda = $proizvod["id_proizvoda"];

							if ($id_proizvoda != "" && $kolicina != "") {
								$artikalKomada = $id_proizvoda . "/" . $kolicina . "/" . $cena . "/" . $tezina_proizvoda . "/" . $broj_paketa; //par "artikal"/"komada"/"cena"
								$artikliKomadi .= $artikalKomada . ","; //------String koji sadrzi parove "artikal"/"komada"---------------//
							} else {
								$artikalKomada = "";
							}

							$ukupna_tezina = (float) $ukupna_tezina + (float) $kolicina * (float) $tezina_proizvoda;
							$ukupan_broj_paketa = (float) $ukupan_broj_paketa + (float) $kolicina * (float) $broj_paketa;

							$zbirno = (float) $kolicina * (float) $cena;
							$ukupno = (float) $ukupno + (float) $zbirno; //------Ukupna cena------//

//							echo "<br>" . $artikliKomadi . "/" . $zbirno . "/" . $ukupno;
						} else {
							echo "<script>alert('Poroizvod " . $naziv_proizvoda . " ne postoji u izabranom magacinu');</script>";
						}

					}
				}

//				echo "Ukupna tezina = " . $ukupna_tezina . "<br>Broj paketa = " . $ukupan_broj_paketa . "<br>Ukupno = " . $ukupno;

				if ($artikliKomadi != "") {
					$artikliKomadi = substr_replace($artikliKomadi, "", -1);

					$query = $insertData->insert_new_porudzbina($id_korisnika, $datum, $id_magacina, $ime_i_prezime, $mesto, $adresa, $telefon, $id_saradnika, $prevoznik, $artikliKomadi, $ukupno, $ukupna_tezina, $ukupan_broj_paketa, $username, $napomena, $postarina);

					if ($query == "") {
/*Ako je upis u bazu uspeo skida se porucena kolicina sa stanja artikala*/
						$artikliKomadi_array = explode(",", $artikliKomadi);

						foreach ($artikliKomadi_array as $artikal_komad) {
							$artikal_komad_array = explode("/", $artikal_komad);
							$id_proizvoda_i = $artikal_komad_array[0];
							$poruceno_i = $artikal_komad_array[1];

							$trenutno_stanje = $getData->get_proizvod_by_id($id_korisnika, $id_proizvoda_i, $id_magacina)['kolicina_u_magacinu'];
							$novo_stanje = $trenutno_stanje - $poruceno_i;

							$insertData->update_stanje_proizvoda($novo_stanje, $id_proizvoda_i, $id_korisnika);
						}
					} else {
						echo "<script>alert('Doslo je do greske pri upisu u bazu'" . $query . ");</script>";
					}
				}
			}

/* END "SUBMIT" */
			if ($getData->get_poslednja_posiljka($id_korisnika)) {
				$id_narudzbine = $getData->get_poslednja_posiljka($id_korisnika)["id_narudzbine"] + 1;
			} else {
				$id_narudzbine = 1;
			}

			?>
			<div id="alert"></div>

    	<div class="unos">

				<div class="unos-form-container">

					<div id="form1" class="show">
						<?php
$magacini = $getData->get_magacini($id_korisnika);
			foreach ($magacini as $magacin) {
				if ($magacin['id_magacina'] != $id_magacina) {
					echo "<a href='narudzbenica.php?id_magacina=" . $magacin['id_magacina'] . "'><button class='center-text submit'>" . $magacin['naziv_magacina'] . "</button></a>";
				} elseif ($magacin['id_magacina'] == $id_magacina) {
					$regular = true;
				}
			}
			if ($regular) {
				$naziv_magacina = $getData->get_magacin_by_id($id_magacina)["naziv_magacina"];
				?>
						<h1 class="center-text white-text">NARUD??BENICA</h1>

						<form action="" method="post" class="forme-unosa">

							<div class="narudzbenica-general">
								<div class="left-3row">
									<div>
										<input type="text" class="center-text input-small" size="5" name="id" value="<?php echo $id_narudzbine; ?>" >
										<input type="date" class="center-text input-small" size="9" name="datum" value="<?php echo $today; ?>" required>
									</div>

									<input type="text" class="awesomplete center-text input-field" name="mesto" list="gradovi" placeholder="Mesto" required>
										<datalist id="gradovi">
											<?php

				$mesta = $getData->get_gradovi();
				foreach ($mesta as $mesto) {
					echo "<option>" . $mesto['ime_grada'] . " " . $mesto['postanski_broj'] . '</option>';
				}?>
										</datalist>

									<select class="center-text input-field" name="saradnik" placeholder="Izaberi Saradnika" required>
										<option value="" disabled selected>Saradnik</option>
									<?php
$saradnici = $getData->get_saradnici($id_korisnika);
				foreach ($saradnici as $saradnik) {
					echo "<option value='" . $saradnik['id_saradnika'] . "'>" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . '</option>';
				}
				?>
									</select>
								</div>

								<div class="center-3row">
									<input type="text" class="center-text input-field" name="ime_i_prezime" placeholder="Ime i Prezime" required>
									<input type="text" class="center-text input-field" name="adresa" placeholder="Adresa" required>
									<select class="center-text input-field" name="prevoznik" placeholder="Prevoznik" required>
										<option disabled selected>Prevoznik</option>
										<?php
$prevoznici = $getData->get_prevoznici($id_korisnika);
				foreach ($prevoznici as $prevoznik) {
					echo "<option>" . $prevoznik['naziv_prevoznika'] . "</option>";
				}
				?>
									</select>
								</div>




								<div class="right-3row">
									<input type="text" class="center-text input-field" name="magacin" value="<?php echo $naziv_magacina ?>" disabled>
									<input type="hidden" name="id_magacina" value="<?php echo $id_magacina ?>">
									<input type="text" class="center-text input-field" name="telefon" placeholder="Telefon" required>
									<input type="text" class="center-text input-field" name="napomena" placeholder="Napomena">
								</div>

							</div>
							<?php $i = 1;?>
							<div id="proizvodi-za-porudzbinu">

								<div class="porudzbenica-artikli" id="<?php echo $i; ?>">
									<div class="redni-broj"><?php echo $i . ". "; ?></div>
									<input type="text" class="awesomplete center-text proizvod-input" name="proizvod<?php echo $i; ?>" id="proizvod<?php echo $i; ?>" list="proizvodi" size="28" placeholder="Izaberi Artikal" onChange="autofillProizvoda(this,'<?php echo $i; ?>','narudzbenica',<?php echo $id_magacina ?>)" required>
									<datalist id="proizvodi">
										<?php

				$proizvodi = $getData->get_proizvodi_from_magacin($id_korisnika, $id_magacina);
				foreach ($proizvodi as $proizvod) {
					echo "<option>" . $proizvod['naziv_proizvoda'] . "</option>";
				}

				?>
									</datalist>

									<input type="text" class="center-text input-small" onblur="calculateSum()" name="kolicina<?php echo $i; ?>" id="kolicina<?php echo $i; ?>" size="5" placeholder="kolicina" required>
									<input type="text" class="center-text input-small" onblur="calculateSum()" name="cena_proizvoda<?php echo $i; ?>" id="cena-proizvoda<?php echo $i; ?>" size="5" placeholder="cena" required>

									<input type="text" class="center-text input-small" onblur="calculateSum()" name="tezina<?php echo $i; ?>" id="tezina<?php echo $i; ?>" size="5" placeholder="tezina" required>
									<input type="text" class="center-text input-small" onblur="calculateSum()" name="broj-paketa<?php echo $i; ?>" id="broj-paketa<?php echo $i; ?>" size="3" placeholder="paketi" required>
									<input type="text" class="center-text input-small" name="stanje<?php echo $i; ?>" id="stanje<?php echo $i; ?>" size="5" placeholder="stanje" disabled>
									<div class="broj center-text input-small plus" id="plus<?php echo $i; ?>" onclick="createNewInput('<?php echo $i; ?>','<?php echo $id_magacina; ?>')">+</div>
								</div>

							</div>

							<input type="hidden" name="broj_artikala" id="broj_artikala" value="<?php echo $i; ?>">

							<div class="unos-button">
						        <input type="submit" class="submit button-full" name="submit-narudzbenica" value="Submit"><br><br>
							</div>

						</form>

						<div class="flex">
							<div class="flex1">
								Vrednost porudzbine: <input type="text" class="center-text input-small" id="suma_porudzbine" size="5" disabled>
							</div>

							<div class="flex1">
								Ukupna tezina: <input type="text" class="center-text input-small" id="suma_tezina" size="5" disabled>
							</div>

							<div class="flex1">
								Broj paketa: <input type="text" class="center-text input-small" id="suma_paketi" size="5" disabled>
							</div>
						</div>
					<?php }?>
					</div>


				</div> <!--END unos-form-container-->

			</div> <!--END unos-->

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
            <h2>Proverite svoju email po??tu</h2>
          </div>";
	}
} else {
	header("Location: index.php");
}

include "assets/footer.php";
?>

<script type="text/javascript">
	function calculateSum(){
		var broj_artikala = $("#broj_artikala").val();
		var i = 1;
		var suma_artikal = 0;
		var suma = 0;
		var paketi = 0;
		var sum_paketa = 0;
		var tezina = 0;
		var sum_tezina = 0;


		while(i <= broj_artikala){
			var kolicina = $("#kolicina"+i).val();
			var cena = $("#cena-proizvoda"+i).val();
			var tezina_artikla = $("#tezina"+i).val();
			var paketi_artikla = $("#broj-paketa"+i).val();

			suma_artikal = Number(kolicina) * Number(cena);
			suma += Number(suma_artikal);

			tezina = Number(kolicina) * Number(tezina_artikla);
			sum_tezina += Number(tezina);

			paketi = Number(kolicina) * Number(paketi_artikla);
			sum_paketa += Number(paketi);

			i++
		}

//		alert(suma);
		$("#suma_porudzbine").val(suma);
		$("#suma_tezina").val(sum_tezina);
		$("#suma_paketi").val(sum_paketa);
	}

 function mediaSize(){
    if (window.matchMedia('(max-device-width: 768px)').matches){
      $("body").css("background-image", "url('images/background_mobile.webp')");
      $(".header").css("border-bottom", "none");
    }
    else{
      $("body").css("background-image", "url('images/background-big.webp')");
    }
  }

  mediaSize();
</script>