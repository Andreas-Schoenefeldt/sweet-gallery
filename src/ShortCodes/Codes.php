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
            'order_by' => 'date'
        ), $attributes ) );

        /** @var $order_by */

        $query = [
            'numberposts' => -1,
            'post_type' => Plugin::gallery_post_type_id,
            'orderby' => $order_by,
            'order' => 'ASC'
        ];

        $items = get_posts($query);

        return Template::render('gallery_grid.html.twig', [
            'items' => $items
        ]);

    }

}