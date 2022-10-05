<?php
$ukupno = 0;
$artikliKomadi = "";
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
	} 
	else {
		$statusKorisnika = 0;
	}

	if ($statusUser !== "0") {

		/*KLIKNUTO JE SUBMIT*/
		if(isset($_POST['submit-proizvod'])){
			$id_proizvoda = strip_tags($_POST['id-proizvoda']);
			$naziv_proizvoda = strip_tags($_POST['naziv-proizvoda']);
			$cena_proizvoda = strip_tags($_POST['cena-proizvoda']);
			$tezina_proizvoda = strip_tags($_POST['tezina-proizvoda']);
			$cena_saradnika = strip_tags($_POST['cena-saradnika']);
			$id_magacina = strip_tags($_POST['id-magacina']);
			$kolicina_u_magacinu = strip_tags($_POST['kolicina-u-magacinu']);

			if($naziv_proizvoda != "" && $cena_proizvoda != "" && $tezina_proizvoda !="" && $cena_saradnika != "" && $id_magacina != "" && $kolicina_u_magacinu != ""){
				$insertData->update_proizvoda($naziv_proizvoda, $cena_proizvoda, $tezina_proizvoda, $cena_saradnika, $id_magacina, $kolicina_u_magacinu, $id_proizvoda, $id_korisnika);
				header('Location: edit-proizvoda.php');
			}
		}

		if ($statusKorisnika == '1') {
			if (isset($_GET['id-proizvoda'])) {
			 	$id_proizvoda = strip_tags($_GET['id-proizvoda']);
			 }else{
			 	$id_proizvoda = -1;
			 } ?>


				<div class="unos">
					<div class="unos-form-container">
						
					<?php 
					if($id_proizvoda<=0){
						$proizvodi = $getData->get_all_proizvodi($id_korisnika);
					?>
						<input type="text" class="center-text input-small" list="datalist_edit" size="34" onChange="redirect_to(this)" placeholder="Odaberi proizvod">

						<datalist id="datalist_edit">

						<?php
						foreach ($proizvodi as $proizvod) {
							echo "<option id='".$proizvod['id_proizvoda']."' value='". $proizvod['naziv_proizvoda'] ."'>";
						}
						echo "</datalist>";
					}
					elseif($getData->if_proizvod_id_exists_for_korisnik($id_korisnika, $id_proizvoda) > 0){
						$proizvod = $getData->get_proizvod_by_id_and_korisnik($id_korisnika, $id_proizvoda);
						?>	
						<!--Forma PROIZVODI-->
						<div id="form1" class="show">

							<h1 class="center-text white-text">PROIZVOD</h1>

							<form action="" method="post" class="forme-unosa">

								<div class="form-inputs">
									<div class="left-row">
										<input type="hidden" name="id-proizvoda" value="<?php echo $proizvod['id_proizvoda'];?>">
										<input type="text" class="center-text input-field" name="naziv-proizvoda" value="<?php echo $proizvod['naziv_proizvoda'];?>" required>
										<input type="text" class="center-text input-field" name="cena-proizvoda" value="<?php echo $proizvod['cena_proizvoda'];?>" required>
										<input type="text" class="center-text input-field" name="tezina-proizvoda" value="<?php echo $proizvod['tezina_proizvoda'];?>" required>
										<input type="text" class="center-text input-field" name="cena-saradnika" value="<?php echo $proizvod['cena_saradnika'];?>" required>
									</div>

									<div class="right-row">
										<select class="submit white-background unos-select" name="id-magacina" required>
										<?php 
										$magacini = $getData->get_magacini($id_korisnika);
										foreach ($magacini as $magacin) {
											if($proizvod['id_magacina'] == $magacin['id_magacina']){
												$selected = "selected";
											}
											else{
												$selected = "";
											}
											echo "<option value='".$magacin['id_magacina']."' ".$selected.">".$magacin['naziv_magacina']."</option>";
										}
										?>
										</select>
										<input type="text" class="center-text input-field" name="kolicina-u-magacinu" value="<?php echo $proizvod['kolicina_u_magacinu'];?>" required>
									</div>
								</div>

								<div class="unos-button">
					        <input type="submit" class="submit button-full" name="submit-proizvod" value="Submit"><br><br>
					      </div>

							</form>

						</div>
					<?php }else{
						echo "<script>alert('Proizvod sa zadatim ID-jem ne postoji u bazi')</script>";
					} ?>
					</div><!--END unos-form-container-->
				</div><!--END unos -->



	<?php	} 
		elseif ($statusKorisnika == '0') {
			echo "<h1 class='centerText'>ZBOG NEIZMIRENIH OBAVEZA STE PRIVREMENO ISKLJUCENI!</h1><br><br><br><br><br><br><br><br>";
		} 
		else {
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