<?php

class InsertData {

	private $db;
	private $status = '1';
	private $ststus_blokiran = '0';

	function __construct($DB_con) {
		$this->db = $DB_con;
	}

	private function insert_data($query, $stmtArray, $bind) {
		try {
			$stmt = $this->db->prepare($query);

			foreach ($stmtArray as $key => $val) {
				$stmt->$bind($key, $val, PDO::PARAM_STR);
			}

			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (DOException $e) {
			echo $e->getMessage();
		}
	}

	public function unos_proizvoda($id_korisnika, $naziv_proizvoda, $cena_proizvoda, $tezina_proizvoda, $cena_saradnika, $id_magacina, $kolicina_u_magacinu) {
		$query = "INSERT INTO proizvodi (id_korisnika, naziv_proizvoda, cena_proizvoda, tezina_proizvoda, cena_saradnika, id_magacina, kolicina_u_magacinu) VALUES (:id_korisnika, :naziv_proizvoda, :cena_proizvoda, :tezina_proizvoda, :cena_saradnika, :id_magacina, :kolicina_u_magacinu)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"naziv_proizvoda" => $naziv_proizvoda,
			"cena_proizvoda" => $cena_proizvoda,
			"tezina_proizvoda" => $tezina_proizvoda,
			"cena_saradnika" => $cena_saradnika,
			"id_magacina" => $id_magacina,
			"kolicina_u_magacinu" => $kolicina_u_magacinu,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function unos_saradnika($id_korisnika, $ime_saradnika, $prezime_saradnika) {
		$query = "INSERT INTO saradnici (id_korisnika, ime_saradnika, prezime_saradnika) VALUES (:id_korisnika, :ime_saradnika, :prezime_saradnika)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"ime_saradnika" => $ime_saradnika,
			"prezime_saradnika" => $prezime_saradnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function unos_grada($ime_grada, $postanski_broj) {
		$query = "INSERT INTO gradovi (ime_grada, postanski_broj) VALUES (:ime_grada, :postanski_broj)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"ime_grada" => $ime_grada,
			"postanski_broj" => $postanski_broj,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function unos_usera($user_ime, $user_prezime, $username, $password, $email, $id_korisnika, $status) {
		$query = "INSERT INTO admin (user_ime, user_prezime, username, password, email, id_korisnika, status) VALUES (:user_ime, :user_prezime, :username, :password, :email, :id_korisnika, :status)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"user_ime" => $user_ime,
			"user_prezime" => $user_prezime,
			"username" => $username,
			"password" => $password,
			"email" => $email,
			"id_korisnika" => $id_korisnika,
			"status" => $status,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function insert_new_porudzbina($id_korisnika, $datum, $id_magacina, $ime_i_prezime, $mesto, $adresa, $telefon, $id_saradnika, $prevoznik, $artikliKomadi, $ukupno, $user, $napomena) {
		$query = "INSERT INTO porudzbine (id_korisnika, datum, id_magacina, ime_i_prezime, mesto, adresa, telefon, id_saradnika, prevoznik, artikliKomadi, ukupno, user, napomena, status) VALUES (:id_korisnika, :datum, :id_magacina, :ime_i_prezime, :mesto, :adresa, :telefon, :id_saradnika, :prevoznik, :artikliKomadi, :ukupno, :user, :napomena, :status)";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"datum" => $datum,
			"id_magacina" => $id_magacina,
			"ime_i_prezime" => $ime_i_prezime,
			"mesto" => $mesto,
			"adresa" => $adresa,
			"telefon" => $telefon,
			"id_saradnika" => $id_saradnika,
			"prevoznik" => $prevoznik,
			"artikliKomadi" => $artikliKomadi,
			"ukupno" => $ukupno,
			"user" => $user,
			"napomena" => $napomena,
			"status" => $this->status,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_porudzbina_by_id($datum, $id_magacina, $ime_i_prezime, $mesto, $adresa, $telefon, $id_saradnika, $prevoznik, $broj_posiljke, $artikliKomadi, $ukupno, $user, $napomena, $status, $id_korisnika, $id_narudzbine) {
		$query = "UPDATE porudzbine SET datum=:datum, id_magacina=:id_magacina, ime_i_prezime=:ime_i_prezime, mesto=:mesto, adresa=:adresa, telefon=:telefon, id_saradnika=:id_saradnika, prevoznik=:prevoznik, broj_posiljke=:broj_posiljke, artikliKomadi=:artikliKomadi, ukupno=:ukupno, user=:user, napomena=:napomena, status=:status WHERE id_korisnika=:id_korisnika AND id_narudzbine=:id_narudzbine";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"datum" => $datum,
			"id_magacina" => $id_magacina,
			"ime_i_prezime" => $ime_i_prezime,
			"mesto" => $mesto,
			"adresa" => $adresa,
			"telefon" => $telefon,
			"id_saradnika" => $id_saradnika,
			"prevoznik" => $prevoznik,
			"broj_posiljke" => $broj_posiljke,
			"artikliKomadi" => $artikliKomadi,
			"ukupno" => $ukupno,
			"user" => $user,
			"napomena" => $napomena,
			"status" => $status,
			"id_korisnika" => $id_korisnika,
			"id_narudzbine" => $id_narudzbine,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_stanje_proizvoda($kolicina_u_magacinu, $id_proizvoda, $id_korisnika) {
		$query = "UPDATE proizvodi SET kolicina_u_magacinu=:kolicina_u_magacinu WHERE id_proizvoda=:id_proizvoda AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"kolicina_u_magacinu" => $kolicina_u_magacinu,
			"id_proizvoda" => $id_proizvoda,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_proizvoda($naziv_proizvoda, $cena_proizvoda, $tezina_proizvoda, $cena_saradnika, $id_magacina, $kolicina_u_magacinu, $sifra_proizvoda, $id_proizvoda, $id_korisnika) {
		$query = "UPDATE proizvodi SET naziv_proizvoda=:naziv_proizvoda, cena_proizvoda=:cena_proizvoda, tezina_proizvoda=:tezina_proizvoda, cena_saradnika=:cena_saradnika, id_magacina=:id_magacina, kolicina_u_magacinu=:kolicina_u_magacinu, sifra_proizvoda=:sifra_proizvoda WHERE id_proizvoda=:id_proizvoda AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"naziv_proizvoda" => $naziv_proizvoda,
			"cena_proizvoda" => $cena_proizvoda,
			"tezina_proizvoda" => $tezina_proizvoda,
			"cena_saradnika" => $cena_saradnika,
			"id_magacina" => $id_magacina,
			"kolicina_u_magacinu" => $kolicina_u_magacinu,
			"sifra_proizvoda" => $sifra_proizvoda,
			"id_proizvoda" => $id_proizvoda,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

/*STARO*/
	public function verify_user($id_admin) {

		$query = "UPDATE admin SET status=:status WHERE id_admin=:id_admin";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"status" => "2",
			"id_admin" => $id_admin,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function insert_new_faktura($broj_fakture, $id_korisnika, $kupac_id, $datum_prometa, $valuta, $ukupno, $username) {
		$query = "INSERT INTO fakture (broj_fakture, id_korisnika, kupac_id, datum_prometa, valuta, ukupno,  username, izvod, status) VALUES (:broj_fakture, :id_korisnika, :kupac_id, :datum_prometa, :valuta, :ukupno, :username, '0', :status)";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"broj_fakture" => $broj_fakture,
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"datum_prometa" => $datum_prometa,
			"valuta" => $valuta,
			"ukupno" => $ukupno,
			"username" => $username,
			"status" => $this->status,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function insert_new_izvod($broj_izvoda, $id_korisnika, $kupac_id, $username, $uplata, $datum_prometa) {
		$query = "INSERT INTO fakture (broj_izvoda, id_korisnika, kupac_id, datum_prometa, username, uplata, izvod, status) VALUES (:broj_izvoda, :id_korisnika, :kupac_id, :datum_prometa, :username, :uplata, '1', :status)";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"broj_izvoda" => $broj_izvoda,
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"datum_prometa" => $datum_prometa,
			"username" => $username,
			"uplata" => $uplata,
			"status" => $this->status,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function edit_faktura_by_id($broj_fakture, $datum_prometa, $valuta, $ukupno, $username, $id_fakture) {
		$query = "UPDATE fakture SET broj_fakture=:broj_fakture, datum_prometa=:datum_prometa, valuta=:valuta, ukupno=:ukupno, username=:username WHERE id_fakture=:id_fakture";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"broj_fakture" => $broj_fakture,
			"datum_prometa" => $datum_prometa,
			"valuta" => $valuta,
			"ukupno" => $ukupno,
			"username" => $username,
			"id_fakture" => $id_fakture,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function edit_izvod_by_id($broj_izvoda, $datum_prometa, $valuta, $uplata, $username, $id_fakture) {
		$query = "UPDATE fakture SET broj_izvoda=:broj_izvoda, datum_prometa=:datum_prometa, valuta=:valuta, uplata=:uplata, username=:username WHERE id_fakture=:id_fakture";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"broj_izvoda" => $broj_izvoda,
			"datum_prometa" => $datum_prometa,
			"valuta" => $valuta,
			"uplata" => $uplata,
			"username" => $username,
			"id_fakture" => $id_fakture,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_faktura($id_korisnika, $kupac_id, $broj_otpremnice, $broj_prijemnice, $nacin, $datumPrometa, $valuta, $datum, $mestoFakture, $artikliKomadi, $rabat, $slovima, $ukupno, $napomena, $br_predracuna, $username) {
		$query = "UPDATE fakture SET id_korisnika=:id_korisnika, kupac_id=:kupac_id, broj_otpremnice=:broj_otpremnice, broj_prijemnice=:broj_prijemnice, nacin=:nacin, datumPrometa=:datumPrometa, valuta=:valuta, datum=:datum, mestoFakture=:mestoFakture, artikliKomadi=:artikliKomadi, rabat=:rabat, slovima=:slovima, ukupno=:ukupno, napomena=:napomena, br_predracuna=:br_predracuna, status=:status  WHERE username=:username AND status='0'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"broj_otpremnice" => $broj_otpremnice,
			"broj_prijemnice" => $broj_prijemnice,
			"nacin" => $nacin,
			"datumPrometa" => $datumPrometa,
			"valuta" => $valuta,
			"datum" => $datum,
			"mestoFakture" => $mestoFakture,
			"artikliKomadi" => $artikliKomadi,
			"rabat" => $rabat,
			"slovima" => $slovima,
			"ukupno" => $ukupno,
			"napomena" => $napomena,
			"br_predracuna" => $br_predracuna,
			"status" => $this->status,
			"username" => $username,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_faktura_by_id($broj_fakture, $id_korisnika, $kupac_id, $broj_otpremnice, $broj_prijemnice, $nacin, $datumPrometa, $valuta, $datum, $mestoFakture, $artikliKomadi, $rabat, $slovima, $ukupno, $napomena, $username, $uplata, $br_predracuna, $id_fakture) {
		$query = "UPDATE fakture SET broj_fakture=:broj_fakture, id_korisnika=:id_korisnika, kupac_id=:kupac_id, broj_otpremnice=:broj_otpremnice, broj_prijemnice=:broj_prijemnice, nacin=:nacin, datumPrometa=:datumPrometa, valuta=:valuta, datum=:datum, mestoFakture=:mestoFakture, artikliKomadi=:artikliKomadi, rabat=:rabat, slovima=:slovima, ukupno=:ukupno, napomena=:napomena, username=:username, uplata=:uplata, br_predracuna=:br_predracuna, status=:status  WHERE id_fakture=:id_fakture";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"broj_fakture" => $broj_fakture,
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"broj_otpremnice" => $broj_otpremnice,
			"broj_prijemnice" => $broj_prijemnice,
			"nacin" => $nacin,
			"datumPrometa" => $datumPrometa,
			"valuta" => $valuta,
			"datum" => $datum,
			"mestoFakture" => $mestoFakture,
			"artikliKomadi" => $artikliKomadi,
			"rabat" => $rabat,
			"slovima" => $slovima,
			"ukupno" => $ukupno,
			"napomena" => $napomena,
			"username" => $username,
			"uplata" => $uplata,
			"br_predracuna" => $br_predracuna,
			"status" => $this->status,
			"id_fakture" => $id_fakture,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_faktura_uplata($uplata, $id_fakture, $id_korisnika) {
		$query = "UPDATE fakture SET uplata=:uplata  WHERE id_fakture=:id_fakture AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"uplata" => $uplata,
			"id_fakture" => $id_fakture,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_kupca($naziv_kupca, $adresa_kupca, $mesto_kupca, $pib_kupca, $mat_broj, $ziro_racun, $email, $status_kupca, $id_kupca, $id_korisnika) {
		$query = "UPDATE kupci SET naziv_kupca=:naziv_kupca, adresa_kupca=:adresa_kupca, mesto_kupca=:mesto_kupca, pib_kupca=:pib_kupca, mat_broj=:mat_broj, ziro_racun='$ziro_racun', email='$email', status_kupca=:status_kupca  WHERE id_kupca=:id_kupca AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"naziv_kupca" => $naziv_kupca,
			"adresa_kupca" => $adresa_kupca,
			"mesto_kupca" => $mesto_kupca,
			"pib_kupca" => $pib_kupca,
			"mat_broj" => $mat_broj,
			"status_kupca" => $status_kupca,
			"id_kupca" => $id_kupca,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function insert_new_kupac($id_korisnika, $naziv_kupca, $adresa_kupca, $mesto_kupca, $pib_kupca, $mat_broj, $ziro_racun, $email) {

		$query = "INSERT INTO kupci (id_korisnika, naziv_kupca,adresa_kupca,mesto_kupca,pib_kupca,mat_broj, ziro_racun, email, status_kupca) VALUE(:id_korisnika, :naziv_kupca, :adresa_kupca, :mesto_kupca, :pib_kupca, :mat_broj, :ziro_racun, :email, :status_kupca)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"naziv_kupca" => $naziv_kupca,
			"adresa_kupca" => $adresa_kupca,
			"mesto_kupca" => $mesto_kupca,
			"pib_kupca" => $pib_kupca,
			"mat_broj" => $mat_broj,
			"ziro_racun" => $ziro_racun,
			"email" => $email,
			"status_kupca" => $this->status,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

/*
public function insert_artikal($id_korisnika,$artikal,$jedinica_mere,$cena,$pdv){
$query = "INSERT INTO artikli (id_korisnika, artikal, jedinica_mere, cena, pdv, status_artikla) VALUE (:id_korisnika, :artikal, :jedinica_mere, :cena, :pdv, :status_artikla)";

$stmt = $this->db->prepare($query);
$stmtArray = array(
"id_korisnika" => $id_korisnika,
"artikal" => $artikal,
"jedinica_mere" => $jedinica_mere,
"cena" => $cena,
"pdv" => $pdv,
"status_artikla" => $this->status
);

return $this->insert_data($query,$stmtArray,"bindValue");
}
 */

	public function update_artikla($artikal, $jedinica_mere, $cena, $pdv, $status_artikla, $id_artikla, $id_korisnika) {
		$query = "UPDATE artikli SET artikal=:artikal, jedinica_mere=:jedinica_mere, cena=:cena, pdv=:pdv, status_artikla=:status_artikla  WHERE id_artikla=:id_artikla AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"artikal" => $artikal,
			"jedinica_mere" => $jedinica_mere,
			"cena" => $cena,
			"pdv" => $pdv,
			"status_artikla" => $status_artikla,
			"id_artikla" => $id_artikla,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function insert_korisnika($korisnik, $adresa, $mesto, $maticni_broj, $pib, $sifra_delatnosti, $telefon, $fax, $tekuci_racun, $banka, $logo, $dodatak_broju, $status) {
		$query = "INSERT INTO korisnici (korisnik, adresa, mesto, maticni_broj, pib, sifra_delatnosti, telefon, fax, tekuci_racun, banka, logo, dodatak_broju, status) VALUE (:korisnik, :adresa, :mesto, :maticni_broj, :pib, :sifra_delatnosti, :telefon, :fax, :tekuci_racun, :banka, :logo, :dodatak_broju, :status)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"korisnik" => $korisnik,
			"adresa" => $adresa,
			"mesto" => $mesto,
			"maticni_broj" => $maticni_broj,
			"pib" => $pib,
			"sifra_delatnosti" => $sifra_delatnosti,
			"telefon" => $telefon,
			"fax" => $fax,
			"tekuci_racun" => $tekuci_racun,
			"banka" => $banka,
			"logo" => $logo,
			"dodatak_broju" => $dodatak_broju,
			"status" => $status,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function update_korisnika($korisnik, $adresa, $mesto, $maticni_broj, $pib, $sifra_delatnosti, $telefon, $fax, $tekuci_racun, $banka, $logo, $dodatak_broju, $status, $id_korisnika) {
		$query = "UPDATE korisnici SET korisnik=:korisnik, adresa=:adresa, mesto=:mesto, maticni_broj=:maticni_broj, pib=:pib, sifra_delatnosti=:sifra_delatnosti, telefon=:telefon, fax=:fax, tekuci_racun=:tekuci_racun, banka=:banka, logo=:logo, dodatak_broju=:dodatak_broju, status=:status  WHERE id_korisnika=:id_korisnika ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"korisnik" => $korisnik,
			"adresa" => $adresa,
			"mesto" => $mesto,
			"maticni_broj" => $maticni_broj,
			"pib" => $pib,
			"sifra_delatnosti" => $sifra_delatnosti,
			"telefon" => $telefon,
			"fax" => $fax,
			"tekuci_racun" => $tekuci_racun,
			"banka" => $banka,
			"logo" => $logo,
			"dodatak_broju" => $dodatak_broju,
			"status" => $status,
			"id_korisnika" => $id_korisnika,
		);
	}

	public function update_admina($username, $upisPassworda, $status, $id_admin, $id_korisnika) {
		$query = "UPDATE admin SET username=:username, $upisPassworda status=:status WHERE id_admin=:id_admin AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"username" => $username,
			"status" => $status,
			"id_admin" => $id_admin,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}

	public function insert_admin($username, $password, $id_korisnika, $status) {
		$query = "INSERT INTO admin (username, password, id_korisnika, status) VALUE (:username, :password, :id_korisnika, :status)";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"username" => $username,
			"password" => $password,
			"id_korisnika" => $id_korisnika,
			"id_korisnika" => $id_korisnika,
		);

		return $this->insert_data($query, $stmtArray, "bindValue");
	}
}