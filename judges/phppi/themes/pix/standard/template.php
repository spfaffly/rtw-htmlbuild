<!DOCTYPE html>
<html>
<head>
<?php $this->insertHeadInfo(); ?>
<style type="text/css">
	#page-title { color: <?php echo $this->settings['theme']['pix']['page_title_text_color']; ?>; text-shadow: <?php echo $this->settings['theme']['pix']['page_title_text_shadow']; ?>; background-color: <?php echo $this->settings['theme']['pix']['page_title_background_color']; ?>; }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php $this->showTitle(); ?></title>
</head>
<body>
<?php $this->showPage(); ?>
<div id="page-footer">
<?php $this->showFooter(); ?>
</div>
</body>
</html>