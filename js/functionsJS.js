	/*Hamburger Menu*/
/*	var isActive = false;

	$('.mobile-js-menu').on('click', function() {
		if (isActive) {
			$(this).removeClass('active');
			$('body').removeClass('menu-open');
		} else {
			$(this).addClass('active');
			$('body').addClass('menu-open');
		}

		isActive = !isActive;
	});
*/	/*END Hamburger Menu*/
 

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
	
	
	