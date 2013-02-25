<div id="page-title"><?php $this->showTitle(); ?></div>
<div class="page-bar">
	<?php if ($this->prevImageExists()) { echo '<a class="previous-link" href="' . $this->showPrevImageURL(1) . '"></a>'; } else { echo '<a class="previous-link-disabled" href="#"></a>'; } ?>
    <?php if ($this->nextImageExists()) { echo '<a class="next-link"  href="' . $this->showNextImageURL(1) . '"></a>'; } else { echo '<a class="next-link-disabled" href="#"></a>'; } ?>
    <a class="home-link" href="<?php $this->showUpFolderURL(); ?>"></a>
    <div style="clear: both;"></div>
</div>
<?php if ($this->noticeExists()) { echo '<div id="page-notice">' . $this->showNotice(1) . '</div>'; } ?>
<div id="page-image-container">
	<?php $this->showImage(); ?>
</div>
<div class="page-bar">
	<?php if ($this->prevImageExists()) { echo '<a class="previous-link" href="' . $this->showPrevImageURL(1) . '"></a>'; } else { echo '<a class="previous-link-disabled" href="#"></a>'; } ?>
    <?php if ($this->nextImageExists()) { echo '<a class="next-link"  href="' . $this->showNextImageURL(1) . '"></a>'; } else { echo '<a class="next-link-disabled" href="#"></a>'; } ?>
    <a class="home-link" href="<?php $this->showUpFolderURL(); ?>"></a>
    <div style="clear: both;"></div>
</div>