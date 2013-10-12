<?php
require_once('lib/phppi.gallery.php');

if ($phppi['query'] == "" || checkExploit($phppi['query']) == true) {
	if (!getDirData($phppi['query'])) {
		addError("Folder doesn't exist.", "FATAL");
		showErrorPage();
	} else {
		genBreadcrumb();

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

		if (count($phppi['file_list']) == 0) {
			$temp_slideshow = false;
		} else {
			$temp_slideshow = $phppi['settings']['enable_slideshow'];
		}

		$page->assign('site_name', $phppi['settings']['site_name']);
		$page->assign('page_title', $phppi['settings']['site_name'] . " - " . $title);
		if ($phppi['settings']['page_title_logo'] !== "") { 
			$page->assign('page_logo', "<img src=\"" . $phppi['settings']['page_title_logo'] . "\" class=\"page-logo\">"); 
		} else {
			$page->assign('page_logo', ""); 
		}
		$page->assign('page_notice', $phppi['settings']['site_notice']);
		
		$page->assign('theme_path', 'themes/' . $phppi['settings']['theme']);
		$page->assign('phppi_head_code', getHead());
		$page->assign('file_list', $phppi['file_list']);
		$page->assign('folder_list', $phppi['dir_list']);
		$page->assign('thumb_width', $phppi['settings']['thumb_size_' . $phppi['thumb_size']]);
		$page->assign('thumb_height', $phppi['settings']['thumb_size_' . $phppi['thumb_size']]);
		$page->assign('thumb_size_change', $phppi['settings']['enable_thumb_size_change']);
		$page->assign('thumb_small_url', '?q=' . $phppi['query'] . '&s=small');
		$page->assign('thumb_medium_url', '?q=' . $phppi['query'] . '&s=medium');
		$page->assign('thumb_large_url', '?q=' . $phppi['query'] . '&s=large');
		$page->assign('slideshow', $temp_slideshow);
		$page->assign('slideshow_url', '?slideshow=' . $phppi['query']);
		$page->assign('nav', $phppi['nav']);

		$page->assign('query', $phppi['query']);
		
		$page->compile_id = md5(PHPPI_ROOT . '/themes/' . $phppi['settings']['theme'] . '/browse.tpl');
		$page->display('browse.tpl', md5($phppi['query']));
	}
} else {
	addError("Folder doesn't exist.", "FATAL");
	showErrorPage();
}
?>