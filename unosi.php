<?php
include("dbconfig.php"); 

include("assets/header.php");

if($user->is_loggedin()!="" && $_SESSION['sess_korisnik_status'] != "0"){

/*Podaci USER-a*/
  $useID = $_SESSION['sess_user_id'];
  $username = $_SESSION['sess_user_name'];
  $idKorisnika = $_SESSION['sess_id_korisnika'];
  $statusUser = $_SESSION['sess_user_status'];



/*Podaci KORISNIKA*/
  $korisnik = $_SESSION['sess_korisnik_name'];

  if($korisnik != ""){
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
  }
  else{  
    $statusKorisnika = 0;
  }

  

  if($statusUser !== "0"){ /*user koji ima status 0 je blokiran*/

    if($statusKorisnika =='1'){ ?>

    	<div class="unos">
	      
	        <div class="unos-menu">
	          <button class="submit">Proizvodi</button>  
	          <button class="submit">Saradnik</button>
	          <button class="submit">Gradovi</button>  
	          <button class="submit">Users</button>
	          <button class="submit">Users</button>
	        </div>
	      
			<div class="unos-form-container">
				<!--Forma PROIZVODI-->
				<div class="form-proizvodi">
					<h1 class="center-text white-text">PROIZVOD</h1>
					<form action="" method="post">
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

					</form>
				</div>


				<!--Forma PROIZVODI-->
				<div class="form-proizvodi">
					<form action="" method="post">
						
					</form>
				</div>


				<!--Forma PROIZVODI-->
				<div class="form-proizvodi">
					<form action="" method="post">
						
					</form>
				</div>


				<!--Forma PROIZVODI-->
				<div class="form-proizvodi">
					<form action="" method="post">
						
					</form>
				</div>
			</div>

		</div>
    
    <?php
    }
    elseif ($statusKorisnika == '0') {
      echo "<h1 class='centerText'>ZBOG NEIZMIRENIH OBAVEZA STE PRIVREMENO ISKLJUCENI!</h1><br><br><br><br><br><br><br><br>";
    }
    else{ // ako status korisnika nije '1' ili '2' vec je '0'
      echo "<h1 class='centerText'>TRENOTNO SE RADI NA POBOLJSANJU APLIKACIJE!</h1><br><br><br><br><br><br><br><br>";
    }

  }
  else{ // ako je status usera "0" (blokirani user)
    echo "<div class='centerText'>
            <h1>IZ NEKOG RAZLOGA STE BLOKIRANI!</h1>
            <h2>Proverite svoju email poštu</h2>
          </div>";
  }
}
else{
	header("Location: /index.php");
} 

include("assets/footer.php"); 
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
</script>