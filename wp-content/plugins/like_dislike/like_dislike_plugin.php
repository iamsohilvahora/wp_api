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

if(!defined('ABSPATH')){
    die();
}

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
        // Plugin Frontend CSS
        wp_enqueue_style( 'style-css', PLUGIN_DIR_PATH .'assets/css/main.css');

        //FontAwesome CSS
        wp_enqueue_style( 'font-awesome', PLUGIN_DIR_PATH. 'assets/css/font-awesome/css/fontawesome-all.min.css', array(), NULL);

        // Plugin Frontend JS
        wp_enqueue_script( 'script-js', PLUGIN_DIR_PATH . 'assets/js/main.js', 'jQuery', '1.0.0', true );

        // Plugin AJAX JS
        wp_enqueue_script( 'plugin-ajax', PLUGIN_DIR_PATH . 'assets/js/ajax.js', array('jquery'), null, true );
        wp_localize_script( 'plugin-ajax', 'plugin_ajax_object',
           array( 
               'ajaxurl' => admin_url( 'admin-ajax.php' )
           )
        );


    }
    add_action( 'wp_enqueue_scripts', 'my_plugin_script' );
}

// Setting menu & page
require plugin_dir_path(__FILE__) .'/inc/settings.php';

// Create table for our plugin
require plugin_dir_path(__FILE__) .'/inc/db.php';

// Create like and dislike button using filter
require plugin_dir_path(__FILE__) .'/inc/btns.php';

//Plugin AJAX function
function wp_like_button_ajax_action(){
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    $table_name = $wpdb->prefix . "likes_system";
    $post_id = $_POST['pid'];
    $user_id = $_POST['uid'];

    if(isset($post_id) && isset($user_id)){
        $like_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND post_id = $post_id AND like_count = 1" );
        $dislike_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND post_id = $post_id AND dislike_count = 1" );
         
        if($like_count > 0){
            echo 'You already liked this post';
        }
        else if($dislike_count > 0){
            $update_query = $wpdb->update($table_name, array('user_id'=>$user_id, 'post_id'=>$post_id, 'like_count'=>1, 'dislike_count'=>0), array('user_id'=>$user_id, 'post_id'=>$post_id));
        }
        else{
            $wpdb->insert( 
            $table_name, 
                array( 
                    'post_id' => $_POST['pid'], 
                    'user_id' => $_POST['uid'], 
                    'like_count' => 1 
                ), 
                array( 
                    '%d', 
                    '%d', 
                    '%d' 
                ) 
            );

            if($wpdb->insert_id){   
                echo "Thank you for like this post";
            }
        }
    }
    wp_die();
}

function wp_dislike_button_ajax_action(){
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    $table_name = $wpdb->prefix . "likes_system";
    $post_id = $_POST['pid'];
    $user_id = $_POST['uid'];

    if(isset($post_id) && isset($user_id)){
        $like_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND post_id = $post_id AND like_count = 1" );
        $dislike_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND post_id = $post_id AND dislike_count = 1" );
         
        if($like_count > 0){ 
            $update_query = $wpdb->update($table_name, array('user_id'=>$user_id, 'post_id'=>$post_id, 'like_count'=>0, 'dislike_count'=>1 ), array('user_id'=>$user_id, 'post_id'=>$post_id));
        }
        else if($dislike_count > 0){
            echo 'You already disliked this post';
        }
        else{
            $wpdb->insert( 
            $table_name, 
                array( 
                    'post_id' => $_POST['pid'], 
                    'user_id' => $_POST['uid'], 
                    'like_count' => 0, 
                    'dislike_count' => 1 
                ), 
                array( 
                    '%d', 
                    '%d', 
                    '%d' 
                ) 
            );

            if($wpdb->insert_id){   
                echo "Thank you for dislike this post";
            }
        }
    }
    wp_die();
}

add_action( 'wp_ajax_wp_like_button_ajax_action', 'wp_like_button_ajax_action' ); 
add_action( 'wp_ajax_nopriv_wp_like_button_ajax_action', 'wp_like_button_ajax_action' );

add_action( 'wp_ajax_wp_dislike_button_ajax_action', 'wp_dislike_button_ajax_action' ); 
add_action( 'wp_ajax_nopriv_wp_dislike_button_ajax_action', 'wp_dislike_button_ajax_action' );

function wpac_show_like_count($content){
    if(is_single()){
        global $wpdb;
        $table_name = $wpdb->prefix . "likes_system";
        $post_id = get_the_ID();
        $like_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE post_id = '$post_id' AND like_count = 1" );
        $like_count_result = "<center>This post has been liked <strong>".$like_count."</strong>, time(s)</center>";
        $content .= $like_count_result;

        return $content;
    }
    
}
add_filter('the_content', 'wpac_show_like_count');

?>