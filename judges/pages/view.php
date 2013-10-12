<?php
require_once('lib/phppi.gallery.php');

if ($phppi['query'] == "" || checkExploit($phppi['query']) == true) {
	if (!getDirData(pathinfo($phppi['query'], PATHINFO_DIRNAME))) {
		addError("Image doesn't exist.", "FATAL");
		showErrorPage();
	} else {
		genBreadcrumb();
		getImageData();
		genNextPrev();

		if ($phppi['settings']['page_title_show_full_path'] == true) {
			if ($phppi['query'] !== "") {
				$title = $phppi['query'];
			} else {
				$title = "/";
			}
		} else {
			if (isset($phppi['nav']['current']['name'])) {
				$title = $phppi['nav']['current']['name'];
			} else {
				$title = "/";
			}
		}

		if (function_exists('exif_read_data') && $phppi['settings']['enable_exif_support'] == true) {
			if (!$phppi['image']['exif'] = @exif_read_data(PHPPI_GALLERY . '/' . $phppi['query'], 0, true)) { $phppi['image']['exif'] = array(); }
		}

		if ($phppi['settings']['enable_hotkeys'] == true) {
			$temp_js = "";

			$temp_js .= "phppi_enable_hotkeys = 1;\n";

			if ($phppi['settings']['enable_up_hotkey'] == true) { 
				$temp_js .= "phppi_enable_up_hotkey = 1;\n";
				$temp_js .= "phppi_up_folder = '" . addslashes($phppi['nav']['list'][count($phppi['nav']['list']) - 1]['url']) . "';\n"; 
			}

			if (isset($phppi['nav_image']['previous'])) {
				$temp_js .= "phppi_prev_image = '" . addslashes($phppi['nav_image']['previous']['url']) . "';\n";
			} else {
				$temp_js .= "phppi_prev_image = '';\n";
			}
			if (isset($phppi['nav_image']['next'])) {
				$temp_js .= "phppi_next_image = '" . addslashes($phppi['nav_image']['next']['url']) . "';\n";
			} else {
				$temp_js .= "phppi_next_image = '';\n";
			}

			addToHead('js_url', "lib/jquery/jquery-1.9.1.min.js");
			addToHead('js_url', "js/phppi_hotkeys.js");
			addToHead('js', $temp_js);
		}

		$page->assign('site_name', $phppi['settings']['site_name']);
		$page->assign('page_title', $phppi['settings']['site_name'] . " - " . $title);
		$page->assign('page_logo', $phppi['settings']['page_title_logo']);
		$page->assign('page_notice', $phppi['settings']['site_notice']);
		
		$page->assign('theme_path', 'themes/' . $phppi['settings']['theme']);
		$page->assign('phppi_head_code', getHead());
		$page->assign('file_list', $phppi['file_list']);
		$page->assign('folder_list', $phppi['dir_list']);
		$page->assign('thumb_width', $phppi['settings']['thumb_size_' . $phppi['thumb_size']]); // 125
		$page->assign('thumb_height', $phppi['settings']['thumb_size_' . $phppi['thumb_size']]); // 125
		$page->assign('nav', $phppi['nav']);
		$page->assign('nav_image', $phppi['nav_image']);
		$page->assign('image', $phppi['image']);
		
		$page->compile_id = md5(PHPPI_ROOT . '/themes/' . $phppi['settings']['theme'] . '/view.tpl');
		$page->display('view.tpl', md5($phppi['query']));
	}
} else {
	addError("Image doesn't exist.", "FATAL");
	showErrorPage();
}
?>