<?php

// Set constant for current theme directory

$dirname = get_stylesheet_directory();

define('IUSEHELVETICA_THEME_DIRECTORY', $dirname . '/');


// Get theme panel
require_once(IUSEHELVETICA_THEME_DIRECTORY . 'admin/admin-panel.php');

// Get panel options
require_once(IUSEHELVETICA_THEME_DIRECTORY . 'admin/admin-panel-options.php');

// Functions related to admin panel
require_once(IUSEHELVETICA_THEME_DIRECTORY . 'admin/admin-panel-functions.php');


// widgets
require_once(IUSEHELVETICA_THEME_DIRECTORY . 'widgets.php');


//////////////////////////////////////  favicon  //////////////////////////////////////////////

function iusehelvetica_favicon() { ?>
<link rel="shortcut icon" href="<?php echo bloginfo('stylesheet_directory') ?>/images/favicon.ico" />
<?php }

add_action('wp_head', 'iusehelvetica_favicon');


//////////////////////////////////////  Add Post Thumbnail Support  //////////////////////////////////////////////

// Make backwards compatible prior to WordPres v2.9
if ( function_exists( 'add_theme_support' ) ) {
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 490, 125, true ); // Normal post thumbnails
add_image_size( 'single-post-thumbnail', 490, 400 ); // Permalink thumbnail size
}





//////////////////////////////////////  Set default widgets /////////////////////////////////////////////////////////////////////////

function preset_child_widgets() {
	$child_preset_widgets = array (
		'primary-aside'  => array( 'search','My Recent Posts'),
		'secondary-aside'  => array( 'rss-links', 'categories','tag-cloud','archives'  )
	);
return $child_preset_widgets;
}
add_filter('thematic_preset_widgets','preset_child_widgets' );


//////////////////////////////////////  actions to remove /////////////////////////////////////////////////////////////////////////

function remove__actions() {

remove_action('thematic_header','thematic_blogtitle',3);
remove_action('thematic_header','thematic_blogdescription',5);
//remove_action('thematic_indexloop', 'thematic_index_loop');
}
add_action('init', 'remove__actions');





//////////////////////////////////// add thumbnail to post and single //////////////////////////////////////////////////

function iusehelvetica_add_pagetitles_thumbnail() {
   // Make changes to the original function
   if (is_page()) {
          //$posttitle = ''; //if you want no title in pages
	  $posttitle = '<h1 class="entry-title">' . get_the_title() . "</h1>\n";
       } elseif (is_single()) {
          $posttitle = '<h1 class="entry-title">' . get_the_title() . "</h1>\n";
	  $posttitle .= '<div class="entry-thumbnail">'. get_the_post_thumbnail($post_id,'single-post-thumbnail') . '</div>';
   //continue with original function
       } elseif (is_404()) {
           $posttitle = '<h1 class="entry-title">' . __('Not Found', 'thematic') . "</h1>\n";
       } else {
           $posttitle = '<h2 class="entry-title"><a href="';
           $posttitle .= get_permalink();
           $posttitle .= '" title="';
           $posttitle .= __('Permalink to ', 'thematic') . the_title_attribute('echo=0');
           $posttitle .= '" rel="bookmark">';
           $posttitle .= get_the_title();
           $posttitle .= "</a></h2>\n";
	   $posttitle .= '<div class="entry-thumbnail">'. get_the_post_thumbnail() . '</div>';
       }
   return $posttitle;
   }
add_filter('thematic_postheader_posttitle' ,'iusehelvetica_add_pagetitles_thumbnail');


/////////////////////////////////////// edit link //////////////////////////////////////////////////

function iusehelvetica_postfooter_posteditlink() {

    global $id;
    
    $posteditlink = '<br/><span class="edit"><a href="' . get_bloginfo('wpurl') . '/wp-admin/post.php?action=edit&amp;post=' . $id;
    $posteditlink .= '" title="' . __('Edit post', 'thematic') .'">';
    $posteditlink .= __('Edit', 'thematic') . '</a></span>';
    return $posteditlink; 
    
}
add_filter('thematic_postfooter_posteditlink' ,'iusehelvetica_postfooter_posteditlink');


/////////////////////////////////////// category link //////////////////////////////////////////////////

function iusehelvetica_postfooter_postcategory() {
    
    $postcategory = '<span class="cat-links">';
    if (is_single()) {
        $postcategory .= __('Cat: ', 'thematic') . get_the_category_list(', ');
        $postcategory .= '</span>';
    } elseif ( is_category() && $cats_meow = thematic_cats_meow(', ') ) { /* Returns categories other than the one queried */
        $postcategory .= __('Also posted in ', 'thematic') . $cats_meow;
        $postcategory .= '</span>';
    } else {
        $postcategory .= '';
        $postcategory .= '</span>';
    }
    return $postcategory; 
    
}
add_filter('thematic_postfooter_postcategory' ,'iusehelvetica_postfooter_postcategory');


/////////////////////////////////////// tags link //////////////////////////////////////////////////

function iusehelvetica_postfooter_posttags() {

    if (is_single()) {
        $tagtext = __('Tagged:', 'thematic');
        $posttags = get_the_tag_list("<br/><span class=\"tag-links\"> $tagtext ",', ','</span>');
    } elseif ( is_tag() && $tag_ur_it = thematic_tag_ur_it(', ') ) { /* Returns tags other than the one queried */
        $posttags = '<br/><span class="tag-links">' . __(' Also tagged ', 'thematic') . $tag_ur_it . '</span>';
    } else {
        $tagtext = '';
        $posttags ='';
    }
    return $posttags; 

}
add_filter('thematic_postfooter_posttags' ,'iusehelvetica_postfooter_posttags');


/////////////////////////////////////// comments link //////////////////////////////////////////////////

function iusehelvetica_postfooter_postcomments() {
    if (comments_open()) {
        $postcommentnumber = get_comments_number();
        if ($postcommentnumber > '1') {
            $postcomments = '<br/> <span class="comments-link"><a href="' . get_permalink() . '#comments" title="' . __('Comment on ', 'thematic') . the_title_attribute('echo=0') . '">';
            $postcomments .= get_comments_number() . __(' Comments', 'thematic') . '</a></span>';
        } elseif ($postcommentnumber == '1') {
            $postcomments = ' <br/><span class="comments-link"><a href="' . get_permalink() . '#comments" title="' . __('Comment on ', 'thematic') . the_title_attribute('echo=0') . '">';
            $postcomments .= get_comments_number() . __(' Comment', 'thematic') . '</a></span>';
        } elseif ($postcommentnumber == '0') {
            $postcomments = '';
        }
    } else {
        $postcomments = ' <span class="comments-link comments-closed-link">' . __('Comments closed', 'thematic') .'</span>';
    }
    // Display edit link
    if (current_user_can('edit_posts')) {
        $postcomments .= thematic_postfooter_posteditlink();
    }               
    return $postcomments; 
    
}
add_filter('thematic_postfooter_postcomments' ,'iusehelvetica_postfooter_postcomments');

/////////////////////////////////////// postheader postmeta /////////////////////

function iusehelvetica_postheader_postmeta() {
    $postmeta = '<div class="entry-meta">';
    $postmeta .= thematic_postmeta_entrydate();
    $postmeta .= "</div><!-- .entry-meta -->\n";
    return $postmeta;
    }
add_filter('thematic_postheader_postmeta' ,'iusehelvetica_postheader_postmeta');

/////////////////////////////////////// Create permalink, comments link, and RSS on single posts link //////////////////////////////////////////////////

function iusehelvetica_postfooter_postconnect() {
    
    $postconnect = __('<br/>Bookmark the ', 'thematic') . '<a href="' . get_permalink() . '" title="' . __('Permalink to ', 'thematic') . the_title_attribute('echo=0') . '">';
    $postconnect .= __('permalink', 'thematic') . '</a>.';
    if ((comments_open()) && (pings_open())) { /* Comments are open */
        $postconnect .= ' <a class="comment-link" href="#respond" title ="' . __('Post a comment', 'thematic') . '">' . __('Post a comment', 'thematic') . '</a>';
        $postconnect .= __(' or leave a trackback: ', 'thematic');
        $postconnect .= '<a class="trackback-link" href="' . trackback_url(FALSE) . '" title ="' . __('Trackback URL for your post', 'thematic') . '" rel="trackback">' . __('Trackback URL', 'thematic') . '</a>.';
    } elseif (!(comments_open()) && (pings_open())) { /* Only trackbacks are open */
        $postconnect .= __(' Comments are closed, but you can leave a trackback: ', 'thematic');
        $postconnect .= '<a class="trackback-link" href="' . trackback_url(FALSE) . '" title ="' . __('Trackback URL for your post', 'thematic') . '" rel="trackback">' . __('Trackback URL', 'thematic') . '</a>.';
    } elseif ((comments_open()) && !(pings_open())) { /* Only comments open */
        $postconnect .= __(' Trackbacks are closed, but you can ', 'thematic');
        $postconnect .= '<a class="comment-link" href="#respond" title ="' . __('Post a comment', 'thematic') . '">' . __('post a comment', 'thematic') . '</a>.';
    } elseif (!(comments_open()) && !(pings_open())) { /* Comments and trackbacks closed */
        $postconnect .= __(' Both comments and trackbacks are currently closed.', 'thematic');
    }
    // Display edit link on single posts
    if (current_user_can('edit_posts')) {
        $postconnect .= ' ' . thematic_postfooter_posteditlink();
    }
    return $postconnect; 

}
add_filter('thematic_postfooter_postconnect' ,'iusehelvetica_postfooter_postconnect');

/////////////////////////////////////////// custom footer  /////////////////////////////////////
function my_footer($thm_footertext) {
$thm_footertext = ' ';
$thm_footertext .= '      

     <a rel="iusehelvetica" title="iusehelvetica by tolu sonaike" href="http://www.tolusonaike.com/iusehelvetica" class="wp-link">iusehelvetica</a> by
     <a rel="Tolu Sonaike"  title="Tolu Sonaike" href="http://www.tolusonaike.com" class="wp-link">Tolu Sonaike</a>.
     Built on the <a rel="designer" title="Thematic Theme Framework" href="http://themeshaper.com/thematic/" class="theme-link">Thematic Theme Framework</a>.     
     Powered by <a rel="generator" title="WordPress" href="http://WordPress.org/" class="wp-link">WordPress</a>.';

return $thm_footertext;
}
add_filter('thematic_footertext', 'my_footer');

?>
