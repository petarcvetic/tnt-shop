<?php
$ukupno = 0;
$artikliKomadi = "";
$today = date("Y-m-d");
$id_magacina = "";

include "dbconfig.php";
include "assets/header.php";

/*Podaci USER-a*/
$useID = $_SESSION['sess_user_id'];
$username = $_SESSION['sess_user_name'];
$id_korisnika = $_SESSION['sess_id_korisnika'];
$statusUser = $_SESSION['sess_user_status'];

/*KLIKNUTO JE Search ili EXPORT Bex ili EXPORT magacin na filteru*/
if (isset($_POST['filter-search']) OR isset($_POST['export-narudzbenica']) OR isset($_POST['export-magacin'])) {

	$id_magacina = strip_tags($_POST['id-magacina']);

	if (isset($_POST['filter-ime']) && $_POST['filter-ime'] != "") {
		$where1 = " AND ime_i_prezime LIKE '%" . $_POST['filter-ime'] . "%'";
	} else { $where1 = "";}

	if (isset($_POST['filter-od-datuma']) && $_POST['filter-od-datuma'] != "") {
//		$od_datuma_filter = date("d-m-Y", strtotime($_POST['filter-od-datuma']));
		$od_datuma_filter = $_POST['filter-od-datuma'];

		if (isset($_POST['filter-do-datuma']) && $_POST['filter-do-datuma'] != "") {
			$do_datuma_filter = $_POST['filter-do-datuma'];
			$where2 = " AND datum BETWEEN '" . $od_datuma_filter . "' AND '" . $do_datuma_filter . "'";
		} else {
			$where2 = " AND datum = '" . $od_datuma_filter . "'";
		}

	} else { $where2 = "";}

	if (isset($_POST['filter-saradnik']) && $_POST['filter-saradnik'] != "") {
		$where3 = " AND id_saradnika='" . $_POST['filter-saradnik'] . "'";
	} else { $where3 = "";}

	$where = $where1 . $where2 . $where3;

	$porudzbine = $getData->get_porudzbine_by_filter($id_korisnika, $id_magacina, $where);

}
/*END Filter*/

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {
	if (isset($_GET['id_magacina'])) {
		$id_magacina = strip_tags($_GET['id_magacina']);
	}
	if (isset($_POST['id-magacina'])) {
		$id_magacina = strip_tags($_POST['id-magacina']);
	}

	if ($id_korisnika == "") {
		header("Location: index.php");
	}

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

			if (!isset($_POST['filter-search']) && !isset($_POST['export-narudzbenica']) && !isset($_POST['export-magacin'])) {
				$porudzbine = $getData->get_porudzbine_by_magacin_DESC($id_korisnika, $id_magacina);
			}

			?>

		<div id="alert"></div>



		<?php
$magacini = $getData->get_magacini($id_korisnika);
			foreach ($magacini as $magacin) {
				if ($magacin['id_magacina'] != $id_magacina) {
					echo "<a href='porudzbine.php?id_magacina=" . $magacin['id_magacina'] . "'><button class='center-text submit' style='margin-left:50px'>" . $magacin['naziv_magacina'] . "</button></a>";
				} elseif ($magacin['id_magacina'] == $id_magacina) {
					$regular = true;
				}
			}

/*EXPORT Bex*/
			if (isset($_POST['export-narudzbenica'])) {
				include "SimpleXLSXGen.php";
				$filename = "export-bex" . date("d-m-Y-h-i-s") . ".xlsx";

				$ziro_racun = "";
				$tip_magacina = $getData->get_magacin_by_id($id_magacina)['tip_magacina'];
				if ($tip_magacina == 1) {
					$ziro_racun = $getData->get_korisnik($id_korisnika)['tekuci_racun'];
				}

				$narudzbine_xlsx = array();

				array_push($narudzbine_xlsx, ["Ime i prezime", "adresa", "mesto", "telefon", "težina", "broj paketa", "vrednost", "otkup", "žiro račun", "lično uručenje", "otpremnica", "povratnica", "plaćen odgovor", "poštarina", "interna napomena", "napomena za dostavu", "pravno lice"]);

				foreach ($porudzbine as $row) {

					array_push($narudzbine_xlsx, [$row['ime_i_prezime'], $row['adresa'], $row['mesto'], $row['telefon'], $row['ukupna_tezina'], $row['ukupan_broj_paketa'], " ", $row['ukupno'], $ziro_racun, "0", "0", "0", "0", $row['postarina'], " ", $row['napomena'], "0"]);
				}

				$xlsx = Shuchkin\SimpleXLSXGen::fromArray($narudzbine_xlsx);
				$xlsx->downloadAs($filename);
				exit();
			}
/*END Export Bex*/

/*EXPORT Magacin*/
			$proizvod_kolicina = "";
			if (isset($_POST['export-magacin'])) {
				include "SimpleXLSXGen.php";
				$filename = "export-magacin" . date("d-m-Y-h-i-s") . ".xlsx";

				$narudzbine_magacin = array();

				array_push($narudzbine_magacin, ["Proizvod / Kolicina", "Ime i prezime", "Prevoznik", "napomena za dostavu"]);

				foreach ($porudzbine as $row) {
					$proizvod_kolicina = "";
					$art_kom_array = explode(",", $row['artikliKomadi']);

					foreach ($art_kom_array as $art_kom) {
						$art_kom_row = explode("/", $art_kom);
						$art_id = $art_kom_row[0];
						$art_naziv = $getData->get_proizvod_by_id($id_korisnika, $art_id, $id_magacina)['naziv_proizvoda'];
						$art_kolicina = $art_kom_row[1];

//						$proizvod_kolicina .= $art_naziv . ' * ' . $art_kolicina . 'kom, ' . utf8_encode(chr(10) . chr(13));
						$proizvod_kolicina .= $art_naziv . ' * ' . $art_kolicina . ' kom, ' . "\x0D";
					}

					array_push($narudzbine_magacin, [$proizvod_kolicina, $row['ime_i_prezime'], $row['prevoznik'], $row['napomena']]);
				}

				$xlsx = Shuchkin\SimpleXLSXGen::fromArray($narudzbine_magacin);
				$xlsx->downloadAs($filename);
				exit();
			}
/*END Export Magacin*/

			?>

		<div class="unos-big">

			<div class="filter">

				<h3 class="center-text">Filter</h3>

				<form action="" method="post">
					<input type="hidden" name="id-magacina" id="id-magacina" value="<?php echo $id_magacina; ?>">

					<div class="row-filter">
						Kupac: <input type="text" class="center-text filter-field-small float-right" id="filter-ime" name="filter-ime">
					</div><br>

					<div class="row-filter">
						Od datum: <input type="date" class="center-text filter-field-small float-right" id="filter-od-datuma" name="filter-od-datuma">
					</div><br>

					<div class="row-filter">
						Do datum: <input type="date" class="center-text filter-field-small float-right" id="filter-do-datuma" name="filter-do-datuma">
					</div><br>

					<div class="row-filter">
						Saradnik: <select class="center-text filter-field-small float-right" name="filter-saradnik" id="filter-saradnik">
											<option value="" disabled selected>Saradnik</option>
										<?php
$saradnici = $getData->get_saradnici($id_korisnika);
			foreach ($saradnici as $saradnik) {
				echo "<option value='" . $saradnik['id_saradnika'] . "'>" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . '</option>';
			}
			?>
										</select>
					</div><br>

					<div class="center-row">
						<button type="submit" class="submit button-full" name="filter-search" id="filter-search"><i class="fa fa-search"></i></button>
						<input type="submit" class="submit button-full" name="export-narudzbenica" id="export-narudzbenica" value="EXPORT Bex">
						<input type="submit" class="submit button-full" name="export-magacin" id="export-magacin" value="EXPORT magacin"><br><br>
					</div>
				</form>
			</div>

			<div class="porudzbine-list">
				<div><h3 class="center-text">Magacin <?php echo $naziv_magacina; ?></h3></div>
				<div class="scroll-overflow">
					<table class="unos-table" id="magacin-tabela">
						<tr>
							<th>ID</th>
							<th>DATUM</th>
							<th>IME I PREZIME</th>
							<th>SARADNIK</th>
							<th>BROJ POSILJKE</th>
							<th>IZNOS</th>
							<th>Napomena</th>
							<th></th>
						</tr>
				<?php
$i = 1;
			foreach ($porudzbine as $porudzbina) {
				$id_saradnika = $porudzbina["id_saradnika"];
				$saradnik = $getData->get_saradnik_by_id($id_korisnika, $id_saradnika);
				echo "
						<tr id='tr" . $i . "'>
							<td><a href='edit-porudzbine.php?id_narudzbine=" . $porudzbina['id_narudzbine'] . "'><button class='broj center-text input-small plus'>" . $porudzbina['id_narudzbine'] . "</button></a></td>
							<td>" . date("d-m-Y", strtotime($porudzbina['datum'])) . "</td>
							<td>" . $porudzbina['ime_i_prezime'] . "</td>
							<td>" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . "</td>
							<td>" . $porudzbina['broj_posiljke'] . "</td>
							<td>" . $porudzbina['ukupno'] . "</td>
							<td>" . $porudzbina['napomena'] . "</td>
							<td><button class='broj center-text input-small plus' onclick='plati(" . '"' . $porudzbina['id_narudzbine'] . '"' . ")'><i class='fa fa-check'></i></button></td>
						</tr>
						";
				if ($porudzbina['status'] == 2) {
					$background_color = "#49a549";
					$text_color = "white";
				} elseif ($porudzbina['status'] == 3) {
					$background_color = "#ff5858";
					$text_color = "white";
				} elseif ($porudzbina['status'] == 4) {
					$background_color = "#ffff5c";
					$text_color = "#2b2b2b";
				} else {
					$background_color = "white";
					$text_color = "#2b2b2b";}
				echo "<script>$('#tr" . $i . "').css({'background-color':'" . $background_color . "', 'color':'" . $text_color . "'});</script>";
				$i++;
			}
			?>
					</table>
				</div>

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
      $("body").css("background-image", "url('images/background-big.webp')");
    }
  }

  mediaSize();

  function filter_porudzbina(id_magacina){
  	var ime_i_prezime = $("#filter-ime").val();
  	var od_datuma = $("#filter-od-datuma").val();
  	var do_datuma = $("#filter-do-datuma").val();
  	var saradnik = $("#filter-saradnik").find(":selected").val();

  	$(location).prop('href', 'porudzbine.php?id_magacina='+id_magacina+'&filter=1&ime='+ime_i_prezime+'&od-datuma='+od_datum+'&do-datuma='+do_datum+'&saradnik='+saradnik);
  }


  function plati(id){
  	var id_porudzbine = id.value;
		var id_porudzbine = encodeURIComponent(id_porudzbine);

  	var xhr = new XMLHttpRequest();
		xhr.open("get", "ajax_response.php?plati="+id, false);
		xhr.send();
		var odgovor = xhr.responseText;
		if(odgovor!==""){
			$("#alert").html(odgovor);
		}
  }

</script>