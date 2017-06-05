<?
if (isset($_GET['user_id'])) {
	$us = new Users\User(intval($_GET['user_id']));
	if (!$us -> exists()) {
		$title = 'Ой, ошибочка получилась...';
		include(HEAD);
		echo alerts::error("Пользователь не найден");
		doc::back("Назад", "/");
		include(FOOT);
	}
} else {
	Users\User::if_user('is_reg');
	$us = $u;
}
$title = 'Страничка '.$us -> login;
include(HEAD);
?>
<div class="content">
	<? echo $us -> ava_prof(1)?>
</div>
<hr>
<? if (trim($us -> info -> name) || trim($us -> info -> city) || trim($us -> info -> birth_year) || intval($us -> info -> icq) || trim($us -> info -> email) && $us -> info -> show_email || $us -> getGroup() -> id > 1 || adminka::access('users_change_group') && $us -> getGroup() -> level < $u -> getGroup() -> level) { ?>
<div class="content hl2">
	<? if ($us -> getGroup() -> id > 1 || adminka::access('users_change_group') && $us -> getGroup() -> level < $u -> getGroup() -> level) {
		?>
		<span class="ank_q">Должность:</span> <span style="color: <? if ($us -> getGroup() -> level == 3)echo "green"; elseif ($us -> getGroup() -> level == 2)echo "blue"; else echo "red";?>"><? echo TextUtils::escape($us -> getGroup() -> name)?></span><? echo (isset($u) && adminka::access('users_change_group') && $us -> getGroup() -> level < $u -> getGroup() -> level?"&nbsp;<a href='/adminka/?act=change_user_group&user_id=".$us -> id."'>".imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))."</a>":null)?><br />
		<?
	}
	?>
	<? if (trim($us -> info -> name)) { ?><span class="ank_q">Имя:</span> <span class="ank_a"><? echo TextUtils::escape($us -> info -> name);?></span><br /><? } ?>
	<? if (trim($us -> info -> birth_year)) { ?><span class="ank_q">Дата рождения:</span> <span class="ank_a"><? echo TextUtils::escape(sprintf("%02.0f", $us -> info -> birth_day)).'.'.TextUtils::escape(sprintf("%02.0f", $us -> info -> birth_month)).'.'.TextUtils::escape($us -> info -> birth_year);?></span><br /><? } ?>
	<? if (trim($us -> info -> city)) { ?><span class="ank_q">Родной город:</span> <span class="ank_a"><? echo TextUtils::escape($us -> info -> city);?></span><br /><? } ?>
	<? if (intval($us -> info -> icq)) { ?><span class="ank_q">ICQ:</span> <span class="ank_a"><? echo TextUtils::escape($us -> info -> icq);?></span><br /><? } ?>
	<? if (trim($us -> info -> email && $us -> info -> show_email)) { ?><span class="ank_q">E-mail:</span> <span class="ank_a"><a href="mailto:<? echo TextUtils::escape($us -> info -> email);?>"><? echo TextUtils::escape($us -> info -> email);?></a></span><br /><? } ?>
</div>
<hr>
<? } ?>
<div class="content hl2">
	<span class="ank_q">Рейтинг:</span> <span class="ank_a green"><? echo $us -> rating;?></span><br />
	<span class="ank_q">Состояние:</span> <span class="ank_a"><? echo ($us -> info -> state == 1?"Свободен":"Занят");?></span><br />
	<? if ($us -> info -> specialization) { ?><span class="ank_q">Специализация:</span> <span class="ank_a"><? echo TextUtils::escape($us -> info -> specialization);?></span><? if (!$us -> info -> specialization_add) { ?><br /><? } } ?>
	<? if ($us -> info -> specialization_add) { ?><div class="mail_mess"><? echo TextUtils::escape($us -> info -> specialization_add);?><br /></div><? } ?>
	<span class="ank_q">Дата регистрации:</span> <span class="ank_a"><? echo TimeUtils::show($us -> info -> date_reg);?></span><br />
	<span class="ank_q">Посл. посещение:</span> <span class="ank_a"><? echo TimeUtils::show($us -> info -> date_last);?></span><br />
</div>
<hr>
<? if ($us -> info -> wmid) {
?>
<div class="content">
	<span class="ank_q">WMID:</span> <span class="ank_a"><a href="https://passport.webmoney.ru/asp/CertView.asp?wmid=<? echo $us -> info -> wmid;?>"><? echo $us -> info -> wmid;?></a></span><br />
	<span class="ank_q">BL:</span> <?=$us -> info -> wm_bl?><br />
	Отзывы: <?
	if ($us -> info -> wm_negclaims && $us -> info -> wm_posclaims)echo "<span class=\"green\">".TextUtils::declension($us -> info -> wm_posclaims, array('позитивный', 'позитивных', 'позитивных'))."</span> и <span class=\"red\">".TextUtils::declension($us -> info -> wm_negclaims, array('негативный', 'негативных', 'негативных'))."</span>\n";
	elseif ($us -> info -> wm_negclaims)echo "<span class=\"red\">".TextUtils::declension($us -> info -> wm_negclaims, array('негативный', 'негативных', 'негативных'))."</span>\n";
	elseif ($us -> info -> wm_posclaims)echo "<span class=\"green\">".TextUtils::declension($us -> info -> wm_posclaims, array('позитивный', 'позитивных', 'позитивных'))."</span>\n";
	else echo "<span class=\"ank_a\">нет отзывов</span>\n";
	?><br />
	<span class="ank_q">Тип аттестата:</span> <span class="ank_a"><? echo mb_convert_case($us -> info -> wm_attestat, MB_CASE_TITLE, "UTF-8");?></span><br />
</div>
<hr>
<? } ?>
<div class="mod_up">
	Вы можете подписаться на обновления этого пользователя, используя свой електронный адрес, и получать оповещения о всех новых товарах етого продавца!<br />
	<a href="/adds/subscribing/?act=subscribe&mod=seller&user_id=<? echo $us -> id?>">Подписаться прямо сейчас &raquo;</a><br />
</div>
<hr>
<div class="mod">
	<?
	if (isset($u) && ($u -> id == $us -> id || adminka::access('users_edit_anketa') && $u -> getGroup() -> level > $us -> getGroup() -> level)) {
		echo imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="<? echo ($u -> id != $us -> id?"/adminka/?act=users_edit_ank&user_id=".$us -> id:"/edit_profile")?>">Редактировать профиль</a><br />
		<?
	}
	echo imgs::show("shop.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/user/shop/?user_id=<? echo $us -> id;?>">Магазин пользователя</a><br />
	<?
	if (Users\User::if_user('is_reg', 1)) {
		echo imgs::show("mail.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/post/send/<? echo $us -> id;?>">Написать письмо</a><br />
		<?
	}
	?>
</div>
<?doc::back("Назад", "/")?>
<?
include(FOOT);
/*
<img src="http://stats.wmtransfer.com/Levels/pWMIDLevel.aspx?wmid=<? echo $us -> info -> wmid;?>&w=35&h=18&bg=0XDBE2E9" class="ic" alt="">
Отзывы: <?
	if ($us -> info -> wm_negclaims && $us -> info -> wm_posclaims)echo "<span class=\"green\">".TextUtils::declension($us -> info -> wm_posclaims, array('позитивный', 'позитивных', 'позитивных'))."</span> и <span class=\"red\">".TextUtils::declension($us -> info -> wm_negclaims, array('негативный', 'негативных', 'негативных'))."</span>\n";
	elseif ($us -> info -> wm_negclaims)echo "<span class=\"red\">".TextUtils::declension($us -> info -> wm_negclaims, array('негативный', 'негативных', 'негативных'))."</span>\n";
	elseif ($us -> info -> wm_posclaims)echo "<span class=\"green\">".TextUtils::declension($us -> info -> wm_posclaims, array('позитивный', 'позитивных', 'позитивных'))."</span>\n";
	else echo "<span class=\"ank_a\">нет отзывов</span>\n";
	?><br />
	<span class="ank_q">Тип аттестата:</span> <span class="ank_a"><? echo mb_convert_case($us -> info -> wm_attestat, MB_CASE_TITLE, "UTF-8");?></span><br />
*/
?>