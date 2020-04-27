<?php


namespace SweetGallery\Plugin;


use SweetGallery\ShortCodes\Codes;
use SweetGallery\Util\PostUtils;
use SweetGallery\Util\Template;

class Plugin {

    const version = '1.0.0';

    const plugin_name = 'sweet-gallery';
    const gallery_post_type_id = "sweet-gallery";
    const gallery_categories_id = 'sweet-gallery-categories';

    const admin_page = 'sweet-gallery-admin';

    public $structure;

    public function __construct() {

        $this->structure = [
            // 'swg_description' => [ 'label' => __('Description'), 'input' => 'html'],
            'swg_grid_image' =>     [ 'label' => __('Preview Image'), 'input' => 'image' ],
            'swg_images' =>         [ 'label' => __('Images'), 'input' => 'list_image'], // image upload thx to https://jeroensormani.com/how-to-include-the-wordpress-media-selector-in-your-plugin/
        ];

        Template::init(self::plugin_name);


        add_action( 'init', array( $this, 'register_gallery_post_type' ) );

        // shortcode Area
        add_action( 'init', [Codes::class, 'init']);

        add_action( "admin_init", array( $this, "admin_init") );

        add_action( 'save_post', array( $this, "save_product_details") );


        // add_filter( 'manage_' . self::gallery_post_type_id . '_posts_columns', array( $this, "handle_gallery_columns") );
        // add_action( 'manage_' . self::gallery_post_type_id . '_posts_custom_column', array( $this, "custom_column_value"), 10, 2 );
        // add_action( 'manage_edit-' . self::gallery_post_type_id . '_sortable_columns', array( $this, "sortable_columns"));
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
                    'editor',
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

    public function admin_init () {
        add_meta_box(
            "box_gallery_item_details",
            __('Gallery Item Settings'),
            array( $this, 'render_gallery_item_details_box' ),
            self::gallery_post_type_id,
            "normal",
            "high"
        );
    }


    public function render_gallery_item_details_box () {
        global $post;

        // adds the image functionality
        wp_enqueue_media();

        $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

        echo Template::render('admin/box_gallery_item_details.html.twig', [
            'values' => PostUtils::get_custom_data($post->ID, $this->structure),
            'structure' => $this->structure,
            'attachment_post_id' => $my_saved_attachment_post_id
        ]);
    }

    public function sortable_columns () {}
    public function custom_column_value () {}
    public function handle_gallery_columns () {}

}