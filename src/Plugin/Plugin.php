<?php


namespace SweetGallery\Plugin;


use SweetGallery\Util\PostUtils;
use SweetGallery\Util\Template;

class Plugin {

    const version = '1.0.0';

    const plugin_name = 'sweet-gallery';
    const gallery_post_type_id = "sweet-gallery";
    const gallery_categories_id = 'sweet-gallery-categories';

    const admin_page = 'sweet-gallery-admin';

    public function __construct() {
        add_action( 'init', array( $this, 'register_gallery_post_type' ) );

        // shortcode Area
        add_action( 'init', function () {
            // include_once(__DIR__ . '/src/ShortCodes/codes.php');
            // add_shortcode('whatever', 'fn_whatever');
        });

        add_action( 'save_post', array( $this, "save_product_details") );

        add_filter( 'manage_' . self::gallery_post_type_id . '_posts_columns', array( $this, "handle_gallery_columns") );
        add_action( 'manage_' . self::gallery_post_type_id . '_posts_custom_column', array( $this, "custom_column_value"), 10, 2 );
        add_action( 'manage_edit-' . self::gallery_post_type_id . '_sortable_columns', array( $this, "sortable_columns"));
    }

    public static function getCacheDirBase() {
        return __DIR__ . '/../../../../wp-content/uploads/' . self::plugin_name .  '/cache/';
    }

    public function register_gallery_post_type () {

        $name = 'Gallery Item';
        $namePlural = 'Gellery Items';

        // register the dedicated product post type
        register_post_type(
            self::gallery_post_type_id,
            array(
                'labels' => array(
                    'name' => __( $namePlural ),
                    'singular_name' => __( $name ),
                    'add_new_item' => __("Add New $name"),
                    'edit_item' => __("Edit $name"),
                    'new_item' => __("New $name"),
                    'view_item' => __("View $name"),
                    'view_items' => __("View $namePlural"),
                    'search_items' => __("Seach $namePlural"),
                    'not_found' => __("No $namePlural found"),
                    'not_found_in_trash' => __("No $namePlural found in Trash"),
                    'all_items' => __("All $namePlural"),
                    'archives' => __("$name Archives"),
                    'attributes' => __("$name Attributes"),
                    'insert_into_item' => __("Insert into $name"),
                    'uploaded_to_this_item' => __("Uploaded to this $name"),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-products',
                'show_in_menu' => self::admin_page,
                'rewrite' => array('slug' => 'produkte'),
                'supports' => [
                    'title',
                    // 'editor',
                    'thumbnail',
                    // 'custom-fields',
                    // 'page-attributes'
                ]
            )
        );

        // the categories for the product
        register_taxonomy(
            self::gallery_categories_id,
            self::gallery_post_type_id,
            array(
                "hierarchical" => true,
                "label" => __("$name Categories"),
                "singular_label" => __("$name Category"),
                "public" => true,
                "rewrite" => true,
                'show_ui' => true
            )
        );
    }

    public function save_product_details () {
        global $post;

        if (is_object($post)) {

            switch ($post->post_type) {
                case self::gallery_post_type_id:
                    PostUtils::update_custom_data($post->ID, $this->structure);
                    break;
            }
        }
    }

    public function render_product_details_box () {
        global $post;
        echo Template::render('admin/box_gallery_item_details.html.twig', [
            'values' => PostUtils::get_custom_data($post->ID, $this->structure),
            'structure' => $this->structure
        ]);
    }

    public function sortable_columns () {}
    public function custom_column_value () {}
    public function handle_gallery_columns () {}

}