<!doctype html>
<html>
<head>
{$phppi_head_code}
<title>{$page_title}</title>
<link rel="stylesheet" type="text/css" href="{$theme_path}/style.css">
</head>
<body>
    {if $page_logo ne ""}
    {$page_logo}
    {else}
    <h1 style="margin: 0px 0px;">{$site_name}</h1>
    {/if}
    <hr>
    <div style="padding: 10px;">
        <ul class="nav-bar">
            <li><a href="{$nav.home.url}">Home</a></li>
            {if isset($nav.list)}
            {foreach from=$nav.list item=nav_item}
            <li>&nbsp;&raquo;&nbsp;</li>
            <li><a href="{$nav_item.url}">{$nav_item.name}</a></li>
            {/foreach}
            {/if}
            {if isset($nav.current)}
            <li>&nbsp;&raquo;&nbsp;</li>
            <li>{$nav.current.name}</li>
            {/if}
        </ul>
        <div style="clear:both;"></div>
    </div>
    <hr>
    {if $page_notice ne ""}<div id="page-notice">{$page_notice}</div><hr>{/if}
    <div id="page-container">
        <div id="page-image-container" style="max-width: {$image.data.0}px;">
            {if isset($nav_image.previous) }<a class="image-nav-left" href="{$nav_image.previous.url}">Previous</a>{/if}
            {if isset($nav_image.next) }<a class="image-nav-right" href="{$nav_image.next.url}">Next</a>{/if}
            <div style="clear: both;"></div>
            {$image.html}
            {if isset($nav_image.previous) }<a class="image-nav-left" href="{$nav_image.previous.url}">Previous</a>{/if}
            {if isset($nav_image.next) }<a class="image-nav-right" href="{$nav_image.next.url}">Next</a>{/if}
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>