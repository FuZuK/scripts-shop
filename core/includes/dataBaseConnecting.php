<?
include(LIBS.'DataBase/db.php');
$db = new DB_PDO("mysql:host=".DBK_HOST.";dbname=".DBK_NAME, DBK_USER, DBK_PASS);
if (!$db)die("Error connecting with database!");
$db -> q("SET NAMES 'utf8'");
?>