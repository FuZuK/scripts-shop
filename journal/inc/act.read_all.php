<?
if (ussec::check_g()) {
	$db -> q("UPDATE `journal` SET `read` = ? WHERE `id_user` = ?", array(1, $u -> id));
}
header("Location: /journal");
?>