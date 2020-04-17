<?php


namespace SweetGallery\Plugin;


class Admin {

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'add_plugin_pages' ) );

    }

    public function add_plugin_pages() {
        // This page will be under "Settings"
        add_menu_page(
            'Sweet Gallery Items',
            'Gallery Items',
            'manage_options',
            Plugin::admin_page,
            array( $this, 'create_admin_page' ),
            'dashicons-format-gallery',
            30
        );

        add_submenu_page(
            Plugin::admin_page,
            __('Sweet Gallery Categories'),
            __('Gallery Categories'),
            'manage_options',
            'edit-tags.php?taxonomy=' . Plugin::gallery_categories_id,
            false
        );
    }

    public function create_admin_page () {
        echo '|||';
    }

}