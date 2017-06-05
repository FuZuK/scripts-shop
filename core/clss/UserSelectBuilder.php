<?
class UserSelectBuilder {
	private $select_data = array();
	private $select_type;
	private $select;
	private $selId = 0;
	public function __construct() {}

	public function addSelectElement($element_key, $element_value) {
		$this -> select_data[$element_key] = $element_value;
	}

	public function setType($type) {
		$this -> select_type = $type;
	}

	public function create() {
		if (Users\User::if_user('is_reg', 1))
			$this -> _create();
	}

	public function _create() {
		if (!$this -> selectExists()) $this -> createFirstSelect();
		else $this -> updateExistingSelect();
		$this -> fillExistingSelect();
	}

	public function selectExists() {
		return $this -> getExistingSelectId() != 0 ? true : false;
	}

	public function fillExistingSelect() {
		$this -> select = new UserSelect($this -> getExistingSelectId());
	}

	public function createFirstSelect() {
		global $db, $u;
		$db_insert = array($u -> id, $this -> select_type, $this -> getSerializedSelectData(), TimeUtils::currentTime());
		$db -> q('INSERT INTO `users_selects` (`id_user`, `id_type`, `select_data`, `time`) VALUES (?, ?, ?, ?)', $db_insert);
		$this -> selId = $db -> lastInsertId();
	}

	public function updateExistingSelect() {
		global $db;
		$db_update = array(TimeUtils::currentTime(), $this -> getId());
		$db -> q('UPDATE `users_selects` SET `time` = ? WHERE `id` = ?', $db_update);
	}

	public function getExistingSelectId() {
		if ($this -> getId() != 0) return $this -> getId();
		global $db, $u;
		$db_select = array($u -> id, $this -> select_type, $this -> getSerializedSelectData());
		$this -> selId = $db -> res('SELECT `id` FROM `users_selects` WHERE `id_user` = ? AND `id_type` = ? AND `select_data` = ?', $db_select);
		return $this -> getId();
	}

	public function getSerializedSelectData() {
		return serialize($this -> select_data);
	}

	public function getId() {
		return $this -> selId;
	}
}
?>