<?php
/*
Plugin Name: Sweet Gallery
Plugin URI: https://github.com/Andreas-Schoenefeldt/sweet-gallery
Description: A Gallery with little function but great usability.
Version: 1.0.0
Author: Andreas
Author URI: https://github.com/Andreas-Schoenefeldt
Contributors: Andreas Schönefeldt
Text Domain: sweet-gallery
Domain Path: /languages
*/

use SweetGallery\Plugin\Admin;
use SweetGallery\Plugin\Plugin;

require __DIR__ . '/vendor/autoload.php';


$GLOBALS['sweet_gallery'] = new Plugin();

if (is_admin()) {
    $my_admin_page = new Admin(__FILE__);
}