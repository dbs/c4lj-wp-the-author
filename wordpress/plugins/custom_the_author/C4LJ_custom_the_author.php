<?php
/*
Plugin Name: C4LJ Custom the_author()
Plugin URI: http://journal.code4lib.org/
Description: Replaces the text returned by the_author() with the value of the "author" custom field.
Version: 0.3
Author: Jonathan Brinley
Author URI: http://xplus3.net/
*/
?>
<?php
function c4lj_get_custom_author($user_author) {
	global $post;
	$custom_author = get_post_meta($post->ID, "author", TRUE);
	$custom_author = str_replace('& ', '&amp;', $custom_author);
	return $custom_author;
}

add_filter('the_author', 'c4lj_get_custom_author');
?>