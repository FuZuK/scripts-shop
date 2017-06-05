<?
class files {
	public static function fileExists($file_path) {
		return file_exists($file_path);
	}
	public static function dirExists($dir_path) {
		return is_dir($dir_path);
	}
	public static function fileSizeWord($filesize = 0) {
		$filesize_ed = 'B';
		if ($filesize>=1024) {
			$filesize= round($filesize/1024 , 2);
			$filesize_ed='KB';
		}
		if ($filesize>=1024) {
			$filesize= round($filesize/1024 , 2);
			$filesize_ed='MB';
		}
		if ($filesize>=1024) {
			$filesize= round($filesize/1024 , 2);
			$filesize_ed='GB';
		}
		if ($filesize>=1024) {
			$filesize= round($filesize/1024 , 2);
			$filesize_ed='TB';
		}
		return $filesize.$filesize_ed;
	}
	public static function fileSizeFromWord($filesize = 0, $word = 'B') {
		$words_array = array('B' => 5, 'KB' => 4, 'MB' => 3, 'GB' => 2, 'TB' => 1);
		$num = $words_array[$word];
		for ($i = $num; $i < 5; $i++) {
			$filesize = $filesize * 1024;
		}
		return $filesize;
	}
	public static function fileSizeLimited($filesize, $limit) {
		$filesize = strtoupper($filesize);
		$limit = strtoupper($limit);
		$filesize_new = self::fileSizeFromString($filesize);
		$limit_new = self::fileSizeFromString($limit);
		return $filesize_new < $limit_new;
	}
	public static function fileSizeFromString ($str) {
		if (preg_match("|^([\d.]+?)([A-z].*)$|", $str, $tmpVar)) {
			$num = $tmpVar[1];
			$word = $tmpVar[2];
			// echo $num.' '.$word;
			return self::fileSizeFromWord($num, $word);
		}
		return $str;
	}
	public static function fileDL($filename, $name, $mimetype = 'application/octet-stream') {
		if (!file_exists($filename))die('Файл не найден');
		@ob_end_clean();
		$from = 0;
		$size = filesize($filename);
		$to = $size;
		if (isset($_SERVER['HTTP_RANGE'])) {
			if (preg_match ('#bytes=-([0-9]*)#i',$_SERVER['HTTP_RANGE'],$range)) { // если указан отрезок от конца файла
				$from=$size-$range[1];
				$to=$size;
			} 	elseif(preg_match('#bytes=([0-9]*)-#i',$_SERVER['HTTP_RANGE'],$range)) { // если указана только начальная метка
				$from=$range[1];
				$to=$size;
			} elseif(preg_match('#bytes=([0-9]*)-([0-9]*)#i',$_SERVER['HTTP_RANGE'],$range)) { // если указан отрезок файла
				$from=$range[1];
				$to=$range[2];
			}
			header('HTTP/1.1 206 Partial Content');
			$cr='Content-Range: bytes '.$from .'-'.$to.'/'.$size;
		}
		else
		header('HTTP/1.1 200 Ok');
		$etag = md5($filename);
		$etag = substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
		header('ETag: "'.$etag.'"');
		header('Accept-Ranges: bytes');
		header('Content-Length: ' .($to-$from));
		if (isset($cr))header($cr);
		header('Connection: close');
		header('Content-Type: ' . $mimetype);
		header('Last-Modified: ' . gmdate('r', filemtime($filename)));
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($filename))." GMT");
		header("Expires: ".gmdate("D, d M Y H:i:s", time() + 3600)." GMT");
		$f=fopen($filename, 'rb');
		if (preg_match('#^image/#i',$mimetype))header('Content-Disposition: filename="'.$name.'";');
		else header('Content-Disposition: attachment; filename="'.$name.'";');
		fseek($f, $from, SEEK_SET);
		$size=$to;
		$downloaded=0;
		while(!feof($f) and !connection_status() and ($downloaded < $size)) {
			$block = min(1024 * 8, $size - $downloaded);
			echo fread($f, $block);
			$downloaded += $block;
			flush();
		}
		fclose($f);
	}
	public static function extToMime($ext = null) {
		$ext = strtolower($ext);
		if ($ext == null)return 'application/octet-stream';
		$mimes = new configs('mimes.dat');
		if ($mimes -> $ext)return $mimes -> $ext;
		return 'application/octet-stream';
	}
	public static function imagePreview ($path_to_image, $save_path, $height, $width, $save_prop = 1) {
		if ($image_resourse = @imageCreateFromString(file_get_contents($path_to_image))) {
			$img_x = imageSX($image_resourse);
			$img_y = imageSY($image_resourse);
			if ($save_prop) {
				$new_x = $width; // ширина
				$new_y = $height; // высота
			} else {
				if ($img_x == $img_y) {
					$new_x = $width; // ширина
					$new_y = $height; // высота
				} elseif ($img_x > $img_y) {
					$prop = $img_x / $img_y;
					$new_x = $width;
					$new_y = ceil($new_x / $prop);
				} else {
					$prop = $img_y / $img_x;
					$new_y = $height;
					$new_x = ceil($new_y / $prop);
				}
				if ($img_x < $new_x)$new_x = $img_x;
				if ($img_y < $new_y)$new_y = $img_y;
			}
			$screen = imageCreateTrueColor($new_x, $new_y);
			$white = imageColorAllocate($screen, 255, 255, 255);
			imageFill($screen, 1, 1, $white);
			imageCopyResampled(
				$screen, 
				$image_resourse, 
				0, 0, 
				0, 0, 
				$new_x, $new_y, 
				$img_x, $img_y
			);
			imageJpeg($screen, $save_path, 100);
			imageDestroy($screen);
			imageDestroy($image_resourse);
		} else {
			return false;
		}
	}
	public static function imagePreviewCenter ($img_path, $save_path, $width, $height) {
		if ($img = @imageCreateFromString(file_get_contents($img_path))) {
			$img_x = imageSX($img);
			$img_y = imageSY($img);
			$width_copy = $width * 2;
			$height_copy = $height * 2;
			if ($img_x == $img_y) {
				$img_copy_x = $width_copy; // ширина
				$img_copy_y = $height_copy; // высота
			} elseif ($img_x > $img_y) {
				$img_copy_x = $width_copy;
				$img_copy_y = ceil($img_copy_x / ($img_x / $img_y));
			} else {
				$img_copy_y = $height_copy;
				$img_copy_x = ceil($img_copy_y / ($img_y / $img_x));
			}
			$img_copy = imageCreateTrueColor($img_copy_x, $img_copy_y);
			$white = imageColorAllocate($img_copy, 255, 255, 255);
			imageFill($img_copy, 1, 1, $white);
			
			imageCopyResampled(
				$img_copy, 
				$img, 
				0, 0, 
				0, 0, 
				$img_copy_x, $img_copy_y, 
				$img_x, $img_y
			);
			$img_new_p_x = (($img_copy_x - $width) / 2);
			$img_new_p_y = (($img_copy_y - $height) / 2);
			$img_new = imageCreateTrueColor($width, $height);
			$white = imageColorAllocate($img_new, 255, 255, 255);
			imageFill($img_new, 1, 1, $white);
			$img_new_x = imageSX($img_new);
			$img_new_y = imageSY($img_new);
			imageCopyResampled(
				$img_new, 
				$img_copy, 
				0, 0, 
				$img_new_p_x, $img_new_p_y, 
				$img_new_x, $img_new_y, 
				$img_new_x, $img_new_y
			
			);
			imageJpeg($img_new, $save_path, 100);
			imageDestroy($screen);
			imageDestroy($image_resourse);
		} else {
			return false;
		}
	}
	public static function audioPlayer($audioSrc) {
		static $count;
		$count++;
		?>
		<script language="JavaScript" src="/media/audio/audio-player.js"></script>
		<object type="application/x-shockwave-flash" data="/media/audio/player.swf" height="24" width="538">
			<param name=movie value="/media/audio/player.swf"></param>
			<param name=FlashVars value="soundFile=<?=str_replace(';', null, $audioSrc)?>"></param>
			<param name=quality value=high></param>
			<param name=menu value=false></param>
			<param name=wmode value=transparent></param>
		</object>
		<?
	}
	public static function videoPlayer($videoSrc, $previewSrc) {
		static $count;
		$count++;
		?>
		<script type="text/javascript" src="/media/video/jwplayer.html5.js"></script>
		<script type="text/javascript" src="/media/video/jwplayer.js"></script>
		<div id="myElement<?=$count?>">Загрузка плеера...</div>

		<script type="text/javascript">
		jwplayer("myElement<?=$count?>").setup({
			file: "<?=$videoSrc?>",
			height: 405,
			image:"<?=$previewSrc?>",
			width: 532
		});
		</script>
		<?
	}
}
?>