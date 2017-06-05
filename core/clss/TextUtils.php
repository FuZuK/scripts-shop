<?
final class TextUtils {
	private function __construct() {}

	public static function length($string) {
		$rus = array('й','ц','у','к','е','н','г','ш','щ','з','х','ъ','ф','ы','в','а','п','р','о','л','д','ж','э','я','ч','с','м','и','т','ь','б','ю','Й','Ц','У','К','Е','Н','Г','Ш','Щ','З','Х','Ъ','Ф','Ы','В','А','П','Р','О','Л','Д','Ж','Э','Я','Ч','С','М','И','Т','Ь','Б','Ю');
		return strlen(str_replace($rus, '0', $string));
	}

	public static function lenNtrim($string) {
		return self::length(trim($string));
	}

	public static function cut($string, $maxchars = 500, $show_dots = false, $encoding = 'UTF-8') {
		$new_string = NULL;
		if (self::length($string) > $maxchars)$new_string = mb_substr($string, 0, $maxchars, $encoding);
		else $new_string = $string;
		if ($new_string != $string && $show_dots)$new_string.= '...';
		return $new_string;
	}

	public static function DBFilter($string) {
		return htmlspecialchars($string);
	}

	public static function declension ($int, $expressions, $show = true) { // $expression = array('день', 'дня', 'дней');
		if (count($expressions) < 3)$expressions[2] = $expressions[1];
		$count = $int % 100;
		if ($count >= 5 && $count <= 20)$result = 2;
		else $count = $count % 10;
		if ($count == 1)$result = 0;
		elseif ($count >= 2 && $count <= 4)$result = 1;
		else $result = 2;
		return ($show ? $int.' ' : NULL).$expressions[$result];
	}

	public static function bbcode($string) {
		$bbcode = array();
		$bbcode['/\[i\](.+)\[\/i\]/isU']='<i>$1</i>';
		$bbcode['/\[b\](.+)\[\/b\]/isU']='<b>$1</b>';
		$bbcode['/\[u\](.+)\[\/u\]/isU']='<u>$1</u>';
		$bbcode['/\[s\](.+)\[\/s\]/isU']='<s>$1</s>';
		$bbcode['/\[quote\](.+)\[\/quote\]/isU']='<div class="quote">$1</div>';
		$bbcode['/\[color=([\#0-9a-zA-Z]+)\](.+)\[\/color\]/isU']='<span style="color: $1;">$2</span>';
		$bbcode['/\[fon=([\#0-9a-zA-Z]+)\](.+)\[\/fon\]/isU']='<div style="background-color: $1; padding: 7px; border-radius: 4px;">$2</div>';
		if (count($bbcode))
			$string = preg_replace(array_keys($bbcode), array_values($bbcode), $string);
		return $string;
	}

	public static function links($string, $id_user = 0) {
		$ank = new Users\User($id_user);
		$string = str_replace('[site]', $_SERVER['HTTP_HOST'], $string);
		$string = preg_replace_callback("/\[url=((?:http|https|ftp):\/\/.+?)\](.+?)\[\/url\]/uis", array(new TextUtils, 'bb_link'), $string);
		$string = preg_replace_callback('/\[user\](.+)\[\/user\]/isU', array(new TextUtils, 'user_link'), $string);
		$string = preg_replace_callback('~(^|\s)([a-z]+://([^ \r\n\t`\'"]+))(\s|$)~iu', array(new TextUtils, "web_link"), $string);
		return $string;
	}

	public static function bb_link($matches) {
		$_result = $matches[0];
		if (TextUtils::lenNtrim($matches[1]) > 0 && TextUtils::lenNtrim($matches[2]) > 0) {
			$_result = Doc::showLink(self::typed_link($matches[1]), $matches[2]);
		}
		return $_result;
	}

	public static function user_link($matches) {
		$_result = $matches[0];
		if (TextUtils::lenNtrim($matches[1]) > 0) {
			$us = Users\Users::findUserByLogin($matches[1]);
			if ($us -> exists())
				$_result = $us -> login();
		}
		return $_result;
	}
	
	public static function web_link($matches) {
		$_result = $matches[0];
		if (TextUtils::lenNtrim($matches[2]) > 0) {
			$_result = Doc::showLink(self::typed_link($matches[2]), $matches[2]);
			$_result = $matches[1].$_result.$matches[4];
		}
		return $_result;
	}

	public static function typed_link($link) {
		if (preg_match('#^http://'.preg_quote($_SERVER['HTTP_HOST']).'(?:\?:$|\/)#', $link))
			$_result = $link;
		else
			$_result = 'http://'.$_SERVER['HTTP_HOST'].'/redirect.php?url='.$link;
		return $_result;
	}

	public static function smiles($string, $id_user) {
		global $u, $db;
		$ank = new Users\User($id_user);
		$query = $db -> query("SELECT * FROM `smiles` ORDER BY `name` DESC");
		while ($post = $query -> fetch()) {
			$string = str_replace($post -> name, Doc::showImage("http://$_SERVER[HTTP_HOST]/images/smiles/{$post -> id}.png"), $string);
		}
		return $string;
	}
	public static function br($string, $br='<br />') {
		$string = preg_replace("/(\r\n){2,}/", "<br/><br/>", $string); //если 2 и более подряд
		$string = preg_replace("/(\r\n)/", "<br/>", $string); // если 1 на <br/> или сколько поставишь столько и будет
		return $string;
	}

	public static function show($string, $user_id = 0, $br = true, $html = true, $smiles = true, $links = true, $bbcode = true) {
		$user = new Users\User($user_id);
		if ($html)
			$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		if ($smiles)
			$string = self::smiles($string, $user -> id); // вставка смайлов
		if ($bbcode)
			$string = self::bbcode($string);
		if ($links)
			$string = self::links($string, $user -> id);
		if ($br)
			$string = self::br($string);
		// return stripslashes($string);
		return $string;
	}

	public static function input_value($string) {
		return self::show($string, 0, 0, 1, 0, 0, 0);
	}

	public static function inputValue($string) {
		return self::show($string, 0, 0, 1, 0, 0, 0);
	}

	public static function translit($in) {
		$trans1 = array("JO","ZH","CH","SH","SCH","JE","JY","JA","jo","zh","ch","sh","sch","je","jy","ja","A","B","V","G","D","E","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","H","C","'","Y","a","b","v","g","d","e","z","i","j","k","l","m","n","o","p","r","s","t","u","f","h","c","'","y");
		$trans2 = array("Ё","Ж","Ч","Ш","Щ","Э","Ю","Я","ё","ж","ч","ш","щ","э","ю","я","А","Б","В","Г","Д","Е","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ь","Ы","а","б","в","г","д","е","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ь","ы");
		return str_replace($trans1, $trans2, $in);
	}



	public static function retranslit($in) {
		$trans1 = array("Ё","Ж","Ч","Ш","Щ","Э","Ю","Я","ё","ж","ч","ш","щ","э","ю","я","А","Б","В","Г","Д","Е","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ь","Ы","а","б","в","г","д","е","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ь","ы");
		$trans2 = array("JO","ZH","CH","SH","SCH","Je","Jy","Ja","jo","zh","ch","sh","sch","je","jy","ja","A","B","V","G","D","E","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","H","C","","Y","a","b","v","g","d","e","z","i","j","k","l","m","n","o","p","r","s","t","u","f","h","c","","y");
		return str_replace($trans1, $trans2, $in);
	}

	public static function escape($string) {
		return htmlspecialchars($string);
	}

	public static function is_empty($string) {
		return self::lenNtrim($string) == 0;
	}
}
?>