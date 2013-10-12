<?php
/*
PHP Picture Index 1.3.0
--------------------------
Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://code.google.com/p/phppi/
Licence: GNU General Public License v3                   		 

This file is part of PHP Picture Index (PHPPI).

PHPPI is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

PHPPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with PHPPI. If not, see <http://www.gnu.org/licenses/>.
*/

$phppi['version'] = '1.3.0';

require_once('settings.php');

if ($phppi['settings']['debug_mode'] == true) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

require_once('folder_config.php');
require_once('lib/phppi.utils.php');
require_once('lib/phppi.general.php');
require_once(SMARTY . '/Smarty.class.php');
require_once('lib/phppi.smarty.php');

ini_set("gd.jpeg_ignore_warning", 1);
ini_set('memory_limit', $phppi['settings']['php_memory'] . 'M');
ini_set('zlib.output_compression', $phppi['settings']['use_gzip_compression']);
ini_set('zlib.output_compression_level', $phppi['settings']['gzip_compression_level']);

if (count($_GET) > 0) {
	$key = current(array_keys($_GET));
	
	if ($_GET[$key] !== "" && $key !== "q") {
		$phppi['page'] = $key;
		$phppi['query'] = stripslashes($_GET[$key]);
	} else {
		$phppi['page'] = 'browse';
		$phppi['query'] = stripslashes(getQuery());
	}
} else {
	$phppi['page'] = 'browse';
	$phppi['query'] = stripslashes(getQuery());
}

if (isset($_GET['s'])) { $thumb_size = $_GET['s']; } else { $thumb_size = ""; }

setThumbSize($thumb_size);

$page = new smartyPage();

$page->debugging = $phppi['settings']['smarty_theme_debug'];

addToHead('custom', "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; user-scalable = no; maximum-scale=1.0;\">");
addToHead('custom', "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">");
addToHead('css_url', "css/global.css");

switch ($phppi['page']) {
	case 'thumb':
		require_once('lib/phppi.gallery.php');
		
		if (isset($_GET['size'])) { $phppi['thumb_size'] = $_GET['size']; } else { $phppi['thumb_size'] = $phppi['settings']['thumb_size_default']; }
		getThumbnail();
	break;
	case 'image':
		if (checkExploit($phppi['query']) == true) {
			header("Content-length: " . filesize(PHPPI_GALLERY . '/' . $phppi['query']));
			header("Content-type: " . getImageType(PHPPI_GALLERY . '/' . $phppi['query']));
			readfile(PHPPI_GALLERY . '/' . $phppi['query']);
		} else {
			addError("Image doesn't exist.", "FATAL");
			showErrorPage();
		}
	break;
	case 'slideshow':
		if (checkExploit($phppi['query']) == true) {
			include(PHPPI_ROOT . '/pages/slideshow.php');
		} else {
			addError("Folder doesn't exist.", "FATAL");
			showErrorPage();
		}
	break;
	case 'view':
	case 'browse':
		if ($phppi['settings']['use_popup_image_viewer'] == true && $phppi['page'] == 'browse') {
			addToHead('js_url', "lib/jquery/jquery-1.9.1.min.js");
			addToHead('js_url', "lib/fancybox/jquery.fancybox.pack.js?v=2.1.5");
			addToHead('css_url', "lib/fancybox/jquery.fancybox.css?v=2.1.5");
			addToHead('js', "$(document).ready(function() {	$(\".thumb-container\").attr('rel', 'gallery').fancybox( { openEffect: '" . $phppi['settings']['open_image_animation'] . "', closeEffect: '" . $phppi['settings']['close_image_animation'] . "', nextEffect: '" . $phppi['settings']['nextprev_image_animation'] . "', prevEffect: '" . $phppi['settings']['nextprev_image_animation'] . "' } ); });");
		}

		include(PHPPI_ROOT . '/pages/' . $phppi['page'] . '.php');

		if ($phppi['settings']['debug_show_vars'] == true) {
			echo "PHPPI Debug";
			echo "<pre>";
			print_r($phppi);
			echo "</pre>";
		}
	break;
	default:
		addError("Page not found.", "FATAL");
		showErrorPage();
	break;
}
?>