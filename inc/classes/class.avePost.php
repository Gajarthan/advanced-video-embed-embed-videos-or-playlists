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
  function ave_publishPost(){
    $title = $_REQUEST['title'];
    $term = $_REQUEST['term'];
    $thumb = $_REQUEST['thumb'];
    $short = $_REQUEST['short'];
    $id = $_REQUEST['id'];
    $type = $_REQUEST['type'];
    $user_id = get_current_user_id();
    if($title == '' || $short == '' || $term == '' || $thumb == ''){
      echo 'ERROR';
    } else {
      $post = array(
          //'ID' => $car->VehicleRecordID,
          'post_title'    => $title,
          'post_content'  => $short,
          'post_status'   => 'publish',
          'post_author'   => $user_id,
          'post_type' => $type
      );
      $post_id = wp_insert_post($post);
     	$filename = rand().".jpeg";
     	$image_data = file_get_contents($thumb);
      $parent_post_id = $post_id;
      $upload_dir = wp_upload_dir();
     if(wp_mkdir_p($upload_dir['path']))
         $file = $upload_dir['path'] . '/' . $filename;
     else
         $file = $upload_dir['basedir'] . '/' . $filename;
     file_put_contents($file, $image_data);

     $wp_filetype = wp_check_filetype($filename, null );
     $attachment = array(
         'post_mime_type' => $wp_filetype['type'],
         'post_title' => sanitize_file_name($filename),
         'post_content' => '',
         'post_status' => 'inherit'
     );
     $attach_id = wp_insert_attachment( $attachment, $file, $parent_post_id );
     require_once(ABSPATH . 'wp-admin/includes/image.php');
     $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
     wp_update_attachment_metadata( $attach_id, $attach_data );
     set_post_thumbnail( $parent_post_id, $attach_id );
     $suggestion_term = $term;
     $taxonomy = 'category'; // The name of the taxonomy the term belongs in
      wp_set_post_terms( $post_id, array($suggestion_term), $taxonomy );
      echo site_url().'/?p='.$post_id;
    }
  }
}
