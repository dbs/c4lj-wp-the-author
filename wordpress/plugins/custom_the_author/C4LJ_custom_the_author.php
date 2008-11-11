<?php
/*
Plugin Name: C4LJ Custom the_author()
Plugin URI: http://journal.code4lib.org/
Description: Replaces the text returned by the_author() with the value of the "author" custom field.
Version: 3.0
Author: Jonathan Brinley
Author URI: http://xplus3.net/
*/
?>
<?php
function c4lj_get_the_author($user_author) {
	global $post;
	$custom_author = get_post_meta($post->ID, "author", TRUE);
  if ( $custom_author == "" ) {
    return $user_author;
  }
	$custom_author = str_replace('& ', '&amp; ', $custom_author);
	return $custom_author;
}

function c4lj_add_author_meta_box(  ) {
  // This function exists solely for the purpose of calling add_meta_box with the c4lj_author_meta_box callback
  if ( function_exists('add_meta_box') ) {
    add_meta_box( 'author-meta-box', 'Author(s)', 'c4lj_author_meta_box', 'post', 'normal', 'high' );
  }
}

function c4lj_author_meta_box(  ) {
  global $post;
  $meta_box_value = get_post_meta($post->ID, 'author', true);
  
  echo'<input type="hidden" name="author_noncename" id="author_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
  echo'<div><input type="text" name="author_value" value="'.$meta_box_value.'" size="55" /></div>';
  echo'<p><label for="author_value">The name of the author(s) to appear on the issue page and the syndication feeds.</label></p>';
}

function c4lj_save_author_data( $post_id ) {
  global $post;
  if ( !wp_verify_nonce( $_POST['author_noncename'], plugin_basename(__FILE__) )) {  
    return $post_id;
  }
  if ( 'page' == $_POST['post_type'] ) {  
    if ( !current_user_can( 'edit_page', $post_id ))  
      return $post_id;  
  } else {  
    if ( !current_user_can( 'edit_post', $post_id ))  
      return $post_id;  
  }
  $data = $_POST['author_value'];
  $old_data = get_post_meta($post_id, 'author', true);
  
  if ( $data == "" ) {
    delete_post_meta( $post_id, 'author' );
  } elseif ( $old_data == "" ) {
    add_post_meta($post_id, 'author', $data, true);
  } elseif ( $data != $old_data ) {
    update_post_meta($post_id, 'author', $data);
  }
}

add_filter('the_author', 'c4lj_get_the_author');
add_action('admin_menu', 'c4lj_add_author_meta_box');
add_action('save_post', 'c4lj_save_author_data');
?>