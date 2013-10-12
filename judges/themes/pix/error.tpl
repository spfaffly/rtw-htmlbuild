<!doctype html>
<html>
<head>
    {$phppi_head_code}
    <title>{$page_title}</title>
    <link rel="stylesheet" type="text/css" href="{$theme_path}/style.css">
</head>
<body>
    <div id="page-title">
        <div style="float: left;">
            {if $page_logo ne ""}
                {$page_logo}
            {else}
                <div style="padding: 10px;">{$site_name}</div>
            {/if}
        </div>
        <div style="clear: both;"></div>
    </div>
    {if $page_notice ne ""}<div id="page-notice">{$page_notice}</div>{/if}
    <div class="nav-bar">
        <ul>
            <li class="nav-home"><a href="{$nav.home.url}"><img src="{$theme_path}/images/home.png"></a></li>
            {if isset($nav.current)}<li class="nav-current">{$nav.current.name}</li>{/if}
        </ul>
    </div>
    <div id="page-container">
        {$error_output}
    </div>
</body>
</html>