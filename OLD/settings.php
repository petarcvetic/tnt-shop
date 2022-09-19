<?php
$firma = $firmaId = $brFakture = $val = $datum = $firmaStaro = "";

//SEO TEST

$page_title = "Unos Dobavljaca";
$keywords = "dobavljac, unos, edit";
$description = "Unos novih i editovanje postojecih dobavljaca i faktura";

//include("config.php");
include "dbconfig.php";
include "assets/header.php";
?>

<script>
	$("#n3").css({"color": "#415a2d", "font-weight":"bold", "padding":"5px", "border": "solid 1px", "border-radius":"4px"});
</script>

<div id="alert"></div>


<?php

//-----------KLIKNUTO JE "DODAJ KUPCA"------------------------------------
if (isset($_POST["novi_kupac"])) {
	$nova_firma = strip_tags($_POST["nova_firma"]);
	$nova_adresa = strip_tags($_POST["nova_adresa"]);
	$novo_mesto = strip_tags($_POST["novo_mesto"]);
	$novi_pib = strip_tags($_POST["novi_pib"]);
	$novi_matBr = strip_tags($_POST["novi_matBr"]);
	$status = strip_tags($_POST["novi_status"]);
	$ziro_racun = strip_tags($_POST["ziro_racun"]);
	$email = strip_tags($_POST["email_klijenta"]);

	$find = $getData->if_kupac_exists($idKorisnika, $nova_firma);
	if ($find == 0) {
		$insertData->insert_new_kupac($idKorisnika, $nova_firma, $nova_adresa, $novo_mesto, $novi_pib, $novi_matBr, $ziro_racun, $email);
	}

} // END "DODAJ KUPCA"

//--------------KLIKNUTO JE "IZMENI KUPCA" ------------------------------------------------
if (isset($_POST['izmeni_kupca'])) {
	$firmaID = strip_tags($_POST["firmaHidden"]);
	$firmaNovo = strip_tags($_POST["firmaEdit"]);
	$adresa = strip_tags($_POST["adresaEdit"]);
	$mesto = strip_tags($_POST["mestoEdit"]);
	$pib = strip_tags($_POST["pibEdit"]);
	$matBr = strip_tags($_POST["matBrEdit"]);
	$ziro_racun = strip_tags($_POST["ziro_racunEdit"]);
	$email = strip_tags($_POST["email_klijentaEdit"]);

	if (isset($_POST["block"]) && $_POST["block"] == "0") {
		$status = '0';
	} else {
		$status = '1';
	}

	if ($firmaID != "" && $firmaNovo != "" && $adresa != "" && $mesto != "" && $pib != "") {
		$insertData->update_kupca($firmaNovo, $adresa, $mesto, $pib, $matBr, $ziro_racun, $email, $status, $firmaID, $idKorisnika);
	}

}

//--------------KLIKNUTO JE "KORISNIK" ------------------------------------------------
if (isset($_POST['submitKorisnik'])) {
	$korisnikID = strip_tags($_POST["korisnikHidden"]);
	$korisnikNovo = strip_tags($_POST["korisnik"]);
	$adresaKorisnika = strip_tags($_POST["adresa_korisnika"]);
	$mestoKorisnika = strip_tags($_POST["mesto_korisnika"]);
	$pibKorisnika = strip_tags($_POST["pib_korisnika"]);
	$matBrKorisnika = strip_tags($_POST["mat_broj_korisnika"]);

	$sifraDelatnostiKorisnika = strip_tags($_POST["sifra_delatnosti_korisnika"]);
	$telefonKorisnika = strip_tags($_POST["telefon_korisnika"]);
	$faxKorisnika = strip_tags($_POST["fax_korisnika"]);
	$racunKorisnika = strip_tags($_POST["racun_korisnika"]);
	$bankaKorisnika = strip_tags($_POST["banka_korisnika"]);
	$logoKorisnika = strip_tags($_POST["logo_korisnika"]);

	if ($logoKorisnika == "") {$logoKorisnika = "images/default.png";}

	$dodatakBroju = "";

	if (isset($_POST["status_korisnika"])) {
		$status = strip_tags($_POST["status_korisnika"]);
	} else {
		$status = 1;
	}

	if ($korisnikID !== "") {
		if ($korisnikNovo !== "" && $adresaKorisnika !== "" && $mestoKorisnika !== "" && $pibKorisnika !== "" && $sifraDelatnostiKorisnika !== "") {
			$insertData->update_korisnika($korisnikNovo, $adresaKorisnika, $mestoKorisnika, $matBrKorisnika, $pibKorisnika, $sifraDelatnostiKorisnika, $telefonKorisnika, $faxKorisnika, $racunKorisnika, $bankaKorisnika, $logoKorisnika, $dodatakBroju, $status, $korisnikID);
		} else {echo "<script> alert('Polja KORISNIK, ADRESA, MESTO, PIB i SIFRA DELATNOSTI moraju biti popunjena!'); </script>";}
	}

	if ($korisnikID == "") {
		if ($korisnikNovo !== "" && $adresaKorisnika !== "" && $mestoKorisnika !== "" && $pibKorisnika !== "" && $sifraDelatnostiKorisnika !== "") {
			$insertData->insert_korisnika($korisnikNovo, $adresaKorisnika, $mestoKorisnika, $matBrKorisnika, $pibKorisnika, $sifraDelatnostiKorisnika, $telefonKorisnika, $faxKorisnika, $racunKorisnika, $bankaKorisnika, $logoKorisnika, $dodatakBroju, $status);
		} else {echo "<script> alert('Polja KORISNIK, ADRESA, MESTO, PIB i SIFRA DELATNOSTI moraju biti popunjena!'); </script>";}
	}

}

//--------------KLIKNUTO JE "Izmeni Admina" ------------------------------------------------
if (isset($_POST['izmeniAdmina'])) {
	$idKorisnika = strip_tags($_POST["idKorisnika"]);
	$admin = $getData->get_admins($idKorisnika);

	foreach ($admin as $row) {
		$id = $row["id_admin"];
		$username = $row["username"];
		$password = $row["password"];
		$status = $row["status"];

		$newUsername = strip_tags($_POST["user" . $id]);
		$newPassword = strip_tags($_POST["pass" . $id]);
		$newStatus = strip_tags($_POST["status" . $id]);

		if ($newPassword !== "") {
			$newPassword = sha1($newPassword);
			$upisPassworda = "password='" . $newPassword . "',";
		} else {
			$upisPassworda = "";
		}

		if ($username !== $newUsername || $password !== $newPassword || $status !== $newStatus && $status !== "3") {
			$insertData->update_admina($newUsername, $upisPassworda, $newStatus, $id, $idKorisnika);
		}
	}
}

//------------- KLIKNUTO JE "Dodaj Admina" ------------------------------
if (isset($_POST["dodajAdmina"])) {
	$idKorisnika = strip_tags($_POST["hiddenIdKorisnika"]);
	$newUsername = strip_tags($_POST["newUsername"]);
	$newPassword = strip_tags($_POST["newPassword"]);
	$newStatus = strip_tags($_POST["status"]);

	if ($newPassword !== "") {
		$newPassword = sha1($newPassword);
	} else {
		$upisPassworda = "";
	}

	$existingUsername = if_admin_exists($newUsername, $idKorisnika);

	if ($existingUsername == 0 || $newPassword !== "") {
		$insertData->insert_admin($username, $password, $id_korisnika, $status);
	} else {
		echo "<script> alert('Korisnicko ime " . $newUsername . " je zauzeto') </script>";
	}
}

if ($user->is_loggedin() != "" /* && $statusKorisnika!=0 */) {
	//Ako je korisnik ulogovan prikazuje se sledeca (redovna) stranica

	if ($statusUser == "3"): ?>
		<div class="top-full">
			<button class="setings-button" onclick="location.href='print_delete.php';">Print/Delete</button> &nbsp &nbsp
			<button class="setings-button" onclick="location.href='exportdatabase.php';">Export Database</button>&nbsp &nbsp
		</div>
	<?php endif;?>

	<div class="container-div unos-box">

		<div id="alert"></div>


		<!-- NOVI KUPAC -->
		<div class="editKupaca novi-dobavljac">
			<form method="post" action="">
				<fieldset class="fieldset">
					<legend>&nbsp;NOVI DOBAVLJAČ&nbsp;</legend><br>
					<div class="input-row">
						FIRMA: <input type="text" name="nova_firma" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						ADRESA: &nbsp;<input type="text" name="nova_adresa" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						MESTO:<input type="text" name="novo_mesto" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						PIB:<input type="text" name="novi_pib" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						MAT. BR. <input type="text" name="novi_matBr" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						ŽIRO RAČUN: <input type="text" name="ziro_racun" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						EMAIL: <input type="email" name="email_klijenta" class="input-right input-kupac"><br>
					</div>
						<input type="hidden" id="novi_status" name="novi_status" value="1">

						<button type="submit" class="submit" name="novi_kupac">DODAJ</button>

				</fieldset>

				<br><br>

			</form>

		</div> <!-- END inputNew -->


		<!-- EDITOVANJE KUPACA -->
		<div class="editKupaca edit-dobavljaca">
			<form method="post" action="">
				<fieldset class="fieldset">

					<legend>&nbsp;EDIT  DOBAVLJAČA&nbsp;</legend>

					<input type="hidden" id="firmaHidden" name="firmaHidden" value=""><br>

					<div class="input-row">
						DOBAVLJAČ:  &nbsp; <input type="text" class="awesomplete input-right input-kupac" onblur="autofill(this,'edit')" name="firmaEdit" id="firma" list="kupci" value="" size="17" required> <br>

							<datalist id="kupci">

								<?php
$kupci = $getData->get_kupci_all($idKorisnika);

	foreach ($kupci as $row) {
		echo "<option>" . $row['naziv_kupca'] . '</option>';
	}
	?>

							</datalist>
					</div>

					<div class="input-row">
						ADRESA: &nbsp; <input id="adresa" name="adresaEdit" type="text" class="input-right input-kupac" required><br>
					</div>

					<div class="input-row">
						MESTO: &nbsp; <input id="mesto" name="mestoEdit" type="text" class="input-right input-kupac" required><br>
					</div>

					<div class="input-row">
						PIB:<input id="pib" name="pibEdit" type="text" class="input-right input-kupac" required><br>
					</div>

					<div class="input-row">
						MAT. BR:<input id="matBr" name="matBrEdit" type="text" class="input-right input-kupac" required><br>
					</div>

					<div class="input-row">
						ŽIRO RAČUN: <input id="ziro-racun" type="text" name="ziro_racunEdit" class="input-right input-kupac" required><br>
					</div>

					<div class="input-row">
						EMAIL: <input id="email" type="email" name="email_klijentaEdit" class="input-right input-kupac"><br>
					</div>

					<div class="input-row">
						BLOKIRAJ DOBAVLJAČA &nbsp &nbsp <input id="block" name="block" type="checkbox" value="0">
					</div>

						<button type="submit" class="submit" name="izmeni_kupca">IZMENI</button>

				</fieldset>

			</form>

		</div> <!-- END EDITOVANJE KUPACA -->


<?php

	if ($statusUser == "3") {

		?>



<!-- EDITOVANJE korisnika -->

		<div class="editKupaca">

			<form method="post" action="">

				<fieldset class="fieldset">

					<legend>&nbsp;EDIT  KORISNIKA&nbsp;</legend>

					<div class="korisnikInfo">

						<input type="hidden" id="korisnikHidden" name="korisnikHidden" value=""><br>

						Korisnik:  &nbsp; <input class="awesomplete" onblur="autofillKorisnik(this)" name="korisnik" id="korisnik" list="korisnici" value="" size="17"> <br><br>

							<datalist id="korisnici">

								<?php

		$korisnici = $getData->get_korisnici();

		foreach ($korisnici as $row) {

			echo "<option>" . $row['korisnik'] . '</option>';

		}

		?>

							</datalist>

						Adresa: &nbsp; <input id="adresa_korisnika" name="adresa_korisnika" type="text" class="input-right"><br>

						Mesto: &nbsp; <input id="mesto_korisnika" name="mesto_korisnika" type="text" class="input-right"><br>

						Mat. broj: &nbsp; <input id="mat_broj_korisnika" name="mat_broj_korisnika" type="text" class="input-right"><br>

						PIB:<input id="pib_korisnika" name="pib_korisnika" type="text" class="input-right"><br>

						Sifra del.:<input id="sifra_delatnosti_korisnika" name="sifra_delatnosti_korisnika" type="text" class="input-right">	<br>

						Telefon: &nbsp; <input id="telefon_korisnika" name="telefon_korisnika" type="text" class="input-right"><br>

						Faks: &nbsp; <input id="fax_korisnika" name="fax_korisnika" type="text" class="input-right"><br>

						Ziro rac.: &nbsp; <input id="racun_korisnika" name="racun_korisnika" type="text" class="input-right"><br>

						Banka:<input id="banka_korisnika" name="banka_korisnika" type="text" class="input-right"><br>

						Logo:<input id="logo_korisnika" name="logo_korisnika" type="text" class="input-right"><br>

						Blokiraj korisnika<input id="status_korisnika" name="status_korisnika" type="checkbox" value="0">

						<button type="submit" class="submit clear" name="submitKorisnik">Izmeni</button>

					</div> <!-- End .korisnikInfo -->

				</fieldset>

			</form>







		</div> <!-- END EDITOVANJE KUPACA -->



		<div class="editKupaca" id="admins">  </div> <!-- U ovom divu se pomocu JS i AJAXA (korisnikAutofill.php) izpisuje lista admina -->


<?php

	}
	echo "</div>"; // END container-div
} else {
	header('Location:index.php');
}

include_once 'assets/footer.php';
?>


</div> <!-- END wrapper -->

</body>

</html>