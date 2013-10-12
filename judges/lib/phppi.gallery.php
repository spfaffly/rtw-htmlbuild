<?php
function resizedSize($width, $height, $mode = "fill", $return = 2) {
	global $phppi;
	
	//Returns width, height or an array of width and height for the thumbnail size of a full sized image
	if ($mode == "" || $mode == "fill") {
		if ($width > $height) {
			$new_height = $phppi['settings']['thumb_size_' . $phppi['thumb_size']];
			$new_width = $width * ($phppi['settings']['thumb_size_' . $phppi['thumb_size']] / $height);
		} else if ($width < $height) {
			$new_height = $height * ($phppi['settings']['thumb_size_' . $phppi['thumb_size']] / $width);
			$new_width = $phppi['settings']['thumb_size_' . $phppi['thumb_size']];
		} else if ($width == $height) {
			$new_width = $phppi['settings']['thumb_size_' . $phppi['thumb_size']];
			$new_height = $phppi['settings']['thumb_size_' . $phppi['thumb_size']];
		}
	} else if ($mode == "fit") {
		if ($width > $height) {
			$ratio = $phppi['settings']['thumb_size_' . $phppi['thumb_size']] / $width;
		} else if ($width < $height) {
			$ratio = $phppi['settings']['thumb_size_' . $phppi['thumb_size']] / $height;
		} else if ($width == $height) {
			$ratio = $phppi['settings']['thumb_size_' . $phppi['thumb_size']] / $width;
		}

		$new_width = $width * $ratio;
		$new_height = $height * $ratio;		
	}
	
	if ($return == 0) {
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

function getImageData()
{
	global $phppi;
	
	$file = "?image=" . $phppi['query'];

	$html_output = '<img id="image" src="' . $file . '" alt="' . $file . '">';

	$phppi['image'] = array(
		'url'=>$file,
		'data'=>getimagesize(PHPPI_GALLERY . '/' . $phppi['query']),
		'html'=>$html_output
	);
}

function genNextPrev() {
	global $phppi;

	for($i = 0; $i < count($phppi['file_list']); $i++) {
		if ($phppi['file_list'][$i]['file'] == pathinfo($phppi['query'], PATHINFO_BASENAME))
		{
			$phppi['nav_image']['current'] = $phppi['file_list'][$i];
			
			if ($i > 0) {
				$phppi['nav_image']['previous'] = $phppi['file_list'][$i - 1];
			}
			if ($i < (count($phppi['file_list']) - 1)) {
				$phppi['nav_image']['next'] = $phppi['file_list'][$i + 1];
			}
			
			break;
		}
	}
}

function genCacheDir($dir) {
	$cache_folder = fixPath(PHPPI_CACHE_GALLERY);
	
	if (substr($dir, 0, strlen(PHPPI_GALLERY) + 1) == PHPPI_GALLERY . '/') {
		$dir = substr($dir, strlen(PHPPI_GALLERY) + 1);
	}
	
	if (!is_dir($cache_folder . $dir))
	{
		//Create cache folder/s if possible
		$temp_folders = explode('/', $dir);
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

function getThumbnail() {
	global $phppi;
	
	if (isset($_GET['thumb'])) { $filename = stripslashes($_GET['thumb']); } else { $filename = ""; }
	
	$full_file = PHPPI_CACHE_GALLERY . '/' . fixPath(pathinfo($filename, PATHINFO_DIRNAME)) . pathinfo($filename, PATHINFO_FILENAME) . '_' . $phppi['thumb_size'] . '.' . pathinfo($filename, PATHINFO_EXTENSION);
	
	if (is_file($full_file) && $phppi['settings']['use_gd_cache'] == true) {
		if ((time() - filemtime($full_file)) < $phppi['settings']['gd_server_cache_expire']) {
			if (checkExploit($filename, true) == true) {			
				header("Content-length: " . filesize($full_file));
				header("Content-type: " . getImageType($full_file));
				readfile($full_file);
			} else {
				addError("File not found.", "FATAL");
				showErrorPage();
			}
		} else {
			genThumbnail();
		}
	} else {
		genThumbnail();
	}
}

function genThumbnail() {
	//Creates thumbnail, either dynamically or for cache depending on settings
	global $phppi;

	if (isset($_GET['thumb'])) { $filename = stripslashes($_GET['thumb']); } else { $filename = ""; }
	if (isset($_GET['cover'])) { $cover = $_GET['cover']; } else { $cover = ""; }
	if (isset($_GET['mode'])) { $mode = $_GET['mode']; } else { $mode = "fill"; }
	
	if ($cover == "true") {
		$filename = fixPath($filename);
		
		if (checkExploit($filename . $phppi['settings']['folder_cover_filename'], true) == true) {
			header("Content-length: " . filesize(PHPPI_GALLERY . '/' . $filename . $phppi['settings']['folder_cover_filename']));
			header("Content-type: " . getImageType(PHPPI_GALLERY . '/' . $filename . $phppi['settings']['folder_cover_filename']));
			readfile(PHPPI_GALLERY . '/' . $filename . $phppi['settings']['folder_cover_filename']);
		} else {
			addError("File not found.", "FATAL");
			showErrorPage();
		}
	} else {
		if (is_file(PHPPI_GALLERY . '/' . $filename)) {
			if (checkExploit($filename, true) == true) {
				$filename = PHPPI_GALLERY . '/' . $filename;
				
				$temp_count = strlen(PHPPI_GALLERY) + 1;
				$temp_path = substr(pathinfo($filename, PATHINFO_DIRNAME), $temp_count);
				
				$cache_folder = PHPPI_CACHE_GALLERY . '/' . $temp_path;
				
				$create_cache_file = false;
				
				if ($phppi['settings']['use_gd_cache'] == true) {
					$create_cache_file = genCacheDir($temp_path);
				}

				$file_type = getImageType($filename);

				if ($file_type == 'image/jpeg') {
					$image = imagecreatefromjpeg($filename);
					$format = 'jpeg';
				} else if ($file_type == 'image/png') {
					$image = imagecreatefrompng($filename);
					$format = 'png';
				} else if ($file_type == 'image/gif') {
					$image = imagecreatefromgif($filename);
					$format = 'gif';
				}
				
				$width = imagesx($image);
				$height = imagesy($image);

				$new_size = resizedSize($width, $height, $mode);

				$new_image = ImageCreateTrueColor($new_size[0], $new_size[1]);
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_size[0], $new_size[1], $width, $height);
				
				if ($create_cache_file == false) {
					header('Pragma: public');
					header('Cache-Control: maxage=' . $phppi['settings']['gd_client_cache_expire']);
					header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $phppi['settings']['gd_client_cache_expire']) . ' GMT');
					header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
					
					if ($format == 'jpeg')
					{
						header('Content-type: image/jpeg');
						imagejpeg($new_image, null, $phppi['settings']['jpeg_quality']);
					} else if ($format == 'png') {
						header('Content-type: image/png');
						imagepng($new_image);
					} else if ($format == 'gif') {
						header('Content-type: image/gif');
						imagegif($new_image);
					}
				} else if ($create_cache_file == true) {
					header('Pragma: public');
					header('Cache-Control: maxage=' . $phppi['settings']['gd_client_cache_expire']);
					header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $phppi['settings']['gd_client_cache_expire']) . ' GMT');
					header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
					
					if ($format == 'jpeg') {
						header('Content-type: image/jpeg');
						imagejpeg($new_image, $cache_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '_' . $phppi['thumb_size'] . '.' . pathinfo($filename, PATHINFO_EXTENSION), $phppi['settings']['jpeg_quality']);
						imagejpeg($new_image);
					} else if ($format == 'png') {
						header('Content-type: image/png');
						imagepng($new_image, $cache_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '_' . $phppi['thumb_size'] . '.' . pathinfo($filename, PATHINFO_EXTENSION));
						imagepng($new_image);
					} else if ($format == 'gif') {
						header('Content-type: image/gif');
						imagegif($new_image, $cache_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '_' . $phppi['thumb_size'] . '.' . pathinfo($filename, PATHINFO_EXTENSION));
						imagegif($new_image);
					}
				}
				
				imagedestroy($new_image);
			} else {
				addError("File not found.", "FATAL");
				showErrorPage();
			}
		} else {
			addError("File not found.", "FATAL");
			showErrorPage();
		}
	}
}

function genThumbURL($filefolder, $folder) {
	//$filefolder = requested file or folder path not including query.
	//$folder = if true $filefolder is a folder, false $filefolder is a file.

	global $phppi;
	
	if ($folder == true) {
		if ($phppi['settings']['thumb_folder_show_thumbs'] == true) {
			if ($phppi['settings']['thumb_folder_use_cache_only'] == true) {
				$dir_data = getDirData($filefolder, true, true, "file");
			} else {
				$dir_data = getDirData($filefolder, true, false, "file");
			}
			
			if (count($dir_data) > 0) {
				if ($phppi['settings']['thumb_folder_shuffle'] == true) { shuffle($dir_data); }

				$img_url = genThumbURL($dir_data[0]['full_path'], false);
			} else {
				if (is_file(PHPPI_GALLERY . '/' . $filefolder . '/' . $phppi['settings']['folder_cover_filename'])) {
					$img_url = '?thumb=' . $filefolder . '&cover=true';
				} else {
					$img_url = '';
				}
			}
		} else {
			$img_url = '';
		}
	} else {
		if ($phppi['settings']['use_gd'] == true)
		{		
			$img_url = '?thumb=' . $filefolder . '&size=' . $phppi['thumb_size'];
		}  else {
			$file_ext = pathinfo($filefolder, PATHINFO_EXTENSION);
			$file_dir = pathinfo($filefolder, PATHINFO_DIRNAME);
			$file_name = pathinfo($filefolder, PATHINFO_FILENAME);

			$img_url = $phppi['settings']['thumbs_folder'] . '/' . $file_dir . '/' . $file_name . '.' . $phppi['settings']['thumb_file_ext'];
		}
	}
	
	return $img_url;
}

function getDirData($dir, $folder_mode = false, $cache_only = false, $type = "all") {
	//$folder_mode indicates whether getting data for currently displayed folder (false) or individual folders for one time lookups (true)
	
	global $phppi;
	
	$cache_expire = true;
	
	if (is_dir(PHPPI_GALLERY . '/' . $dir)) {
		if ($phppi['settings']['use_file_cache'] == true && is_file(PHPPI_CACHE_GALLERY . '/' . $dir . '/cache.xml')) {
			//Cache exists, check to see if expired.
			if ((time() - filemtime(PHPPI_CACHE_GALLERY . '/' . $dir . '/cache.xml')) < $phppi['settings']['file_cache_expire']) {
				$cache_expire = false;
			} else {
				$cache_expire = true;
			}
		} else {
			$cache_expire = true;
		}
		
		if ($cache_expire == false) {
			//XML Cache data retrieval
			$xml = new SimpleXMLElement(file_get_contents(PHPPI_CACHE_GALLERY . '/' .  $dir . '/cache.xml'));
		
			$x = 0;
			
			if (isset($xml->directories) && $type !== "file")
			{
				foreach($xml->directories->dir as $dirs)
				{
					$fd[$x]['full_path'] = (string)$dirs->path;
					$fd[$x]['dir'] = (string)$dirs->dirname;
					$fd[$x]['url'] = (string)$dirs->url;
					$fd[$x]['thumbnail'] = genThumbURL((string)$dirs->path, true);
					
					$x++;
				}
			}
			
			$x = 0;
			
			if (isset($xml->files) && $type !== "dir")
			{
				if ($phppi['settings']['use_popup_image_viewer'] == true) {
					$url_prefix = "?image=";
				} else {
					$url_prefix = "?view=";
				}

				foreach($xml->files->file as $files)
				{
					$ff[$x]['full_path'] = (string)$files->path;
					$ff[$x]['file'] = (string)$files->filename;
					$ff[$x]['thumbnail'] = (string)genThumbURL($files->path, false);
					$ff[$x]['thumb_size'] = array((integer)$files->thumb_width, (integer)$files->thumb_height);
					$ff[$x]['url'] = (string)$url_prefix . $files->path;
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

			$phppi['debug']['read_cache'] = "Read from cache.xml";
		} else if ($cache_expire == true && $cache_only == false) {
			//Retrieve current file/folder listing
			if ($dh = opendir(PHPPI_GALLERY . '/' . $dir)) {
				if ($dir != '') {
					$dir = $dir . '/';
				}

				while (($item = readdir($dh)) !== false) {
					if (filetype(PHPPI_GALLERY . '/' . $dir . $item) == 'dir' && checkCommaDelimited($item, $phppi['settings']['folder_blacklist']) == false && $type !== "file")
					{	
						$fd[] = array(
							'full_path'=>$dir . $item,
							'dir'=>$item,
							'url'=>'?q=' . $dir . $item,
							'thumbnail'=>genThumbURL($dir . $item, true)
						);
						
						sort($fd);
					} else if (filetype(PHPPI_GALLERY . '/' . $dir . $item) == 'file' && checkCommaDelimited($item, $phppi['settings']['file_blacklist']) == false && checkCommaDelimited(pathinfo($item, PATHINFO_EXTENSION), $phppi['settings']['file_types']) == true && $type !== "dir") {
						$data_array = getimagesize(PHPPI_GALLERY . '/' . $dir . $item);

						if ($phppi['settings']['use_popup_image_viewer'] == true) {
							$url_prefix = "?image=";
						} else {
							$url_prefix = "?view=";
						}

						$ff[] = array(
							'full_path'=>$dir . $item,
							'file'=>$item,
							'thumbnail'=>genThumbURL($dir . $item, false), //, array('full_path'=>$dir . '/' . $item, 'file'=>$item, 'data'=>$data_array)
							'thumb_size'=>resizedSize($data_array[0], $data_array[1]),
							'url'=>$url_prefix . $dir . $item,
							'data'=>$data_array
						);
						
						sort($ff);
					}
				}
				closedir($dh);

				$phppi['debug']['read_cache'] = "Read from directory";
			} else {
				return false;
			}
		}
		
		if ($folder_mode == false) {
			if (isset($ff)) { 
				if (count($ff > 0)) { 
					$phppi['file_list'] = $ff; 
				}
			} else { 
				$phppi['file_list'] = array(); 
			}

			if (isset($fd)) { 
				if (count($fd > 0)) { 
					$phppi['dir_list'] = $fd; 
				} 
			} else { 
				$phppi['dir_list'] = array(); 
			}

			if ($phppi['settings']['use_file_cache'] == true && $cache_expire == true) { 
				cacheDir(PHPPI_GALLERY . DIRECTORY_SEPARATOR . $dir);
			}
		} else {
			if (isset($ff)) { 
				if (count($ff > 0)) { 
					return $ff;
				}
			} else { 
				return array();
			}
		}

		return true;
	} else {
		return false;
	}	
}

function cacheDir($dir) {
	global $phppi;
	
	$cache_exists = false;
	
	if (substr($dir, 0, strlen(PHPPI_GALLERY) + 1) == PHPPI_GALLERY . DIRECTORY_SEPARATOR) {
		$dir = substr($dir, strlen(PHPPI_GALLERY) + 1);
	}
	
	if (count($phppi['dir_list']) > 0 || count($phppi['file_list']) > 0) {
		if (!is_dir(PHPPI_CACHE_GALLERY . '/' . $dir))
		{
			//Create cache folder/s if possible
			$temp_folders = explode(DIRECTORY_SEPARATOR, substr($dir, 0));
			$prefix = '';
			
			if (count($temp_folders) > 0) {
				foreach($temp_folders as $dirs) {
					if (!is_dir(PHPPI_CACHE_GALLERY . '/' . $prefix . $dirs)) {
						if (@mkdir(PHPPI_CACHE_GALLERY . '/' . $prefix . $dirs, 0775))
						{
							chmod(PHPPI_CACHE_GALLERY . '/' . $prefix . $dirs, 0775);
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
		
		if ($cache_exists == true)
		{
			//Generate XML Cache File
			$xmlstr = "<?xml version='1.0' ?>\n<cache></cache>";
			$xml = new SimpleXMLElement($xmlstr);
			
			if (isset($phppi['dir_list']))
			{
				if (count($phppi['dir_list']) > 0) {
					$xml_dir = $xml->addChild('directories');
					
					foreach($phppi['dir_list'] as $dirs)
					{
						$xml_dirs_data = $xml_dir->addChild('dir');
						$xml_dirs_data->addChild('path', $dirs['full_path']);
						$xml_dirs_data->addChild('dirname', $dirs['dir']);
						$xml_dirs_data->addChild('url', $dirs['url']);
					}
				}
			}
			
			if (isset($phppi['file_list']))
			{
				if (count($phppi['file_list']) > 0) {
					$xml_files = $xml->addChild('files');
					
					foreach($phppi['file_list'] as $files)
					{
						$xml_files_data = $xml_files->addChild('file');
						$xml_files_data->addChild('path', $files['full_path']);
						$xml_files_data->addChild('filename', $files['file']);
						$xml_files_data->addChild('thumb_width', $files['thumb_size'][0]);
						$xml_files_data->addChild('thumb_height', $files['thumb_size'][1]);
						
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
			}
			
			$xml->asXML(PHPPI_CACHE_GALLERY . '/' . $dir . '/cache.xml');
			$phppi['debug']['write_cache'] = "Created cache file";
			return true;
			
		} else {
			return false;
		}
	} else {
		return false;
	}
}
?>