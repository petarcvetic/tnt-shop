<?php

class AllData{

	private $db;

	function __construct($DB_con){
      $this->db = $DB_con;
    }


    public function get_korisnik($id_korisnika){
    	try{
	        $query = "select * from `korisnici` WHERE `id_korisnika`=:id_korisnika AND `status`=:status";
	        $stmt = $this->db->prepare($query);
	        $stmt->bindparam('id_korisnika', $id_korisnika, PDO::PARAM_STR);
	        $stmt->bindparam('status', $status, PDO::PARAM_STR);

	        return $stmt->fetch(PDO::FETCH_ASSOC);
	    }
	    catch(PDOException $e){
			echo $e->getMessage();
		}    
	}
}

?>