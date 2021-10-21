<?php
// http://localhost/wp_api/wp-json/wp/v2/api/login
require( get_stylesheet_directory() . '/api/login.php' );
require( get_stylesheet_directory() . '/api/register.php' );
require( get_stylesheet_directory() . '/api/delete.php' );
require( get_stylesheet_directory() . '/api/update.php' );

function wp_rest_login_endpoints($request) {
	register_rest_route('wp/v2', 'api/register', array(
        'methods' => 'POST',
        'callback' => 'user_register',
    ));

    /**
     * endpoints will be login here
     */
    register_rest_route('wp/v2', 'api/login', array(
        'methods' => 'POST',
        'callback' => 'user_login',
    ));

    //register_rest_route('wp/v2', 'api/delete/(?P<id>\d+)', array(
    register_rest_route('wp/v2', 'api/delete', array(
        'methods' => 'DELETE',
        'callback' => 'user_delete',
    ));

    register_rest_route('wp/v2', 'api/update', array(
        'methods' => 'PATCH',
        'callback' => 'user_update',
    ));

    // register_rest_route( 'myplugin/v1', '/posts/?number=(?P<number>[\d]+)&amp;offset=(?P<offset>[\d]+)&amp;total=(?P<total>[\d]+)', array(
    //     'methods'             => 'GET',
    //     'callback'            => 'my_rest_function',
    //     'permission_callback' => '__return_true',
    //     'args'                => array(
    //         'number' => array(
    //             'validate_callback' => function( $param, $request, $key ) {
    //                 return is_numeric( $param );
    //             }
    //         ),
    //         'offset' => array(
    //             'validate_callback' => function( $param, $request, $key ) {
    //                 return is_numeric( $param );
    //             }
    //         ),
    //         'total' => array(
    //             'validate_callback' => function( $param, $request, $key ) {
    //                 return is_numeric( $param );
    //             }
    //         ),
    //     ),
    // ) );
}
add_action('rest_api_init', 'wp_rest_login_endpoints');
?>