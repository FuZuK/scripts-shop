<?
$modules = new modules();
$modules -> LoadModules("advt_site");
$users_online = $db -> res("SELECT COUNT(*) FROM `users_infos` WHERE `date_last` > ?", array(time() - 600));
$guests_online = $db -> res("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ?", array(time() - 600));
?>
<hr>
<div class="foot">
<?if (URL != '/'):?>
<a href="/">Главная</a> | <?endif?>
<a href="/adds/contacts">Контакты</a> | <a href="/wiki">Справка</a> | <a href="/adds/subscribing/?act=subscribe&mod=site">Рассылка</a> | <a href="/?select_theme=web">WEB</a>
<br />
<!-- begin WebMoney Transfer : accept label -->
<a href="http://www.megastock.ru/" target="_blank"><img src="http://www.megastock.ru/doc/Logo/acc_blue_on_white_ru.png" alt="www.megastock.ru" border="0"></a>
<!-- end WebMoney Transfer : accept label -->
<!-- begin WebMoney Transfer : attestation label -->
<a href="https://passport.webmoney.ru/asp/certview.asp?wmid=664936584080" target="_blank"><img src="http://www.megastock.ru/doc/Logo/v_blue_on_white_ru.png" alt="Здесь находится аттестат нашего WM идентификатора 664936584080" border="0" /></a>
<!-- end WebMoney Transfer : attestation label -->
<br />
На сайте <a href="/users/online"><?=TextUtils::declension($users_online, array('юзер', 'юзера', 'юзеров'))?></a> (<?=TextUtils::declension($guests_online, array('гость', 'гостя', 'гостей'))?>)
</div>
</div>
</div>
</body>
</html>