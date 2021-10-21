<?php
/**
 * Plugin Name:       Inquiry Form WordPress
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Submit inquiry in form.Use Short code [inquiry-form]
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sohil Vahora
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       inquiry_form_wordpress-plugin
 * Domain Path:       /languages
*/

	if(!defined('ABSPATH')){
    	die("What are you trying to get");
	}

    if(!defined('PLUGIN_DIR_PATH')){
        define('PLUGIN_DIR_PATH', plugin_dir_url(__FILE__));
    }

    // Database file
    require plugin_dir_path(__FILE__) .'/inc/db.php';

    // Call the function (wp_contact_form_7_table) in database file when plugin is activated
    register_activation_hook( __FILE__, 'wp_inquiry_form_table');

    // Show contact form 7 data inside menu page
    // require plugin_dir_path(__FILE__) .'/inc/settings.php';
    
    // Load css and js
    // require plugin_dir_path(__FILE__) .'/inc/assets.php';

    class InquiryForm{
        public function __construct(){
            // Create admin menu page
            add_action('admin_menu', array($this, 'register_my_custom_menu_page'));            
            // Add assets (js, css, etc)
            add_action('wp_enqueue_scripts',array($this, 'load_assets'));

            // Add shortcode
            add_shortcode('inquiry-form', array($this, 'load_shortcode'));

            // Load javascript footer side
            add_action('wp_footer', array($this, 'load_scripts'));

            // Register rest api
            add_action('rest_api_init', array($this, 'register_rest_api'));

            // Update CSS and JS within in Admin        
            add_action('admin_enqueue_scripts', array($this, 'admin_style_script'));

            add_action('wp_ajax_addinquiry', array($this, 'addinquiry'));
            add_action('wp_ajax_nopriv_addinquiry', array($this, 'addinquiry'));

            
        }

        public function register_my_custom_menu_page(){
            if(current_user_can('administrator')){   
                add_menu_page('Inquiry Form Data',
                    'INQUIRY-DB',
                    'manage_options',
                    'inquiry_slug',
                    array($this, 'inquiry_page_html'),
                    'dashicons-database-view',
                    25
                );
            }
        }

        function inquiry_page_html(){
            if(!is_admin()){
                return;
            }
            ?>
            <h1>User Inquiry Details</h1>
            <?php
                global $wpdb;

                $table_name = $wpdb->prefix . "inquiry_form_wordpress";
                $posts = $wpdb->get_results("SELECT name, contact_no, message, time FROM $table_name");
                if($posts):
                ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Contact No.</th>
                            <th scope="col">Message</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $id = 1;
                        foreach($posts as $post):
                        ?>
                        <tr class="text-center">
                            <td><?= $id++; ?></td>
                            <td><?= $post->name; ?></td>
                            <td><?= $post->contact_no; ?></td>
                            <td><?= $post->message; ?></td>
                            <td><?= $post->time; ?></td>
                        </tr>
                        <?php 
                        endforeach;
                        ?>    
                    </tbody>
                </table>
                <?php
                    else:
                ?>
                    <p>Inquiry Details Not Found.</p>
                <?php        
                    endif;    
        }

        public function load_assets(){
            wp_enqueue_style( 'my_css', plugin_dir_url(__FILE__) . 'assets/css/main.css', array(), 1, 'all' );
            wp_enqueue_style( 'bootstrap_css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', array(), 1, 'all' );
            wp_enqueue_script( 'bootstrap_js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array('jquery'), 1, true );

            wp_enqueue_script( 'custom_js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), 1, true );
            // wp_localize_script('custom_js', 'ajax_object', 
            //     // array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) 
            //     array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) 
            //   );
           


        }

        public function load_shortcode(){
        ?>
            <div class="simple-contact-form">
                <p>Please fill the below form</p>

                <form id="inquiry_form">
                    <div class="form-group mb-2">
                        <input type="text" name="user_name" placeholder="Name" class="form-control" id="user_name" required>
                    </div>

                     <div class="form-group mb-2">
                        <input type="number" name="contact_no" placeholder="contact_no" class="form-control" id="contact_no" required>
                    </div>

                    <div class="form-group mb-2">
                        <textarea placeholder="Type your message" class="form-control" name="message" id="message"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block w-100">Send Inquiry</button>
                    </div>                    
                </form>
            </div>
        <?php 
        }

        public function load_scripts(){
        ?>
            <script type="text/javascript">
            (function($){
                // var ajaxVar = ajax_object.ajaxurl;
                // $("#inquiry_form").submit(function(e){
                //     e.preventDefault();
                //     var form = $(this).serialize();
                //     console.log(form);

                //     $.ajax({
                //         method: 'post',
                //         url: ajaxVar,
                //         // headers: {'X-WP-Nonce' : nonce},
                //         data: form,
                //         action: "addinquiry",
                //         success: function(response){
                //             alert("Data submitted successfully");
                //             $("#name").val("");
                //             $("#contact_no").val("");
                //             $("#message").val("");
                //         }
                //     });
                // });
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

        function addinquiry(){
            // echo "<script>alert('heejk');</script>";
            $user_name = $_POST['user_name'];
            $contact_no = $_POST['contact_no'];
            $message = $_POST['message'];
            // print_r($_POST);
            // exit;
            if ($user_name != "") {
                echo "Your Data is" . $user_name;
            } else {
                echo "Data you Entered is wrong";
            }
            die();
        }

        
        
    }
    new InquiryForm;

    // add_action('wp_ajax_addinquiry', ['InquiryForm', 'addinquiry']);
    // add_action('wp_ajax_nopriv_addinquiry', ['InquiryForm', 'addinquiry']);
?>