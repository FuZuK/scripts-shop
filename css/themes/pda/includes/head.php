<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8" />
<title><?=$title?></title>
<link rel="stylesheet" href="/css/themes/<?=$theme -> theme?>/<?=$theme -> css?>" type="text/css">
<meta keywords="killer"></meta>
<meta description="Buy-Script.Ru"></meta>
<?if ($set -> wb):?>
<script type="text/javascript" src="/js/glue.php?jquery.js,float_wind.js,fncs.js"></script>
<link rel="stylesheet" href="/css/float_wind/float_wind.css" type="text/css">
<?endif?>
</head>
<body>
<div class="wrapper">
<div class="main">
<div class="head">
<div class="logo">
<?=Doc::showlink('/', Doc::showImage('/images/logo_mobile.png'))?>
</div>
<?if (isset($u)):?>
<a href="/cab/basket" class="shopping" title="Корзина">
<img src="/images/shopping.png" height="24" width="24" alt="Корзина" style="vertical-align: -6px">
<?=$u -> getCountGoodsInBasket()?>
</a>
<?endif?>
</div>
<div class="user_panel">
<?if (!isset($u)):?>
<a href="/auth">Вход</a>
<a href="/reg">Регистрция</a>
<?else:?>
<a href="/cab">Кабинет</a>
<a href="/post">Моя почта<?if ($u -> getCounter('mail')):?> <span class='red'>+<?=$u -> getCounter('mail')?></span><?endif?></a>
<?if ($u -> getCounter('journal')):?><a href="/journal">Журнал <span class="red">+<?=$u -> getCounter('journal')?></span></a> <?endif?>
<?if (adminka::access('adminka_enter')):?>
<a href="/adminka">Админка<?if ($u -> getCounter('adminka')):?> <span class='red'>+<?=$u -> getCounter('adminka')?></span><?endif?></a>
<?endif?>
<a href="/shop">Магазин</a>
<?endif?>
</div>

<?
$modules = new modules();
$modules -> LoadModules("advt_tovs");
?>
<?if (isset($title) && $title && URL != '/'):?>
<div class="title_main">
<?=$title?>
</div>
<?
endif;