<!doctype html>
<html>
<head>
{$phppi_head_code}
<title>{$page_title}</title>
<link rel="stylesheet" type="text/css" href="{$theme_path}/style.css">
</head>
<body>
    <div id="page-title">
        <ul class="title-bar">
            <li style="float: left;">
                {if $page_logo ne ""}
                    {$page_logo}
                {else}
                    <h1 style="margin: 0px 0px;">{$site_name}</h1>
                {/if}
            </li>
            <li style="float: right;">
                <ul>
                    {if $thumb_size_change ne false}
                    <li style="margin: 10px 10px 0px 0px;"><a href="{$thumb_small_url}" title="Small Thumbnails">Small</a></li>
                    <li style="margin: 10px 10px 0px 0px;"><a href="{$thumb_medium_url}" title="Medium Thumbnails">Medium</a></li>
                    <li style="margin: 10px 10px 0px 0px;"><a href="{$thumb_large_url}" title="Large Thumbnails">Large</a></li>
                    {/if}
                    {if $slideshow ne false}
                    <li style="margin: 10px 10px 0px 0px;"><a href="{$slideshow_url}" title="Start Slideshow">Slideshow</a></li>
                    {/if}
                </ul>
            </li>
        </ul>
        <div style="clear: both;"></div>
    </div>
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
        {foreach from=$folder_list item=dir}
        <a class="thumb-container" href="{$dir.url}" title="{$dir.dir}">
            <div class="thumb" style="width: {$thumb_width}px; height: {$thumb_height}px; background-image: url('{if $dir.thumbnail ne ""}{$dir.thumbnail|escape:'quotes'}&mode=fit{else}{$theme_path}/images/no_images.png{/if}');"></div>
            <div class="thumb-title" style="width: {$thumb_width}px;">{$dir.dir}</div>
        </a>
        {/foreach}
        {foreach from=$file_list item=file}
        <a class="thumb-container" href="{$file.url}" title="{$file.file}">
            <div class="thumb" style="width: {$thumb_width}px; height: {$thumb_height}px; background-image: url('{if $file.thumbnail ne ""}{$file.thumbnail|escape:'quotes'}&mode=fit{else}{$theme_path}/images/no_images.png{/if}');"></div>
            <div class="thumb-title" style="width: {$thumb_width}px;">{$file.file}</div>
        </a>
        {/foreach}
        <div style="clear:both;"></div>
    </div>
</body>
</html>