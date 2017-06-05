<?
/*
Класс для упрощенной работы с PDO
by Killer 01.06.2013 21:20
icq: 
*/
class DB_PDO extends PDO {
	public $error = false;
	public $query_str = NULL;
	public function __construct ($dsn, $username='', $password='', $driver_options=array()) {
		try {
			parent::__construct($dsn, $username, $password, $driver_options);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this -> setAttribute(PDO :: ATTR_DEFAULT_FETCH_MODE, PDO :: FETCH_OBJ);
			$this -> q("SET NAMES 'utf8'");
		}
		catch(PDOException $e) {
			echo "Произошла ошибка в работе с базой данных: <b>".$e->getMessage()."</b>";
			exit();
		}
	}
	public function prepare ($sql, $markers=array(), $driver_options=array()) {
		try {
			$query = parent :: prepare($sql, $driver_options=array());
			$query -> execute($markers);
			return $query;
		}
		catch(PDOException $e) {
			$this->error($e->getMessage()."<br />$sql");
		}
	}
	public function q($sql, $markers=array(), $driver_options=array()) {
		try {
			$query = $this -> prepare($sql, $markers, $driver_options);
			return $query;
		}
		catch(PDOException $e) {
			$this->error($e->getMessage()."<br />$sql");
		}
	}
	public function farr($sql, $markers=array(), $driver_options=array()) {
		try {
			$query = $this -> q($sql, $markers, $driver_options);
			$query = $query -> fetch();
			return $query;
		}
		catch(PDOException $e) {
			$this->error($e->getMessage()."<br />$sql");
		}
	}
	public function res($sql, $markers=array(), $driver_options=array()) {
		try {
			$query = $this -> prepare($sql, $markers, $driver_options);
			return $query -> fetchColumn(0);
		}
		catch(PDOException $e) {
			$this->error($e->getMessage()."<br />$sql");
		}
	}
	public function exec($sql) {
		try {
			return parent::exec($sql);
		}
		catch(PDOException $e) {
			$this->error($e->getMessage()."<br />$sql");
		}
	}
	public function error($msg) {
		if ($msg) {
			echo $msg;
		} else {
			echo "Произошла ошибка в работе с базой данных.";
		}
		exit();
	}
}
?>