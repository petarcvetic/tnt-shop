<?php
$message = "";
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

	//Ako je kliknuto submit-proizvod
	$naziv_proizvoda = $cena_proizvoda = $tezina_proizvoda = $cena_saradnika = $kolicina_u_magacinu = $sifra_proizvoda = $broj_pakete = "";
	if (isset($_POST['submit-proizvod'])) {
		$naziv_proizvoda = strip_tags($_POST['naziv-proizvoda']);
		$cena_proizvoda = strip_tags($_POST['cena-proizvoda']);
		$tezina_proizvoda = strip_tags($_POST['tezina-proizvoda']);
		$cena_saradnika = strip_tags($_POST['cena-saradnika']);
		$id_magacina = strip_tags($_POST['magacin']);
		$kolicina_u_magacinu = strip_tags($_POST['kolicina-u-magacinu']);
		$sifra_proizvoda = strip_tags($_POST['sifra-proizvoda']);
		$broj_pakete = strip_tags($_POST['broj-pakete']);

		$naziv_magacina = $getData->get_magacin_by_id($id_magacina)["naziv_magacina"];

		if ($naziv_proizvoda != "" && $cena_proizvoda != "" && $tezina_proizvoda != "" && $cena_saradnika != "" && $id_magacina != "" && $kolicina_u_magacinu != "") {

			$existingProizvod = $getData->if_proizvod_exists($id_korisnika, $naziv_proizvoda, $id_magacina);
			if ($existingProizvod > 0) {
				$message = "Ovaj proizvod je već dodat u magacin " . $naziv_magacina;
			}

			if ($message == "") {
				$insertData->unos_proizvoda($id_korisnika, $naziv_proizvoda, $cena_proizvoda, $tezina_proizvoda, $cena_saradnika, $id_magacina, $kolicina_u_magacinu, $sifra_proizvoda, $broj_pakete);
				$naziv_proizvoda = $cena_proizvoda = $tezina_proizvoda = $cena_saradnika = $kolicina_u_magacinu = $sifra_proizvoda = $broj_pakete = "";
			} else {
				echo "<script> alert('" . $message . "'); </script>";
			}
		} else {
			echo "<script> alert('Sva polja moraju biti popunjena'); </script>";
		}
	}

	//Ako je kliknuto submit-saradnik
	if (isset($_POST['submit-saradnik'])) {
		$ime_saradnika = strip_tags($_POST['ime-saradnika']);
		$prezime_saradnika = strip_tags($_POST['prezime-saradnika']);

		if ($ime_saradnika != "" && $prezime_saradnika != "") {

			$existingSaradnik = $getData->if_saradnik_exists($id_korisnika, $ime_saradnika, $prezime_saradnika);
			if ($existingSaradnik > 0) {
				$message = "Ovaj saradnik je već dodat u bazu podataka";
			}

			if ($message == "") {
				$insertData->unos_saradnika($id_korisnika, $ime_saradnika, $prezime_saradnika);
			} else {
				echo "<script> alert('" . $message . "'); </script>";
			}

		} else {
			echo "<script> alert('Sva polja moraju biti popunjena'); </script>";
		}
	}

	//Ako je kliknuto submit-grad
	if (isset($_POST['submit-grad'])) {
		$ime_grada = strip_tags($_POST['ime-grada']);
		$postanski_broj = strip_tags($_POST['postanski-broj']);

		if ($ime_grada != "" && $postanski_broj != "") {
			$existingGrad = $getData->if_grad_exists($ime_grada);

			if ($existingGrad > 0) {
				$message = "Ovaj grad je već unet u bazu podataka";
			}

			if ($message == "") {
				$insertData->unos_grada($ime_grada, $postanski_broj);
			} else {
				echo "<script> alert('" . $message . "'); </script>";
			}
		} else {
			echo "<script> alert('Sva polja moraju biti popunjena'); </script>";
		}
	}

	//Ako je kliknuto submit-user
	if (isset($_POST['submit-user'])) {
		$user_ime = strip_tags($_POST['user-ime']);
		$user_prezime = strip_tags($_POST['user-prezime']);
		$user_username = strip_tags($_POST['user-username']);
		$password = sha1(strip_tags($_POST['user-password']));
		$email = strip_tags($_POST['user-email']);
		$status = strip_tags($_POST['user-rola']);

		if ($user_ime != "" && $user_prezime != "" && $user_username != "" && $password != "" && $email != "" && $status != "") {
			$existingUsername = $getData->if_user_exists($user_username, $id_korisnika);
			$existingEmail = $getData->if_user_mail_exists($email);

			if ($existingUsername > 0) {
				$message = "Ovaj username je zauzet";
			}
			if ($existingEmail > 0) {
				$message = "Ovaj email je zauzet";
			}

			if ($message == "") {
				$insertData->unos_usera($user_ime, $user_prezime, $user_username, $password, $email, $id_korisnika, $status);
			} else {
				echo "<script> alert('" . $message . "'); </script>";
			}
		}
	}

	/*Ako je kliknuto submit-trosak*/
	if (isset($_POST['submit-trosak'])) {
		$datum_troska = strip_tags($_POST['datum-troska']);
/*		$datum_troska = date("d-m-Y", strtotime($datum_troska));
 */
		$namena_troska = strip_tags($_POST['namena-troska']);
		$iznos_troska = strip_tags($_POST['iznos-troska']);

		if ($datum_troska != "" && $namena_troska != "" && $iznos_troska != "") {

			$insertData->unos_troska($id_korisnika, $datum_troska, $namena_troska, $iznos_troska, $username);

		} else {
			echo "<script> alert('Sva polja moraju biti popunjena'); </script>";
		}
	}

	if ($statusUser !== "0") {

		/*user koji ima status 0 je blokiran*/

		if ($statusKorisnika == '1') {
			$magacini = $getData->get_magacini_by_korisnik($id_korisnika);
			?>

  	<div class="unos">

      <div class="unos-menu">
        <button id="1" class="submit active-button">Proizvodi</button>
        <button id="2" class="submit">Saradnik</button>
        <button id="3" class="submit">Gradovi</button>
        <button id="4" class="submit">Users</button>
        <button id="5" class="submit">Magacin</button>
        <button id="6" class="submit">Trošak</button>
      </div>

			<div class="unos-form-container">

				<!--Forma PROIZVODI-->
				<div id="form1" class="show">
					<a href='edit-proizvoda.php'><button class='center-text submit'>Edit proizvoda</button></a>
					<h1 class="center-text white-text">PROIZVOD</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="form-inputs">
							<div class="left-row">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" value="<?php echo $naziv_proizvoda; ?>" placeholder="Naziv" required>
								<input type="text" class="center-text input-field" name="cena-proizvoda" value="<?php echo $cena_proizvoda; ?>" placeholder="Cena" required>
								<input type="text" class="center-text input-field" name="tezina-proizvoda" value="<?php echo $tezina_proizvoda; ?>" placeholder="Težina" required>
								<input type="text" class="center-text input-field" name="cena-saradnika" value="<?php echo $cena_saradnika; ?>" placeholder="Cena Saradnika" required>
							</div>

							<div class="right-row">
								<select class="center-text input-field" name="magacin">
									<option value="" disabled selected>Izaberi magacin</option>
									<?php
foreach ($magacini as $magacin) {
				echo "
										<option value=" . $magacin['id_magacina'] . ">" . $magacin['naziv_magacina'] . "</option>
										";
			}
			?>
								</select>

								<input type="text" class="center-text input-field" name="kolicina-u-magacinu" value="<?php echo $kolicina_u_magacinu; ?>" placeholder="Količina u magacinu" required>
								<input type="text" class="center-text input-field" name="sifra-proizvoda" value="<?php echo $sifra_proizvoda; ?>" placeholder="Šifra proizvoda" required>
								<input type="text" class="center-text input-field" name="broj-pakete" value="<?php echo $broj_pakete; ?>" placeholder="Broj paketa za slanje" required>
							</div>
						</div>

						<div class="unos-button">
			        <input type="submit" class="submit button-full" name="submit-proizvod" value="Submit"><br><br>
			      </div>

					</form>

				</div>

				<!--Forma SARADNIK-->
				<div id="form2" class="hide">

					<h1 class="center-text white-text">SARADNIK</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="form-inputs">
							<div class="center-row">
								<input type="text" class="center-text input-field" name="ime-saradnika" placeholder="Ime" required>
								<input type="text" class="center-text input-field" name="prezime-saradnika" placeholder="Prezime" required>
							</div>
						</div>

						<div class="unos-button">
			        <input type="submit" class="submit button-full" name="submit-saradnik" value="Submit"><br><br>
			      </div>

					</form>

				</div>


				<!--Forma GRADOVI-->
				<div id="form3" class="hide">

					<h1 class="center-text white-text">GRADOVI</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="form-inputs">
							<div class="center-row">
								<input type="text" class="center-text input-field" name="ime-grada" placeholder="Ime grada" required>
								<input type="text" class="center-text input-field" name="postanski-broj" placeholder="Poštansko broj">
							</div>
						</div>

						<div class="unos-button">
			        <input type="submit" class="submit button-full" name="submit-grad" value="Submit"><br><br>
			      </div>

					</form>

				</div>


				<!--Forma USERS-->
				<div id="form4" class="hide">

					<h1 class="center-text white-text">USER</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="form-inputs">
							<div class="left-row">
								<input type="text" class="center-text input-field" name="user-ime" placeholder="Ime" required>
								<input type="text" class="center-text input-field" name="user-prezime" placeholder="Prezime" required>
								<input type="text" class="center-text input-field" name="user-username" placeholder="Username" required>
								<input type="password" class="center-text input-field" name="user-password" id="user-password" placeholder="Password" required>
								<input type="checkbox" onclick="showPassword()">Show Password
							</div>

							<div class="right-row">
								<input type="email" class="center-text input-field" name="user-email" placeholder="email" required>
								<select class="center-text input-field" name="user-rola" placeholder="Privilegija" required>
									<option value="" disabled selected>Privilegija</option>
									<option value="1">Saradnik</option>
									<option value="2">Zaposleni</option>
									<option value="3">Admin</option>
								</select>
							</div>
						</div>

						<div class="unos-button">
			        <input type="submit" class="submit button-full" name="submit-user" value="Submit"><br><br>
			      </div>

					</form>

				</div>


				<!--Forma MAGACIN-->
				<div id="form5" class="hide">

					<div class="flex">
						<div class="flex2">
							<h1 class="center-text white-text">MAGACIN</h1>
						</div>

						<div class="flex1">

								<select class="submit white-background unos-select" onchange="autofillTabeleMagacin(this)">
									<option value="" disabled selected>Izaberi magacin</option>
									<?php
foreach ($magacini as $magacin) {
				echo "
										<option value=" . $magacin['id_magacina'] . ">" . $magacin['naziv_magacina'] . "</option>
										";
			}
			?>
								</select>

						</div>
					</div>

					<div class="scroll-overflow">
						<table class="unos-table" id="unos-tabela">

						</table>
					</div>

				</div>


				<!--Forma SARADNIK-->
				<div id="form6" class="hide">

					<h1 class="center-text white-text">SARADNIK</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="form-inputs">
							<div class="center-row">
								<input type="date" class="center-text input-field" name="datum-troska" value="<?php echo $today; ?>" required>
								<input type="text" class="center-text input-field" name="namena-troska" placeholder="Namena" required>
								<input type="text" class="center-text input-field" name="iznos-troska" placeholder="Iznos" required>
							</div>
						</div>

						<div class="unos-button">
			        <input type="submit" class="submit button-full" name="submit-trosak" value="Submit"><br><br>
			      </div>

					</form>

				</div>

			</div>

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

<script>
    $("#n1").css({"color": "#415a2d", "padding":"5px", "border": "solid 1px", "border-radius":"4px", "font-weight":"bold"});

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
  window.addEventListener('resize', mediaSize, false);
  $("#headerTop").css("background-color", "rgba(10,10,10,0)");
  $("#pc-code-logo").css("display", "none");

  $(document).ready(function(){
    $("#1").click(function(){
    	$("#1").addClass("active-button");
    	$("#2, #3, #4, #5, #6").removeClass("active-button");

      $("#form1").removeClass("hide");
      $("#form1").addClass("show");

      $("#form2, #form3, #form4, #form5, #form6").removeClass("show");
      $("#form2, #form3, #form4, #form5, #form6").addClass("hide");
    });
    $("#2").click(function(){
    	$("#1, #3, #4, #5, #6").removeClass("active-button");
    	$("#2").addClass("active-button");

			$("#form2").removeClass("hide");
      $("#form2").addClass("show");

      $("#form1, #form3, #form4, #form5, #form6").removeClass("show");
      $("#form1, #form3, #form4, #form5, #form6").addClass("hide");
    });
    $("#3").click(function(){
      $("#1, #2, #4, #5, #6").removeClass("active-button");
    	$("#3").addClass("active-button");

			$("#form3").removeClass("hide");
      $("#form3").addClass("show");

      $("#form1, #form2, #form4, #form5, #form6").removeClass("show");
      $("#form1, #form2, #form4, #form5, #form6").addClass("hide");
    });
    $("#4").click(function(){
      $("#1, #2, #3, #5, #6").removeClass("active-button");
    	$("#4").addClass("active-button");

			$("#form4").removeClass("hide");
      $("#form4").addClass("show");

      $("#form1, #form2, #form3, #form5, #form6").removeClass("show");
      $("#form1, #form2, #form3, #form5, #form6").addClass("hide");
    });
    $("#5").click(function(){
      $("#1, #2, #3, #4, #6").removeClass("active-button");
    	$("#5").addClass("active-button");

			$("#form5").removeClass("hide");
      $("#form5").addClass("show");

      $("#form1, #form2, #form3, #form4, #form6").removeClass("show");
      $("#form1, #form2, #form3, #form4, #form6").addClass("hide");
    });
    $("#6").click(function(){
      $("#1, #2, #3, #4, #5").removeClass("active-button");
    	$("#6").addClass("active-button");

			$("#form6").removeClass("hide");
      $("#form6").addClass("show");

      $("#form1, #form2, #form3, #form4, #form5").removeClass("show");
      $("#form1, #form2, #form3, #form4, #form5").addClass("hide");
    });
  });

  function showPassword() {
	  var x = document.getElementById("user-password");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	}

</script>