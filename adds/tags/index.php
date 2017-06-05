<?
include('../../core/st.php');
$title = 'Теги';
include(HEAD);
?>
<div class="content">
	<b class="red lh2">Текстовые теги:</b><br />
	[b]Жырный текст[/b] - <? echo TextUtils::show("[b]Жырный текст[/b]")?><br />
	[i]Наклонный текст[/i] - <? echo TextUtils::show("[i]Наклонный текст[/i]")?><br />
	[u]Подчеркнутый текст[/u] - <? echo TextUtils::show("[u]Подчеркнутый текст[/u]")?><br />
	[s]Перечеркнутый текст[/s] - <? echo TextUtils::show("[s]Перечеркнутый текст[/s]")?><br />
	[color=green]Цветной текст[/color] - <? echo TextUtils::show("[color=green]Цветной текст[/color]")?><br />
	<b class="red lh2">Теги ссылок:</b><br />
	http://<? echo $_SERVER['HTTP_HOST'];?> - <? echo TextUtils::show("http://$_SERVER[HTTP_HOST]");?><br />
	[url=http://<? echo $_SERVER['HTTP_HOST'];?>]<? echo $_SERVER['HTTP_HOST'];?>[/url] - <? echo TextUtils::show("[url=http://$_SERVER[HTTP_HOST]]$_SERVER[HTTP_HOST][/url]");?><br />
	<b class="red lh2">Внутренние ссылки:</b><br />
	[user]admin[/user] - <? echo TextUtils::show("[user]admin[/user]")?><br />
</div>
<?
doc::back("Назад", "/cab");
include(FOOT);
?>