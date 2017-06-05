<?
class WM_Info {
	public static $url = "http://passport.webmoney.ru/xml/XMLGetWMIDInfo.aspx";

	function getWMIDInfo ($wmid) {
		$this -> wmid = $wmid;
		$this -> request = "<request><wmid>".$this -> wmid."</wmid></request>"; //запрос
		$ch = curl_init(self::$url); //CURL запустись.Ниже мы его настроим...
		curl_setopt($ch, CURLOPT_HEADER, 0); //заголовки не отправляем
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //вернём ответ
		curl_setopt($ch, CURLOPT_POST, 1); //запрос у нас POST а не GET
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> request); //запрос то POST - но тут мы зададим что мы что либо передаём
		$xml = simplexml_load_string(curl_exec($ch)); //результат курлятской работы

		@$this -> wmid_new = $xml -> certinfo -> wmids -> row['wmid'];
		if ($this -> wmid_new) {
			$this -> tid = $xml -> certinfo -> attestat -> row['tid'];
			$this -> attestat_name = $xml -> certinfo -> attestat -> row['typename'];
			$this -> bl = $xml -> certinfo -> wmids -> row['level'];
			$this -> datereg = $xml -> certinfo -> wmids -> row['datereg'];

			$this -> posclaims = intval($xml -> certinfo -> claims -> row['posclaimscount']);
			$this -> negclaims = intval($xml -> certinfo -> claims -> row['negclaimscount']);
			$this -> claimslastdate = $xml -> certinfo -> claims -> row['claimslastdate'];

			$this -> surname = $xml -> certinfo -> userinfo -> value -> row['fname'];
			$this -> name = $xml -> certinfo -> userinfo -> value -> row['iname'];
			$this -> fname = $xml -> certinfo -> userinfo -> value -> row['oname'];
		}
	}

	public function getPurseInfo ($purse, $type = 'R') {
		$this -> purse = $purse;
		$this -> request = "<request><purse>$type".$this -> purse."</purse></request>"; //запрос
		$ch = curl_init(self::$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> request);
		$xml = simplexml_load_string(curl_exec($ch));
		$this -> wmid = $xml -> certinfo['wmid'];
	}
}
?>