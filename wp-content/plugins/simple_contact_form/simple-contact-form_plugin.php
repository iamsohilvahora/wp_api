<?php
/**
 * Plugin Name:       Simple Contact Form
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with simple contact form plugin.
 * Version:           1.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sohil Vahora
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       simple_contact_form-plugin
 * Domain Path:       /languages
*/

	if(!defined('ABSPATH')){
    	die("What are you trying to get");
	}

    class SimpleContactForm{
        public function __construct(){
            // Create custom post type
            add_action('init', array($this, 'create_custom_post_type'));            
            // Add assets (js, css, etc)
            add_action('wp_enqueue_scripts',array($this, 'load_assets'));

            // Add shortcode
            add_shortcode('contact-form', array($this, 'load_shortcode'));

            // Load javascript footer side
            add_action('wp_footer', array($this, 'load_scripts'));

            // Register rest api
            add_action('rest_api_init', array($this, 'register_rest_api'));

            // Update CSS and JS within in Admin        
            add_action('admin_enqueue_scripts', array($this, 'admin_style_script'));
        }

        public function create_custom_post_type(){
            // CPT Options
            $args = array(
                    'labels' => array(
                        'name' => __( 'Contact Form' ),
                        'singular_name' => __( 'Contact Form Entry' )
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'supports' => array( 'title' ),
                    //'supports' => array( 'title', 'editor', 'thumbnail' ),
                    'rewrite' => array('slug' => 'contact_form'),
                    'show_in_rest' => true,
                    'publicly_queryable' => false,
                    'exclude_from_search' => true,
                    'capability' => 'manage_options',
                    'menu_icon' => 'dashicons-media-text'
                );

            if(current_user_can('administrator')){   
                register_post_type( 'simple_contact_form', $args);
            }
        }

        public function load_assets(){
            wp_enqueue_style( 'my_css', plugin_dir_url(__FILE__) . 'assets/css/main.css', array(), 1, 'all' );
            wp_enqueue_style( 'bootstrap_css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', array(), 1, 'all' );
            wp_enqueue_script( 'custom_js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), 1, true );
            wp_enqueue_script( 'bootstrap_js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array('jquery'), 1, true );
        }

        public function load_shortcode(){
        ?>
            <div class="simple-contact-form">
                <h1>Send us email</h1>
                <p>Please fill the below form</p>

                <form id="simple-contact-form_form">
                    <div class="form-group mb-2">
                        <input type="text" name="name" placeholder="Name" class="form-control" id="name" required>
                    </div>

                     <div class="form-group mb-2">
                        <input type="email" name="email" placeholder="Email" class="form-control" id="email" required>
                    </div>

                    <div class="form-group mb-2">
                        <input type="tel" name="phone" placeholder="Phone" class="form-control" id="phone" required>
                    </div>

                    <div class="form-group mb-2">
                        <textarea placeholder="Type your message" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block w-100">Send Messeage</button>
                    </div>                    
                </form>
            </div>
        <?php 
        }

        public function load_scripts(){
        ?>
            <script type="text/javascript">
            var nonce = '<?php echo wp_create_nonce('wp_rest'); ?>';    

            (function($){
                $("#simple-contact-form_form").submit(function(e){
                    e.preventDefault();
                    var form = $(this).serialize();

                    $.ajax({
                        method: 'post',
                        url: '<?php echo get_rest_url(null, 'simple-contact-form/v1/send-email'); ?>',
                        headers: {'X-WP-Nonce' : nonce},
                        data: form,
                        success: function(){
                            alert("Data submitted successfully");
                            $("#name").val("");
                            $("#email").val("");
                            $("#phone").val("");
                        }
                    });
                });
            })(jQuery)
            </script>
        <?php 
        }

        function register_rest_api(){
            register_rest_route('simple-contact-form/v1', 'send-email', array(
                'methods' => 'post',
                'callback' => array($this, 'handle_contact_form'), 
            ));
        }

        function handle_contact_form($data){
            $headers = $data->get_headers();
            $params = $data->get_params();
            $nonce = $headers['x_wp_nonce'][0];

            if(!wp_verify_nonce($nonce, 'wp_rest')){
                return new WP_REST_Response('Message Not Sent', 422);
            }

            $post_id = wp_insert_post([
                'post_type' => 'simple_contact_form',
                'post_title' => 'Contact enquiry',
                'post_status' => 'publish',
                'meta_input'    => array(
                    'name' => $params['name'],
                    'email' => $params['email'],
                    'phone' => $params['phone']
                )
            ]);

            if($post_id){  
                return new WP_REST_Response('Thanks for email', 200);
            }
        }


        function admin_style_script() {
            if (isset($_GET['action']) == "edit"){
                wp_enqueue_script( 'admin_js', plugin_dir_url(__FILE__) . 'assets/js/admin_script.js', array('jquery'), 1, true );
            }
        }
        
    }
    new SimpleContactForm;
?>