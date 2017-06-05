<?php

	include('_header.php');

	# http://wiki.webmoney.ru/wiki/show/Interfeys_X16
	$res = $wmxi->X16(
		PRIMARY_WMID,   # WMID кошелька
		'R',            # тип кошелька
		'Ещё один WMR'  # название кошелька
	);

	print_r($res->toObject());


?>