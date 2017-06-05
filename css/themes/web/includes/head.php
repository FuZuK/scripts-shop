<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8" />
<title><?=$title?></title>
<link rel="stylesheet" href="/css/themes/<?=$theme -> theme?>/<?=$theme -> css?>" type="text/css">
<meta keywords="killer"></meta>
<meta description="Buy-Script.Ru"></meta>
<script type="text/javascript" src="/js/glue.php?jquery.js,float_wind.js,scroll.js,fncs.js"></script>
<link rel="stylesheet" href="/css/float_wind/float_wind.css" type="text/css">
</head>
<body>
<?
$mods = new modules();
$mods -> loadModules('modals');
?>
<div class="wrapper headerWrapper">
	<div class="boxWrapper">
		<div class="header">
			<?if (isset($u)):?>
			<a href="/cab/basket" class="shopping" title="Корзина">
				<img src="/images/shopping.png" alt="Корзина"><br />
				<?=TextUtils::declension($u -> getCountGoodsInBasket(), array('товар', 'товара', 'товаров'))?>
			</a>
			<?endif?>
			<div class="logo">
				<a href="/" title="Buy-Script.Ru"><?=imgs::show("logo.png")?></a>
			</div>
			<div class="userPanel">
				<div class="userPanelL">
					<?if (!isset($u)):?>
					<a href="auth" data-toggle="modal">Вход</a>
					<a href="reg" data-toggle="modal">Регистрция</a>
					<?else:?>
					<a href="/cab">Кабинет</a>
					<a href="/post">Моя почта<?if ($u -> getCounter('mail')):?> <span class="red">+<?=$u -> getCounter('mail')?></span><?endif?></a>
					<?if (adminka::access('adminka_enter')):?><a href="adminka" data-toggle="modal">Админка<? if ($u -> getCounter('adminka')):?> <span class="red">+<?=$u -> getCounter('adminka')?></span><?endif?></a><?endif?>
					<?endif?>
					<a href="/shop">Магазин</a>
				</div>
				<?if (isset($u)):?>
				<div class="userPanelR">
					<a href="/journal">Журнал<?if ($u -> getCounter('journal')):?> <span class="red">+<?=$u -> getCounter('journal')?></span><?endif?></a>
					<?if (!$u -> info -> wmid):?><a href="/edit_profile/webmoney"><img src="/images/wmid_empty.png"></a><?endif?>
				</div>
				<?endif?>
			</div>
		</div>
	</div>
</div>

<div class="wrapper">
<div class="casing">
<div class="sidebar">
<?
$modules = new modules();
$modules -> LoadModules(array(
	'panelMenu', 
	'panelShop', 
	'panelLeaders', 
	'panelAdvt', 
	'panelAdvtGoods', 
	'panelStatistics'
));
?>
</div>
<div class="hide_f">
<div class="boxWrapper">
<div class="boxItems">
<?if (URL != '/'):?>
<div class="contentTitle">
<?=$title?>
</div>
<?
endif;