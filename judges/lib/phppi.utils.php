<?php
function getQuery() {
	global $phppi;

	if (isset($_GET['q'])) {
		if ($phppi['settings']['cyrillic_support'] == true) {
			$query = cleanPath(rawurldecode($_GET['q']));
		} else {
			$query = cleanPath($_GET['q']);
		}
	} else {
		$query = "";
	}
	
	return $query;
}

function getImageType($image) {
	$type = getimagesize($image);
	return $type['mime'];
}

function checkCommaDelimited($item, $string) {
	$string = explode(',', $string);
	
	foreach($string as $list_item)
	{
		if (strtolower($list_item) == strtolower($item))
		{
			return true;
		}
	}
	
	return false;
}

function fixPath($path) {
	if ($path == '') {
		return '';
	} else if (substr($path, -1) !== '/') {
		return $path . '/';
	} else {
		return $path;
	}
}

function cleanPath($path) {
	$path = str_replace('%20', ' ', $path);
	if (substr($path, 0, 1) == '/') { $path = substr($path, 1); }
	if (substr($path, -1, 1) == '/') { $path = substr($path, 0, -1); }
	
	return $path;
}

function checkExploit($path) {
	$real_base = realpath(PHPPI_GALLERY);
	
	$path = (DIRECTORY_SEPARATOR === '\\') ? str_replace('/', '\\', $path) : str_replace('\\', '/', $path);
	
	$var_path = PHPPI_GALLERY . DIRECTORY_SEPARATOR . $path;
	$real_var_path = realpath($var_path);
	
	/*echo "Requested: " . $path . "<br>";
	echo "Base real path: " . $real_base . "<br>";
	echo "Requested real path: " . $real_var_path . "<br>";
	echo "Therefore " . $real_base . DIRECTORY_SEPARATOR . $path . " should equal " . $real_var_path . "<br>";*/
	
	if ($real_var_path === false || ($real_base . DIRECTORY_SEPARATOR . $path) !== $real_var_path) {
		return false;
	} else {
		return true;
	}
}

function addError($message, $level = "WARNING") {
	global $phppi;
	
	//NOTICE
	//WARNING
	//CRITICAL
	
	$phppi['errors'][] = array($level, $message);	
}

function showError($index = NULL) {
	global $phppi;
	
	if (isset($phppi['errors'])) {
		if (isset($index)) {
			switch ($index) {
				case 'last':
					$error = $phppi['errors'][count($phppi['errors']) - 1];
					break;
				case 'first':
					$error = $phppi['errors'][0];
					break;
				default:
					$error = $phppi['errors'][$index];
					break;
			}
			
			return "<ul><li class=\"error_" . strtolower($error[0]) . "\">" . $error[1] . "</li></ul>\n";
		} else {
			$output = "<ul>\n";
			foreach($phppi['errors'] as $error) {
				$output .= "<li class=\"error_" . strtolower($error[0]) . "\">" . $error[1] . "</li>\n";
			}
			$output .= "</ul>\n";
			
			return $output;
		}
	}
	
	return false;
}

function showErrorPage() {
	global $phppi;
	global $page;
	
	$phppi['page'] = 'error';

	$phppi['nav']['home']['url'] = "?q=";
	$phppi['nav']['home']['name'] = "";
	$phppi['nav']['current']['url'] = "?q=";
	$phppi['nav']['current']['name'] = "Error";
	
	$page->assign('site_name', $phppi['settings']['site_name']);
	$page->assign('page_title', $phppi['settings']['site_name'] . " - Error");
	$page->assign('page_logo', $phppi['settings']['page_title_logo']);
	$page->assign('page_notice', $phppi['settings']['site_notice']);

	$page->assign('theme_path', 'themes/' . $phppi['settings']['theme']);
	$page->assign('phppi_head_code', getHead());

	$page->assign('error_array', $phppi['errors']);
	$page->assign('error_output', showError());

	$page->assign('nav', $phppi['nav']);

	$page->compile_id = md5(PHPPI_ROOT . '/themes/' . $phppi['settings']['theme'] . '/error.tpl');
	$page->display('error.tpl', md5($phppi['query']));

	if ($phppi['settings']['debug_show_vars'] == true) {
		echo "PHPPI Debug";
		echo "<pre>";
		print_r($phppi);
		echo "</pre>";
	}
}
?>