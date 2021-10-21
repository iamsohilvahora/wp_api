<?php
/**
 * Plugin Name:       Like Dislike Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sohil Vahora
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       like_dislike-plugin
 * Domain Path:       /languages
 */

//If this file called directly, abort
if(!defined('WPINC')){
    die();
}

if(!defined('MY_PLUGIN_VERSION')){
    define('MY_PLUGIN_VERSION', '1.0.0');
}

if(!defined('PLUGIN_DIR_PATH')){
    define('PLUGIN_DIR_PATH', plugin_dir_url(__FILE__));
}


if(!function_exists('my_plugin_script')){
    /**
     * Proper way to enqueue scripts and styles.
     */
    function my_plugin_script() {
        wp_enqueue_style( 'style-css', PLUGIN_DIR_PATH .'/assets/css/main.css');
        wp_enqueue_script( 'script-js', PLUGIN_DIR_PATH . '/assets/js/main.js', 'jQuery', '1.0.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'my_plugin_script' );
}

// add_menu_page( page_title, menu_title, capability, menu_slug, function, icon_url, position )
/**
 * Register a custom menu page.
 */
function register_my_custom_menu_page() {
    add_menu_page('Like System Title',
        'Like System',
        'manage_options',
        'like_system_slug',
        'like_system_frontend',
        'dashicons-thumbs-up',
        6
    );

    // add_submenu_page( parent_slug, page_title, menu_title, capability, menu_slug, function, position)
    // add_submenu_page( 'tools.php', 'Like Data Title', 'Like Data', 'manage_options', 'add_like_system_slug', 'add_like_data' );
    // add_submenu_page( 'options-general.php', 'Like Data Title', 'Like Data', 'manage_options', 'add_like_system_slug', 'add_like_data' );
    
    add_submenu_page( 'like_system_slug', 'Like Data Title', 'Like Data', 'manage_options', 'add_like_system_slug', 'add_like_data' );
}
add_action( 'admin_menu', 'register_my_custom_menu_page' );

function like_system_frontend(){
    echo "<h1>Hello WordPress</h1>";
}

function add_like_data(){
    echo "<p>Like data not found</p>";
}

?>