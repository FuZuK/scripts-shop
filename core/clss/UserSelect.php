<?
final class UserSelect {

	const SELECT_LINK_KEY = 'selectId';

	public $selId = null, $select = null, $select_data = array(), $selected_data = array(), $exists = false;
	private $edit_selected_data = array(), $edit_selected = false;
	private $edit_select_data = array(), $edit_select = false;
	public function __construct($selId = null) {
		global $db, $u;
		if (!isset($_GET[self::SELECT_LINK_KEY]) && $selId == null || !Users\User::if_user('is_reg', 1)) return;
		if (isset($_GET[self::SELECT_LINK_KEY]))
			$this -> selId = intval($_GET[self::SELECT_LINK_KEY]);
		if ($selId != null)
			$this -> selId = intval($selId);
		$this -> select = $db -> farr('SELECT * FROM `users_selects` WHERE `id` = ? AND `id_user` = ?', array($this -> getId(), $u -> id));
		$this -> exists = @$this -> select -> id ? true : false;
		if ($this -> exists()) {
			$this -> select_data = unserialize($this -> select -> select_data);
			$this -> selected_data = unserialize($this -> select -> selected_data);
		}
	}

	public function exists() {
		return $this -> exists;
	}

	public function selected() {
		return !$this -> exists() ? false : $this -> select -> selected == 1;
	}

	public function setSelected($selected = true) {
		global $db;
		$db_update = array(intval($selected), $this -> getId());
		return $db -> q('UPDATE `users_selects` SET `selected` = ? WHERE `id` = ?', $db_update);
	}

	public function typeEquals($type) {
		if (!$this -> exists()) return false;
		return $this -> select -> id_type = $type;
	}

	public function setType($type) {
		if (!$this -> exists()) return false;
		global $db;
		$db_update = array($type, $this -> getId());
		return $db -> q('UPDATE `users_selects` SET `id_type` = ? WHERE `id` = ?');
	}

	public function getSelectedElementValue($selected_data_key) {
		return !$this -> exists() ? false : $this -> selected_data[$selected_data_key];
	}

	public function selectedDataEquals($selected_data_key, $selected_data_value) {
		return !$this -> exists() ? false : $this -> selected_data[$selected_data_key] == $selected_data_value;
	}

	public function getSelectElementValue($select_data_key) {
		return !$this -> exists() ? false : $this -> select_data[$select_data_key];
	}

	public function selectDataEquals($select_data_key, $select_data_value) {
		return !$this -> exists() ? false : $this -> select_data[$select_data_key] == $select_data_value;
	}

	public function setSelectedElementValue($add_selected_key, $add_selected_value) {
		if (!$this -> edit_selected) {
			$this -> edit_selected = true;
			$this -> edit_selected_data = $this -> selected_data;
		}
		$this -> edit_selected_data[$add_selected_key] = $add_selected_value;
	}

	public function setSelectElementValue($add_select_key, $add_select_value) {
		if (!$this -> edit_select) {
			$this -> edit_select = true;
			$this -> edit_select_data = $this -> select_data;
		}
		$this -> edit_select_data[$add_select_key] = $add_select_value;
	}

	public function editSelectedElementsCommit() {
		if (!$this -> edit_selected) return false;
		global $db;
		$this -> edit_selected = false;
		$this -> selected_data = $this -> edit_selected_data;
		$db_update = array(serialize($this -> selected_data), $this -> getId());
		return $db -> q('UPDATE `users_selects` SET `selected_data` = ? WHERE `id` = ?', $db_update);
	}

	public function editSelectElementsCommit() {
		if (!$this -> edit_select) return false;
		global $db;
		$this -> edit_select = false;
		$this -> select_data = $this -> edit_select_data;
		$db_update = array(serialize($this -> select_data), $this -> getId());
		return $db -> q('UPDATE `users_selects` SET `select_data` = ? WHERE `id` = ?', $db_update);
	}

	public function delete() {
		global $db;
		return $db -> q('DELETE FROM `users_selects` WHERE `id` = ?', array($this -> getId()));
	}

	public function __destruct() {
		if ($this -> edit_selected)
			$this -> editSelectedElementsCommit();
		if ($this -> edit_select)
			$this -> editSelectElementsCommit();
		// if ($this -> selected())
		// 	$this -> delete();
	}

	public function getId() {
		return $this -> selId;
	}

	public static function clean() {
		global $db;
		$db -> q('DELETE FROM `users_selects` WHERE `time` < ?', array(TimeUtils::currentTime() - 3600 * 24));
	}
}
?>