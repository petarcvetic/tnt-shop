 		<div class="unos-box">

			<div id="alert"></div>



			<form method="post" action="" class="unos-form">



				<div class="dobavljac-box">

					<fieldset>

		    		<legend>&nbsp; <b> DOBAVLJAČ </b> &nbsp; </legend>
		    		<div class="input-row">
						DOBAVLJAČ: <input type="text" class="awesomplete input-right input-kupac" onblur="autofill(this,'faktura')" name="firma" id="firma" list="kupci" value="<?php if (isset($_POST['firma'])) {echo $_POST['firma'];}?>"  autofocus required><br>

						<datalist id="kupci">

							<?php

$kupci = $getData->get_kupci($idKorisnika);

foreach ($kupci as $row) {

	echo "<option>" . $row['naziv_kupca'] . '</option>';

}

?>

						</datalist>
					</div>
					<div class="input-row">
						ADRESA:<input id="adresa" name="adresa" type="text" class="input-right input-kupac"><br>
					</div>
					<div class="input-row">
						MESTO:<input id="mesto" name="mesto" type="text" class="input-right input-kupac" ><br>
					</div>
					<div class="input-row">
						PIB:<input id="pib" name="pib" type="text" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						MAR. BR <input id="matBr" name="matBr" type="text" class="input-right input-kupac" required><br>
					</div>
					<div class="input-row">
						EMAIL: <input id="email" name="email" type="email" class="input-right input-kupac" ><br>
					</div>
					<div class="input-row">
						ŽIRO RAČUN: <input id="ziro-racun" name="ziro-racun" type="text" class="input-right input-kupac" required>
					</div>
						<input type="hidden" id="status" name="status" value="1">

					</fieldset>

				</div><!-- END dobavljac-box -->





				<div class="fakture-izvodi">



					<div class="float-left fakture-box">

						<fieldset>

		    			<legend>&nbsp; <b> FAKTURA </b> &nbsp; </legend>
		    			<div class="input-row">
		    				BROJ FAKTURE: <input type="text" name="br-fakture" class="input-right" size="35"><br>
		    			</div>
		    			<div class="input-row">
		    				IZNOS SA PDV-om: <input type="text" name="iznos-fakture" class="input-right" size="35"><br>
		    			</div>
		    			<div class="input-row">
		    				DATUM: <input type="date" name="datum-fakture" class="input-right" value="<?php echo $today; ?>" size="35"><br>
		    			</div>
<!--
		    			<div class="input-row">
		    				VALUTA: <input type="text" name="valuta-fakture" class="input-right" size="35"><br><br>
		    			</div>
-->
		    				<input type="submit" name="unos-fakture" class="submit" value="UNESI FAKTURU">

		    			</fieldset>

					</div>



					<div class="float-right izvodi-box">

						<fieldset>

		    			<legend>&nbsp; <b> IZVOD </b> &nbsp; </legend>
		    			<div class="input-row">
		    				BROJ IZVODA: <input type="text" name="br-izvoda" class="input-right"><br>
		    			</div>
		    			<div class="input-row">
		    				DATUM UPLATE: <input type="date" name="datum-uplate" class="input-right" value="<?php echo $today; ?>"><br>
		    			</div>
		    			<div class="input-row">
		    				IZNOS UPLATE: <input type="text" name="iznos-uplate"class="input-right"><br><br>
		    			</div>
		    				<input type="submit" name="unos-izvoda" class="submit" value="UNESI IZVOD">

		    			</fieldset>

					</div>



				</div><!-- END fakture-izvodi -->



			</form>



		</div><!-- END unos-box -->



	<script type="text/javascript">

		//ENTER radi kao TAB

		$('body').on('keydown', 'input, select, textarea', function(e) {

			var self = $(this)

			  , form = self.parents('form:eq(0)')

			  , focusable

			  , next

			  ;

			if (e.keyCode == 13) {

			  focusable = form.find('input,a,select,button,textarea').filter(':visible');

			  next = focusable.eq(focusable.index(this)+1);

			  if (next.length) {

			    next.focus();

			  } else {

			    form.submit();

			  }

			  return false;

			}

		});

	</script>