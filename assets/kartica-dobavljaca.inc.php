
	<?php  
	$saldo=0;

		if(isset($_POST["pretrazi"])){
			$firma = strip_tags($_POST["firma"]);
			$kupacRow = $getData->get_kupac($idKorisnika,$firma);
			if(!$kupacRow){
				echo "<script>alert('DOBAVLJAČ \"".$firma."\" JE NE POSTOJEĆI');</script>";
				header("Refresh:0");	
			}
	 		$kupac_id = $kupacRow["id_kupca"];
	 		$od_datuma = strip_tags($_POST["od_datuma"]);
	 		$do_datuma = strip_tags($_POST["do_datuma"]);

	 	}
	 	elseif(isset($_GET["page"]) && $_GET["page"]!="") {
	 		$kupac_id = strip_tags($_GET["kupac_id"]);
	 		$od_datuma = strip_tags($_GET["od_datuma"]);
	 		$do_datuma = strip_tags($_GET["do_datuma"]);

	 		$kupac = $getData->get_kupac_po_id($idKorisnika,$kupac_id);
	 		$firma = $kupac["naziv_kupca"];
	 	}

	 	$end_datum = date_create($do_datuma);
 		$end_datum = date_format($end_datum,"d-m-Y");

 		if($do_datuma == ""){
 			$do_datuma = $today;
 			$end_datum = date_create($do_datuma);
 			$end_datum = date_format($end_datum,"d-m-Y");
 		}

 		if($od_datuma == ""){
 			$start_datum = "DO ";
 			$od_datuma = $startDate;
 		}
 		else{
 			$start_datum = date_create($od_datuma);
 			$start_datum = date_format($start_datum,"d-m-Y");
 		}

 		if($od_datuma == "2017-01-01"){
 			$start_datum = "DO ";
 		}

 		if($kupac_id != ""){
 			$total_pages = $getData->get_ukupan_broj_faktura_po_kupcu_i_datumu($idKorisnika,$kupac_id,$od_datuma,$do_datuma);
 		}

 		$fakture_ukupno = $getData->get_fakture_po_kupcu_i_datumu($idKorisnika,$kupac_id,$od_datuma,$do_datuma);	
	?>


		<div class="kartica-klijenta" id='kartica-klijenta' >

			<h2>STANJE ZA PERIOD <?php echo $start_datum . " - " . $end_datum ?></h2>
			<table class="tabela-kartica">
				<tr id='tr'>
					<th class='red'>Red. Br</th>
					<th class='naziv'>Br. Naloga</th>
					<th class='naziv'>Datum</th>
			<!--		<th class='naziv'>Valuta</th>  -->
					<th class='naziv'>Uplata</th>
					<th class='naziv'>Potražnja</th>
				</tr>

			<?php
			

			$fakture_array = array();


			foreach($fakture_ukupno as $faktura_singl){ //pravljenje niza ID-jeva faktura

				$id_fakture = $faktura_singl["id_fakture"];

				$fakture_array[] = $id_fakture;

			}	

//			arsort($fakture_array);

			//PAGIN------------------------------------------

			$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
			$total = count( $fakture_array ); //total items in array    
			$limit = 10; //per page    
			$totalPages = ceil( $total/ $limit ); //calculate total pages
			$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
			$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
			$offset = ($page - 1) * $limit;
			if( $offset < 0 ) $offset = 0;

			$fakture_array = array_slice( $fakture_array, $offset, $limit, true);


			//-----------------------------------------------	
 
			foreach($fakture_array as $id_fakture){

				$faktura = $getData->get_faktura_po_id($idKorisnika,$id_fakture);
				

				$tip_naloga = "";

				//Utvrdjivanje tipa naloga radi prosledjivanja u funkciju "popupEdit" u dugmetu za edit naloga
				if($faktura["izvod"] == "1"){
					$tip_naloga = "izvod";
					$broj_naloga = $faktura["broj_izvoda"];
					$suma = $faktura["uplata"];
				}
				elseif($faktura["izvod"] == "0"){
					$tip_naloga = "faktura";
					$broj_naloga = $faktura["broj_fakture"];
					$suma = $faktura["ukupno"];				
				}
				else{
					$tip_naloga = "neodredjen";
					$broj_naloga = "???";
					$suma = 0.00;
				}

				$datum_naloga = date_create($faktura['datum_prometa']);
				
				
				$edit_this_nalog = $tip_naloga . '","' . $faktura["id_fakture"] . '","' . $broj_naloga . '","' . $faktura["valuta"]. '","' . $suma . '","' . $faktura['datum_prometa'];

				echo "
				<tr>
					<td class='red'>".$i."</td>
					<td class='naziv'><button class='submit middle' onclick='popupEdit(".'"'.$edit_this_nalog.'")'."'>" .$broj_naloga."</button></td>
					<td class='naziv'>".date_format($datum_naloga,"d-m-Y")."</td>
				<!--	<td class='naziv'>".$faktura['valuta']."</td> -->
					<td class='naziv'>".number_format($faktura['uplata'],2,',','.')."</td>
					<td class='naziv'>".number_format($faktura['ukupno'],2,',','.')."</td>
				</tr>";

				$i++;
			}
			echo "</table>";


			//PAGIN COUNTER--------------
			$link = "kartica-dobavljaca.php?page=%d&kupac_id=".$kupac_id."&od_datuma=".$od_datuma."&do_datuma=".$do_datuma;
			$pagerContainer = '<div class="pagination-kartica non-printable">';   
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
			  $pagerContainer .= " <span class='pagin-page'> page <strong>" . $page . "</strong> of " . $totalPages . "</span>"; 
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


			foreach($fakture_ukupno as $faktura_uk){

				$faktura_upl = $faktura_uk["uplata"];
				if($faktura_upl == ""){$faktura_upl = 0;}
				$ukupna_uplata = $ukupna_uplata + $faktura_upl;

				$faktura_ukp = $faktura_uk["ukupno"];
				if($faktura_ukp == ""){$faktura_ukp = 0;}
				$ukupna_potraznja = $ukupna_potraznja + $faktura_ukp;

				$saldo = $ukupna_potraznja - $ukupna_uplata;
			}

			echo "<table class='tabela-kartica'>
					<tr>
						<td colspan='4'><b>UKUPNO UPLATA</b></td>
						<td class='naziv'><b>".number_format($ukupna_uplata,2,',','.')."</b></td>
					</tr>

					<tr>
						<td colspan='4'><b>UKUPNO POTRAŽNJA</b></td>
						<td class='naziv'><b>".number_format($ukupna_potraznja,2,',','.')."</b></td>
					</tr>

					<tr>
						<td colspan='4'><b>SALDO</b></td>
						<td class='naziv'><b>".number_format($saldo,2,',','.')."</b></td>
					</tr>
				</table>";

			$sve_fakture_kupca = $getData->get_fakture_po_kupcu($idKorisnika,$kupac_id);

			$suma_ukupna_uplata = 0;
			$suma_ukupna_potraznja = 0;

			foreach($sve_fakture_kupca as $faktura_kupca){
				$suma_ukupna_uplata = $suma_ukupna_uplata + $faktura_kupca["uplata"];
				$suma_ukupna_potraznja = $suma_ukupna_potraznja + $faktura_kupca["ukupno"];
			}
				$suma_saldo = $suma_ukupna_uplata - $suma_ukupna_potraznja;
			?>

			<h2>UKUPNO STANJE ZA DOBAVLJAČA <?php echo $firma; ?> </h2>			
			<p>UKUPNA POTRAZNJA: <?php echo number_format($suma_ukupna_potraznja,2,',','.'); ?></p>
			<p>UKUPNO UPLACENO: <?php echo number_format($suma_ukupna_uplata,2,',','.'); ?></p>
			<p>UKUPNI SALDO: <?php echo number_format($suma_saldo,2,',','.'); ?></p>

			<button name='print' id='print' class='submit non-printable' onClick="document.location.href='kartica-dobavljaca-print.php?page=1&kupac_id=<?php echo $kupac_id; ?>&od_datuma=<?php echo $od_datuma; ?>&do_datuma=<?php echo $do_datuma; ?>'" value='Print'>PRINT</button>
		</div>




		<script type="text/javascript">

			//funkcija za ispis pop-upa za edit fakture ili izvoda
			function popupEdit(tip_naloga,id_fakture,broj_fakture,valuta,suma,datum){

				if(tip_naloga == "faktura"){
					var label = "FAKTURA";			
					var valuta = "<br>VALUTA: <input type='text' name='valuta-naloga' class='input-right' size='35' value='"+valuta+"'>";
					var button = "<br><br><input type='submit' name='izmena-fakture' class='submit' value='IZMENI FAKTURU'>";
				}
				else if(tip_naloga == "izvod"){
					var label = "IZVOD";			
					var valuta = "";
					var button = "<br><br><input type='submit' name='izmena-izvoda' class='submit' value='IZMENI IZVOD'>";			
				}
				else if(tip_naloga == neodredjen){
					var label = "FAKTURA / IZVOD";			
					var valuta = "<br>VALUTA: <input type='text' name='valuta-naloga' class='input-right' size='35' value='"+valuta+"'>";
					var button = "<br><br><input type='submit' name='izmena-izvoda' class='submit' value='IZMENI IZVOD'><br><br><input type='submit' name='izmena-izvoda' class='submit' value='IZMENI IZVOD'>";
				}

				var id_naloga = "<input type='hidden' name='id-naloga' value='"+id_fakture+"'>";
				var broj_naloga = "BROJ NALOGA: <input type='text' name='br-naloga' class='input-right' size='35' value='"+broj_fakture+"'>";
				var iznos = "<br>IZNOS SA PDV-om: <input type='text' name='iznos-naloga' class='input-right' size='35' value='"+suma+"'>";
				var datum = "<br>DATUM: <input type='date' name='datum-naloga' class='input-right' size='35' value='"+datum+"'>";



				var popup_window = "<div id='popup_window'><div id='popup_box' class='fakture-box'> <button class='kill' id='kill' onClick=\"kill_popup()\">X</button> <form method='post' action='' id='unos-form' class='unos-form'> </form> </div> </div>";

				var fields = "<div class='fakture-box'> <fieldset> <legend>&nbsp; <b> " + label + "</b> &nbsp; </legend> " + id_naloga + broj_naloga + iznos + datum + valuta + button +"</fieldset></div>";
				
				$("#popup").html(popup_window);
				$("#unos-form").html(fields);

				//CSS
				$("#popup_window").css({"background-color": "rgba(173, 173, 173, 0.4)", "z-index": "10", "position":"fixed", "top":"0px", "left":"0px", "width":"100%", "height":"100%"});
				$("#popup_box").css({"background-color": "#fff", "width":"450px", "margin":"10% auto", "padding":"15px"});
				$("#kill").css({"background-color":"red", "color":"white", "float":"right"});
			}

			function kill_popup(){
				$("#popup_window").css({"display":"none"});
			}

		</script>




