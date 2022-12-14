<?php
include "dbconfig.php";

$id_korisnika = $_SESSION['sess_id_korisnika'];

//POPUNJAVANJE TABELE MAGACIN NA STRANI unosi.php
if (isset($_GET['id_magacina'])) {
	$id_magacina = strip_tags($_GET['id_magacina']);
	$proizvodi = $getData->get_proizvodi_from_magacin($id_korisnika, $id_magacina);

	if (isset($_GET['page']) && $_GET['page'] == "unosi") {
		$i = 1;
		?>
		<tr>
			<th>Br</th>
			<th>NAZIV ARTIKLA</th>
			<th>CENA</th>
			<th>CENA SARADNIKA</th>
			<th>KOLICINA</th>
		</tr>

		<?php
foreach ($proizvodi as $proizvod) {
			echo "
			<tr>
				<td><a href='edit-proizvoda.php?id-proizvoda=" . $proizvod['id_proizvoda'] . "'><button class='broj center-text input-small plus'>" . $i . "</button></a></td>
				<td>" . $proizvod['naziv_proizvoda'] . "</td>
				<td>" . $proizvod['cena_proizvoda'] . "</td>
				<td>" . $proizvod['cena_saradnika'] . "</td>
				<td>" . $proizvod['kolicina_u_magacinu'] . "</td>
			</tr>
			";
			$i++;
		}
	}
}
/*END popunjavanje tabele magacin na unosi.php*/

/*OZNACAVANJE PORUDZBINE DA JE PLACENA*/
if (isset($_GET['plati'])) {
	$id_narudzbine = strip_tags($_GET['plati']);
	$insertData->update_status_porudzbine($id_korisnika, $id_narudzbine, 2);
	echo "<script>location.reload();</script>";
}

/*UPIS BROJA PORUDZBINE*/
if (isset($_GET['edit-broja-porudzbine']) && $_GET['edit-broja-porudzbine'] == 1) {
	$broj_posiljke = strip_tags($_GET['broj-posiljke']);
	$id_narudzbine = strip_tags($_GET['id-narudzbine']);

	if ($broj_posiljke != "" && $id_narudzbine != "") {
		$insertData->update_broja_posiljke($broj_posiljke, $id_narudzbine, $id_korisnika);
		echo 1;
	} else {
		echo 2;
	}
}
/*END upis broja porudzbine*/

/*autofillProizvoda*/
if (isset($_GET['proizvod'])) {
	$naziv_proizvoda = strip_tags($_GET['proizvod']);
	$i = strip_tags($_GET['i']);
	$id_magacina = strip_tags($_GET['id_magacina']);

	if ($getData->if_proizvod_exists($id_korisnika, $naziv_proizvoda, $id_magacina) == 1) {
		$proizvod = $getData->get_proizvod_by_name($id_korisnika, $naziv_proizvoda, $id_magacina);
		$cena = $proizvod['cena_proizvoda'];
		$stanje = $proizvod['kolicina_u_magacinu'];
		$tezina_proizvoda = $proizvod['tezina_proizvoda'];
		$broj_paketa = $proizvod['broj_paketa'];

		if ($proizvod !== false) {
			echo '<script>
						$("#cena-proizvoda' . $i . '").val("' . $cena . '");
						$("#stanje' . $i . '").val("' . $stanje . '");
						$("#tezina' . $i . '").val("' . $tezina_proizvoda . '");
						$("#broj-paketa' . $i . '").val("' . $broj_paketa . '");
					</script>';
		}
	}
}

/*DELETE input*/
if (isset($_GET['delete']) && $_GET['delete'] == 1) {
	$tabela = strip_tags($_GET['tabela']);
	$id = strip_tags($_GET['id']);

	if ($tabela == "porudzbine") {
		$porudzbina = $getData->get_porudzbina_by_id($id_korisnika, $id);
		$artikli_komadi = $porudzbina['artikliKomadi'];

		$artikli_komadi_array = explode(",", $artikli_komadi);

		foreach ($artikli_komadi_array as $artikal_komad) {
			$artikal_komad_array = explode("/", $artikal_komad);
			$id_proizvoda = $artikal_komad_array[0];
			$komada = $artikal_komad_array[1];

			$proizvod = $getData->get_proizvod_by_id_and_korisnik($id_korisnika, $id_proizvoda);
			$staro_stanje = $proizvod['kolicina_u_magacinu'];
			$novo_stanje = $staro_stanje + $komada;

			$insertData->update_stanje_proizvoda($novo_stanje, $id_proizvoda, $id_korisnika);
		}
	}

	if ($tabela != "" and $id != "") {
		$insertData->delete_row($tabela, $id, $id_korisnika);
		echo "<script>location.reload();</script>";
	}
}

if (isset($_GET['change-status']) && $_GET['change-status'] == 1) {
	$tabela = strip_tags($_GET['tabela']);
	$id = strip_tags($_GET['id']);
	$status = strip_tags($_GET['status']);

	if ($tabela == "porudzbine") {
		$porudzbina = $getData->get_porudzbina_by_id($id_korisnika, $id);
		$artikli_komadi = $porudzbina['artikliKomadi'];

		$artikli_komadi_array = explode(",", $artikli_komadi);

		foreach ($artikli_komadi_array as $artikal_komad) {
			$artikal_komad_array = explode("/", $artikal_komad);
			$id_proizvoda = $artikal_komad_array[0];
			$komada = $artikal_komad_array[1];

			$proizvod = $getData->get_proizvod_by_id_and_korisnik($id_korisnika, $id_proizvoda);
			$staro_stanje = $proizvod['kolicina_u_magacinu'];
			$novo_stanje = $staro_stanje + $komada;

			$insertData->update_stanje_proizvoda($novo_stanje, $id_proizvoda, $id_korisnika);
		}
	}

	if ($tabela != "" and $id != "") {
		$insertData->change_status($tabela, $id, $status, $id_korisnika);
		echo "<script>location.reload();</script>";
	}
}

//STARO
if (isset($_GET['t'])) {
	$zahtev = strip_tags($_GET['z']);

	if ($zahtev == "faktura" || $zahtev == "edit") {
		$naziv_kupca = strip_tags($_GET['t']);

		$kupacRow = $getData->get_kupac($idKorisnika, $naziv_kupca);

		if ($kupacRow !== false) {
			$statusKupca = $kupacRow["status_kupca"];

			if ($zahtev == "faktura" && $statusKupca == 0) {
				echo '<script> alert("BLOKIRANO !!!\nKupac ' . $naziv_kupca . ' je iz nekog razloga blokiran");
								document.getElementById("firma").value = "";
					</script>';
			} else {

				if ($statusKupca == 0) {
					$checked = true;
				} else {
					$checked = false;
				}

				echo '<script>
						$("#firmaHidden").val("' . $kupacRow["id_kupca"] . '");
						$("#adresa").val("' . $kupacRow["adresa_kupca"] . '");
						$("#mesto").val("' . $kupacRow["mesto_kupca"] . '");
						$("#pib").val("' . $kupacRow["pib_kupca"] . '");
						$("#matBr").val("' . $kupacRow["mat_broj"] . '");
						$("#status").val("' . $kupacRow["status_kupca"] . '");
						$("#ziro-racun").val("' . $kupacRow["ziro_racun"] . '");
						$("#email").val("' . $kupacRow["email"] . '");
						$("#block").prop("checked", ' . $checked . ');
					</script>';
			}
		}

	} elseif ($zahtev == "editKorisnika") {
		//AUTOFILL KORISNIKA U settings.php

		$korisnik = strip_tags($_GET['t']);

		$korisnik_row = $getData->get_korisnik_by_name($korisnik);

		if ($korisnik_row != false) {
			if ($korisnik_row["status"] === "0") {
				$status_korisnika = true;
			} else {
				$status_korisnika = false;
			}
			echo '<script>
						$("#korisnikHidden").val("' . $korisnik_row["id_korisnika"] . '");
						$("#adresa_korisnika").val("' . $korisnik_row["adresa"] . '");
						$("#mesto_korisnika").val("' . $korisnik_row["mesto"] . '");
						$("#pib_korisnika").val("' . $korisnik_row["pib"] . '");
						$("#mat_broj_korisnika").val("' . $korisnik_row["maticni_broj"] . '");
						$("#sifra_delatnosti_korisnika").val("' . $korisnik_row["sifra_delatnosti"] . '");
						$("#telefon_korisnika").val("' . $korisnik_row["telefon"] . '");
						$("#fax_korisnika").val("' . $korisnik_row["fax"] . '");

						$("#racun_korisnika").val("' . $korisnik_row["tekuci_racun"] . '");
						$("#banka_korisnika").val("' . $korisnik_row["banka"] . '");
						$("#logo_korisnika").val("' . $korisnik_row["logo"] . '");
						$("#status_korisnika").prop( "checked", ' . $status_korisnika . ' );

					</script>';
		}

	}

}

//unos uplate sa stranice fakture
if (isset($_GET['page'])) {
	if (strip_tags($_GET["page"]) == "arhiva-ulaz") {
		$vrednost = strip_tags($_GET['v']);
		$id = strip_tags($_GET['i']);

		$insertData->update_faktura_uplata_ulaz($vrednost, $id, $idKorisnika);

		echo $vrednost;
	}
}

/*AUTO-FILL ARTIKALA */
if (isset($_GET['artikal'])) {
	$naziv_artikla = strip_tags($_GET['artikal']);
	$i = strip_tags($_GET['i']);

	$artikalRow = $getData->get_artikal($idKorisnika, $naziv_artikla);
	$cena = $artikalRow['cena'];
	if (isset($_GET["page"])) {
		echo "<script>
					$('#cenaArtiklaEdit').val('" . $artikalRow['cena'] . "');
					$('#stopaPDVEdit').val('" . $artikalRow['pdv'] . "');
					$('#artikalHidden').val('" . $artikalRow['id_artikla'] . "');
					$('#meraEdit').val('" . $artikalRow['jedinica_mere'] . "');
				</script>";
	} elseif ($artikalRow !== false) {
		echo '<script>
					$("#cena' . $i . '").val("' . $artikalRow["cena"] . '");
					$("#mera' . $i . '").html("' . $artikalRow["jedinica_mere"] . '");
					$("#stopaPDV' . $i . '").html("' . $artikalRow["pdv"] . '");
				</script>';
	}

}

/*BROJ FAKTURE*/
if (isset($_GET["username"])) {
	$username = strip_tags($_GET["username"]);

	$poslednjaFakturaRow = $getData->get_poslednja_uneta_faktura($idKorisnika, 0);

	$poslednjaFaktura = $poslednjaFakturaRow['broj_fakture'];
	$idPoslednjeFakture = $poslednjaFakturaRow['id_fakture'];
	$vremeFakture = $poslednjaFakturaRow['vreme'];

	$trenutna_godina = date("Y");
//	$trenutna_godina = "2020";

	$datum = explode("-", $vremeFakture);
	$godina = $datum[0];

	if ($godina == $trenutna_godina) {
		//nije doslo do promene godine
		if (is_numeric($poslednjaFaktura)) {
			$brojFakture = $poslednjaFaktura + 1;
		} else {
			$n = 1;
			while (!is_numeric($poslednjaFaktura)) {
				$offset = $n;
				$poslednjaFakturaRow = $getData->get_poslednja_uneta_faktura($idKorisnika, $offset);
				$poslednjaFaktura = $poslednjaFakturaRow['broj_fakture'];

				if (is_numeric($poslednjaFaktura)) {
					$brojFakture = $poslednjaFaktura + 1;
				} else { $n = $n + 1;}
			}
		}

		$zapocetaFaktura = $getData->is_zapoceta_faktura($idKorisnika, $username);

//		echo "Korisnik: ".$idKorisnika. " / user: " .$username. " / ZAPOCETE " . $zapocetaFaktura . "<br>";

		//AKO NE POSTOJI ZAPOCETA FAKTURA - zapocinje se faktura (sa kupac_id=0)
		if ($zapocetaFaktura == 0) {
//			echo "<br> NEMA ZAPOCETE FAKTURE, zapocinje se nova br. " . $brojFakture;
			$insertData->insert_new_faktura($idKorisnika, $brojFakture, $username);
		}
	}
	/*Ako je doslo do promene godine u toku kucanja fakture*/
	else {
// Ako se godina poslednje fakture i trenutna godina ne poklapaju broj fakture se pretvara u "1" a dodatak broju u trenutnu godinu

		//	echo "<br>" . $idKorisnika . "/" . $trenutna_godina . "/" . $dodatakBroju_test . "<br>";
		//	$query = $insertData->update_dodatak_broju($idKorisnika,$trenutna_godina);

		$insertData->update_dodatak_broju($idKorisnika, $trenutna_godina);

		$dodatakBroju_test = $getData->get_korisnik($idKorisnika)["dodatak_broju"];

//		echo "<br>" . $dodatakBroju_test . "/" . $trenutna_godina  . "<br>";
		if ($dodatakBroju_test == $trenutna_godina) {
			$brojFakture = 1;
		} else {
			echo "Greska";
		}

		if ($getData->is_zapoceta_faktura($idKorisnika, $username) == 0) {
			$insertData->insert_new_faktura($idKorisnika, $brojFakture, $username);
		}

	}

	$zapocetaFakturaRow = $getData->get_zapoceta_faktura($idKorisnika, $username);

	$br = $zapocetaFakturaRow['broj_fakture'];

	$dodatak_broju = $getData->get_korisnik($idKorisnika)["dodatak_broju"];

	echo "RACUN BR. " . $br . "/" . $dodatak_broju;

}

/*BROJ OTPREMNICE*/
if (isset($_GET["broj_otpremnice"]) && $_GET["broj_otpremnice"] == "yes") {
	$username = $_GET["user"];
	$id_korisnika = $_SESSION['sess_id_korisnika'];
	$dodatak_broju = $getData->get_korisnik($idKorisnika)["dodatak_broju"];

//	$poslednjaOtpremnica = mysqli_query($conn,"SELECT * FROM otpremnice WHERE id_korisnika='$idKorisnika'")->num_rows;
	$query_poslednjaOtpremnica = $getData->get_poslednja_uneta_otpremnica($id_korisnika, 0);
	$datum_poslednje_otpremnice = $query_poslednjaOtpremnica["vreme"];

	$trenutna_godina = date("Y");

	$godina = explode("-", $datum_poslednje_otpremnice)[0];

	if ($query_poslednjaOtpremnica) {
		$poslednjaOtpremnica = $query_poslednjaOtpremnica['broj_otpremnice'];
	} else {
		$poslednjaOtpremnica = 0;
	}

	$idPoslednjeotpremnice = $query_poslednjaOtpremnica['id_otpremnice'];
	if (!$idPoslednjeotpremnice) {
		$idPoslednjeotpremnice = "";
	}

	if (is_numeric($poslednjaOtpremnica)) {
		if ($trenutna_godina == $godina) {
			$brojotpremnice = $poslednjaOtpremnica + 1;
		} else {
			$brojotpremnice = 1;
			$dodatak_broju = $trenutna_godina;
		}
	} else {
		$n = 1;
		while (!is_numeric($poslednjaOtpremnica)) {
			$offset = $n;
			$poslednjaOtpremnicaRow = $getData->get_poslednja_uneta_otpremnica($idKorisnika, $offset);
			$poslednjaOtpremnica = $poslednjaOtpremnicaRow['broj_otpremnice'];

			/*
				$idPrethodneotpremnice = $idPoslednjeotpremnice - $n;
				$query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM otpremnice WHERE id_otpremnice='$idPrethodneotpremnice' "));
				$poslednjaOtpremnica = $query['broj_otpremnice'];
			*/
			if (is_numeric($poslednjaOtpremnica)) {
				if ($trenutna_godina == $godina) {
					$brojotpremnice = $poslednjaOtpremnica + 1;
				} else {
					$brojotpremnice = 1;
					$dodatak_broju = $trenutna_godina;
				}
			} else { $n = $n + 1;}
		}

	}

	$zapocetaOtpremnica = $getData->is_zapoceta_otpremnica($idKorisnika, $username);

	//Ako ne postoji zapoceta otpremnica zapocinje se nova otpremnica
	if ($zapocetaOtpremnica == 0) {
		$insertData->insert_new_otpremnica($idKorisnika, $brojotpremnice, $username);
	}

	$zapocetaOtpremnicaRow = $getData->get_zapoceta_otpremnica($idKorisnika, $username);

	$br = $zapocetaOtpremnicaRow['broj_otpremnice'];

	echo "OTPREMNICA BR. " . $br . "/" . $dodatak_broju;
}

?>


