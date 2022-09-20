<?php

$br=$firma=$firmaId=$brFakture=$val=$datum=$adminHeader=$admin=$totalPages="";
$page_title = "kucanje fakture";
$keywords = "poslovanje,online,faktura,fakture,kucanje,automatizovana,aplikacija";
$description = "Aplikacija Moje Poslovanje Online omogućava jednostavno kucanje, izlistavanje i skladištenje faktura. Olakšajte svoje poslovanje ovom aplikacijom";

include("config.php");
include($path."dbconfig.php");	
include($path."assets/header.php");
 
$adminStatus = $_SESSION['sess_korisnik_name'];
?>

<script>
	$("#n6").css("color", "white");
</script>
	
	  
<?php	
//SETOVANJE DA LI CE SE IZLISTAVATI SAMO FAKTURE SA ISTEKLOM VALUTOM ILI SVE
	//$istekla_valuta_uslov = "AND STR_TO_DATE(datum_prometa, '%d.%m.%Y') <= CURDATE() - INTERVAL valuta DAY";
	$istekla_valuta_uslov = "";
//

	$query = "SELECT * FROM kupci WHERE id_kupca IN (SELECT DISTINCT kupac_id FROM fakture WHERE ROUND(ukupno*(1-rabat/100), 2) > uplata $istekla_valuta_uslov)  AND id_korisnika='$idKorisnika'"; 




	$total_pages = mysqli_num_rows(mysqli_query($conn,$query));
//	echo $total_pages;
//----------------

//----------------	

	$promet = "SELECT ROUND(SUM(ukupno*(1-rabat/100)), 2) AS promet FROM fakture WHERE id_korisnika='$idKorisnika'";

	$dospelo = "SELECT ROUND(SUM(ukupno*(1-rabat/100)), 2) AS dospelo FROM fakture WHERE id_korisnika='$idKorisnika' $istekla_valuta_uslov ";

	$uplata = "SELECT SUM(uplata) AS uplata FROM fakture WHERE id_korisnika='$idKorisnika' $istekla_valuta_uslov ";

	$total_promet = mysqli_fetch_object(mysqli_query($conn,$promet));
	$total_dospelo = mysqli_fetch_object(mysqli_query($conn,$dospelo));
	$total_uplata = mysqli_fetch_object(mysqli_query($conn,$uplata));

	$dug = $total_dospelo->dospelo - $total_uplata->uplata;

	echo "<div class='non-printable ukupna-potrazivanja'>";
	echo "Ukupan promet=" . number_format($total_promet->promet, 2, ',', '.') . "<br>"; 
	echo "Dospelo za naplatu=" . number_format($total_dospelo->dospelo, 2, ',', '.') . "<br>"; 
	echo "Naplaćeno="  . number_format($total_uplata->uplata, 2, ',', '.') . "<br>";
	echo "<h3>UKUPNA POTRAZIVANJA: " . number_format($dug, 2, ',', '.') . "</h3>";
	echo "</div>";

	$ii=1;
	$i=1;
	
	if ($user->is_loggedin()!="" && $statusKorisnika!=0){ //Ako je korisnik ulogovan prikazuje se sledeca (redovna) stranica
		
		//AKO JE KLIKNUTO "SEARCH"
		if(isset($_POST["search"])){
			$naziv_kupca = $_POST["firma"];

			$q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kupci WHERE naziv_kupca = '$naziv_kupca' AND id_korisnika='$idKorisnika'"));
			$id_kupca = $q["id_kupca"]; //dobijanje ID-ja kupca
			if($id_kupca == ""){ // ako nema rezultata u bazi
				echo "<script> alert ('OVE FIRME NEMA U BAZI !'); </script>";
			}

			echo "
			<div id='arhiva'>
				
				<table id='tabelaArhiva' border='1'>
					<tr id='tr'>
						<th class='red'></th>
						<th class='naziv'>KUPAC</th>
						<th class='naziv'>UKUPNI PROMET</th>
						<th class='naziv'>DOSPELO ZA NAPLATU</th>
						<th class='naziv'>UKUPNO NAPLAĆENO</th>
						<th class='naziv'>PREOSTALA POTRAŽIVANJA</th>
					</tr>
			";
 
			$promet = "SELECT ROUND(SUM(ukupno*(1-rabat/100)),2) AS promet FROM fakture WHERE kupac_id = '$id_kupca'";

			$dospelo = "SELECT ROUND(SUM(ukupno*(1-rabat/100)), 2) AS dospelo FROM fakture WHERE kupac_id = '$id_kupca' AND  STR_TO_DATE(datum, '%d.%m.%Y')+INTERVAL valuta DAY < NOW()";						

			$uplata = "SELECT SUM(uplata) AS uplata FROM fakture WHERE kupac_id = '$id_kupca' $istekla_valuta_uslov ";

			$total_promet = mysqli_fetch_object(mysqli_query($conn,$promet));
			$total_dospelo = mysqli_fetch_object(mysqli_query($conn,$dospelo));
			$total_uplata = mysqli_fetch_object(mysqli_query($conn,$uplata));

			$dug = $total_dospelo->dospelo - $total_uplata->uplata;

			if($dug > 0){

				//Boja polja "Nenaplacena potrazivanja"
				if($dug/180 < 110){
					$num = $dug/180+141;
					$color = $num . ",252,3";
				}
				else{
					$num = 252-$dug/500;
					$color = "252," . $num . ",3";
				}

				echo "<tr>
						<td class='red'></td>
						<td class='naziv' >" . $naziv_kupca . "</td>
						<td class='naziv'>" . number_format($total_promet->promet,2,",",".") . "</td>
						<td class='naziv'>" . number_format($total_dospelo->dospelo,2,",",".") . "</td>
						<td class='naziv'>" . number_format($total_uplata->uplata,2,",",".") . "</td> 
						<td class='naziv' style='background-color: rgb(" . $color . ");'>" . number_format($dug,2,",",".") . "</td>
					</tr>";
			}
		}
		else{

			echo "
			<div id='arhiva'>
				
				<table id='tabelaArhiva' border='1'>
					<tr id='tr'>
						<th class='red'></th>
						<th class='naziv'>KUPAC</th>
						<th class='naziv'>UKUPNI PROMET</th>
						<th class='naziv'>DOSPELO ZA NAPLATU</th>
						<th class='naziv'>UKUPNO NAPLAĆENO</th>
						<th class='naziv'>PREOSTALA POTRAŽIVANJA</th>
					</tr>
			";
			$a = 1; 

			$id_kupaca = mysqli_query($conn, $query);

			$duznici_array = array();


			foreach($id_kupaca as $id_kupca_koji_duguje){ //pravljenje niza id=>dug petljom 

				$id_duznika = $id_kupca_koji_duguje["id_kupca"];

//				$dug = mysqli_fetch_object(mysqli_query($conn,"SELECT SUM(ROUND(ukupno*(1-rabat/100), 2) - uplata) AS dug FROM fakture WHERE kupac_id = '$id_duznika'"))->dug;
				$dug = mysqli_fetch_object(mysqli_query($conn,"SELECT SUM(ukupno*(1-rabat/100) - uplata) AS dug FROM fakture WHERE kupac_id = '$id_duznika' $istekla_valuta_uslov"))->dug;

				$duznici_array[$id_duznika] = $dug;

//GOOD				$duznici_array[] = array($id_duznika => $dug);

//				$duznici_array = array_push_assoc($duznici_array, $id_duznika, $dug);

//				 echo $id_duznika ." / ". $dug . "<br>";
			}	

			arsort($duznici_array);

			

//PAGIN------------------------------------------

			$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
			$total = count( $duznici_array ); //total items in array    
			$limit = 20; //per page    
			$totalPages = ceil( $total/ $limit ); //calculate total pages
			$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
			$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
			$offset = ($page - 1) * $limit;
			if( $offset < 0 ) $offset = 0;

			$duznici_array = array_slice( $duznici_array, $offset, $limit, true);

/*			foreach($duznici_array as $key => $value) {
				echo $key . " is at $value <br>";
			}
*/
//			var_dump($duznici_array);
//-----------------------------------------------			

			foreach ($duznici_array as $id=>$dug) {
				$id_kupca = $id;

/*
				foreach ($duznik as $id=>$dug) {
					$id_kupca = $id;
//					echo $id."=".$dug." / ";
				}
*/				$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kupci WHERE id_kupca='$id_kupca'")); 

					$naziv_kupca = $row['naziv_kupca'];

					$promet = "SELECT ROUND(SUM(ukupno*(1-rabat/100)),2) AS promet FROM fakture WHERE kupac_id = '$id_kupca'";

					$dospelo = "SELECT ROUND(SUM(ukupno*(1-rabat/100)),2) AS dospelo FROM fakture WHERE kupac_id = '$id_kupca' $istekla_valuta_uslov ";

					$uplata = "SELECT SUM(uplata) AS uplata FROM fakture WHERE kupac_id = '$id_kupca' $istekla_valuta_uslov ";

					$total_promet = mysqli_fetch_object(mysqli_query($conn,$promet));
					$total_dospelo = mysqli_fetch_object(mysqli_query($conn,$dospelo));
					$total_uplata = mysqli_fetch_object(mysqli_query($conn,$uplata));

					$dug = $total_dospelo->dospelo - $total_uplata->uplata;

					

					if($dug > 0){

						//ODREDJIVANJE BOJE POLJA
						if($dug/180 < 110){
							$num = $dug/180+141;
							$color = $num . ",252,3";
						}
						else{
							$num = 252-$dug/500;
							$color = "252," . $num . ",3";
						}
						//-------------------------


						echo "<tr>
								<td class='red' >" . $a . "</td>
								<td class='naziv'>" . $naziv_kupca . "</td>
								<td class='naziv'>" . number_format($total_promet->promet,2,",",".") . "</td>
								<td class='naziv'>" . number_format($total_dospelo->dospelo,2,",",".") . "</td>
								<td class='naziv'>" . number_format($total_uplata->uplata,2,",",".") . "</td> 
								<td class='naziv' style='background-color: rgb(" . $color . ");'>" . number_format($dug,2,",",".") . "</td>
							</tr>";
						$a++;
					}
				
			}
		}

		echo "</table>
			</div>";
		
	?>

	<div id="filter" class="non-printable">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<fieldset class="fieldset">
				<legend>&nbsp;PRETRAGA&nbsp;</legend><br>
				Firma: <input type="text" name="firma" id="firma" class="kupacInfo awesomplete" size="18" onblur="autofill(this)"  list="kupci" value="" ><br><br>
					<datalist id="kupci">
						<?php
							$kupci = mysqli_query($conn,"SELECT * FROM kupci WHERE id_korisnika='$idKorisnika'");
							while ($row = mysqli_fetch_assoc($kupci)) {
								echo '<option >'.$row['naziv_kupca'].'</option>';
							}	
						?>
					</datalist>	
				<button type="submit" class="submit" name="search">Pretrazi</button>
			</fieldset>
			<br>
		</form>	

		<button name='print' id='print' class='submit' onClick='printArhiva()' value='Print'>PRINT</button>
	</div>

	<?php
	}
	else{  //Ako korisnik nije ulogovan
		header ('Location:index.php');
	}


//PAGIN COUNTER--------------
	$link = 'potrazivanja.php?page=%d';
	$pagerContainer = '<div class="pagination-potrazivanja non-printable">';   
	if( $totalPages != 0 ) 
	{
	  if( $page == 1 ) 
	  { 
	    $pagerContainer .= ''; 
	  } 
	  else 
	  { 
	    $pagerContainer .= sprintf( '<a href="' . $link . '" > &#171;</a>', $page - 1 ); 
	  }
	  $pagerContainer .= ' <span> page <strong>' . $page . '</strong> of ' . $totalPages . '</span>'; 
	  if( $page == $totalPages ) 
	  { 
	    $pagerContainer .= ''; 
	  }
	  else 
	  { 
	    $pagerContainer .= sprintf( '<a href="' . $link . '" > &#187; </a>', $page + 1 ); 
	  }           
	}                   
	$pagerContainer .= '</div>';

	echo $pagerContainer;
//-----------------------------


	include($path."assets/footer.php");
	
	
	
?>

	<script type="text/javascript">
		/*
		$('#brFakture').on('blur', function() { //ako unet broj fakture ondan polje "firma" i checkbox "valuta" postaju neaktivni
	        if ($(this).val() !== ''){
				$('#firma').attr("disabled", true); 
				$('#valuta').attr("disabled", true); 			
			}
			else {
				$('#firma').attr("disabled", false); 
				$('#valuta').attr("disabled", false); 			
			}
	    });
		*/
		
		function printArhiva(){
			window.print();
		}
		
	</script>