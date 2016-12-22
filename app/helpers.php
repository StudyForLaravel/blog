<?php

/**
 * 返回可读性更好的文件尺寸
 * @param  string  $bytes    文件大小字符串
 * @param  integer $decimals 精确到小数点后哪一位
 * @return string            返回格式化后的文件大小字符串
 */
function human_filesize($bytes, $decimals = 2){
    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .@$size[$factor];
}

/**
 * 判断文件的MIME类型是否为图片
 * @param  [type]  $mimeType [description]
 * @return boolean           [description]
 */
function is_image($mimeType){
    return starts_with($mimeType, 'image/');
}


/**
 * Return "checked" if true
 */
function checked($value)
{
    return $value ? 'checked' : '';
}

/**
 * Return img url for headers
 */
function page_image($value = null)
{
    if (empty($value)) {
        $value = config('blog.page_image');
    }
    if (! starts_with($value, 'http') && $value[0] !== '/') {
        $value = config('blog.uploads.webpath') . '/' . $value;
    }

    return $value;
}