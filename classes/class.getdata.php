<?php
class GetData {
	private $db;
	private $status = 1;
	private $ststus_blokiran = 0;

	function __construct($DB_con) {
		$this->db = $DB_con;
	}

	public function get_fetch_data($query, $stmtArray, $bind) {
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

	public function get_fetch_all($query, $stmtArray, $bind) {
		try {
			$stmt = $this->db->prepare($query);

			foreach ($stmtArray as $key => $val) {
				$stmt->$bind($key, $val, PDO::PARAM_STR);
			}

			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (DOException $e) {
			echo $e->getMessage();
		}
	}

	public function get_numRows($query, $stmtArray) {
		try {
			$stmt = $this->db->prepare($query);

			foreach ($stmtArray as $key => $val) {
				$stmt->bindParam($key, $val, PDO::PARAM_STR);
			}

			$stmt->execute();
			return $stmt->fetchColumn();
		} catch (DOException $e) {
			echo $e->getMessage();
		}
	}

	public function if_user_exists($username, $id_korisnika) {
		$query = "SELECT COUNT(*) FROM admin WHERE username='$username' AND id_korisnika='$id_korisnika'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_user_mail_exists($email) {
		$query = "SELECT COUNT(*) FROM admin WHERE email='$email'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_grad_exists($ime_grada) {
		$query = "SELECT COUNT(*) FROM gradovi WHERE ime_grada='$ime_grada'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_saradnik_exists($id_korisnika, $ime_saradnika, $prezime_saradnika) {
		$query = "SELECT COUNT(*) FROM saradnici WHERE id_korisnika='$id_korisnika' AND ime_saradnika='$ime_saradnika' AND prezime_saradnika='$prezime_saradnika'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_proizvod_exists($id_korisnika, $naziv_proizvoda, $id_magacina) {
		$query = "SELECT COUNT(*) FROM proizvodi WHERE id_korisnika='$id_korisnika' AND naziv_proizvoda='$naziv_proizvoda' AND id_magacina='$id_magacina'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_proizvod_id_exists($id_korisnika, $id_proizvoda, $id_magacina) {
		$query = "SELECT COUNT(*) FROM proizvodi WHERE id_korisnika='$id_korisnika' AND id_proizvoda='$id_proizvoda' AND id_magacina='$id_magacina'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_proizvod_id_exists_for_korisnik($id_korisnika, $id_proizvoda) {
		$query = "SELECT COUNT(*) FROM proizvodi WHERE id_korisnika='$id_korisnika' AND id_proizvoda='$id_proizvoda'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function if_porudzbina_id_exists($id_korisnika, $id_narudzbine) {
		$query = "SELECT COUNT(*) FROM porudzbine WHERE id_korisnika='$id_korisnika' AND id_narudzbine='$id_narudzbine'";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	public function get_magacini($id_korisnika) {
		$query = "SELECT * FROM magacini WHERE id_korisnika=:id_korisnika ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindParam");
//		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_magacin_by_id($id_magacina) {
		$query = "SELECT * FROM magacini WHERE id_magacina=:id_magacina ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_magacini_by_korisnik($id_korisnika) {
		$query = "SELECT * FROM magacini WHERE id_korisnika=:id_korisnika AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");
	}

	public function get_all_proizvodi($id_korisnika) {
		$query = "SELECT * FROM proizvodi WHERE id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_proizvodi_from_magacin($id_korisnika, $id_magacina) {
		$query = "SELECT * FROM proizvodi WHERE id_korisnika=:id_korisnika AND id_magacina=:id_magacina AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_proizvod_by_name($id_korisnika, $naziv_proizvoda, $id_magacina) {
		$query = "SELECT * FROM proizvodi WHERE id_korisnika=:id_korisnika AND naziv_proizvoda=:naziv_proizvoda AND id_magacina=:id_magacina AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"naziv_proizvoda" => $naziv_proizvoda,
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_proizvod_by_id($id_korisnika, $id_proizvoda, $id_magacina) {
		$query = "SELECT * FROM proizvodi WHERE id_korisnika=:id_korisnika AND id_proizvoda=:id_proizvoda AND id_magacina=:id_magacina AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_proizvoda" => $id_proizvoda,
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_proizvod_by_id_and_korisnik($id_korisnika, $id_proizvoda) {
		$query = "SELECT * FROM proizvodi WHERE id_korisnika=:id_korisnika AND id_proizvoda=:id_proizvoda";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_proizvoda" => $id_proizvoda,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_saradnici($id_korisnika) {
		$query = "SELECT * FROM saradnici WHERE id_korisnika=:id_korisnika AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_saradnik_by_ime_i_prezime($id_korisnika, $ime_saradnika, $prezime_saradnika) {
		$query = "SELECT * FROM saradnici WHERE id_korisnika=:id_korisnika AND ime_saradnika=:ime_saradnika AND prezime_saradnika=:prezime_saradnika AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"ime_saradnika" => $ime_saradnika,
			"prezime_saradnika" => $prezime_saradnika,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_saradnik_by_id($id_korisnika, $id_saradnika) {
		$query = "SELECT * FROM saradnici WHERE id_korisnika=:id_korisnika AND id_saradnika=:id_saradnika AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_saradnika" => $id_saradnika,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_gradovi() {
		$query = "SELECT * FROM gradovi ";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");
	}

	public function get_poslednja_posiljka($id_korisnika) {
		$query = "SELECT * FROM porudzbine WHERE id_korisnika=:id_korisnika ORDER BY id_narudzbine DESC LIMIT 1 OFFSET 0";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);
		return $this->get_fetch_data($query, $stmtArray, "bindParam");
	}

	public function get_porudzbine_by_magacin($id_korisnika, $id_magacina) {
		$query = "SELECT * FROM porudzbine WHERE id_korisnika=:id_korisnika AND id_magacina=:id_magacina";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_porudzbine_by_magacin_DESC($id_korisnika, $id_magacina) {
		$query = "SELECT * FROM porudzbine WHERE id_korisnika=:id_korisnika AND id_magacina=:id_magacina ORDER BY datum  DESC, id_narudzbine DESC";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}


	public function get_porudzbine_by_filter($id_korisnika, $id_magacina, $where) {
		$query = "SELECT * FROM porudzbine WHERE id_korisnika=:id_korisnika AND id_magacina=:id_magacina $where";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_magacina" => $id_magacina,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_troskovi_by_filter($id_korisnika, $where) {
		$query = "SELECT * FROM troskovi WHERE id_korisnika=:id_korisnika $where";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_trosak_by_id($id_korisnika, $id_troska) {
		$query = "SELECT * FROM troskovi WHERE id_korisnika=:id_korisnika AND id_troska=:id_troska";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_troska" => $id_troska,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_trosakovi_current_month($id_korisnika) {
		$query = "SELECT * FROM troskovi WHERE MONTH(datum_troska) = MONTH(CURRENT_DATE()) AND YEAR(datum_troska) = YEAR(CURRENT_DATE()) AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_trosakovi_current_year($id_korisnika) {
		$query = "SELECT * FROM troskovi WHERE YEAR(datum_troska) = YEAR(CURRENT_DATE()) AND id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}








	public function get_porudzbina_by_id($id_korisnika, $id_narudzbine) {
		$query = "SELECT * FROM porudzbine WHERE id_korisnika=:id_korisnika AND id_narudzbine=:id_narudzbine";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_narudzbine" => $id_narudzbine,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_prevoznici($id_korisnika) {
		$query = "SELECT * FROM prevoznici WHERE id_korisnika=:id_korisnika ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindParam");
	}

/* STARO */
	public function get_korisnik($id_korisnika) {
		$query = "SELECT * FROM korisnici WHERE id_korisnika=:id_korisnika";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindParam");
	}

	public function get_korisnik_by_name($korisnik) {
		$query = "SELECT * FROM korisnici WHERE korisnik=:korisnik";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"korisnik" => $korisnik,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindParam");
	}

	public function get_korisnici() {
		$query = "SELECT * FROM korisnici ";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");
	}

/*
public function if_faktura_exists($id_korisnika,$broj_fakture,$naziv_kupca,$datum){
$query = "SELECT COUNT(*) FROM fakture WHERE id_korisnika='$id_korisnika' AND broj_fakture=$broj_fakture AND naziv_kupca='$naziv_kupca'";
$stmt = $this->db->prepare($query);
$stmt->execute();

$count = $stmt->fetchColumn();
return $count;
}
 */

/**/
	public function if_faktura_exists($id_korisnika, $broj_fakture, $kupac_id) {
		$query = "SELECT COUNT(*) FROM fakture WHERE id_korisnika='$id_korisnika' AND broj_fakture='$broj_fakture' AND kupac_id='$kupac_id'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$count = $stmt->fetchColumn();
		return $count;
	}

/**/
	public function if_izvod_exists($id_korisnika, $broj_izvoda, $kupac_id) {
		$query = "SELECT COUNT(*) FROM fakture WHERE id_korisnika='$id_korisnika' AND broj_izvoda='$broj_izvoda' AND kupac_id='$kupac_id'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}
/**/
	public function get_faktura_po_broju_i_kupcu($id_korisnika, $broj_fakture, $kupac_id) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND broj_fakture=:broj_fakture AND kupac_id=:kupac_id AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"broj_fakture" => $broj_fakture,
			"kupac_id" => $kupac_id,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

/**/

	public function get_ukupan_broj_faktura_po_kupcu_i_datumu($id_korisnika, $kupac_id, $od_datuma, $do_datuma) {
		$query = "SELECT COUNT(*) FROM fakture WHERE id_korisnika='$id_korisnika' AND kupac_id='$kupac_id' AND STR_TO_DATE(datum_prometa, '%Y-%m-%d') BETWEEN '$od_datuma' AND '$do_datuma'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$count = $stmt->fetchColumn();
		return $count;
	}

/**/
	public function get_fakture_po_kupcu_i_datumu($id_korisnika, $kupac_id, $od_datuma, $do_datuma) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id AND STR_TO_DATE(datum_prometa, '%Y-%m-%d') BETWEEN :od_datuma AND :do_datuma ORDER BY STR_TO_DATE(datum_prometa, '%Y-%m-%d') DESC";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"od_datuma" => $od_datuma,
			"do_datuma" => $do_datuma,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

/**/

	public function get_fakture_po_kupcu_i_datumu_pagin($id_korisnika, $kupac_id, $od_datuma, $do_datuma, $start, $limit) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id AND STR_TO_DATE(datum_prometa, '%Y-%m-%d') BETWEEN :od_datuma AND :do_datuma ORDER BY STR_TO_DATE(datum_prometa, '%Y-%m-%d') ASC LIMIT $start, $limit";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"od_datuma" => $od_datuma,
			"do_datuma" => $do_datuma,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

/**/

	public function get_fakture_po_kupcu($id_korisnika, $kupac_id) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id";
		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_ukupan_broj_faktura($id_korisnika) {
		$query = "SELECT COUNT(*) FROM `fakture` WHERE `id_korisnika` = :id_korisnika AND `status`=:status";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"status" => $this->status,
		);
		return $this->get_numRows($query, $stmtArray);
	}

/**/
	public function get_ukupan_broj_kupaca($id_korisnika) {
		$query = "SELECT COUNT(*) FROM kupci WHERE id_korisnika = :id_korisnika AND status_kupca=:status";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"status" => $this->status,
		);

		return $this->get_numRows($query, $stmtArray);
	}

/**/
	public function get_faktura_po_id($id_korisnika, $id_fakture) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND id_fakture=:id_fakture ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_fakture" => $id_fakture,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_id_ulaz($id_korisnika, $id_fakture) {
		$query = "SELECT * FROM ulazne_fakture WHERE id_korisnika=:id_korisnika AND id_fakture=:id_fakture ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"id_fakture" => $id_fakture,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_broju($id_korisnika, $broj_fakture) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND broj_fakture=:broj_fakture AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"broj_fakture" => $broj_fakture,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_broju_ulaz($id_korisnika, $broj_fakture) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND broj_fakture=:broj_fakture AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"broj_fakture" => $broj_fakture,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_datumu($id_korisnika, $datum) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND datum=:datum AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"datum" => $datum,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_datumu_ulaz($id_korisnika, $datum) {
		$query = "SELECT * FROM ulazne_fakture WHERE id_korisnika=:id_korisnika AND datum=:datum AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"datum" => $datum,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_kupcu($id_korisnika, $kupac_id) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_kupcu_ulaz($id_korisnika, $kupac_id) {
		$query = "SELECT * FROM ulazne_fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_kupcu_i_datumu($id_korisnika, $kupac_id, $datum) {
		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id AND datum=:datum AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"datum" => $datum,
		);
		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function get_faktura_po_kupcu_i_datumu_ulaz($id_korisnika, $kupac_id, $datum) {
		$query = "SELECT * FROM ulazne_fakture WHERE id_korisnika=:id_korisnika AND kupac_id=:kupac_id AND datum=:datum AND status='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
			"kupac_id" => $kupac_id,
			"datum" => $datum,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");
	}

	public function is_zapoceta_faktura($id_korisnika, $username) {

		$query = "SELECT COUNT(*) FROM fakture WHERE id_korisnika='$id_korisnika' AND kupac_id='0' AND username='$username'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$count = $stmt->fetchColumn();
		return $count;
	}

	public function get_zapoceta_faktura($id_korisnika, $username) {
		$query = "SELECT * FROM `fakture` WHERE `id_korisnika`=:id_korisnika AND `kupac_id`='0' AND `username`=:username  AND `status`=:status";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"username" => $username,
			"id_korisnika" => $id_korisnika,
			"status" => $this->ststus_blokiran,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function get_poslednja_uneta_faktura($id_korisnika, $num) {

		$query = "SELECT * FROM `fakture` WHERE `id_korisnika`=:id_korisnika ORDER BY id_fakture DESC LIMIT 1 OFFSET $num";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindParam");

	}

/**/
	public function get_kupci_all($id_korisnika) {

		$query = "SELECT * FROM `kupci` WHERE `id_korisnika`=:id_korisnika";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");
	}

/**/
	public function get_kupci($id_korisnika) {

		$query = "SELECT * FROM `kupci` WHERE `id_korisnika`=:id_korisnika AND `status_kupca`='1'";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"id_korisnika" => $id_korisnika,
		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");
	}

	public function get_kupac($id_korisnika, $naziv_kupca) {

		$query = "SELECT * FROM kupci WHERE `id_korisnika`=:id_korisnika AND `naziv_kupca`=:naziv_kupca";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

			"naziv_kupca" => $naziv_kupca,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function get_kupac_po_id($id_korisnika, $id_kupca) {

		$query = "SELECT * FROM kupci WHERE `id_korisnika`=:id_korisnika AND `id_kupca`=:id_kupca";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

			"id_kupca" => $id_kupca,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function if_kupac_exists($id_korisnika, $naziv_kupca) {

		$query = "SELECT COUNT(*) FROM kupci WHERE id_korisnika='$id_korisnika' AND naziv_kupca='$naziv_kupca'";

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

	public function get_artikli($id_korisnika) {

		$query = "SELECT * FROM artikli WHERE id_korisnika=:id_korisnika AND status_artikla='1'";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");

	}

	public function get_artikal($id_korisnika, $naziv_artikla) {

		$query = "SELECT * FROM artikli WHERE `id_korisnika`=:id_korisnika AND `artikal`=:naziv_artikla AND `status_artikla`='1'";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

			"naziv_artikla" => $naziv_artikla,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function if_artikal_exists($id_korisnika, $naziv_artikla) {

		$query = "SELECT COUNT(*) FROM artikli WHERE id_korisnika='$id_korisnika' AND artikal='$naziv_artikla'";

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

	public function get_artikal_po_id($id_korisnika, $id_artikla) {

		$query = "SELECT * FROM `artikli` WHERE `id_korisnika`=:id_korisnika AND `id_artikla`=:id_artikla ";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

			"id_artikla" => $id_artikla,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function fetchAll_by_query($query) {

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}

	public function fetch_by_query($query) {

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);

	}

	public function get_ukupan_broj_otpremnica($id_korisnika) {

		$query = "SELECT COUNT(*) FROM `otpremnice` WHERE `id_korisnika` = :id_korisnika AND `status`='1'";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

		);

		return $this->get_numRows($query, $stmtArray);

	}

	public function is_zapoceta_otpremnica($id_korisnika, $username) {

		$query = "SELECT COUNT(*) FROM otpremnice WHERE id_korisnika='$id_korisnika' AND id_kupca='0' AND username='$username'";

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

	public function get_poslednja_uneta_otpremnica($id_korisnika, $num) {

		$query = "SELECT * FROM `otpremnice` WHERE `id_korisnika`=:id_korisnika ORDER BY id_otpremnice DESC LIMIT 1 OFFSET $num";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindParam");

	}

	public function get_zapoceta_otpremnica($id_korisnika, $username) {

		$query = "SELECT * FROM otpremnice WHERE id_korisnika=:id_korisnika AND id_kupca='0' AND username=:username  AND status=:status";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"username" => $username,

			"id_korisnika" => $id_korisnika,

			"status" => $this->ststus_blokiran,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function get_otpremnica_po_broju($id_korisnika, $broj_otpremnice) {

		$query = "SELECT * FROM fakture WHERE id_korisnika=:id_korisnika AND broj_otpremnice=:broj_otpremnice AND status='1'";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

			"broj_otpremnice" => $broj_otpremnice,

		);

		return $this->get_fetch_all($query, $stmtArray, "bindValue");

	}

	public function get_otpremnica_po_id($id_korisnika, $id_otpremnice) {

		$query = "SELECT * FROM `otpremnice` WHERE `id_korisnika`=:id_korisnika AND `id_otpremnice`=:id_otpremnice ";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

			"id_otpremnice" => $id_otpremnice,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function if_broj_exists($tabela, $id_korisnika, $broj) {

		$year = date("Y");

		$query = "SELECT COUNT(*) FROM $tabela WHERE id_korisnika='$id_korisnika' AND broj_$tabela='$broj' AND YEAR(vreme)='$year'";

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

	public function if_broj_ulazne_fakture_exists($broj_fakture, $id_korisnika, $kupac_id) {

		$query = "SELECT COUNT(*) FROM ulazne_fakture WHERE id_korisnika='$id_korisnika' AND kupac_id='$kupac_id' AND broj_fakture='$broj_fakture'";

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

	public function get_admins($id_korisnika) {

		$query = "SELECT * FROM admin WHERE id_korisnika=:id_korisnika";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"id_korisnika" => $id_korisnika,

		);

		return $this->get_fetch_all($query, $stmtArray, "bindParam");

	}

	public function get_admin_by_username($username, $id_korisnika) {

		$query = "SELECT * FROM admin WHERE username=:username AND id_korisnika=:id_korisnika ";

		$stmt = $this->db->prepare($query);

		$stmtArray = array(

			"username" => $username,

			"id_korisnika" => $id_korisnika,

		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");

	}

	public function if_admin_exists($username, $id_korisnika) {

		$query = "SELECT COUNT(*) FROM admin WHERE username='$username' AND id_korisnika='$id_korisnika'";

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

/**/
	public function if_admin_mail_exists($email) {

		$query = "SELECT COUNT(*) FROM admin WHERE email='$email'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$count = $stmt->fetchColumn();

		return $count;

	}

/**/
	public function get_admin_by_email_and_password($email, $password) {

		$query = "SELECT * FROM admin WHERE email=:email AND password=:password ";

		$stmt = $this->db->prepare($query);
		$stmtArray = array(
			"email" => $email,
			"password" => $password,
		);

		return $this->get_fetch_data($query, $stmtArray, "bindValue");
	}

}

?>