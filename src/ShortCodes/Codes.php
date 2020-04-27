<?php

namespace SweetGallery\ShortCodes;

use SweetGallery\Plugin\Plugin;
use SweetGallery\Util\Template;

class Codes {

    public static function init () {
        add_shortcode('gallery_grid', [__CLASS__, 'gallery_grid']);
    }


    public static function gallery_grid ($attributes) {

        extract( shortcode_atts( array(
            'order_by' => 'date',
            'cols' => 3,
            'display' => 'fancy-fade-in'
        ), $attributes ) );

        /** @var $order_by */
        /** @var $cols */
        /** @var $display */

        $query = [
            'numberposts' => -1,
            'post_type' => Plugin::gallery_post_type_id,
            'orderby' => $order_by,
            'order' => 'ASC'
        ];

        $items = get_posts($query);

        for ($i = 0; $i < 3; $i ++ ) {
            $items = array_merge($items, $items);
        }


        return Template::render('gallery_grid.html.twig', [
            'items' => $items,
            'itemCount' => count($items),
            'cols' => $cols,
            'display' => $display,
            'imageSizes' => Plugin::PREVIEW_IMAGE_SIZES
        ]);

    }

}