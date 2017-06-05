<?
$title = 'Выход...';
session_destroy();
// setcookie("id_user", "", 0);
// setcookie("pass", "", 0);
header("Location: /");
exit();
?>