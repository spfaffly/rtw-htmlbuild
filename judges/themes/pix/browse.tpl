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
                    <div style="padding: 10px;">{$site_name}</div>
                {/if}
            </li>
            <li style="float: right;">
                <ul>
                    {if $thumb_size_change ne false}
                    <li style="margin: 7px 0px 0px 0px;"><a href="{$thumb_small_url}" title="Small Thumbnails" style="background-image: url('{$theme_path}/images/small_icon.png');"></a></li>
                    <li style="margin: 7px 0px 0px 0px;"><a href="{$thumb_medium_url}" title="Medium Thumbnails" style="background-image: url('{$theme_path}/images/medium_icon.png');"></a></li>
                    <li style="margin: 7px 15px 0px 0px;"><a href="{$thumb_large_url}" title="Large Thumbnails" style="background-image: url('{$theme_path}/images/large_icon.png');"></a></li>
                    {/if}
                    {if $slideshow ne false}
                    <li style="margin: 7px 15px 0px 0px;"><a href="{$slideshow_url}" title="Start Slideshow" style="background-image: url('{$theme_path}/images/slideshow_icon.png');"></a></li>
                    {/if}
                </ul>
            </li>
        </ul>
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
    <div id="page-container">
        {foreach from=$folder_list item=dir}
        <a class="thumb-container" href="{$dir.url}" title="{$dir.dir}" style="width: {$thumb_width}px; height: {$thumb_height}px;">
            <div class="thumb" style="width: {$thumb_width}px; height: {$thumb_height}px;">
                <div class="thumb-overlay" style="background-image: url('{$theme_path}/images/hover_folder.png'); width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-folder" style="background-image: url('{$theme_path}/images/folder_icon.png'); width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-image" style="background-image: url('{if $dir.thumbnail ne ""}{$dir.thumbnail|escape:'quotes'}{else}{$theme_path}/images/no_images.png{/if}'); width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-loading" style="width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-title-back-show" style="width: {$thumb_width}px;">
                    <div>&nbsp;</div>
                </div>
                <div class="thumb-title-container-show" style="width: {$thumb_width}px;">
                    <div>{$dir.dir}</div>
                </div>
            </div>
        </a>
        {/foreach}
        {foreach from=$file_list item=file}
        <a class="thumb-container" href="{$file.url}" title="{$file.file}" style="width: {$thumb_width}px; height: {$thumb_height}px;">
            <div class="thumb" style="width: {$thumb_width}px; height: {$thumb_height}px;">
                <div class="thumb-overlay" style="background-image: url('{$theme_path}/images/hover_image.png'); width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-image" style="background-image: url('{if $file.thumbnail ne ""}{$file.thumbnail|escape:'quotes'}{else}{$theme_path}/images/no_images.png{/if}'); width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-loading" style="width: {$thumb_width}px; height: {$thumb_height}px;"></div>
                <div class="thumb-title-back" style="width: {$thumb_width}px;">
                    <div>&nbsp;</div>
                </div>
                <div class="thumb-title-container" style="width: {$thumb_width}px;">
                    <div>{$file.file}</div>
                </div>
            </div>
        </a>
        {/foreach}
        <div style="clear:both;"></div>
    </div>
</body>
</html>