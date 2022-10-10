	function autofillTabeleMagacin(id){
		
		var id_magacina = id.value;
		var id_magacina = encodeURIComponent(id_magacina);

		
		var xhr = new XMLHttpRequest();
		xhr.open("get", "ajax_response.php?id_magacina="+id_magacina+"&page=unosi", false);
		xhr.send();
		var odgovor = xhr.responseText;
		if(odgovor!==""){
			$("#unos-tabela").html(odgovor);
		}		

	}


 	function autofillNarudzbaMagacin(id){
 		var id_magacina = id.value;
		var id_magacina = encodeURIComponent(id_magacina);

		alert(id_magacina);
		
		var xhr = new XMLHttpRequest();
		xhr.open("get", "ajax_response.php?id_magacina="+id_magacina+"&page=narudzbenica", false);
		xhr.send();
		var odgovor = xhr.responseText;
		if(odgovor!==""){
			$("#datalist-proizvodi").html(odgovor);
		}	
 	}

 	function autofillProizvoda(proiz,i,page,id_magacina){
		var proizvod = proiz.value;
		var proizvod = encodeURIComponent(proizvod);
		var id = proiz.id;
		
		if(page=="narudzbenica"){ //Ako je poziv funkcije iz "narudzbenica.php"

			var xhr = new XMLHttpRequest();
			xhr.open("get", "ajax_response.php?proizvod="+proizvod+"&i="+i+"&id_magacina="+id_magacina, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				$("#alert").html(odgovor);
			}
			
		}
	}

	function createNewInput(i,id_magacina){


		i=Number(i);  // broj trenutnog inputa
		var s = Number(i)+1;
		var narudzbenica = "narudzbenica";
		var predhodniArtikal = $('#stanje'+i).val(); //vrednost u predhodnom inputu
		var sledeciArtikal = $('#proizvod'+s).val(); //vrednost u sledecem inputu
		
		if (predhodniArtikal != ""){ // ako predhodni input nije prazan izpisuje se novi input
			i = s;
			
			var div_start = '<div class="porudzbenica-artikli" id="'+i+'">';
			var cell1 = '<div class="redni-broj">'+i+'</div>';
			var cell2 = '<input type="text" class="awesomplete center-text input-small" name="proizvod'+i+'" id="proizvod'+i+'" list="proizvodi" size="34" placeholder="Izaberi Artikal" required onChange="autofillProizvoda(this,'+"'"+i+"','narudzbenica','"+id_magacina+"')"+'">';
			var cell3 = '<input type="text" class="center-text input-small" name="kolicina'+i+'" size="5" placeholder="kolicina" required>';			
			var cell4 = '<input type="text" class="center-text input-small" name="cena_proizvoda'+i+'" id="cena-proizvoda'+i+'" size="5" placeholder="cena" required>';

			var cell5 = '<input type="text" class="center-text input-small" name="tezina'+i+'" id="tezina'+i+'" size="5" placeholder="tezina" required>';
			var cell6 = '<input type="text" class="center-text input-small" name="broj-paketa'+i+'" id="broj-paketa'+i+'" size="3" placeholder="paketi" required>';

			var cell7 = '<input type="text" class="center-text input-small" name="stanje'+i+'" id="stanje'+i+'" size="5" placeholder="stanje" disabled>';
			var cell8 = '<div class="broj center-text input-small plus" id="plus'+i+'" onclick="createNewInput('+"'"+i+"','"+id_magacina+"')"+'">+</div>';
			var div_end = '</div>';		

			$('#proizvodi-za-porudzbinu').append(div_start+cell1+cell2+cell3+cell4+cell5+cell6+cell7+cell8+div_end);
			$("#broj_artikala").val(i);
			if(i>1){ //ako proizvod nije prvi brise se dugme "+" sa prethodnog proizvoda
				var ii = i-1;
				$("#plus"+ii).css("display","none");
			}
		}	

	}


	function redirect_to(object){
		var selected = object.value;
		var id_proizvoda = $('#datalist_edit').find('option[value="'+ selected +'"]').attr('id');
		location.href = "edit-proizvoda.php?id-proizvoda="+id_proizvoda;
	}

//STARO
	function autofillArtikal(art,i,doc){
		var artikal = art.value;
		var artikal = encodeURIComponent(artikal);
		var id = art.id;
		
		if(doc=="faktura"){ //Ako je poziv funkcije iz "Fakture"

			var xhr = new XMLHttpRequest();
			xhr.open("get", "ajax_response.php?artikal="+artikal+"&i="+i, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				$("#alert").html(odgovor);
			}
			
		}
		
		if(doc=="edit"){ //Ako je poziv funkcije iz "Podesavanja"

			var xhr = new XMLHttpRequest();
			xhr.open("get", "ajax_response.php?artikal="+artikal+"&i="+i+"&page=settings", false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				$("#alert").html(odgovor);
			}
		}


		//Ako je duzina teksta unetog u imput polje duza od 36 karaktera onda se input polje pretvara u textarea
		artikal = decodeURIComponent(artikal); 
		var n = artikal.length;
		if(n>35){
			var texbox = $("#artikal"+i);
			var rows = parseInt(n/35)+1;
			var onblur = 'onblur="autofillArtikal(this,'+"'"+i+"','faktura'"+')"';

			var textarea = "<textarea "+onblur+" class='artikal-input' name='artikal"+i+"'  id='textarea"+i+"' rows='"+rows+"' cols='34'  required>"+artikal+"</textarea>";
 
			if(n>2000){
				var textarea = "<textarea "+onblur+" class='artikal-input' name='artikal"+i+"'  id='textarea"+i+"' rows='1' cols='34'  required>"+alert('Maksimalan broj karaktera je 2000.')+"</textarea>"; 
			}
			$("#artikal"+i).each(function() {
			    var style = $(this).attr('style'); 
			    $(this).replaceWith(textarea);
			});
		}


		function artikli(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "artikal.php?artikal="+artikal, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("hidden").value = odgovor;
			}
		}
		
		function mera(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "artikal.php?mera="+artikal, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				if(doc=="faktura"){
					document.getElementById("mera"+i).innerHTML = odgovor;
				}
				document.getElementById("mera").value = odgovor;
			}
		}
		
		function cena(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "artikal.php?cena="+artikal, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				if(doc=="faktura"){
					document.getElementById("cena"+i).innerHTML = odgovor;
				}
				document.getElementById("cena"+i).value = odgovor;
			}
		}
		
		function pdv(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "artikal.php?pdv="+artikal, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("pdv").value = odgovor;
			}
		}
		
		function statusArtikla(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "artikal.php?statusArtikla="+artikal, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("blockArtical").checked = odgovor;	
			}
		}
	}

	function kes(period){ //ukoliko je valuta placanja "1" (placanje u gotovini) dodeljuje se rabat ("kasa skonto") 5%
		if(period == "1"){
			$("#skonto").val("5");
			$("#skontoHidden").val("5");
			racun(5);
		}
		else{
			$("#skonto").val("0");
			$("#skontoHidden").val("0");
			racun(0);
		}
	}
	
	function calculate(kolicina,cena,id){
		//Obracun i popunjavanje za pojedinacne artikle
	
		if(kolicina == 0){
			var kolicina = $("#kolicina"+id).val();
		}
		if(cena == 0){
			var cena = $("#cena"+id).val();
		}

		var stopaPDV = $("#stopaPDV"+id).html();
		if(stopaPDV==""){
			var stopaPDV = $("#stopaPDV"+id).val();
		}

//		var skonto = $("#skonto").val();
		var skonto = 0;
		var rab = $("#rabat").val();
		var rabat = Number(skonto) + Number(rab);

		var vrednost = (Number(kolicina) * Number(cena)).toFixed(2);
		
		//var osnovicaPDV = (Number(kolicina) * Number(cena)).toFixed(2);
		var osnovicaPDV = (Number(kolicina) * Number(cena) * (1 - rabat/100)).toFixed(2);
		var rabatPojedinacni = (Number(kolicina) * Number(cena) * rabat/100).toFixed(2);

		var obracunPDV = (osnovicaPDV * stopaPDV / 100).toFixed(2);
		var iznos = (Number(osnovicaPDV) + Number(obracunPDV)).toFixed(2);
		
		$("#vrednost"+id).html(vrednost);
		$("#rabatprocent"+id).html(rabatPojedinacni);
		$("#osnovica"+id).html(osnovicaPDV);
		$("#pdv"+id).html(obracunPDV);
		$("#iznos"+id).html(iznos);
		
		
		var i=1;

//		var a = document.getElementById("tabela").rows.length;  //broj redova tabele
//		a = a-6;  //od ukupnog broja redova oduzimaju se donjih 6 posto ne sadreze artikl/kolicina/cena
//		a = a-15;
		var a = document.getElementById("tabela").getElementsByClassName("usluga-artikal").length;
		var neto = 0;
		var netoukupno = 0;
		var rabatpojedin = 0;
		var rabatukup = 0;
		var snovica = 0;
		var pdv = 0;
		var ukupnoPDV = 0;
		var ukupno = 0;
		
		//Ukupno bez rabata
		while (i<a){
			neto = Number($("#kolicina"+i).val()) * Number($("#cena"+i).val());
			netoukupno += Number(neto);

			rabatpojedin = Number($("#rabatprocent"+i).html());
			rabatukup += rabatpojedin;

			osnovica = Number($("#osnovica"+i).html());
			ukupno += osnovica;
			
			pdv = Number($("#pdv"+i).html());
			ukupnoPDV += pdv;
			i++;
		} 
		
		var netoUkupno = (netoukupno).toFixed(2);
		var rabatUkup = (rabatukup).toFixed(2);
		var osnovicaUkupno = (ukupno).toFixed(2);
		var pdvUkupno = (ukupnoPDV).toFixed(2);
		
		var ukupnoIznos = (Number(ukupno) + Number(ukupnoPDV)).toFixed(2);

 
		//Ukupno za uplatu (sa rabatom)
		/*
		var zaUplatuOsnovica = (ukupno-ukupno*rabat/100).toFixed(2);
		var zaUplatuPDV = (ukupnoPDV-ukupnoPDV*rabat/100).toFixed(2);
		var zaUplatuIznos = (Number(zaUplatuOsnovica) + Number(zaUplatuPDV)).toFixed(2);
		*/
		var zaUplatuOsnovica = (ukupno).toFixed(2);
		var zaUplatuPDV = (ukupnoPDV).toFixed(2);
		var zaUplatuIznos = (Number(zaUplatuOsnovica) + Number(zaUplatuPDV)).toFixed(2);



		if(zaUplatuIznos !== ""){
			slovima(zaUplatuIznos);
		}
		
		if (Number(zaUplatuIznos) > 99000){
			$("#zaUplatuOsnovica").css({"font-size":"12px", "font-weight":"bold"});
			$("#zaUplatuPDV").css({"font-size":"14px", "font-weight":"bold"});
			$("#zaUplatuIznos").css({"font-size":"16px", "font-weight":"bold"});
		}
		

		$("#ukupnoNet").html(netoUkupno);
		$("#ukupnoRabati").html(rabatUkup);
		$("#ukupnoOsnovica").html(osnovicaUkupno);
		$("#ukupnoPDV").html(pdvUkupno);
		$("#ukupnoIznos").html("<b>"+ukupnoIznos+"</b>");
		
		$("#ukupnoRabat").html(rabat);
	//	$("#rabatDinari").html((ukupnoIznos * rabat/100).toFixed(2));
	//	$("#rabatDinari").html(rabatukup);
		
		$("#zaUplatuOsnovica").html(zaUplatuOsnovica);
		$("#zaUplatuPDV").html(zaUplatuPDV);
		$("#zaUplatuIznos").html(zaUplatuIznos);
	//	alert(netoUkupno+"/"+rabatUkup+"/"+osnovicaUkupno+"/"+pdvUkupno+"/"+ukupnoIznos+"/"+rabat+"/"+zaUplatuOsnovica+"/"+zaUplatuPDV+"/"+zaUplatuIznos);		
	}

	function row_calculate(kolicina,cena,id){
		//Obracun i popunjavanje za pojedinacne artikle
		if(kolicina == 0){
			var kolicina = $("#kolicina"+id).val();
		}
		if(cena == 0){
			var cena = $("#cena"+id).val();
		}

		var stopaPDV = $("#stopaPDV"+id).html();

		var rabat = 0;

		var vrednost = (Number(kolicina) * Number(cena)).toFixed(2);
		
		//var osnovicaPDV = (Number(kolicina) * Number(cena)).toFixed(2);
		var osnovicaPDV = (Number(kolicina) * Number(cena) * (1 - rabat/100)).toFixed(2);
//		var rabatPojedinacni = (Number(kolicina) * Number(cena) * rabat/100).toFixed(2);

		var obracunPDV = (osnovicaPDV * stopaPDV / 100).toFixed(2);
		var iznos = (Number(osnovicaPDV) + Number(obracunPDV)).toFixed(2);
		
		$("#vrednost"+id).html(vrednost);
		$("#rabatprocent"+id).html("0%");
		$("#osnovica"+id).html(osnovicaPDV);
		$("#pdv"+id).html(obracunPDV);
		$("#iznos"+id).html(iznos);
	}

	function racunRabat(proc){

		var rowCount = $('#tabela tr').length;
		var rows = rowCount - 8;

		var id = 1;
		while(id <= rows){
			var kolicina =  $("#kolicina"+id).val();
			var cena = $("#cena"+id).val();
			//alert(kolicina + " " + cena);
			calculate(kolicina,cena,id);

			id++;
		}
		//alert(rows);
 
	}
	
	//Korekcija ukupnog iznosa za uplatu zavisno od rabata
	function racun(proc){ 
		var rabat = $("#rabat").val();
		racunRabat(rabat);

/*		var ukupnoIznos = $("#ukupnoIznos").html();
		var ukupnoOsnovica = $("#ukupnoOsnovica").html();
		var ukupnoPDVa = $("#ukupnoPDV").html(); 
		var rabat = $("#rabat").val();
		var skonto = $("#skonto").val();
		var procent = Number(rabat) + Number(skonto);
		
		var zaUplatuOsnovica = (ukupnoOsnovica - ukupnoOsnovica * procent/100).toFixed(2);
		var zaUplatuPDV = (ukupnoPDVa - ukupnoPDVa * procent/100).toFixed(2);
		var zaUplatuIznos = (Number(zaUplatuOsnovica) + Number(zaUplatuPDV)).toFixed(2);

		
		if(zaUplatuIznos !== ""){
			slovima(zaUplatuIznos);
		}
		
		$("#skontoHidden").val(skonto);
		$("#ukupnoRabat").html(procent);
		$("#rabatDinari").html((ukupnoIznos * procent/100).toFixed(2));
		
		$("#zaUplatuOsnovica").html(zaUplatuOsnovica);
		$("#zaUplatuPDV").html(zaUplatuPDV);
		$("#zaUplatuIznos").html(zaUplatuIznos);
*/
	}


	
	function upis(vrednost,id){
		if(isNaN(vrednost)){
			alert('Uneta vrednost mora biti u formatu broja (npr. 123.45)');
		}
		else{
			var xhr = new XMLHttpRequest();
			xhr.open("get", "uplata.php?v="+vrednost+"&i="+id, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("uplaceno"+id).innerHTML = odgovor;
				document.getElementById("uplata"+id).value = "";
			}
		}
	}

	function upis_ulaz(vrednost,id){
		if(isNaN(vrednost)){
			alert('Uneta vrednost mora biti u formatu broja (npr. 123.45)');
		}
		else{
			var xhr = new XMLHttpRequest();
			xhr.open("get", "../ajax_response.php?v="+vrednost+"&i="+id+"&page=arhiva-ulaz", false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				/*
				document.getElementById("uplaceno"+id).innerHTML = odgovor;
				document.getElementById("uplata"+id).value = "";
				*/
				location.reload();
			}
		}
	}


	function autofill(g,s){
	
		var f=g.value;
		
		var p = encodeURIComponent(f);

		

		if(s=="edit"){
			kupac_autofill();
		}
		else if(s=="faktura"){
			if(p !== ""){
				kupac_autofill();
			}
		}
  
		function kupac_autofill(){
			
			var xhr = new XMLHttpRequest();
			xhr.open("get", "ajax_response.php?t="+p+"&z="+s, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				$("#alert").html(odgovor);
			}
			
		}


 

		function firma(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "firma.php?t="+p+"&z="+s, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("firmaHidden").value = odgovor;
			}
		}
		
		function status1(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "status1.php?t="+p+"&z="+s, false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("block").checked = odgovor;				
			}
		}


	}  
	

	
	function autofillKorisnik(g){
	
		var f=g.value;
		
		var p = encodeURIComponent(f);

		korisnik_autofill();
		

		function korisnik_autofill(){

			var xhr = new XMLHttpRequest();
			xhr.open("get", "ajax_response.php?t="+p+"&z=editKorisnika", false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				$("#alert").html(odgovor);
			}

		}


		
		function statusKorisnika(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "korisnikAutofill.php?t="+p+"&z=status", false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("status_korisnika").checked = odgovor
			}
		}
		
		function admins(){
			var xhr = new XMLHttpRequest();
			xhr.open("get", "korisnikAutofill.php?t="+p+"&z=admins", false);
			xhr.send();
			var odgovor = xhr.responseText;
			if(odgovor!==""){
				document.getElementById("admins").innerHTML = odgovor
			}
		}
		
	}  
	
	
	