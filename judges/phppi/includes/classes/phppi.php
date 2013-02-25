<?php
ini_set("gd.jpeg_ignore_warning", 1);

class PHPPI
{
	var $settings;
	var $vars;

	function startTimer()
	{
		$temp_time = microtime();
		$temp_time = explode(" ", $temp_time);
		$temp_time = $temp_time[1] + $temp_time[0];
		$this->vars['start_time'] = $temp_time;
	}

	function endTimer()
	{
		$temp_time = microtime();
		$temp_time = explode(" ", $temp_time);
		$temp_time = $temp_time[1] + $temp_time[0];
		$this->vars['end_time'] = $temp_time;
		$this->vars['total_time'] = ($this->vars['end_time'] - $this->vars['start_time']);
	}

	function logs($log, $action, $value) {
		$temp_output = "";

		if (!is_dir("phppi/logs/"))
		{
			//Create logs folder
			if (!mkdir("phppi/logs/", 0775))
			{
				return false;
			}
		}

		$datetime = new DateTime('', new DateTimeZone('GMT'));
		$datetime->setTimezone(new DateTimeZone($this->settings['advanced']['log_timezone']));

		if ($action == "add") {
			if ($log == "access" && $this->settings['advanced']['access_log'] == "on") {
				$temp_output = $datetime->format('Y-m-d H:i:s') . ", " . $_SERVER["REMOTE_ADDR"] . ": " . $value;
			} else if ($log == "phppi") {

			}
		}

		if ($temp_output !== "") {
			$fh = @fopen("phppi/logs/" . $log . ".log", 'a');
			fwrite($fh, $temp_output . "\n");
			fclose($fh);
		}
	}

	function setThemeMode()
	{
		require('phppi/includes/classes/browser.php');
		$browser = new Browser();

		$this->vars['isIE'] = false;

		switch ($browser->getBrowser())
		{
			case Browser::BROWSER_ANDROID:
				$this->vars['theme_mode'] = 'mobile';
				if ($this->settings['general']['disable_popup_image_viewer_for_mobile'] == true) { $this->settings['general']['use_popup_image_viewer'] = false; }
				break;
			case Browser::BROWSER_IPHONE:
				$this->vars['theme_mode'] = 'mobile';
				if ($this->settings['general']['disable_popup_image_viewer_for_mobile'] == true) { $this->settings['general']['use_popup_image_viewer'] = false; }
				break;
			case Browser::BROWSER_IPOD:
				$this->vars['theme_mode'] = 'mobile';
				if ($this->settings['general']['disable_popup_image_viewer_for_mobile'] == true) { $this->settings['general']['use_popup_image_viewer'] = false; }
				break;
			case Browser::BROWSER_IE:
				$this->vars['theme_mode'] = 'standard';
				$this->vars['isIE'] = true;
				break;
			default:
				$this->vars['theme_mode'] = 'standard';
				break;
		}
	}

	function loadSettings($theme = false)
    {
	//Set theme to true if you want to retrieve theme settings

		if ($theme == true)
		{
			if (!is_file('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/settings.php'))
			{
				return false;
			} else {
				require('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/settings.php');
				$this->setThumbSize();
				return true;
			}
		} else {
			if (!is_file('phppi/settings.php'))
			{
				return false;
			} else {
				require('phppi/settings.php');
				$this->setThumbSize();
				return true;
			}
		}
    }

	function loadVars()
	{
		$this->vars['dir_local'] = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); //								/var/www/pictures
		$this->vars['dir_req'] = $this->cleanPath($_SERVER['QUERY_STRING']); //										photo/landscape
		$this->vars['dir_req_parent'] = dirname($this->vars['dir_req']); //											photo
		$this->vars['dir_parent'] = $this->pathInfo($_SERVER['SCRIPT_NAME'], 'dir_path'); //						/pictures
		$this->vars['dir_root_cache'] = $this->settings['advanced']['cache_folder'];//								phppi/cache
		$this->vars['dir_cache'] = $this->fixPath($this->vars['dir_root_cache']) . $this->vars['dir_req'];//		photo/landscape/cache

		if ($this->settings['advanced']['cyrillic_support'] == true) {
			$this->vars['dir_req'] = rawurldecode($this->vars['dir_req']);
		}

		$temp_current_req = explode('/', $this->vars['dir_req']);
		$this->vars['dir_req_current'] = $temp_current_req[count($temp_current_req) - 1];//							landscape
		if ($this->vars['dir_req_current'] == '') { $this->vars['dir_req_current'] = '/'; }

		if ($this->vars['dir_req_parent'] == '.')
		{
			$this->vars['dir_req_parent'] = '';
		}
	}

	function loadLists()
	{
		$temp_file = file_get_contents('phppi/file_blacklist.txt');
		$this->vars['file_blacklist'] = explode(",", $temp_file);

		$temp_folder = file_get_contents('phppi/folder_blacklist.txt');
		$this->vars['folder_blacklist'] = explode(",", $temp_folder);

		$temp_type = file_get_contents('phppi/file_types.txt');
		$this->vars['file_types'] = explode(",", $temp_type);
	}

	function checkList($item, $list)
	{
		foreach($list as $list_item)
		{
			if (strtolower($list_item) == strtolower($item))
			{
				return true;
			}
		}

		return false;
	}

	function cleanPath($path)
	{
		$path = str_replace('%20', ' ', $path);

		if (substr($path, 0, 1) == '/')
		{
			$path = substr($path, 1);
		}

		if (substr($path, -1, 1) == '/')
		{
			$path = substr($path, 0, -1);
		}

		return $path;
	}

	function checkExploit($path, $file = false) {
		$real_base = realpath($this->vars['dir_local']);

		$path = (DIRECTORY_SEPARATOR === '\\') ? str_replace('/', '\\', $path) : str_replace('\\', '/', $path);

		$var_path = $this->vars['dir_local'] . $path;
		$real_var_path = realpath($var_path);

		/*echo "Requested: " . $path . "<br>";
		echo "Base real path: " . $real_base . "<br>";
		echo "Requested real path: " . $real_var_path . "<br>";
		echo "Therefore " . $real_base . $path . " should equal " . $real_var_path . "<br>";*/

		if ($real_var_path === false || ($real_base . $path) !== $real_var_path) {
			return false;
		} else {
			return true;
		}
	}

	function escapeString($string, $action = "add") {
		if ($action == "add") {
			if (get_magic_quotes_gpc()) {
				return $string;
			} else {
				return addslashes($string);
			}
		} elseif ($action == "strip") {
			return stripslashes($string);
		}
	}

	function pathInfo($path, $info)
	{
		$temp = pathinfo($path);

		if ($info == 'dir_path')
		{
			return $temp['dirname'];
		} else if ($info == 'full_file_name') {
			return $temp['basename'];
		} else if ($info == 'file_ext') {
			return $temp['extension'];
		} else if ($info == 'file_name') {
			return $temp['filename'];
		}
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

	function getDir($dir) {
		if ($dir_data = $this->getDirData($dir, 'both', true)) {
			if (isset($dir_data['file'])) { if (count($dir_data['file']) > 0) { $this->vars['file_list'] = $dir_data['file']; } }
			if (isset($dir_data['dir'])) { if (count($dir_data['dir']) > 0) { $this->vars['folder_list'] = $dir_data['dir']; } }

			$cache_folder = $this->fixPath($this->vars['dir_root_cache']);
			$dir = $this->fixPath($dir);

			if (($this->settings['advanced']['use_file_cache'] == true) && (time() - @filemtime($cache_folder . $dir . 'cache.xml') > $this->settings['advanced']['expire_file_cache']))
			{
				$this->cacheDir($dir);
			}

			return true;
		} else {
			return false;
		}
	}

	function getDirData($dir, $type = 'both', $cached = false, $forced_cache = false) {
		//$full_dir: Root folder combined with requested folder with trailing /
		//$dir: Requested folder
		//$dh: Directory Handler
		//$item: File/Dir data during directory scan
		//$fd: Found Directories array
		//$ff: Found Files array

		$cache_folder = $this->fixPath($this->vars['dir_root_cache']);
		$dir = $this->fixPath($dir);
		$full_dir = $this->fixPath($this->vars['dir_local']) . $dir;
		$output = array();
		$cache_expire = true;
		$ff = array();
		$fd = array();

		if (is_dir($full_dir)) {
			if ($cached == true && $this->settings['advanced']['use_file_cache'] == true && is_file($cache_folder . $dir . 'cache.xml')) {
				if (((time() - filemtime($cache_folder . $dir . 'cache.xml')) < $this->settings['advanced']['expire_file_cache']) || $forced_cache == true) {
					$cache_expire = false;
				} else {
					$cache_expire = true;
				}
			} else {
				$cache_expire = true;
			}

			if ($cache_expire == false) {
				$xml = new SimpleXMLElement(file_get_contents($cache_folder . $dir . 'cache.xml'));

				$x = 0;

				if (isset($xml->directories))
				{
					foreach($xml->directories->dir as $dirs)
					{
						$fd[$x]['full_path'] = (string)$dirs->path;
						$fd[$x]['dir'] = (string)$dirs->dirname;

						$x++;
					}
				}

				$x = 0;

				if (isset($xml->files))
				{
					foreach($xml->files->file as $files)
					{
						$ff[$x]['full_path'] = (string)$files->path;
						$ff[$x]['file'] = (string)$files->filename;
						$ff[$x]['data'][0] = (integer)$files->data->width;
						$ff[$x]['data'][1] = (integer)$files->data->height;
						$ff[$x]['data'][2] = (integer)$files->data->imagetype;
						$ff[$x]['data'][3] = (string)$files->data->sizetext;
						if (isset($files->data->bits)) { $ff[$x]['data']['bits'] = (integer)$files->data->bits; }
						if (isset($files->data->channels)) { $ff[$x]['data']['channels'] = (integer)$files->data->channels; }
						if (isset($files->data->mime)) { $ff[$x]['data']['mime'] = (string)$files->data->mime; }

						$x++;
					}
				}
			} else if ($cache_expire == true) {
				if ($dh = opendir($full_dir)) {
					while (($item = readdir($dh)) !== false) {
						if (filetype($full_dir . $item) == 'dir' && $type != 'file' && $this->checkList($item, $this->vars['folder_blacklist']) == false)
						{
							$fd[] = array(
								'full_path'=>$dir . $item,
								'dir'=>$item
							);

							sort($fd);
						} else if (filetype($full_dir . $item) == 'file' && $type != 'dir' && $this->checkList($item, $this->vars['file_blacklist']) == false && $this->checkList($this->pathInfo($item, 'file_ext'), $this->vars['file_types']) == true) {
							$ff[] = array(
								'full_path'=>$dir . $item,
								'file'=>$item,
								'data'=>getimagesize($full_dir . $item)
							);

							sort($ff);
						}
					}
					closedir($dh);
				} else {
					return false;
				}
			}

			if ($type == 'both') {
				if (isset($ff)) { $output['file'] = $ff; }
				if (isset($fd)) { $output['dir'] = $fd; }

				return $output;
			} else if ($type == 'file') {
				if (isset($ff)) { $output = $ff; }

				return $output;
			} else if ($type == 'dir') {
				if (isset($fd)) { $output = $fd; }

				return $output;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function cacheDir($dir)
	{
		$cache_folder = $this->fixPath($this->vars['dir_root_cache']);
		$cache_exists = false;

		if (count($this->vars['folder_list']) > 0 or count($this->vars['file_list']) > 0) {
			$cache_exists = $this->genCacheDir($dir);

			if ($cache_exists == true)
			{
				$xmlstr = "<?xml version='1.0' ?>\n<cache></cache>";
				$xml = new SimpleXMLElement($xmlstr);

				if (isset($this->vars['folder_list']))
				{
					$xml_dir = $xml->addChild('directories');

					foreach($this->vars['folder_list'] as $dirs)
					{
						$xml_dirs_data = $xml_dir->addChild('dir');
						$xml_dirs_data->addChild('path', $dirs['full_path']);
						$xml_dirs_data->addChild('dirname', $dirs['dir']);
					}
				}

				if (isset($this->vars['file_list']))
				{
					$xml_files = $xml->addChild('files');

					foreach($this->vars['file_list'] as $files)
					{
						$xml_files_data = $xml_files->addChild('file');
						$xml_files_data->addChild('path', $files['full_path']);
						$xml_files_data->addChild('filename', $files['file']);

						$xml_data = $xml_files_data->addChild('data');
						$xml_data->addChild('width', $files['data'][0]);
						$xml_data->addChild('height', $files['data'][1]);
						$xml_data->addChild('imagetype', $files['data'][2]);
						$xml_data->addChild('sizetext', $files['data'][3]);
						if (isset($files['data']['bits'])) { $xml_data->addChild('bits', $files['data']['bits']); }
						if (isset($files['data']['channels'])) { $xml_data->addChild('channels', $files['data']['channels']); }
						if (isset($files['data']['mime'])) { $xml_data->addChild('mime', $files['data']['mime']); }
					}
				}

				$xml->asXML($cache_folder . $dir . 'cache.xml');
				return true;

			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function genCacheDir($dir) {
		$cache_folder = $this->fixPath($this->vars['dir_root_cache']);

		if (!is_dir($cache_folder . $dir))
		{
			//Create cache folder/s if possible
			$temp_folders = explode('/', substr($dir, 0, -1));
			$prefix = '';

			if (count($temp_folders) > 0) {
				foreach($temp_folders as $dirs) {
					if (!is_dir($cache_folder . $prefix . $dirs)) {
						if (mkdir($cache_folder . $prefix . $dirs, 0775))
						{
							chmod($cache_folder . $prefix . $dirs, 0775);
							$prefix .= $dirs . '/';
							$cache_exists = true;
						} else {
							$cache_exists = false;
							break;
						}
					} else {
						$prefix .= $dirs . '/';
						$cache_exists = true;
					}
				}
			}
		} else {
			$cache_exists = true;
		}

		if ($cache_exists == true) {
			return true;
		} else {
			return false;
		}
	}

	function genThumbURL($dir, $file_data) {
		$cache_folder = $this->fixPath($this->vars['dir_root_cache']);
		$use_cache = false;
		$file_ext = '';
		$temp_file_ext = '';
		$thumb_width = 0;
		$thumb_height = 0;
		$thumb_size = array();

		$file_ext = $this->pathInfo($file_data['full_path'], 'file_ext');
		//$temp_file_ext = strtolower($file_ext);

		$dir = $this->fixPath($dir);

		//if ($temp_file_ext == 'jpeg' or $temp_file_ext == 'jpg') { $file_ext = 'jpg'; }

		if ($this->settings['advanced']['use_gd'] == true)
		{
			if ($this->settings['advanced']['use_gd_cache'] == true)
			{
				$use_cache = false;

				if (!is_file($cache_folder . $dir . $this->pathInfo($file_data['full_path'], 'file_name') . '_' . $this->vars['thumb_size'] .  '.' . $file_ext))
				{
					//Cached image does not exist, create if possible
					$use_cache = false;
				} else {
					//Cached image exists, check if correct image size
					list($thumb_width, $thumb_height) = getimagesize($cache_folder . $dir . $this->pathInfo($file_data['full_path'], 'file_name') . '_' . $this->vars['thumb_size'] .  '.' . $file_ext);

					$thumb_size = $this->resizedSize($file_data['data'][0], $file_data['data'][1]);

					if ($thumb_size[0] != $thumb_width and $thumb_size[1] != $thumb_height)
					{
						//Cached image does not match the current thumbnail size settings, create new thumbnail
						$use_cache = false;
					} else {
						//Cached image does not need updating, use cached thumbnail
						$use_cache = true;
					}
				}

				if ($use_cache == true)
				{
					$img_url = $cache_folder . $dir . $this->pathInfo($file_data['full_path'], 'file_name') . '_' . $this->vars['thumb_size'] .  '.' . $file_ext;
				} else {
					$img_url = '?thumb=' . $dir . $this->pathInfo($file_data['full_path'], 'file_name') . '.' . $file_ext . '&size=' . $this->vars['thumb_size'];
				}
			} else {
				$img_url = '?thumb=' . $dir . $this->pathInfo($file_data['full_path'], 'file_name') . '.' . $file_ext . '&size=' . $this->vars['thumb_size'];
			}
		}  else {
			$img_url = $this->fixPath($this->settings['advanced']['thumbs_folder']) . $dir . $this->pathInfo($file_data['full_path'], 'file_name') . '.' . $this->settings['general']['thumb_file_ext'];
		}

		return $img_url;
	}

	function genThumbnail($filename)
	{
		//Creates thumbnail, either dynamically or for cache depending on settings

		$filename = $this->escapeString($filename, "strip");

		if ($this->checkExploit('/' . $filename, true) == true) {
			$filename = realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/' . $filename;

			$temp_path = substr($this->fixPath($this->pathInfo($filename, 'dir_path')), strlen($this->vars['dir_local']));
			if (substr($temp_path, 0, 1) == '/') { $temp_path = substr($temp_path, 1); }

			$cache_folder = $this->fixPath($this->vars['dir_root_cache']) . $temp_path;

			$create_cache_file = false;

			if ($this->settings['advanced']['use_gd'] == true)
			{
				$create_cache_file = $this->genCacheDir($temp_path);
			}

			$file_ext = strtolower($this->pathInfo($filename, 'file_ext'));

			if ($file_ext == 'jpg' or $file_ext == 'jpeg')
			{
				$image = imagecreatefromjpeg($filename);
				$format = 'jpeg';
			} else if ($file_ext == 'png') {
				$image = imagecreatefrompng($filename);
				$format = 'png';
			} else if ($file_ext == 'gif') {
				$image = imagecreatefromgif($filename);
				$format = 'gif';
			}

			$width = imagesx($image);
			$height = imagesy($image);

			$new_size = $this->resizedSize($width, $height);

			$new_image = ImageCreateTrueColor($new_size[0], $new_size[1]);
			imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_size[0], $new_size[1], $width, $height);

			if ($create_cache_file == false)
			{
				header('Pragma: public');
				header('Cache-Control: maxage=' . $this->settings['advanced']['gd_cache_expire']);
				header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $this->settings['advanced']['gd_cache_expire']) . ' GMT');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

				if ($format == 'jpeg')
				{
					header('Content-type: image/jpeg');
					imagejpeg($new_image, null, $this->settings['advanced']['jpeg_quality']);
				} else if ($format == 'png') {
					header('Content-type: image/png');
					imagepng($new_image);
				} else if ($format == 'gif') {
					header('Content-type: image/gif');
					imagegif($new_image);
				}
			} else if ($create_cache_file == true) {
				header('Pragma: public');
				header('Cache-Control: maxage=' . $this->settings['advanced']['gd_cache_expire']);
				header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $this->settings['advanced']['gd_cache_expire']) . ' GMT');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

				if ($format == 'jpeg')
				{
					header('Content-type: image/jpeg');
					imagejpeg($new_image, $cache_folder . $this->pathInfo($filename, 'file_name') . '_' . $this->vars['thumb_size'] . '.' . $this->pathInfo($filename, 'file_ext'), $this->settings['advanced']['jpeg_quality']);
					imagejpeg($new_image);
				} else if ($format == 'png') {
					header('Content-type: image/png');
					imagepng($new_image, $cache_folder . $this->pathInfo($filename, 'file_name') . '_' . $this->vars['thumb_size'] . '.' . $this->pathInfo($filename, 'file_ext'));
					imagepng($new_image);
				} else if ($format == 'gif') {
					header('Content-type: image/gif');
					imagegif($new_image, $cache_folder . $this->pathInfo($filename, 'file_name') . '_' . $this->vars['thumb_size'] . '.' . $this->pathInfo($filename, 'file_ext'));
					imagegif($new_image);
				}
			}

			if ($this->settings['advanced']['access_log_no_thumbnail'] == false) {
				$this->logs("access", "add", "Generated thumbnail (/" . $cache_folder . $this->pathInfo($filename, 'file_name') . '_' . $this->vars['thumb_size'] . '.' . $this->pathInfo($filename, 'file_ext') . ")");
			}

			imagedestroy($new_image);
		} else {
			echo 'File not found.';
		}
	}

	function setThumbSize($size) {
		//$size: small|medium|large

		if (isset($size) && $this->settings['general']['enable_thumb_size_change'] == true) {
			setcookie("thumb_size", $size);
			$this->vars['thumb_size'] = $size;
		} else {
			if (isset($_COOKIE['thumb_size']) && $this->settings['general']['enable_thumb_size_change'] == true) {
				$this->vars['thumb_size'] = $_COOKIE['thumb_size'];
			} else {
				$this->vars['thumb_size'] = $this->settings['general']['thumb_size_default'];
			}
		}
	}

	function insertThumbSize($format = 0)
	{
		//0 = Output thumb size changer code
		//1 = Return thumb size changer code as string

		if ($this->settings['general']['enable_thumb_size_change'] == true && $this->settings['advanced']['use_gd'] == true) {
			$output .= "<form method=\"post\">";
			$output .= "<select id=\"thumb-size-select\" name=\"thumb_size\">";
			$output .= "<option value=\"0\" " . ($this->vars['thumb_size'] == 'small' ? 'selected' : '') . ">Small</option>";
			$output .= "<option value=\"1\" " . ($this->vars['thumb_size'] == 'medium' ? 'selected' : '') . ">Medium</option>";
			$output .= "<option value=\"2\" " . ($this->vars['thumb_size'] == 'large' ? 'selected' : '') . ">Large</option>";
			$output .= "</select>&nbsp;";
			$output .= "<input type=\"submit\" value=\"Set\">";
			$output .= "</form>";
		} else {
			$output = "";
		}

		if ($format == 0)
		{
			echo $output;
		} else if ($format == 1) {
			return $output;
		}
	}

	function prevFolderExists()
	{
		if ($this->vars['dir_req'] != '')
		{
			return true;
		} else {
			return false;
		}
	}

	function prevImageExists($ignore_javascript = false)
	{
		//Set ignore_javascript to true if you want accurate results, otherwise if use_javascript_navigation is set to true this will always return true

		if (isset($this->vars['previous_image_id']))
		{
			return true;
		} else if ($ignore_javascript == false and $this->settings['advanced']['use_javascript_navigation'] == true) {
			return true;
		} else {
			return false;
		}
	}

	function nextImageExists($ignore_javascript = false)
	{
		//Set ignore_javascript to true if you want accurate results, otherwise if use_javascript_navigation is set to true this will always return true

		if (isset($this->vars['next_image_id']))
		{
			return true;
		} else if ($ignore_javascript == false and $this->settings['advanced']['use_javascript_navigation'] == true) {
			return true;
		} else {
			return false;
		}
	}

	function noticeExists()
	{
		if ($this->settings['general']['site_notice'] != '')
		{
			return true;
		} else {
			return false;
		}
	}

	function outputSettingsArray()
	{
		echo '<pre>';
		print_r($this->settings);
		echo '</pre>';
	}

	function outputVarsArray()
	{
		echo '<pre>';
		print_r($this->vars);
		echo '</pre>';
	}

	function showError($format = 0)
	{
		//0 = Output error
		//1 = Return error as string

		if ($format == 0)
		{
			echo $this->vars['error'];
		} else if ($format == 1) {
			return $this->vars['error'];
		}
	}

	function showNotice($format = 0)
	{
		//0 = Output notice
		//1 = Return notice as string

		if ($format == 0)
		{
			echo $this->settings['general']['site_notice'];
		} else if ($format == 1) {
			return $this->settings['general']['site_notice'];
		}
	}

	function showImage($format = 2)
	{
		//0 = Output url
		//1 = Return url as string
		//2 = Output full img tag
		//3 = Return full img tag as string

		if ($format == 0)
		{
			echo $_GET['file'];
		} else if ($format == 1) {
			return $_GET['file'];
		} else if ($format == 2) {
			$output = '<img id="image" src="' . $_GET['file'] . '" alt="' . $_GET['file'] . '">';

			if ($this->settings['general']['enable_click_next'] == true && isset($this->vars['file_list'][$this->vars['next_image_id']])) {
				if ($this->settings['advanced']['use_javascript_navigation'] == true) {
					$output = '<a href="javascript: phppi.go_next_image();">' . $output . '</a>';
				} else {
					$output = '<a href="?file=' . $this->vars['file_list'][$this->vars['next_image_id']]['full_path'] . '">' . $output . '</a>';
				}
			}

			echo $output;
		} else if ($format == 3) {
			$output =  '<img id="image" src="' . $_GET['file'] . '" alt="' . $_GET['file'] . '">';

			if ($this->settings['general']['enable_click_next'] == true && isset($this->vars['file_list'][$this->vars['next_image_id']])) {
				if ($this->settings['advanced']['use_javascript_navigation'] == true) {
					$output = '<a href="javascript: phppi.go_next_image();">' . $output . '</a>';
				} else {
					$output = '<a href="?file=' . $this->vars['file_list'][$this->vars['next_image_id']]['full_path'] . '">' . $output . '</a>';
				}
			}

			return $output;
		}
	}

	function showFooter($format = 0)
	{
		//0 = Output footer
		//1 = Return footer as string

		if (is_file('phppi/footer.txt'))
		{
			$footer = file_get_contents('phppi/footer.txt');

			$this->endTimer();

			$search = array(
				'[site_name]',
				'[current_item]',
				'[version]',
				'[load_time]'
				);
			$replace = array(
				$this->settings['general']['site_name'],
				$this->vars['page_title'],
				$this->vars['version'],
				number_format($this->vars['total_time'], 4)
				);

			if ($format == 0)
			{
				echo str_replace($search, $replace, $footer);
			} else if ($format == 1) {
				return str_replace($search, $replace, $footer);
			}
		}
	}

	function showGallery()
	{
		if ($this->vars['dir_req'] != '')
		{
			$request = $this->vars['dir_req'] . '/';
		} else {
			$request = '';
		}

		if (isset($this->vars['folder_list']))
		{
			foreach ($this->vars['folder_list'] as $dir)
			{
				if (is_dir($request . $dir['dir']))
				{
					if ($this->settings['general']['thumb_folder_show_thumbs'] == true) {
						if ($this->settings['general']['thumb_folder_use_cache_only'] == true) {
							$dir_data = $this->getDirData($request . $dir['dir'], 'both', true, true);
						} else {
							$dir_data = $this->getDirData($request . $dir['dir'], 'both', true);
						}

						if ($this->settings['general']['thumb_folder_shuffle'] == true) { shuffle($dir_data['file']); }

						if ($dir_data['file']) {
							$temp_dir_data = $dir_data['file'][0];
							$img_url = $this->genThumbURL($request . $dir['dir'], $temp_dir_data);
						} else {
							$img_url = $this->showThemeURL(1) . 'images/no_images.png';
						}
					} else {
						$img_url = $this->showThemeURL(1) . 'images/no_images.png';
					}
				} else {
					$img_url = $this->showThemeURL(1) . 'images/no_images.png';
				}

				if ($this->settings['general']['folder_show_title_on_hover'] == false) {
					$class = '-show';
				} else {
					$class = '';
				}

				$output = '';
				$output .= '<a class="thumb-container" href="?' . $request . $dir['dir'] .'" title="' . $dir['dir'] . '" style="width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;">';
				$output .= '<div class="thumb" style="background-image: url(\'' . $this->showThemeURL(1) . 'images/hover_folder.png\'); width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;">';
				$output .= '<div class="thumb-folder" style="background-image: url(\'' . $this->showThemeURL(1) . 'images/folder_icon.png\'); width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"></div>';
				$output .= '<div class="thumb-image" style="background-image: url(\'' . $this->escapeString($img_url) . '\'); width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"></div>';
				$output .= '<div class="thumb-title-back' . $class . '" style="width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"><div>&nbsp;</div></div>';
				$output .= '<div class="thumb-title-container' . $class . '" style="width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"><div>' . $dir['dir'] . '</div></div></div>';
				$output .= '</a>';
				$output .= "\n";

				echo $output;
			}
		}

		if (isset($this->vars['file_list']))
		{
			foreach ($this->vars['file_list'] as $file)
			{
				$output = '';

				$img_url = $this->genThumbURL($request, $file);

				if ($this->settings['general']['use_popup_image_viewer'] == true) {
					$url = $request . $file['file'];
					if ($this->settings['general']['show_thumbs_under_viewer'] == true) {
						$attr = 'class="thumb-container fancybox-thumbs" data-fancybox-group="thumb"';
					} else {
						$attr = 'class="thumb-container fancybox" data-fancybox-group="gallery"';
					}
				} else {
					$url = '?file=' . $request . $file['file'];
					$attr = 'class="thumb-container"';
				}

				if ($this->settings['general']['image_show_title_on_hover'] == false) {
					$class = '-show';
				} else {
					$class = '';
				}

				$output .= '<a ' . $attr . ' href="' . $url . '" title="' . $file['file'] . '" style="width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;" >';
				$output .= '<div class="thumb" style="background-image: url(\'' . $this->showThemeURL(1) . 'images/hover_image.png\'); width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;">';
				$output .= '<div class="thumb-image" style="background-image: url(\'' . $this->escapeString($img_url) . '\'); width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px; height: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"></div>';
				$output .= '<div class="thumb-title-back' . $class . '" style="width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"><div>&nbsp;</div></div>';
				$output .= '<div class="thumb-title-container' . $class . '" style="width: ' . $this->settings['general']['thumb_size'][$this->vars['thumb_size']] . 'px;"><div>' . $file['file'] . '</div></div></div>';
				$output .= '</a>';
				$output .= "\n";

				echo $output;
			}
		}

		echo "<div style=\"clear: both;\"></div>\n";
	}

	function showPrevFolderURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string
		if ($format == 0)
		{
			echo '?' . $this->vars['dir_req_parent'];
		} else if ($format == 1) {
			return '?' . $this->vars['dir_req_parent'];
		}
	}

	function showPrevImageURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string
		if ($format == 0)
		{
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				echo 'javascript: phppi.go_prev_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['previous_image_id']]['full_path'])
				{
					echo '?file=' . $this->vars['file_list'][$this->vars['previous_image_id']]['full_path'];
				} else {
					echo '';
				}
			}
		} else if ($format == 1) {
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				return 'javascript: phppi.go_prev_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['previous_image_id']]['full_path'])
				{
					return '?file=' . $this->vars['file_list'][$this->vars['previous_image_id']]['full_path'];
				} else {
					return '';
				}
			}
		}
	}

	function showNextImageURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string
		if ($format == 0)
		{
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				echo 'javascript: phppi.go_next_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['next_image_id']]['full_path'])
				{
					echo '?file=' . $this->vars['file_list'][$this->vars['next_image_id']]['full_path'];
				} else {
					echo '';
				}
			}
		} else if ($format == 1) {
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				return 'javascript: phppi.go_next_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['next_image_id']]['full_path'])
				{
					return '?file=' . $this->vars['file_list'][$this->vars['next_image_id']]['full_path'];
				} else {
					return '';
				}
			}
		}
	}

	function showUpFolderURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string
		if ($format == 0)
		{
			echo '?' . $this->pathInfo($_GET['file'], 'dir_path');
		} else if ($format == 1) {
			return '?' . $this->pathInfo($_GET['file'], 'dir_path');
		}
	}

	function showThemeURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string
		if ($format == 0)
		{
			echo 'phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/';
		} else if ($format == 1) {
			return 'phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/';
		}
	}

	function showTitle($format = 0)
	{
		//0 = Output url
		//1 = Return url as string
		$search = array(
			'[S]',
			'[P]'
			);
		$replace = array(
			$this->settings['general']['site_name'],
			$this->vars['page_title']
			);

		if ($format == 0)
		{
			echo str_replace($search, $replace, $this->settings['general']['page_title_format']);
		} else if ($format == 1) {
			return str_replace($search, $replace, $this->settings['general']['page_title_format']);
		}
	}

	function showPage()
	{
		require($this->showThemeURL(1) . 'pages/' . $this->vars['page_requested'] . '.php');
	}

	function resizedSize($width, $height, $return = 2)
	{
		//Returns width, height or an array of width and height for the thumbnail size of a full sized image
		if ($width > $height)
		{
			$new_height = $this->settings['general']['thumb_size'][$this->vars['thumb_size']];
			$new_width = $width * ($this->settings['general']['thumb_size'][$this->vars['thumb_size']] / $height);
		} else if ($width < $height) {
			$new_height = $height * ($this->settings['general']['thumb_size'][$this->vars['thumb_size']] / $width);
			$new_width = $this->settings['general']['thumb_size'][$this->vars['thumb_size']];
		} else if ($width == $height) {
			$new_width = $this->settings['general']['thumb_size'][$this->vars['thumb_size']];
			$new_height = $this->settings['general']['thumb_size'][$this->vars['thumb_size']];
		}

		if ($return == 0)
		{
			//Return width
			return floor($new_width);
		} else if ($return == 1) {
			//Return height
			return floor($new_height);
		} else if ($return == 2) {
			//Return array with width and height
			return array(floor($new_width), floor($new_height));
		}
	}

	function insertHeadInfo()
	{
		echo "
<!--
PHP Picture Index " . $this->vars['version'] . "

Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://phppi.pixelizm.com/
Licence: GNU General Public License v3
http://www.gnu.org/licenses/
-->\n\n";

		echo "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; user-scalable = no; maximum-scale=1.0;\">\n";
		if (isset($_GET['file']) && !isset($this->vars['error'])) {
			echo "<script type=\"text/javascript\" src=\"phppi/scripts/jquery/jquery-1.7.2.min.js\"></script>";
		} elseif ($this->settings['general']['use_popup_image_viewer'] == true) {
			echo "<script type=\"text/javascript\" src=\"phppi/scripts/jquery/jquery-1.7.2.min.js\"></script>\n";
		}

		if (isset($_GET['file']) && !isset($this->vars['error']))
		{
			if ($this->settings['general']['page_title_show_full_path'] == true) { $temp_title_full_path = '1'; } else { $temp_title_full_path = '0'; }
			if ($this->settings['general']['enable_hotkeys']) { $enable_hotkeys = 1; } else { $enable_hotkeys = 0; }
			if ($this->settings['general']['enable_up_hotkey']) { $enable_up_hotkey = 1; } else { $enable_up_hotkey = 0; }

			echo "
<script type=\"text/javascript\" src=\"phppi/scripts/phppi_js.js\"></script>
<script type=\"text/javascript\">
	$(document).ready(function() { phppi.initialize(); });

	phppi.image_width = " . $this->vars['file_list'][$this->vars['current_image_id']]['data'][0] . ";
	phppi.image_height = " . $this->vars['file_list'][$this->vars['current_image_id']]['data'][1] . ";
	phppi.up_folder = '" . $this->escapeString($this->showUpFolderURL(1)) . "';
	phppi.prev_image = '" . $this->escapeString($this->showPrevImageURL(1)) . "';
	phppi.next_image = '" . $this->escapeString($this->showNextImageURL(1)) . "';
	phppi.title_full_path = " . $temp_title_full_path . ";
	phppi.enable_hotkeys = " . $enable_hotkeys . ";
	phppi.enable_up_hotkey = " . $enable_up_hotkey . ";";

			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				$file_list = "";
				$x = 0;

				$dir = $this->pathInfo($_GET['file'], 'dir_path');

				foreach($this->vars['file_list'] as $file) {
					$file_list .= "['" . $this->escapeString($dir) . "/" . $this->escapeString($file['file']) . "', '" . $this->escapeString($file['file']) . "', " . $file['data'][0] . ", " . $file['data'][1] . "]";

					if ($x < (count($this->vars['file_list']) - 1)) { $file_list .= ","; }

					$x++;
				}

				echo "
	phppi.site_name = '" . $this->settings['general']['site_name'] . "';
	phppi.page_title = '" . $this->vars['page_title'] . "';
	phppi.page_title_format = '" . $this->settings['general']['page_title_format'] . "';
	phppi.current_file = " . $this->vars['current_image_id'] . ";
	phppi.files = [" . $file_list . "];";
			}

			echo "</script>\n";
		}

		if ($this->settings['general']['use_popup_image_viewer'] == true)
		{
			echo "<script type=\"text/javascript\" src=\"phppi/scripts/fancybox/jquery.fancybox.js\"></script>\n";
			if ($this->settings['general']['show_thumbs_under_viewer'] == true) { echo "<script type=\"text/javascript\" src=\"phppi/scripts/fancybox/jquery.fancybox-thumbs.js\"></script>\n"; }
			if ($this->settings['general']['enable_mousewheel'] == true) { echo "<script type=\"text/javascript\" src=\"phppi/scripts/fancybox/jquery.mousewheel-3.0.6.pack.js\"></script>\n"; }

			if ($this->settings['general']['show_thumbs_under_viewer'] == true) {
				//Thumb Helper Version
				echo "<script type=\"text/javascript\">
	$(document).ready(function() {
		$('.fancybox-thumbs').fancybox({
			openEffect: '" . $this->settings['general']['open_image_animation'] . "',
			closeEffect: '" . $this->settings['general']['close_image_animation'] . "',
			prevEffect: '" . $this->settings['general']['nextprev_image_animation'] . "',
			nextEffect: '" . $this->settings['general']['nextprev_image_animation'] . "',

			closeBtn: false,
			arrows: false,
			nextClick: true,

			helpers: {
				thumbs: {
					width: " . $this->settings['general']['popup_thumb_size'] . ",
					height: " . $this->settings['general']['popup_thumb_size'] . "
				}
			}
		});
	});
</script>\n";
			} else {
				//Normal Version
				echo "<script type=\"text/javascript\">
	$(document).ready(function() {
		$('.fancybox').fancybox({
			openEffect: '" . $this->settings['general']['open_image_animation'] . "',
			closeEffect: '" . $this->settings['general']['close_image_animation'] . "',
			prevEffect: '" . $this->settings['general']['nextprev_image_animation'] . "',
			nextEffect: '" . $this->settings['general']['nextprev_image_animation'] . "'
		});
	});
</script>\n";
			}

			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"phppi/scripts/fancybox/jquery.fancybox.css\">\n";
			if ($this->settings['general']['show_thumbs_under_viewer'] == true) { echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"phppi/scripts/fancybox/jquery.fancybox-thumbs.css\">\n"; }
		}

		if ($this->settings['general']['use_css_animations'] == true and $this->vars['isIE'] == false) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"phppi/css/global.css\">\n";
		} else {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"phppi/css/global_no_anim.css\">\n";
		}
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->showThemeURL(1) . "style.css\">\n";
	}

	function initialize()
	{
		//Debug Mode
		if ($this->settings['advanced']['debug_mode'] == true)
		{
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}

		//Set Thumb Size if changed
		if (isset($_POST['thumb_size'])) {
			if ($_POST['thumb_size'] == 0) {
				$this->setThumbSize('small');
			} else if ($_POST['thumb_size'] == 1) {
				$this->setThumbSize('medium');
			} else if ($_POST['thumb_size'] == 2) {
				$this->setThumbSize('large');
			}
		}

		//GZIP Compression
		ini_set('zlib.output_compression', $this->settings['advanced']['use_gzip_compression']);
		ini_set('zlib.output_compression_level', $this->settings['advanced']['gzip_compression_level']);

		//Theme Mode
		$this->setThemeMode();

		if ($this->settings['advanced']['allow_mobile_theme'] == true)
		{
			if (!is_file('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/template.php'))
			{
				$this->vars['theme_mode'] = 'standard';
			}
		} else {
			$this->vars['theme_mode'] = 'standard';
		}

		//Theme Specific Settings
		if ($this->settings['advanced']['allow_theme_settings'] == true)
		{
			$this->loadSettings(true);
		}

		//Load Variables
		$this->loadVars();

		//Load Blacklists/Whitelists
		$this->loadLists();

		//Display Content
		if (isset($_GET['thumb']))
		{
			//Show thumbnail only
			$this->genThumbnail($_GET['thumb']);
		} else if (isset($_GET['file'])) {
			//Show full image view
			$req_path = $this->pathInfo($_GET['file'], 'dir_path');

			if ($this->checkExploit('/'  . $req_path) == true) {
				if (!$this->getDir($req_path . '/'))
				{
					$this->vars['error'] = 'Folder doesn\'t exist';
					$this->vars['page_title'] = 'Error';
					$this->vars['page_requested'] = 'error';

					$this->logs("access", "add", "Folder not found (/" . $_GET['file'] . ")");
				} else if (!is_file($_GET['file'])) {
					$this->vars['error'] = 'File doesn\'t exist';
					$this->vars['page_title'] = 'Error';
					$this->vars['page_requested'] = 'error';

					$this->logs("access", "add", "File not found (/" . $_GET['file'] . ")");
				} else {
					for($i = 0; $i < count($this->vars['file_list']); $i++)
					{
						if ($this->vars['file_list'][$i]['file'] == $this->pathInfo($_GET['file'], 'full_file_name'))
						{
							$this->vars['current_image_id'] = $i;

							if ($i > 0)
							{
								$this->vars['previous_image_id'] = $i - 1;
							}
							if ($i < (count($this->vars['file_list']) - 1))
							{
								$this->vars['next_image_id'] = $i + 1;
							}

							break;
						}
					}

					if ($this->settings['general']['page_title_show_full_path'] == true) {
						$this->vars['page_title'] = '/' . $_GET['file'];
					} else {
						$this->vars['page_title'] = $this->pathInfo($_GET['file'], 'full_file_name');
					}
					$this->vars['page_requested'] = 'image';

					$this->logs("access", "add", "Viewed image (/" . $_GET['file'] . ")");
				}
			} else {
				$this->vars['error'] = 'File doesn\'t exist';
				$this->vars['page_title'] = 'Error';
				$this->vars['page_requested'] = 'error';

				$this->logs("access", "add", "Possible exploit attempt, blocked access (/" . $_GET['file'] . ")");
			}

			require('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/template.php');

			if ($this->settings['advanced']['debug_show_all'] == true)
			{
				echo "DEBUG - Page Variables: <br><br>";
				echo "<pre>";
				print_r($this->vars);
				echo "</pre>";
			}
		} else {
			//Show folder view
			if ($this->vars['dir_req'] == '')
			{
				$dir_req = '';
			} else {
				$dir_req = $this->vars['dir_req'] . '/';
			}

			if ($this->vars['dir_req'] == '' || $this->checkExploit('/' . $this->vars['dir_req']) == true) {
				if (!$this->getDir($dir_req))
				{
					$this->vars['error'] = 'Folder doesn\'t exist';
					$this->vars['page_title'] = 'Error';
					$this->vars['page_requested'] = 'error';

					$this->logs("access", "add", "Folder not found (/" . $dir_req . ")");
				} else {
					if ($this->settings['general']['page_title_show_full_path'] == true) {
						$this->vars['page_title'] = '/' . $this->vars['dir_req'];
					} else {
						$this->vars['page_title'] = $this->vars['dir_req_current'];
					}
					$this->vars['page_requested'] = 'folder';

					$this->logs("access", "add", "Viewed folder (/" . $dir_req . ")");
				}
			} else {
				$this->vars['error'] = 'Folder doesn\'t exist';
				$this->vars['page_title'] = 'Error';
				$this->vars['page_requested'] = 'error';

				$this->logs("access", "add", "Folder not found or exploit attempt, blocked access (/" . $dir_req . ")");
			}

			require('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/template.php');

			if ($this->settings['advanced']['debug_show_all'] == true)
			{
				echo "DEBUG - Page Variables: <br><br>";
				echo "<pre>";
				print_r($this->vars);
				echo "</pre>";
			}
		}
	}
}
?>