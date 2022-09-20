<?php 

//SEO 
$page_title = "Kartica Dobavljaca";
$keywords = "dobavljac,kartica,firma,stanje,potraznja uplata";
$description = "Izlistavanje kartica firmi iz baze i pregled naloga za uplatu i isplatu";

$msg = $artikliKomadi = $cena = $ukupno = $broj_otpremnice = $broj_prijemnice = $br_predracuna = $kupac_id = $od_datuma = $do_datuma = "";

$i = 1;
$ukupna_uplata = 0;
$ukupna_potraznja = 0;

$today = date("Y-m-d");

$time = strtotime("2017-01-01");
$startDate = date("Y-m-d", $time);

include("dbconfig.php");  
include("assets/header.php");



/*AKO JE USER ULOGOVAN (ako postoji sesija sess_user_id*/
if($user->is_loggedin()!=""){

/*Podaci USER-a*/
  $userID = $_SESSION['sess_user_id'];
  $username = $_SESSION['sess_user_name'];
  $idKorisnika = $_SESSION['sess_id_korisnika'];
  $statusUser = $_SESSION['sess_user_status'];

/*Podaci KORISNIKA*/
  $korisnik = $_SESSION['sess_korisnik_name'];

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

  if($statusUser !== "0"){ /*user koji ima status 0 je blokiran*/
    

    if($statusKorisnika =='1'){ //AKO KORISNIK NIJE BLOKIRAN PRIKAZUJE SE SLEDECE-->

      

		$total_pages = $getData->get_ukupan_broj_kupaca($idKorisnika);
 		

 		$svi_kupci = $getData->get_kupci($idKorisnika);	
	?>

		<div id="alert"></div> <!--DIV u kome AJAX upisuje jQuery koji popunjava polja-->
     	<div id="popup"></div> <!--DIV u kome jQuery ispisuje popup za editovanje fakture-->

		<div class="kartica-klijenta" id='kartica-klijenta' >

			<h2>LISTA DOBAVLJAČA</h2>
			<table class="tabela-kartica">
				<tr id='tr'>
					<th class='red'></th>
					<th class='naziv'>NAZIV</th>
					<th class='naziv'>UPLATA</th>
					<th class='naziv'>POTRAŽNJA</th>
					<th class='naziv'>SALDO</th>
				</tr>

			<?php
			
			
			$kupci_array = array();


			foreach($svi_kupci as $kupac_singl){ //pravljenje niza ID-jeva faktura

				$id_kupca = $kupac_singl["id_kupca"];

				$kupci_array[] = $id_kupca;

			}	

//			arsort($kupci_array);

			//PAGIN------------------------------------------

			$page = ! empty( $_GET['stranica'] ) ? (int) $_GET['stranica'] : 1;
			$total = count( $kupci_array ); //total items in array    
			$limit = 5; //per page    
			$totalPages = ceil( $total/ $limit ); //calculate total pages
			$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
			$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
			$offset = ($page - 1) * $limit;
			if( $offset < 0 ) $offset = 0;

	//		$kupci_array = array_slice( $kupci_array, $offset, $limit, true);


			//-----------------------------------------------	

 
			foreach($kupci_array as $id_kupca){

				$ukupna_uplata = 0;
				$ukupna_potraznja = 0;
				$saldo = 0;

				$fakture_ukupno = $getData->get_faktura_po_kupcu($idKorisnika,$id_kupca);

				foreach($fakture_ukupno as $faktura_uk){

					$faktura_upl = $faktura_uk["uplata"];
					if($faktura_upl == ""){$faktura_upl = 0;}
					$ukupna_uplata = $ukupna_uplata + $faktura_upl;

					$faktura_ukp = $faktura_uk["ukupno"];
					if($faktura_ukp == ""){$faktura_ukp = 0;}
					$ukupna_potraznja = $ukupna_potraznja + $faktura_ukp;

					$saldo = $ukupna_uplata - $ukupna_potraznja;
				}


				$kupac = $getData->get_kupac_po_id($idKorisnika,$id_kupca);

				echo "
				<tr>
					<td class='red'>".$i."</td>
					<td class='naziv'>".$kupac['naziv_kupca']."</td>
					<td class='naziv'>".number_format($ukupna_uplata,2,',','.')."</td>
					<td class='naziv'>".number_format($ukupna_potraznja,2,',','.')."</td>
					<td class='naziv'>".number_format($saldo,2,',','.')."</td>
				</tr>";

				$i++;
			}
			
		echo "</table>";
	
		echo "</div>";

		}
	}
}
else { 
  header("Location: index.php");
}
	
include("assets/footer.php"); 
?>		

<script>

    $(document).ready(function(){
      $("#kartica-klijenta").css("width", "180mm");
      window.print();
      window.onafterprint = function(event) {
          window.location.href = 'kartica-dobavljaca.php';
      };
  //    etTimeout("closePrintView()", 3000);
    });

</script>
