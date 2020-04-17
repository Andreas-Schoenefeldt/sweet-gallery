<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 20.05.19
 * Time: 15:46
 */

namespace SweetGallery\Util;


class PostUtils {

    /**
     * get's the values of a certain data structure from a post
     *
     * @param $postId
     * @param array $structure
     * @return array
     */
    public static function get_custom_data ($postId, $structure) {

        $result = [];
        $custom = get_post_custom($postId);

        if ($custom) {
            foreach ($structure as $attributeId => $conf) {
                if (array_key_exists($attributeId, $custom)) {
                    $result[$attributeId] = $custom[$attributeId][0];
                }
            }
        }

        return $result;
    }

    /**
     * updates a certain data structure of a post
     *
     * @param $postId
     * @param array $structure
     */
    public static function update_custom_data ($postId, $structure) {
        if ($postId) {
            foreach ($structure as $attributeId => $conf) {
                if (array_key_exists($attributeId, $_POST)) {
                    update_post_meta($postId, $attributeId, $_POST[$attributeId]);
                }
            }
        }
    }

}