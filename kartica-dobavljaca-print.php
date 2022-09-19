<?php 

//SEO 
$page_title = "";
$keywords = "";
$description = "";

$msg = $artikliKomadi = $cena = $ukupno = $broj_otpremnice = $broj_prijemnice = $br_predracuna = ""; 

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
    

    if($statusKorisnika =='1'){
      ?>
      <div class="dobavljac-box float-left non-printable">
      <form class="" method="post" action="">
        <fieldset>
        <legend>&nbsp; <b> DOBAVLJAČ </b> &nbsp; </legend>
          DOBAVLJAČ: <input type="text" class="awesomplete input-right input-kupac" onblur="autofill(this,'faktura')" name="firma" id="firma" list="kupci" value=""  autofocus required><br>
          <datalist id="kupci">
            <?php

              $kupci = $getData->get_kupci($idKorisnika);
              foreach($kupci as $row) {
                echo "<option>" . $row['naziv_kupca'] . '</option>';
              }
              
            ?>
          </datalist> 
          OD DATUMA<input type="date" name="od_datuma" class="input-right"><br>
          DO DATUMA<input type="date" name="do_datuma" class="input-right"><br>
          <input type="submit" name="pretrazi" class="submit" value="PRETRAŽI">
        </fieldset>
      </form>
    </div>


    <?php  
    $kupac_id = $od_datuma = $do_datuma = "";
    $today = date("Y-m-d");

    $time = strtotime("-30 year", time());
      $startDate = date("Y-m-d", $time);

      if(isset($_POST["pretrazi"]) || isset($_GET["page"])){
        if(isset($_POST["pretrazi"])){
          $firma = strip_tags($_POST["firma"]);
          $kupacRow = $getData->get_kupac($idKorisnika,$firma);

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

        if($do_datuma == ""){
          $do_datuma = $today;
        }

        if($od_datuma == ""){
          $start_datum = "DO ";
          $od_datuma = $startDate;
        }
        else{
          $start_datum = $od_datuma;
        }

        if($kupac_id != ""){
          $total_pages = $getData->get_ukupan_broj_faktura_po_kupcu_i_datumu($idKorisnika,$kupac_id,$od_datuma,$do_datuma);
        }

        $fakture_ukupno = $getData->get_fakture_po_kupcu_i_datumu($idKorisnika,$kupac_id,$od_datuma,$do_datuma);  
    ?>

        <div class="kartica-klijenta float-right" id='kartica-klijenta' >

          <h2>STANJE ZA PERIOD <?php echo $start_datum . " - " . $do_datuma ?></h2>
          <table class="tabela-kartica">
            <tr id='tr'>
              <th class='red'>Red. Br</th>
              <th class='naziv'>Br. Naloga</th>
              <th class='naziv'>Datum</th>
              <th class='naziv'>Valuta</th>
              <th class='naziv'>Uplata</th>
              <th class='naziv'>Potražnja</th>
            </tr>

          <?php
          
          $i = 1;
          $ukupna_uplata = 0;
          $ukupna_potraznja = 0;




          $fakture_array = array();


          foreach($fakture_ukupno as $faktura_singl){ //pravljenje niza ID-jeva faktura

            $id_fakture = $faktura_singl["id_fakture"];

            $fakture_array[] = $id_fakture;

          } 

    //      arsort($fakture_array);

          //PAGIN------------------------------------------

          $page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
          $total = count( $fakture_array ); //total items in array    
          $limit = 100000; //per page    
          $totalPages = ceil( $total/ $limit ); //calculate total pages
          $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
          $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
          $offset = ($page - 1) * $limit;
          if( $offset < 0 ) $offset = 0;

          $fakture_array = array_slice( $fakture_array, $offset, $limit, true);


          //----------------------------------------------- 
     
          foreach($fakture_array as $id_fakture){

            $faktura = $getData->get_faktura_po_id($idKorisnika,$id_fakture);
            
            if($faktura["broj_fakture"] == NULL){
              $broj_naloga = $faktura["broj_izvoda"];
            }
            else{
              $broj_naloga = $faktura["broj_fakture"];
            }

            echo "
            <tr>
              <td class='red'>".$i."</td>
              <td class='naziv'>".$broj_naloga."</td>
              <td class='naziv'>".$faktura['datum_prometa']."</td>
              <td class='naziv'>".$faktura['valuta']."</td>
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
            $ukupna_uplata = $ukupna_uplata + $faktura_uk["uplata"];
            $ukupna_potraznja = $ukupna_potraznja + $faktura_uk["ukupno"];
            $saldo = $ukupna_potraznja - $ukupna_uplata;
          }

          echo "<table class='tabela-kartica'>
              <tr>
                <td colspan='4'><b>UKUPNO</b></td>
                <td class='naziv'><b>".number_format($ukupna_uplata,2,',','.')."</b></td>
                <td class='naziv'><b>".number_format($ukupna_potraznja,2,',','.')."</b></td>
              </tr>
              <tr>
                <td colspan='5'><b>SALDO</b></td>
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

        </div>

        <button name='print' id='print' class='submit non-printable' onClick='printArhiva()' value='Print'>PRINT</button>
    <?php
        

      }


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

/*AKO NIJE ULOGOVAN redirektuje se na index stranicu*/
else { 
  header("Location: index.php");
}

include("assets/footer.php"); 
 ?>

<script>
    $("#n2").css("color", "white");

    $(document).ready(function(){
      $("#kartica-klijenta").css("width", "180mm");
      window.print();
      window.onafterprint = function(event) {
          window.location.href = 'kartica-dobavljaca.php?page=1&kupac_id=<?php echo $kupac_id; ?>&od_datuma=<?php echo $od_datuma; ?>&do_datuma=<?php echo $do_datuma; ?>'
      };
  //    etTimeout("closePrintView()", 3000);
    });

</script>
