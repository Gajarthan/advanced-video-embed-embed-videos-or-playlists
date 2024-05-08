<?php
if ( ! defined( 'ABSPATH' ) ) {
    die('not defined');
}
class AvePost{

    function __construct(){
        $this->ave_loadHooks();
    }

    function ave_loadHooks(){
        add_action('wp_ajax_ave_getTypes',array($this,'ave_getTypes'));
        add_action('wp_ajax_nopriv_ave_getTypes',array($this,'ave_getTypes'));
        add_action('wp_ajax_ave_getTerms',array($this,'ave_getTerms'));
        add_action('wp_ajax_nopriv_ave_getTerms',array($this,'ave_getTerms'));
        add_action('wp_ajax_ave_publishPost',array($this,'ave_publishPost'));
        add_action('wp_ajax_nopriv_ave_publishPost',array($this,'ave_publishPost'));
    }

    function ave_getTypes(){
        $post_types = get_post_types( '', 'names' );
        $html = "<select name='post-type-ave' id='post_type_ave_'>";
        $html .= "<option>Select post type</option>";
        foreach ( $post_types as $post_type ) {
            if($post_type =='page' || $post_type == 'attachment' || $post_type == 'revision' || $post_type == 'nav_menu_item'){
                continue;
            }
            $html .= '<option value="'.$post_type.'">' . $post_type . '</option>';
        }
        $html .= "</select>";
        echo $html;
        exit();
    }

    function ave_getTerms(){
        if(isset($_REQUEST['post_t'])){
            $type = $_REQUEST['post_t'];
        } else {
            $type = '';
        }
        if($type != ''){
            $categories = get_terms('category', array(
                'post_type' => array($type),
                'fields' => 'all'
            ));
            $html = "<select name='post-tem-ave' id='post_term_ave_'>";
            $html .= "<option>Select post category</option>";
            $html .= "<option value='Uncategorized'>Uncategorized</option>";
            foreach($categories as $cat){
                $html .= '<option value="'.$cat->name.'">'.$cat->name.'</option>';
            }
            $html .= '</select>';
            echo $html;
        }
        exit();
    }
    function ave_publishPost() {
        // Check nonce and verify user permission
        if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'ave_publish_nonce')) {
            wp_die('Security check failed');
        }

        // Check if the user has permission to publish posts
        if (!current_user_can('publish_posts')) {
            wp_die('You do not have permission to perform this action');
        }

        // Sanitize and validate input data
        $title = sanitize_text_field($_REQUEST['title']);
        $term = sanitize_text_field($_REQUEST['term']);
        $thumb = esc_url_raw($_REQUEST['thumb']);
        $short = sanitize_text_field($_REQUEST['short']);
        $type = sanitize_key($_REQUEST['type']);

        // Check required fields
        if (empty($title) || empty($short) || empty($term) || empty($thumb)) {
            echo 'ERROR: Missing required fields';
            return;
        }

        // Initialize post array
        $user_id = get_current_user_id();
        $post = array(
            'post_title'    => $title,
            'post_content'  => $short,
            'post_status'   => 'publish',
            'post_author'   => $user_id,
            'post_type'     => $type
        );

        // Insert the post
        $post_id = wp_insert_post($post);

        if ($post_id == 0) {
            echo 'ERROR: Failed to create post';
            return;
        }

        // Handle the image upload
        $filename = wp_unique_filename(wp_upload_dir()['path'], rand() . ".jpeg");
        $image_data = file_get_contents($thumb); // Ensure this URL is validated or within your control

        if (!$image_data) {
            echo 'ERROR: Failed to retrieve image';
            return;
        }

        $file = wp_upload_bits($filename, null, $image_data);
        if (!$file['error']) {
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file['file'], $post_id);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);
            set_post_thumbnail($post_id, $attach_id);
        } else {
            echo 'ERROR: Failed to save image';
            return;
        }

        // Set post terms
        $taxonomy = 'category'; // The name of the taxonomy the term belongs in
        wp_set_post_terms($post_id, array($term), $taxonomy);

        echo site_url() . '/?p=' . $post_id;
    }
}
