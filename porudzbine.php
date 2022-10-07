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

			if (isset($_GET['filter']) && $_GET['filter'] = 1) {
				if (isset($_GET['ime']) && $_GET['ime'] != "") {
					$where1 = " AND ime_i_prezime='" . $_GET['ime'] . "'";
				} else { $where1 = "";}
				if (isset($_GET['datum']) && $_GET['datum'] != "") {
					$datum_filter = date("d-m-Y", strtotime($_GET['datum']));
					$where2 = " AND datum='" . $datum_filter . "'";
				} else { $where2 = "";}
				if (isset($_GET['saradnik']) && $_GET['saradnik'] != "") {
					$where3 = " AND id_saradnika='" . $_GET['saradnik'] . "'";
				} else { $where3 = "";}
				$where = $where1 . $where2 . $where3;
				$porudzbine = $getData->get_porudzbine_by_filter($id_korisnika, $id_magacina, $where);
			}

			?>
		<div id="alert"></div>

		<div><h1 class="center-text">Magacin <?php echo $naziv_magacina; ?></h1></div>

		<div class="unos-big">

			<div class="filter">
				<h3 class="center-text">Filter</h3>
				<div class="row-filter">
					Kupac: <input type="text" class="center-text filter-field-small float-right" id="filter-ime" name="filter-ime">
				</div><br>
				<div class="row-filter">
					Datum: <input type="date" class="center-text filter-field-small float-right" id="filter-datum" name="filter-datum">
				</div><br>
				<div class="row-filter">
					Saradnik: <select class="center-text filter-field-small float-right" name="saradnik" id="filter-saradnik" required>
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
					<button type="submit" style="margin: auto;" class="submit" onclick="filter_porudzbina('<?php echo $id_magacina; ?>')"><i class="fa fa-search"></i></button>
				</div>
			</div>

			<div class="porudzbine-list">
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
							<td>" . $porudzbina['datum'] . "</td>
							<td>" . $porudzbina['ime_i_prezime'] . "</td>
							<td>" . $saradnik['ime_saradnika'] . " " . $saradnik['prezime_saradnika'] . "</td>
							<td>" . $porudzbina['broj_posiljke'] . "</td>
							<td>" . $porudzbina['ukupno'] . "</td>
							<td>" . $porudzbina['napomena'] . "</td>
							<td><button class='broj center-text input-small plus' onclick='plati(" . '"' . $porudzbina['id_narudzbine'] . '"' . ")'><i class='fa fa-check'></i></button></td>
						</tr>
						";
				if ($porudzbina['status'] == 2) {
					$background_color = "green";
					$text_color = "white";
				} elseif ($porudzbina['status'] == 3) {
					$background_color = "red";
					$text_color = "white";
				} elseif ($porudzbina['status'] == 4) {
					$background_color = "yellow";
					$text_color = "#333";
				} else {
					$background_color = "white";
					$text_color = "#747673";}
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
      $("body").css("background-image", "url('images/background.webp')");
    }
  }

  mediaSize();

  function filter_porudzbina(id_magacina){
  	var ime_i_prezime = $("#filter-ime").val();
  	var datum = $("#filter-datum").val();
  	var saradnik = $("#filter-saradnik").find(":selected").val();

  	$(location).prop('href', 'porudzbine.php?id_magacina='+id_magacina+'&filter=1&ime='+ime_i_prezime+'&datum='+datum+'&saradnik='+saradnik);
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