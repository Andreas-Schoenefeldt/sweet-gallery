<?php
/**
 * A simple wrapper for twig
 *
 * Created by PhpStorm.
 * User: Andreas
 * Date: 23.06.18
 * Time: 14:01
 */

namespace SweetGallery\Util;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use SweetGallery\Plugin\Plugin;

class Template {

    /** @var Environment  */
    protected static $twig;

    protected static $namespace;
    protected static $cssNamespace;
    protected static $jsNamespace;

    protected static $pluginFile;

    public static function init($namespace, $pluginFile) {

        self::$pluginFile = $pluginFile;

        $cacheDir = Plugin::getCacheDirBase() . 'Templates/';
        $debug = 'debug';
        // $locale = Plugin::get_option('app_language');

        // register used styles and scripts, for later use
        self::$namespace = $namespace;
        self::$cssNamespace = self::$namespace . '-' . 'style';
        self::$jsNamespace = self::$namespace . '-' . 'js';

        // initialize twig template engine
        self::$twig = new Environment(
            new FilesystemLoader(__DIR__ . '/../../templates/'),
            array(
                'cache' => $cacheDir,
                'debug' => $debug === 'debug',
            )
        );

        // adding some wordpress specific callback functions
        self::$twig->addFunction(new TwigFunction(
            'settings_fields',
            'settings_fields',
            array('is_safe' => array('html')))
        );

        self::$twig->addFunction(new TwigFunction(
            'do_settings_sections',
            'do_settings_sections',
            array('is_safe' => array('html')))
        );

        self::$twig->addFunction(new TwigFunction(
            'submit_button',
            'submit_button',
            array('is_safe' => array('html')))
        );

        self::$twig->addFunction(new TwigFunction(
            'get_term_meta',
            'get_term_meta',
            array('is_safe' => array('html')))
        );

        self::$twig->addFunction(new TwigFunction(
            'wpautop',
            'wpautop',
            array('is_safe' => array('html')))
        );

        self::$twig->addFunction(new TwigFunction(
            'host',
            function ($url) {
                $host = parse_url($url, PHP_URL_HOST);
                if (substr($host, 0, 4) == 'www.') {
                    $host = substr($host, 4);
                }
                return $host;
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'gallery_attribute',
            function ($galleryItemId, $attributeName) {
                return get_post_meta($galleryItemId, $attributeName, true);
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'gallery_categories',
            function ($productId) {
                return get_the_terms($productId, Plugin::gallery_categories_id);
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'postlink',
            function ($postOrPostId) {
                return get_permalink($postOrPostId);
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'wp_editor',
            function ($content, $fieldName) {
                return  wp_editor( $content, $fieldName, [] );
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            '__',
            function ($string) {
                return  __($string);
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'wp_get_attachment_image',
            function ($attachment_id, $size = 'thumbnail', $icon = false, $attr = '') {
                return  wp_get_attachment_image($attachment_id, $size, $icon, $attr);
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'wp_get_attachment_image_src',
            function ($attachment_id, $size = 'thumbnail', $icon = false) {
                return  wp_get_attachment_image_src($attachment_id, $size, $icon)[0];
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'build_srcset',
            function ($sizes, $attachment_id) {

                $srcSet = [];

                foreach ($sizes as $size) {
                    $srcSet[] = wp_get_attachment_image_src($attachment_id, "preview_{$size}x{$size}")[0] . " {$size}w";
                }

                return $srcSet;
            }
        ));

        self::$twig->addFunction(new TwigFunction(
            'build_sizes',
            function ($cols) {

                return [ (100 / $cols ) . 'vw' ];
            }
        ));


        self::$twig->addFunction(new TwigFunction(
            'dump',
            function ($val) {

                echo '<pre>';
                var_dump($val);
                echo '</pre>';
            },
            array('is_safe' => array('html'))
        ));

    }

    public static function render($template, $variables = []) {
        // we render? We'll let's include also the assets then ;)
        // self::includeAssets();

        return self::$twig->render($template, $variables);
    }

    public static function includeAssets(){

        wp_register_style(self::$cssNamespace, plugins_url('assets/css/frontend.css', self::$pluginFile));
        wp_register_script(self::$jsNamespace, plugins_url('assets/js/frontend.js', self::$pluginFile));

        wp_enqueue_style(self::$cssNamespace);
        wp_enqueue_script(self::$jsNamespace);
    }

    public static function includeAdminAssets () {

        wp_register_style(self::$cssNamespace . '-admin', plugins_url('assets/css/admin.css', self::$pluginFile), [], Plugin::version);
        wp_enqueue_style(self::$cssNamespace . '-admin');
    }

}