<?php
class smartyPage extends Smarty {
	function __construct() {
	   	global $phppi;
		
       	parent::__construct();
		
		$this->setCompileDir($phppi['settings']['smarty_compile_folder']);
		$this->setConfigDir(SMARTY . '/configs');
		$this->setCacheDir($phppi['settings']['smarty_cache_folder']);
		$this->setTemplateDir(PHPPI_ROOT . '/themes/' . $phppi['settings']['theme']);
		
		if ($phppi['settings']['smarty_cache'] == true) {
			$this->cache_lifetime = $phppi['settings']['smarty_expire'];
	        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
		} else {
			$this->caching = Smarty::CACHING_OFF;
		}
	}
}
?>