<?php
define('PHPPI_ROOT', str_replace('\\', '/', realpath(__DIR__)));
define('PHPPI_CACHE', $phppi['settings']['cache_folder']);
define('PHPPI_CACHE_GALLERY', $phppi['settings']['cache_folder']);
define('PHPPI_GALLERY', $phppi['settings']['gallery_folder']);

define('SMARTY', PHPPI_ROOT . '/lib/smarty');
define('SMARTY_CACHE', $phppi['settings']['smarty_cache_folder']);
?>