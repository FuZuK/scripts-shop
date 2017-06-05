<?php
################################################################################
#                                                                              #
# Webmoney XML Interfaces by DKameleon (http://dkameleon.com)                  #
#                                                                              #
# Updates and new versions: http://my-tools.net/wmxi/                          #
#                                                                              #
################################################################################


# WMXILogger class
class WMXILogger {

	public static function Append($message, $detailed = false) {
		if (!defined('WMXI_LOG')) { return false; }
		file_put_contents(WMXI_LOG, $message."\n", FILE_APPEND | LOCK_EX);
		// if ($detailed) { file_put_contents(WMXI_LOG, print_r($trace, true), FILE_APPEND | LOCK_EX); } 
	}

}


?>