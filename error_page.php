<?
$err = intval($_GET['error']);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ошибка #<? echo $err?></title>
	<style>
	body {
		margin: auto;
		max-width: 340px;
		background: #3AFF32;
	}
	.wrapper {
	    margin-top: 50%;
	    color: #FFF;
	}
	.header {
		font-size: 50px;
		text-shadow: 3px 4px 1px #00AC15;
	}
	.cont {
		font-size: 20px;
	}
	</style>
</head>
<body>
<div class="wrapper">
<div class="header">
	Ошибка #<? echo $err?>
</div>
<div class="cont">
<?
if ($err=='400')echo "Обнаруженная ошибка в запросе\n";
elseif ($err=='401')echo "Нет прав для выдачи документа\n";
elseif ($err=='402')echo "Не реализованный код запроса\n";
elseif ($err=='403')echo "Доступ запрещен\n";
elseif ($err=='404')echo "Нет такой страницы\n";
elseif ($err=='500')echo "Внутренняя ошибка сервера\n";
elseif ($err=='502')echo "Сервер получил недопустимые ответы другого сервера\n";
else echo "Неизвестная ошибка\n";
?>
</div>
</div>
</body>
</html>