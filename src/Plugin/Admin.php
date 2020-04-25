<?php


namespace SweetGallery\Plugin;


class Admin {

    private $pluginFile;

    public function __construct($pluginFile) {

        $this->pluginFile = $pluginFile;

        add_action( 'admin_menu', array( $this, 'add_plugin_pages' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_assets'));
    }

    public function add_admin_assets () {
        // add the dedicated admin js
        $jsAdminNameSpace = Plugin::plugin_name . '-scripts-admin';

        wp_register_script($jsAdminNameSpace, plugins_url('assets/js/admin.js', $this->pluginFile));
        wp_enqueue_script($jsAdminNameSpace);
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