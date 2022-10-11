<?php
$ukupno = 0;
$artikliKomadi = $where = "";
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

			/*KLIKNUTO JE search-trosak*/
			if (isset($_POST['search-trosak'])) {
				if (isset($_POST['namena-troska']) && $_POST['namena-troska'] != "") {
					$where1 = " AND namena_troska='" . $_POST['namena-troska'] . "'";
				} else { $where1 = "";}

				if (isset($_POST['od-datuma']) && $_POST['od-datuma'] != "") {
					$od_datuma = $_POST['od-datuma'];
					if (isset($_POST['do-datuma']) && $_POST['do-datuma'] != "") {
						$do_datuma = $_POST['do-datuma'];
						$where2 = " AND datum_troska BETWEEN '" . $od_datuma . "' AND '" . $do_datuma . "'";
					} else {
						$where2 = " AND datum_troska >= '" . $od_datuma . "'";
					}
				} else {
					if (isset($_POST['do-datuma']) && $_POST['do-datuma'] != "") {
						$do_datuma = $_POST['do-datuma'];
						$where2 = " AND datum_troska <= '" . $do_datuma . "'";
					} else {
						$where2 = "";
					}
				}

				if (isset($_POST['iznos-troska']) && $_POST['iznos-troska'] != "") {
					$where3 = " AND iznos_troska > '" . $_POST['iznos-troska'] . "'";
				} else { $where3 = "";}
				$where = $where1 . $where2 . $where3;
			}

			?>

			<div id="alert"></div>

			<div><h1 class="center-text">TROSKOVI</h1></div>

			<div class="unos-big">

				<div class="filter">

					<h3 class="center-text">Filter</h3>

					<form action="" method="post">
						<div class="row-filter">
							Namena: <input type="text" class="center-text filter-field-small float-right" id="namena-troska" name="namena-troska">
						</div><br>

						<div class="row-filter">
							Od datuma: <input type="date" class="center-text filter-field-small float-right" id="od-datuma" name="od-datuma">
						</div><br>

						<div class="row-filter">
							Do datuma: <input type="date" class="center-text filter-field-small float-right" id="do-datuma" name="do-datuma">
						</div><br>

						<div class="row-filter">
							Iznos > <input type="text" class="center-text filter-field-small float-right" id="iznos-troska" name="iznos-troska">
						</div><br>





						<div class="center-row">
							<button type="submit" style="margin: auto;" class="submit" name="search-trosak"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</div>

				<div class="porudzbine-list">
					<div class="scroll-overflow">
						<table class="unos-table" id="troskovi-tabela">
							<tr>
								<th>ID</th>
								<th>DATUM</th>
								<th>NAMENA</th>
								<th>IZNOS</th>
								<th>UNEO</th>
							</tr>

						<?php
$troskovi = $getData->get_troskovi_by_filter($id_korisnika, $where);
			$ukupan_trosak = 0;
			$i = 1;
			foreach ($troskovi as $trosak) {
				echo "
							<tr id='tr" . $i . "'>
								<td><a href='edit-troska.php?id_troska=" . $trosak['id_troska'] . "'><button class='broj center-text input-small plus'>" . $i . "</button></a></td>
								<td>" . $trosak['namena_troska'] . "</td>
								<td>" . $trosak['iznos_troska'] . "</td>
								<td>" . $trosak['datum_troska'] . "</td>
								<td>" . $trosak['user'] . "</td>
							</tr>
							";
				$ukupan_trosak += $trosak['iznos_troska'];
				$i++;
			}
			echo "UKUPAN TROSAK: " . $ukupan_trosak;
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