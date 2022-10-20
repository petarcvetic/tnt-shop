<?php
$ukupno = $ukupna_tezina = $ukupan_broj_paketa = 0;
$artikliKomadi = $regular = $artikliStanja = "";
$today = date("Y-m-d");

include "dbconfig.php";
include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {
	if (isset($_GET['id_narudzbine'])) {
		$id_narudzbine = strip_tags($_GET['id_narudzbine']);
	} else {
		$id_narudzbine = -1;
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

/* KLIKNUTO JE "SUBMIT" */
			if (isset($_POST['submit-narudzbenica'])) {
				$datum = strip_tags($_POST['datum']);
//				$datum = date("d-m-Y", strtotime($datum));
				//				echo $datum;
				$mesto = strip_tags($_POST['mesto']);

				$id_saradnika = strip_tags($_POST['saradnik']);
				$saradnik = $getData->get_saradnik_by_id($id_korisnika, $id_saradnika);
				$ime_saradnika = $saradnik['ime_saradnika'];
				$prezime_saradnika = $saradnik['prezime_saradnika'];

				$ime_i_prezime = strip_tags($_POST['ime_i_prezime']);
				$adresa = strip_tags($_POST['adresa']);
				$prevoznik = strip_tags($_POST['prevoznik']);
				$broj_posiljke = strip_tags($_POST['broj-posiljke']);
				$id_magacina = strip_tags($_POST['id_magacina']);
				$telefon = strip_tags($_POST['telefon']);
				$napomena = strip_tags($_POST['napomena']);
				$postarina = strip_tags($_POST['postarina']);
				$status = strip_tags($_POST['status']);
				$broj_artikala = strip_tags($_POST['broj_artikala']);

				for ($i = 1; $i <= $broj_artikala; $i++) {
					if (isset($_POST["proizvod" . $i]) && $_POST["proizvod" . $i] != "") {
						$naziv_proizvoda = strip_tags($_POST['proizvod' . $i]);
						$kolicina = strip_tags($_POST["kolicina" . $i]);
						$cena = strip_tags($_POST["cena_proizvoda" . $i]);
						$tezina_proizvoda = strip_tags($_POST['tezina' . $i]);
						$broj_paketa = strip_tags($_POST['broj-paketa' . $i]);
						$trenutno_stanje = strip_tags($_POST['stanje' . $i]);

						if ($getData->if_proizvod_exists($id_korisnika, $naziv_proizvoda, $id_magacina) != 0) {
							$proizvod = $getData->get_proizvod_by_name($id_korisnika, $naziv_proizvoda, $id_magacina);
							$id_proizvoda = $proizvod["id_proizvoda"];

							if ($id_proizvoda != "" && $kolicina != "") {
								$artikalStanje = $id_proizvoda . "/" . $trenutno_stanje . "/" . $kolicina;
								$artikalKomada = $id_proizvoda . "/" . $kolicina . "/" . $cena . "/" . $tezina_proizvoda . "/" . $broj_paketa; //par "artikal"/"komada"/"cena"
								$artikliKomadi .= $artikalKomada . ","; //------String koji sadrzi parove "artikal"/"komada"-------//
								$artikliStanja .= $artikalStanje . ",";
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

				if ($artikliKomadi != "") {
					$artikliKomadi = substr_replace($artikliKomadi, "", -1); /*uklanjanje poslednjeg zareza*/
					$artikliStanja = substr_replace($artikliStanja, "", -1);

					$query = $insertData->update_porudzbina_by_id($datum, $id_magacina, $ime_i_prezime, $mesto, $adresa, $telefon, $id_saradnika, $prevoznik, $broj_posiljke, $artikliKomadi, $ukupno, $ukupna_tezina, $ukupan_broj_paketa, $username, $napomena, $postarina, $status, $id_korisnika, $id_narudzbine);

					if ($query == "") {
/*Ako je upis u bazu uspeo skida se porucena kolicina sa stanja artikala*/
						$artikliStanja_array = explode(",", $artikliStanja);
						foreach ($artikliStanja_array as $artikal_komad) {
							$artikal_komad_array = explode("/", $artikal_komad);
							$id_proizvoda_i = $artikal_komad_array[0];
							$proslo_stanje = (int) $artikal_komad_array[1];
							$poruceno_i = (int) $artikal_komad_array[2];

//							$trenutno_stanje = $getData->get_proizvod_by_id($id_korisnika, $id_proizvoda_i, $id_magacina)['kolicina_u_magacinu'];
							$novo_stanje = (int) ($proslo_stanje - $poruceno_i);
							$insertData->update_stanje_proizvoda($novo_stanje, $id_proizvoda_i, $id_korisnika);
						}
						header("Location:porudzbine.php?id_magacina=" . $id_magacina);
					} else {
						echo "<script>alert('Doslo je do greske pri upisu u bazu'" . $query . ");</script>";
					}
				}
			}

/* END "SUBMIT" */

/*EXPORT CSV*/
			if (isset($_POST['export-narudzbenica'])) {
				include "SimpleXLSXGen.php";
				$row = $getData->get_porudzbina_by_id($id_korisnika, $id_narudzbine);

				$books = [
					["Ime i prezime", "adresa", "mesto", "telefon", "težina", "broj paketa", "vrednost", "otkup", "žiro račun", "lično uručenje", "otpremnica", "povratnica", "plaćen odgovor", "poštarina", "interna napomena", "napomena za dostavu", "pravno lice"],
					[$row['ime_i_prezime'], $row['adresa'], $row['mesto'], $row['telefon'], $row['ukupna_tezina'], $row['ukupan_broj_paketa'], " ", $row['ukupno'], "123-12345678-12", "0", "0", "0", "0", "1", " ", $row['napomena'], "0"],
				];
				$xlsx = Shuchkin\SimpleXLSXGen::fromArray($books);
				/*
					$xlsx->saveAs('books.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx
					$xlsx_content = (string) $xlsx
				*/
				$xlsx->downloadAs('books.xlsx');
			}
/*END Export to CSV*/

/*Vadjenje podataka porudzbine iz baze*/
			$narudzbina = $getData->get_porudzbina_by_id($id_korisnika, $id_narudzbine);

			$datum_e = $narudzbina['datum'];
			$datum_e = date("Y-m-d", strtotime($datum_e));

			$id_magacina_e = $narudzbina['id_magacina'];
			$magacin_e = $getData->get_magacin_by_id($id_magacina_e);

			$id_saradnika_e = $narudzbina['id_saradnika'];
			$saradnik_e = $getData->get_saradnik_by_id($id_korisnika, $id_saradnika_e);

			$prevoznik_e = $narudzbina['prevoznik'];

			$artikliKomadi_e = $narudzbina["artikliKomadi"];

			$status_e = $narudzbina['status'];
			$selected1 = $selected2 = $selected3 = $selected4 = "";
			if ($status_e == 1) {
				$selected1 = "checked";
			} elseif ($status_e == 2) {
				$selected2 = "checked";
			} elseif ($status_e == 3) {
				$selected3 = "checked";
			} elseif ($status_e == 4) {
				$selected4 = "checked";
			}

			$postarina_selected1 = $postarina_selected2 = $postarina_selected3 = $postarina_selected4 = $val1 = $val2 = "";
			$postarina_e = $narudzbina['postarina'];
			if ($postarina_e == 1) {
				$postarina_selected1 = "checked";
			} elseif ($postarina_e == 2) {
				$postarina_selected2 = "checked";
			} elseif ($postarina_e == 3) {
				$postarina_selected3 = "checked";
			} elseif ($postarina_e == 4) {
				$postarina_selected4 = "checked";
			} else {
				$tip_magacina = $getData->get_magacin_by_id($id_magacina_e)['tip_magacina'];
				if ($tip_magacina == 1) {
					$postarina_selected3 = "checked";
				}
				if ($tip_magacina == 2) {
					$postarina_selected1 = "checked";
				}
			}

/*END vadjenje opdataka*/
			?>
			<div id="alert"></div>
			<a href="porudzbine.php?id_magacina=<?php echo $id_magacina_e; ?>"><button class="center-text input-small plus back-button"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</button></a>
    		<div class="unos">

				<div class="unos-form-container">

					<div id="form1" class="show">

						<h1 class="center-text white-text">EDIT narudzbenice</h1>
<?php if ($id_narudzbine > 0) {
				?>
						<form action="" method="post" class="forme-unosa">

							<div class="narudzbenica-general">
								<div class="left-3row">
									<div>
										<input type="text" class="center-text input-small" size="5" name="id" value="<?php echo $id_narudzbine; ?>" required disabled>
										<input type="date" class="center-text input-small" size="9" name="datum" value="<?php echo $datum_e; ?>" required>
									</div>

									<input type="text" class="awesomplete center-text input-field" name="mesto" list="gradovi" value="<?php echo $narudzbina['mesto']; ?>" required>
										<datalist id="gradovi">
											<?php

				$mesta = $getData->get_gradovi();
				foreach ($mesta as $mesto) {
					echo "<option>" . $mesto['ime_grada'] . " " . $mesto['postanski_broj'] . '</option>';
				}?>
										</datalist>

									<select class="center-text input-field" name="saradnik" value="<?php echo $saradnik_e['ime_saradnika'] . " " . $saradnik_e['prezimne_saradnika']; ?>" required>
										<option value="" disabled selected>Saradnik</option>
									<?php
$saradnici = $getData->get_saradnici($id_korisnika);
				foreach ($saradnici as $saradnik) {
					if ($saradnik_e['id_saradnika'] == $saradnik['id_saradnika']) {$selected = "selected";} else { $selected = "";}
					echo "<option value='" . $saradnik['id_saradnika'] . "' " . $selected . ">" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . '</option>';
				}
				?>
									</select>
								</div>

								<div class="center-3row">
									<input type="text" class="center-text input-field" name="ime_i_prezime" value="<?php echo $narudzbina['ime_i_prezime']; ?>" required>
									<input type="text" class="center-text input-field" name="adresa" value="<?php echo $narudzbina['adresa']; ?>" required>
									<select class="center-text input-field" name="prevoznik" required>
											<option disabled selected>Prevoznik</option>
									<?php
$prevoznici = $getData->get_prevoznici($id_korisnika);
				foreach ($prevoznici as $prevoznik) {
					if ($prevoznik_e == $prevoznik['naziv_prevoznika']) {$selected = "selected";} else { $selected = "";}
					echo "<option " . $selected . ">" . $prevoznik['naziv_prevoznika'] . "</option>";
				}
				?>
									</select>
								</div>

								<div class="right-3row">
									<input type="text" class="center-text input-field" name="magacin" value="<?php echo $magacin_e['naziv_magacina']; ?>" disabled>
									<input type="hidden" name="id_magacina" value="<?php echo $magacin_e['id_magacina']; ?>">
									<input type="text" class="center-text input-field" name="telefon" value="<?php echo $narudzbina['telefon']; ?>" required>
									<input type="text" class="center-text input-field" name="broj-posiljke" value="<?php echo $narudzbina['broj_posiljke']; ?>" placeholder="Broj posiljke">
								</div>

							</div><br><br>
							<?php $i = 1;?>
							<div id="proizvodi-za-porudzbinu">
								<datalist id="proizvodi">
									<?php

				$proizvodi = $getData->get_proizvodi_from_magacin($id_korisnika, $id_magacina_e);
				foreach ($proizvodi as $proizvod) {
					echo "<option value=" . $proizvod['id_proizvoda'] . ">" . $proizvod['naziv_proizvoda'] . "</option>";
				}

				?>
								</datalist>

								<div class="porudzbenica-artikli">
									<div class="big-zaglavlje">PROIZVOD</div>
									<div class="small-zaglavlje">kom</div>
									<div class="small-zaglavlje">Cena</div>
									<div class="small-zaglavlje">Tezina</div>
									<div class="small-zaglavlje">Paketi</div>
									<div class="small-zaglavlje">Stanje</div>
								</div>

								<div class="porudzbenica-artikli" id="<?php echo $i; ?>">


								<?php
$artikliKomadi_e_array = explode(",", $artikliKomadi_e);
				$count_artikli = count($artikliKomadi_e_array);
				foreach ($artikliKomadi_e_array as $artikal_komad_e) {
					$artikal_komad_e_array = explode("/", $artikal_komad_e);
					$id_proizvoda_e = $artikal_komad_e_array[0];
					$kolicina_e = $artikal_komad_e_array[1];
					$cena_e = $artikal_komad_e_array[2];

					if (array_key_exists(3, $artikal_komad_e_array)) {
						$tezina_proizvoda_e = $artikal_komad_e_array[3];
					} else { $tezina_proizvoda_e = "";}

					if (array_key_exists(4, $artikal_komad_e_array)) {
						$broj_paketa_e = $artikal_komad_e_array[4];
					} else { $broj_paketa_e = "";}

					$proizvod_e = $getData->get_proizvod_by_id($id_korisnika, $id_proizvoda_e, $id_magacina_e);
					$trenutno_stanje_e = $proizvod_e['kolicina_u_magacinu'] + $kolicina_e;
					?>


									<div class="redni-broj"><?php echo $i . ". "; ?></div>
									<input type="text" class="awesomplete center-text proizvod-input" name="proizvod<?php echo $i; ?>" id="proizvod<?php echo $i; ?>" list="proizvodi" size="32" value="<?php echo $proizvod_e['naziv_proizvoda']; ?>" onChange="autofillProizvoda(this,'<?php echo $i; ?>','narudzbenica',<?php echo $id_magacina_e ?>)" required>


									<input type="text" class="center-text input-small" name="kolicina<?php echo $i; ?>" size="5" placeholder="kolicina" value="<?php echo $kolicina_e; ?>" required>
									<input type="text" class="center-text input-small" name="cena_proizvoda<?php echo $i; ?>" id="cena-proizvoda<?php echo $i; ?>" size="5" placeholder="cena" value="<?php echo $cena_e; ?>" required>

									<input type="text" class="center-text input-small" name="tezina<?php echo $i; ?>" id="tezina<?php echo $i; ?>" size="5" value="<?php echo $tezina_proizvoda_e; ?>" placeholder="tezina" required>
									<input type="text" class="center-text input-small" name="broj-paketa<?php echo $i; ?>" id="broj-paketa<?php echo $i; ?>" size="3" value="<?php echo $broj_paketa_e; ?>" placeholder="paketi" required>

									<input type="text" class="center-text input-small" name="stanje<?php echo $i; ?>" id="stanje<?php echo $i; ?>" size="5" value="<?php echo $trenutno_stanje_e; ?>" placeholder="stanje">

								<?php
if ($count_artikli == $i) {?>
									<div class="broj center-text input-small plus" id="plus<?php echo $i; ?>" onclick="createNewInput(<?php echo $i; ?>,<?php echo $id_magacina_e; ?>)">+</div>
								<?php
}
					echo "<br>";
					$i++;
				}
				?>


								</div>

							</div> <br>
							<div>
								<div class="flex">
									<div class="flex1">
										<div class="radio-label">
											<input type="radio" name="status" value="1" <?php echo $selected1; ?>>
											<label>Ne naplaceno</label>
										</div>
										<div class="radio-label">
											<input type="radio" name="status" value="2" <?php echo $selected2; ?>>
											<label>Naplaceno</label>
										</div>

									</div>

									<div class="flex1">
										<div class="radio-label">
											<input type="radio" name="status" value="3" <?php echo $selected3; ?>>
											<label>Lom</label>
										</div>
										<div class="radio-label">
											<input type="radio" name="status" value="4" <?php echo $selected4; ?> >
											<label>Povrat</label>
										</div>
									</div>

									<div class="flex1">
										<div class="radio-label-postarina">
											Postarina
											<?php
$tip_magacina = $getData->get_magacin_by_id($id_magacina_e)['tip_magacina'];
				if ($tip_magacina == 1) {
					$val1 = "3";
					$val2 = "4";
					?>
											<input type="radio" name="postarina" value="<?php echo $val1; ?>" <?php echo $postarina_selected3; ?>>
											<label><?php echo $val1; ?></label> &nbsp; &nbsp;
											<input type="radio" name="postarina" value="<?php echo $val2; ?>" <?php echo $postarina_selected4; ?>>
											<label><?php echo $val2; ?></label>
											<?php
}
				if ($tip_magacina == 2) {
					$val1 = "1";
					$val2 = "2";
					?>
											<input type="radio" name="postarina" value="<?php echo $val1; ?>" <?php echo $postarina_selected1; ?>>
											<label><?php echo $val1; ?></label> &nbsp; &nbsp; &nbsp;
											<input type="radio" name="postarina" value="<?php echo $val2; ?>" <?php echo $postarina_selected2; ?>>
											<label><?php echo $val2; ?></label> &nbsp; &nbsp; &nbsp;
											<?php
}

				?>
										</div>
									</div>

								</div>
							</div><br>

							<div>


							</div>

							<input type="text" class="center-text input-field" name="napomena" value="<?php echo $narudzbina['napomena']; ?>" placeholder="napomena">

							<input type="hidden" name="broj_artikala" id="broj_artikala" value="<?php echo $i; ?>">

							<div class="unos-button">
						        <input type="submit" class="submit button-full" name="submit-narudzbenica" value="EDIT">
<!--						        <input type="submit" class="submit button-full" name="export-narudzbenica" value="EXPORT xlsx"><br><br>
-->							</div>

						</form>
<?php } else {echo "<script>alert('Nepostojeci ID porudzbine');</script>";}?>
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
      $("body").css("background-image", "url('images/background-big1.webp')");
    }
  }

  mediaSize();
</script>