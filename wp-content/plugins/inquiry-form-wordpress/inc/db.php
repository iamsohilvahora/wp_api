<?php
    // Create table for store inquiry form data
    function wp_inquiry_form_table(){
        global $wpdb;

        $table_name = $wpdb->prefix . "inquiry_form_wordpress";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `contact_no` varchar(15) NOT NULL,
                    `message` varchar(255) NOT NULL,
                    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                    ) $charset_collate";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Save contact form 7 data into (wp_contact_form_7_data) table
    // function save_form($wpcf7){
    // echo $_POST['name'];
    // exit;
        // if(isset(($_POST))){
        //     global $wpdb;

        //     $table_name = $wpdb->prefix . "inquiry_form_wordpress";
        //     $result = $wpdb->insert($table_name, array("name" => $name, "contact_no" => $contact_no, "message" => $message), array("%s", "%s", "%s"));

        //     if(!$result){
        //         die('There is an error to insert data');
        //     }
        // }

        
?>