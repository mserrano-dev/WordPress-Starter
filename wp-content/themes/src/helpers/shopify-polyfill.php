<?php

/**
 * Returns the URL of a file in the "assets" folder of a theme.
 *
 * @param string filename
 * 
 */
function asset_url($filename)
{
    return ASSET_DIR . $filename;
}


/**
 * Returns the asset URL of an image in the "assets" folder of a theme. 
 *  asset_img_url accepts an image size parameter.
 *
 * @param string $filename
 * @param string $size // TODO: wp_get_attachment_image_srcset
 * 
 */
function asset_img_url($filename, $size = '')
{
    return ASSET_DIR . 'images' . DIRECTORY_SEPARATOR . $filename;
}
