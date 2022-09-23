<?php
include "dbconfig.php";

include "assets/header.php";

if ($user->is_loggedin() != "" && $_SESSION['sess_korisnik_status'] != "0") {

/*Podaci USER-a*/
	$useID = $_SESSION['sess_user_id'];
	$username = $_SESSION['sess_user_name'];
	$idKorisnika = $_SESSION['sess_id_korisnika'];
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

	if ($statusUser !== "0") {
		/*user koji ima status 0 je blokiran*/

		if ($statusKorisnika == '1') {?>

    	<div class="unos">

	        <div class="unos-menu">
	          <button id="1" class="submit active-button">Proizvodi</button>
	          <button id="2" class="submit">Saradnik</button>
	          <button id="3" class="submit">Gradovi</button>
	          <button id="4" class="submit">Users</button>
	          <button id="5" class="submit">Magacin</button>
	        </div>

			<div class="unos-form-container">

				<!--Forma PROIZVODI-->
				<div id="form1" class="show">

					<h1 class="center-text white-text">PROIZVOD</h1>

					<form action="" method="post" class="forme-unosa">

						<div class="form-inputs">
							<div class="left-row">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" placeholder="Naziv">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" placeholder="Cena">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" placeholder="Težina">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" placeholder="Naziv">
							</div>

							<div class="right-row">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" placeholder="Magacin">
								<input type="text" class="center-text input-field" name="naziv-proizvoda" placeholder="Količina u magacinu">
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
								<input type="text" class="center-text input-field" name="ime-saradnika" placeholder="Ime">
								<input type="text" class="center-text input-field" name="prezime-saradnika" placeholder="Prezime">
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
								<input type="text" class="center-text input-field" name="ime-grada" placeholder="Ime grada">
								<input type="text" class="center-text input-field" name="prezime-saradnika" placeholder="Poštansko broj">
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
								<input type="text" class="center-text input-field" name="user-ime" placeholder="Ime">
								<input type="text" class="center-text input-field" name="user-prezime" placeholder="Prezime">
								<input type="text" class="center-text input-field" name="user-username" placeholder="Username">
								<input type="password" class="center-text input-field" name="user-password" placeholder="Password">
							</div>

							<div class="right-row">
								<input type="email" class="center-text input-field" name="user-rola" placeholder="email">
								<select class="center-text input-field" name="user-rola" placeholder="Privilegija">
									<option value="" disabled selected>Privilegija</option>
									<option value="1">Saradnik</option>
									<option value="2">Zaposleni</option>
									<option value="3">Admin</option>
								</select>
							</div>
						</div>

						<div class="unos-button">
			        <input type="submit" class="submit button-full" name="submit-proizvodi" value="Submit"><br><br>
			      </div>

					</form>

				</div>


				<!--Forma USERS-->
				<div id="form5" class="hide">

					<div class="flex">
						<div class="flex2">
							<h1 class="center-text white-text">MAGACIN</h1>
						</div>

						<div class="flex1">
							<select class="submit white-background unos-select">
								<option>Biznis soft</option>
								<option>Blagajna</option>
							</select>
						</div>
					</div>

					<table class="unos-table">
						<tbody>
							<tr>
								<th>Br</th>
								<th>NAZIV ARTIKLA</th>
								<th>CENA</th>
								<th>CENA SARADNIKA</th>
								<th>KOLICINA</th>
							</tr>

							<tr>
								<td>1</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>

							<tr>
								<td>2</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>

				</div>

			</div>

		</div>

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
	header("Location: /index.php");
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
    	$("#2, #3, #4, #5").removeClass("active-button");

      $("#form1").removeClass("hide");
      $("#form1").addClass("show");

      $("#form2, #form3, #form4, #form5").removeClass("show");
      $("#form2, #form3, #form4, #form5").addClass("hide");
    });
    $("#2").click(function(){
    	$("#1, #3, #4, #5").removeClass("active-button");
    	$("#2").addClass("active-button");

			$("#form2").removeClass("hide");
      $("#form2").addClass("show");

      $("#form1, #form3, #form4, #form5").removeClass("show");
      $("#form1, #form3, #form4, #form5").addClass("hide");
    });
    $("#3").click(function(){
      $("#1, #2, #4, #5").removeClass("active-button");
    	$("#3").addClass("active-button");

			$("#form3").removeClass("hide");
      $("#form3").addClass("show");

      $("#form1, #form2, #form4, #form5").removeClass("show");
      $("#form1, #form2, #form4, #form5").addClass("hide");
    });
    $("#4").click(function(){
      $("#1, #2, #3, #5").removeClass("active-button");
    	$("#4").addClass("active-button");

			$("#form4").removeClass("hide");
      $("#form4").addClass("show");

      $("#form1, #form2, #form3, #form5").removeClass("show");
      $("#form1, #form2, #form3, #form5").addClass("hide");
    });
    $("#5").click(function(){
      $("#1, #2, #3, #4").removeClass("active-button");
    	$("#5").addClass("active-button");

			$("#form5").removeClass("hide");
      $("#form5").addClass("show");

      $("#form1, #form2, #form3, #form4").removeClass("show");
      $("#form1, #form2, #form3, #form4").addClass("hide");
    });
  });
</script>