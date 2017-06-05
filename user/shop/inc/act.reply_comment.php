<?
Users\User::if_user('is_reg');
$comment = new UsersShop\Comment(intval(@$_GET['comment_id']));
if (!$comment -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Комментарий не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($comment -> id_user);
$good = new UsersShop\Good($comment -> id_good);
$category = $good -> getCategory();
$seller = $good -> getSeller();
$title = 'Ответ на комментарий';
include(HEAD);
$users[] = array(
	'us' => $us, 
	'info' => TextUtils::show($comment -> msg)
);
$sets = array('div' => 'content_mess');
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("users", $users);
$smarty -> display("list.users.tpl");
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'Ваш ответ:', 'br' => true), 
	array('type' => 'textarea', 'name' => 'msg', 'id' => 'textarea', 'value' => '', 'br' => true), 
	array('type' => 'hidden', 'name' => 'reply_id_user', 'value' => $us -> id), 
	array('type' => 'hidden', 'name' => 'reply_id_comment', 'value' => $comment -> getID()), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить'), 
	array('type' => 'hp_smiles'), 
	array('type' => 'hp_tags')
);
new SMX(array('el' => $el, 'action' => '/user/shop/?act=good&good_id='.$good -> id), 'form.tpl');
Doc::back("Назад", "/user/shop/?act=good&good_id={$good -> id}");
include(FOOT);
?>