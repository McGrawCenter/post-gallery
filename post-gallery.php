<?php
   /*
   Plugin Name: Post Gallery
   Description: Embed a gallery of all posts.
   Version: 1.0
   License: GPL2
   */


// REMEMBER ART MAKING !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // LOAD SCRIPTS
    function post_gallery_scripts() {
	wp_register_style('post-gallery-css', plugins_url('css/style.css',__FILE__ ));
	wp_enqueue_style('post-gallery-css');
	
        wp_register_script( 'post-gallery', plugins_url( '/js/script.js', __FILE__ ),  array( 'jquery' ));
        wp_enqueue_script( 'post-gallery' );
        $data = array(	'site_url' => site_url(), 'ajax_url' => admin_url( 'admin-ajax.php' ));
        wp_localize_script( 'post-gallery', 'vars', $data );	
	
	
    }
    add_action( 'wp_enqueue_scripts', 'post_gallery_scripts' );
    
    
    
    

/******************************************
* 
******************************************/

add_shortcode( 'post-gallery', 'insert_post_gallery' );

function insert_post_gallery( $atts ){

  if(isset($atts['exclude'])) { 
     $cat_include = $atts['exclude'];
     $cats = get_categories(array('hide_empty'=> false,'exclude' => $cat_include));
     } 
   else {
     $cats = get_categories(array('hide_empty'=> false));
   }  

  if($cats) {
    foreach($cats as $cat) {
      $catarr[] = "<a href='#' class='cat_filter' rel='{$cat->term_id}'>{$cat->name}</a>";
    }
  }
  
  $nav = "<div id='post-gallery-filters'>".implode(' ', $catarr)."</div>";
  return $nav."\n<div id='gallery'></div>";
}




/******************************************
* 
******************************************/

add_action( 'rest_api_init', 'add_my_custom_fields' );

function add_my_custom_fields() {

  register_rest_field(
    'post', 
    'thumbnail_large',
    array(
        'get_callback'    => 'get_post_thumbnail_large',
        'update_callback' => null,
        'schema'          => null,
         )
    );
}

function get_post_thumbnail_large( $object, $field_name, $request ) {
  $img = get_the_post_thumbnail_url($object['id'], 'large');
  return $img;
}




/******************************************
* 
******************************************/

add_action( 'wp_ajax_post_gallery_pagination', 'post_gallery_pagination' );

function post_gallery_pagination() {

	wp_die(); // this is required to terminate immediately and return a proper response
}




