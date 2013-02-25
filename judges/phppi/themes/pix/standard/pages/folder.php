<div id="page-title">
	<div style="float: left;"><?php $this->showTitle(); ?></div>
    <div id="thumb-size-change"><?php $this->insertThumbSize(); ?></div>
    <div style="clear: both;"></div>
</div>
<div class="page-bar">
	<?php
		if ($this->prevFolderExists()) { $class = ''; $url = $this->showPrevFolderURL(1); } else { $class = '-disabled'; $url = '#'; }
		echo '<a class="previous-link' . $class . '" href="' . $url . '"></a>';
	?>
    <div style="clear: both;"></div>
</div>
<?php if ($this->noticeExists()) { echo '<div id="page-notice">' . $this->showNotice(1) . '</div>'; } ?>
<div id="page-container">
<?php $this->showGallery(); ?>
</div>