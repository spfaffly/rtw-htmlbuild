<?php
function genBreadcrumb() {
	global $phppi;

	if ($phppi['query'] !== "") {
		$split = explode("/", $phppi['query']);
		$url = "";
		$i = 0;

		$phppi['nav']['home']['url'] = "?q=";
		$phppi['nav']['home']['name'] = "";

		foreach ($split as $value) {
			$url .= $value . "/";

			if ($i < (count($split) - 1)) {
				$phppi['nav']['list'][$i]['url'] = "?q=" . substr($url, 0, -1);
				$phppi['nav']['list'][$i]['name'] = $value;
			} else {
				$phppi['nav']['current']['url'] = "?q=" . substr($url, 0, -1);
				$phppi['nav']['current']['name'] = $value;
			}

			$i++;
		}
	} else {
		$phppi['nav']['home']['url'] = "?q=";
		$phppi['nav']['home']['name'] = "";
	}
}

function setThumbSize($size = "") {
	//$size: small|medium|large

	global $phppi;

	if ($size !== "" && $phppi['settings']['enable_thumb_size_change'] == true) {
		if ($size == "small" || $size == "medium" || $size == "large") {
			setcookie("thumb_size", $size);
			$phppi['thumb_size'] = $size;
		} else {
			$phppi['thumb_size'] = $phppi['settings']['thumb_size_default'];
		}
	} else {
		if (isset($_COOKIE['thumb_size']) && $phppi['settings']['enable_thumb_size_change'] == true) {
			$phppi['thumb_size'] = $_COOKIE['thumb_size'];
		} else {
			$phppi['thumb_size'] = $phppi['settings']['thumb_size_default'];
		}
	}
}

function addToHead($type, $value) {
	//type = js_url, css_url, js, css, custom

	global $phppi;

	$phppi['header']['items'][] = array('type' => $type, 'value' => $value);
}

function getHead() {
	global $phppi;
	$output = "";
	
	$output .= "
<!-- 
PHP Picture Index " . $phppi['version'] . "

Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://phppi.pixelizm.com/
Licence: GNU General Public License v3                   		 
http://www.gnu.org/licenses/                
-->\n\n";

	foreach($phppi['header']['items'] as $items) {
		switch ($items['type']) {
			case 'js_url':
				$output .= "<script type=\"text/javascript\" src=\"" . $items['value'] . "\"></script>\n";
			break;
			case 'css_url':
				$output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $items['value'] . "\">\n";
			break;
			case 'js':
				$output .= "<script type=\"text/javascript\">\n" . $items['value'] . "\n</script>\n";
			break;
			case 'css':
				$output .= "<style type=\"text/css\">\n" . $items['value'] . "\n</style>\n";
			break;
			case 'custom':
				$output .= $items['value'] . "\n";
			break;
		}
	}
	
	return $output;
}
?>