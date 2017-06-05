<?
namespace Users;
class Users {
	public static function findUserByLogin($login) {
		global $db;
		return new User($db -> res('SELECT `id` FROM `users` WHERE `login` = ?', array($login)));
	}
}
?>