	<?php
		$total_pages = $getData->get_ukupan_broj_kupaca($idKorisnika);
 		

 		$svi_kupci = $getData->get_kupci($idKorisnika);	
	?>


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
			$limit = 10; //per page    
			$totalPages = ceil( $total/ $limit ); //calculate total pages
			$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
			$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
			$offset = ($page - 1) * $limit;
			if( $offset < 0 ) $offset = 0;

			$kupci_array = array_slice( $kupci_array, $offset, $limit, true);


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


			//PAGIN COUNTER--------------
			$link = "kartica-dobavljaca.php?stranica=%d";
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
		?>		

			

			<button name='print' id='print' class='submit non-printable' onClick="document.location.href='svi-dobavljaci-print.php'" value='Print'>PRINT</button>
		</div> <!--END .kartica-klijenta-->