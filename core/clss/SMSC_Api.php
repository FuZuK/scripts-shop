<?
class SMSC_Api {
	public static $sms_statuses = array(
		-1 => 'Ожидает отправки', 
		0 => 'Передано оператору', 
		1 => 'Доставлено', 
		3 => 'Просрочено', 
		20 => 'Невозможно доставить', 
		22 => 'Неверный номер', 
		23 => 'Запрещено', 
		24 => 'Недостаточно средств', 
		25 => 'Недоступный номер'
	);
	// Функция отправки SMS
	//
	// обязательные параметры:
	//
	// $phones - список телефонов через запятую или точку с запятой
	// $message - отправляемое сообщение
	//
	// необязательные параметры:
	//
	// $translit - переводить или нет в транслит (1,2 или 0)
	// $time - необходимое время доставки в виде строки (DDMMYYhhmm, h1-h2, 0ts, +m)
	// $id - идентификатор сообщения. Представляет собой 32-битное число в диапазоне от 1 до 2147483647.
	// $format - формат сообщения (0 - обычное sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms)
	// $sender - имя отправителя (Sender ID). Для отключения Sender ID по умолчанию необходимо в качестве имени
	// передать пустую строку или точку.
	// $query - строка дополнительных параметров, добавляемая в URL-запрос ("valid=01:00&maxsms=3&tz=2")
	//
	// возвращает массив (<id>, <количество sms>, <стоимость>, <баланс>) в случае успешной отправки
	// либо массив (<id>, -<код ошибки>) в случае ошибки

	public static function send_sms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "") {
		static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1");
		$smsc_configs = new configs('smsc.dat');

		$m = self::_smsc_send_cmd("send", "cost=3&phones=".urlencode($phones)."&mes=".urlencode($message).
						"&translit=$translit&id=$id".($format > 0 ? "&".$formats[$format] : "").
						($sender === false ? "" : "&sender=".urlencode($sender))."&charset=".$smsc_configs -> charset.
						($time ? "&time=".urlencode($time) : "").($query ? "&$query" : ""));
		if (isset($m -> id)) {
			global $db, $u;
			$u_id = 0;
			if (isset($u))
				$u_id = $u -> id;
			$db -> q('INSERT INTO `smsc_messages` (`id_sms`, `id_user`, `message`, `phone`, `time_add`) VALUES (?, ?, ?, ?, ?)', array($m -> id, $u_id, $message, $phones, TimeUtils::currentTime()));
		}
		return $m;
	}

	// SMTP версия функции отправки SMS

	public static function send_sms_mail($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = "") {
		$smsc_configs = new configs('smsc.dat');
		return mail("send@send.smsc.ru", "", $smsc_configs -> login.":".$smsc_configs -> password.":$id:$time:$translit,$format,$sender:$phones:$message", "From: ".self::SMTP_FROM."\nContent-Type: text/plain; charset=".$smsc_configs -> charset."\n");
	}

	// Функция получения стоимости SMS
	//
	// обязательные параметры:
	//
	// $phones - список телефонов через запятую или точку с запятой
	// $message - отправляемое сообщение 
	//
	// необязательные параметры:
	//
	// $translit - переводить или нет в транслит (1,2 или 0)
	// $format - формат сообщения (0 - обычное sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms)
	// $sender - имя отправителя (Sender ID)
	// $query - строка дополнительных параметров, добавляемая в URL-запрос ("list=79999999999:Ваш пароль: 123\n78888888888:Ваш пароль: 456")
	//
	// возвращает массив (<стоимость>, <количество sms>) либо массив (0, -<код ошибки>) в случае ошибки

	public static function get_sms_cost($phones, $message, $translit = 0, $format = 0, $sender = false, $query = "") {
		$smsc_configs = new configs('smsc.dat');
		static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1");

		$m = self::_smsc_send_cmd("send", "cost=1&phones=".urlencode($phones)."&mes=".urlencode($message).
						($sender === false ? "" : "&sender=".urlencode($sender))."&charset=".$smsc_configs -> charset.
						"&translit=$translit".($format > 0 ? "&".$formats[$format] : "").($query ? "&$query" : ""));
		return $m;
	}

	// Функция проверки статуса отправленного SMS или HLR-запроса
	//
	// $id - ID cообщения
	// $phone - номер телефона
	// $all - вернуть все данные отправленного SMS, включая текст сообщения (0 или 1)
	//
	// возвращает массив:
	//
	// для SMS-сообщения:
	// (<статус>, <время изменения>, <код ошибки доставки>)
	//
	// для HLR-запроса:
	// (<статус>, <время изменения>, <код ошибки sms>, <код IMSI SIM-карты>, <номер сервис-центра>, <код страны регистрации>, <код оператора>,
	// <название страны регистрации>, <название оператора>, <название роуминговой страны>, <название роумингового оператора>)
	//
	// При $all = 1 дополнительно возвращаются элементы в конце массива:
	// (<время отправки>, <номер телефона>, <стоимость>, <sender id>, <название статуса>, <текст сообщения>)
	//
	// либо массив (0, -<код ошибки>) в случае ошибки

	public static function get_status($id, $phone, $all = 0) {
		$smsc_configs = new configs('smsc.dat');
		$m = self::_smsc_send_cmd("status", "phone=".urlencode($phone)."&id=".$id."&all=".(int)$all);
		return $m;
	}

	// Функция получения баланса
	//
	// без параметров
	//
	// возвращает баланс в виде строки или false в случае ошибки

	public static function get_balance() {
		$smsc_configs = new configs('smsc.dat');
		$m = self::_smsc_send_cmd("balance"); // (balance) или (0, -error)
		return $m;
	}

	public static function get_operator_info($phone) {
		$smsc_configs = new configs('smsc.dat');
		$m = self::_smsc_send_cmd("info", "&get_operator=1&phone=".urlencode($phone));
		return $m;
	}


	// ВНУТРЕННИЕ ФУНКЦИИ

	// Функция вызова запроса. Формирует URL и делает 3 попытки чтения

	public static function _smsc_send_cmd($cmd, $arg = "") {
		$smsc_configs = new configs('smsc.dat');
		$url = ($smsc_configs -> https ? "https" : "http")."://smsc.ru/sys/$cmd.php?login=".urlencode($smsc_configs -> login)."&psw=".urlencode($smsc_configs -> password)."&fmt=3&err=1&".$arg;

		$i = 0;
		do {
			$ret = self::_smsc_read_url($url);
		} while ($ret == "" && ++$i < 3);
		return json_decode($ret);
	}

	// Функция чтения URL. Для работы должно быть доступно:
	// curl или fsockopen (только http) или включена опция allow_url_fopen для file_get_contents

	public static function _smsc_read_url($url) {
		$smsc_configs = new configs('smsc.dat');
		$ret = "";
		$post = $smsc_configs -> post || strlen($url) > 2000;

		if (function_exists("curl_init"))
		{
			static $c = 0; // keepalive

			if (!$c) {
				$c = curl_init();
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($c, CURLOPT_TIMEOUT, 10);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
			}

			if ($post) {
				list($url, $post) = explode('?', $url, 2);
				curl_setopt($c, CURLOPT_POST, true);
				curl_setopt($c, CURLOPT_POSTFIELDS, $post);
			}

			curl_setopt($c, CURLOPT_URL, $url);

			$ret = curl_exec($c);
		} elseif (!$smsc_configs -> https && function_exists("fsockopen")) {
			$m = parse_url($url);

			$fp = fsockopen($m["host"], 80, $errno, $errstr, 10);

			if ($fp) {
				fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]")." HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP".($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($m['query']) : "")."\r\nConnection: Close\r\n\r\n".($post ? $m['query'] : ""));

				while (!feof($fp))
					$ret .= fgets($fp, 1024);
				list(, $ret) = explode("\r\n\r\n", $ret, 2);

				fclose($fp);
			}
		}
		else
			$ret = file_get_contents($url);

		return $ret;
	}
}
?>