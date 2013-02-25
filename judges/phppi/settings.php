<?php
/* General */

$this->settings['general']['site_name'] = 'River to Well 2012';
$this->settings['general']['site_notice'] = ''; //Display a notice on all pages (depends on theme)(html is allowed), leave blank to disable.
$this->settings['general']['page_title_format'] = '[S] - [P]'; //Title format ([S] = Site name, [P] = Page title).
$this->settings['general']['page_title_show_full_path'] = false; //Show full path in page title, e.g. /This/Is/A/Test/image.png or only show current folder/filename.
$this->settings['general']['theme'] = 'pix'; //Folder name of theme to use.

$this->settings['general']['thumb_size']['small'] = 125; //Thumbnail will not exceed this value in either width or height (pixels).
$this->settings['general']['thumb_size']['medium'] = 175; //Thumbnail will not exceed this value in either width or height (pixels).
$this->settings['general']['thumb_size']['large'] = 225; //Thumbnail will not exceed this value in either width or height (pixels).
$this->settings['general']['thumb_size_default'] = 'medium';
$this->settings['general']['enable_thumb_size_change'] = false; //Allow users to change the thumb size from your choices for their session only. Requires ['advanced']['use_gd'] to be enabled.
$this->settings['general']['thumb_file_ext'] = 'jpg'; //File extention of non GD thumbnails.
$this->settings['general']['thumb_folder_show_thumbs'] = true; //Show images from within the folder as the folder's thumbnail (Can drastically decrease performance if there are a lot of folders (or images within folders) and file cache is turned off).
$this->settings['general']['thumb_folder_shuffle'] = true; //Shuffle thumbnails for folder (Requires ['general']['thumb_folder_show_thumbs'] to be set to true).
$this->settings['general']['thumb_folder_use_cache_only'] = false; //Force cache data only, if no cache exists then no thumbnails are shown for the folder (Can drastically improve performance on large folders) (Ignores cache expire setting).

$this->settings['general']['use_css_animations'] = true; //Enables or disables animations for thumbnails on hover, disabled always for internet explorer as it's not supported.
$this->settings['general']['image_show_title_on_hover'] = true; //Shows image name on hover only if enabled.
$this->settings['general']['folder_show_title_on_hover'] = false; //Shows folder name on hover only if enabled.

$this->settings['general']['use_popup_image_viewer'] = true; //Use fancybox (lightbox alternative) for viewing full images instead of built in viewer.
$this->settings['general']['disable_popup_image_viewer_for_mobile'] = true; //Disables the popup image viewer if the viewing device is a mobile/tablet.
$this->settings['general']['show_thumbs_under_viewer'] = false; //Shows thumbnails underneath popup image viewer. WARNING: Does load every full view image at once, may use a lot of bandwidth.
$this->settings['general']['popup_thumb_size'] = 100; //Sets the thumbnail size for thumbnails generated by ['general']['show_thumbs_under_viewer'] setting.
$this->settings['general']['enable_mousewheel'] = false; //Enables mousewheel to scroll through images on page with popup image viewer.
$this->settings['general']['nextprev_image_animation'] = 'none'; //Sets the animation to use when moving to the next/previous image. Accepts: elastic | fade | none.
$this->settings['general']['open_image_animation'] = 'fade'; //Sets the animation to use when opening an image. Accepts: elastic | fade | none.
$this->settings['general']['close_image_animation'] = 'fade'; //Sets the animation to use when closing an image. Accepts: elastic | fade | none.

$this->settings['general']['enable_hotkeys'] = true; //Left goes to the previous image, Right goes to the next image while in full image view.
$this->settings['general']['enable_up_hotkey'] = false; //Up returns to folder/file view from full image view.
$this->settings['general']['enable_click_next'] = true; //Click full view image to view the next image.

/* Advanced */

$this->settings['advanced']['debug_mode'] = false; //Enable if having issues with PHPPI so you can report the exact error you are getting.
$this->settings['advanced']['debug_show_all'] = false; //Shows all information regarding the current page.
$this->settings['advanced']['access_log'] = "off"; //on, off. Outputs ip address, time and action of anyone accessing phppi to access.log in the logs folder (make sure the logs folder is writeable).
$this->settings['advanced']['access_log_no_thumbnail'] = false; //If true this disables logging of thumbnail generation for the access log (large galleries can cause a large log file if enabled).
$this->settings['advanced']['phppi_log'] = "off"; //info, error, all, off. Outputs events with times to phppi.log in the logs folder (make sure the logs folder is writeable).
$this->settings['advanced']['log_timezone'] = "Australia/Sydney"; //Timezone to use for logs (see http://www.php.net/manual/en/timezones.php for acceptable values).

$this->settings['advanced']['cyrillic_support'] = true; //Enable support for cyrillic characters in folder names. Also helps with certain symbols.

$this->settings['advanced']['allow_mobile_theme'] = true; //Enables mobile version if supported by theme.
$this->settings['advanced']['allow_theme_settings'] = true; //Allow theme settings to override your own.

$this->settings['advanced']['use_gzip_compression'] = 'on'; //Enable gzip compression of html where possible ('on' or 'off')
$this->settings['advanced']['gzip_compression_level'] = 1; //0 to 9 (9 being most compression).

$this->settings['advanced']['use_gd'] = true; //Enable GD thumbnail creation (dynamic thumbnails).
$this->settings['advanced']['use_gd_cache'] = true; //Cache thumbnails so they aren't recreated on every page load.
$this->settings['advanced']['jpeg_quality'] = 75; //Jpeg thumbnail quality (0 to 100)
$this->settings['advanced']['gd_cache_expire'] = 172800; //Seconds till expire (Default: 2 days)
$this->settings['advanced']['use_file_cache'] = true; //Cache list of files to improve speed.
$this->settings['advanced']['expire_file_cache'] = 86400; //Seconds till expire (Default: 1 day)
$this->settings['advanced']['cache_folder'] = 'phppi/cache'; //Where you want to store your cached xml and thumbnail files. Relative to phppi install folder. Web server user must have write permissions.
$this->settings['advanced']['thumbs_folder'] = 'phppi/thumbs'; //Where you want to store your non GD thumbnails. Relative to phppi install folder.

$this->settings['advanced']['use_javascript_navigation'] = false; //Use javascript for changing between images in full view mode, doesn't reload the page for each image.

/* Specific Theme Settings */

$this->settings['theme']['pix']['page_title_background_color'] = '#00548C'; //Darker colors work best
$this->settings['theme']['pix']['page_title_text_color'] = '#ffffff';
$this->settings['theme']['pix']['page_title_text_shadow'] = '1px 1px 1px #000000'; //Format: [x] [y] [blur] [color] (Example (Inset Effect): -1px -1px 0px #000000) (Does not work on IE9 or less)
?>