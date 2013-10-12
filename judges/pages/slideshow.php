<?php
require_once('lib/phppi.gallery.php');

if (!getDirData($phppi['query'])) {
    addError("Folder doesn't exist.", "FATAL");
    showErrorPage();
} else {
?>
<!doctype html>
<html>
<head>
    <title><?php echo $phppi['settings']['site_name'] . " - Slideshow (" . $phppi['query'] . ")"; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="lib/supersized/css/supersized.css" type="text/css" media="screen">
    <link rel="stylesheet" href="lib/supersized/theme/phppi.shutter.css" type="text/css" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <?php if ($phppi['settings']['slideshow_background_color'] !== "") { ?>
    <style type="text/css">
        body {
            background: <?php echo $phppi['settings']['slideshow_background_color']; ?>;
        }
        
        #supersized li {
            background: <?php echo $phppi['settings']['slideshow_background_color']; ?>;
        }
    </style>
    <?php } ?>
</head>
<body>
    <div id="page-container">
        <div id="prevthumb"></div>
        <div id="nextthumb"></div>
    
        <a id="prevslide" class="load-item"></a>
        <a id="nextslide" class="load-item"></a>

        <div id="bottom-tray" class="load-item">
            <a id="play-button" class="pause" title="Play / Pause"></a>
            <div id="slide-data">
                <div id="slidecounter">
                    <span class="slidenumber"></span>&nbsp;/&nbsp;<span class="totalslides"></span>
                </div>
                <div id="slidecaption"></div>
                <div style="clear: both;"></div>
            </div>
            <a id="close-button" href="?q=<?php echo $phppi['query']; ?>" title="Return to Gallery"></a>
            <a id="fullscreen-button" title="Fullscreen"></a>
            <div style="clear: both;"></div>
        </div>
    </div>

    <script type="text/javascript" src="lib/jquery/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="lib/supersized/js/jquery.easing.min.js"></script>
    <script type="text/javascript" src="lib/supersized/js/jquery.animate-enhanced.min"></script>
    <script type="text/javascript" src="lib/supersized/js/supersized.3.2.7.min.js"></script>
    <script type="text/javascript" src="lib/supersized/theme/phppi.shutter.js"></script>
    <script type="text/javascript">
        jQuery(function($){
            $.supersized({
                <?php
                if ($phppi['settings']['slideshow_sizing'] == "both") { echo "fit_always: true,\n"; } else { echo "fit_always: false,\n"; }
                if ($phppi['settings']['slideshow_sizing'] == "landscape") { echo "fit_landscape: true,\n"; } else { echo "fit_landscape: false,\n"; }
                if ($phppi['settings']['slideshow_sizing'] == "portrait") { echo "fit_portrait: true,\n"; } else { echo "fit_portrait: false,\n"; }
                ?>
                progress_bar: 0,
                performance: 0,
                slide_interval: <?php echo $phppi['settings']['slideshow_slide_interval']; ?>,
                transition: <?php echo $phppi['settings']['slideshow_transition_effect']; ?>,
                transition_speed: <?php echo $phppi['settings']['slideshow_transition_speed']; ?>,
                <?php if ($phppi['settings']['slideshow_random'] == true) { echo "random: true,\n"; } else { echo "random: false,\n"; } ?>
                                                           
                slides: [
                    <?php
                    foreach ($phppi['file_list'] as $image) {
                        echo "{image: '?image=" . $image['full_path'] . "', title: '" . $image['file'] . "', thumb: '" . $image['thumbnail'] . "'},\n";
                    }
                    ?>
                ]                
            });
        });
    </script>
</body>
</html>
<?php
}
?>