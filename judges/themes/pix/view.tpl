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
            {if isset($nav.list)}
            {foreach from=$nav.list item=nav_item}
            <li class="nav-sep"><img src="{$theme_path}/images/sep.png"></li>
            <li><a href="{$nav_item.url}">{$nav_item.name}</a></li>
            {/foreach}
            {/if}
            {if isset($nav.current)}<li class="nav-sep"><img src="{$theme_path}/images/sep.png"></li><li class="nav-current">{$nav.current.name}</li>{/if}
        </ul>
    </div>
    <div id="page-container" style="padding: 0px;">
        <div id="page-image-container" style="max-width: {$image.data.0}px;">
            {if isset($nav_image.previous) }<a id="image-nav-left" href="{$nav_image.previous.url}"><div></div></a>{/if}
            {if isset($nav_image.next) }<a id="image-nav-right" href="{$nav_image.next.url}"><div></div></a>{/if}
            {$image.html}
        </div>
    </div>
</body>
</html>