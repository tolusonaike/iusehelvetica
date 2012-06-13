<?php





////////////////////////////////////// Switch CSS Style  //////////////////////////////

function iusehelvetica_inverted_stylesheet($content) {
  
  $content .= "\t";
  $content .= '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/css/inverted.css' . '" />';
  $content .= "\n\n";
  
  return $content;
}

$invertedstyle = get_option('iusehelvetica_theme_style');

if ($invertedstyle=='Inverted') {
	add_filter('thematic_create_stylesheet', 'iusehelvetica_inverted_stylesheet');
}

/////////////////////////////////////  fixed sidebars ////////////////////////////////

function iusehelvetica_theme_layout($content) {
  
  $content .= "\t";
  $content .= '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/css/fixed_sidebars.css' . '" />';
  $content .= "\n\n";
  
  return $content;
}

$fixedstyle = get_option('iusehelvetica_theme_layout');

if ($fixedstyle=='Fixed') {
	add_filter('thematic_create_stylesheet', 'iusehelvetica_theme_layout');
}

////////////////////////////////////  Replace Blog Title with Logo  //////////////////////////////
	
function add_iusehelvetica_logo() {

	$logo = get_option('iusehelvetica_logo');
	if (!empty($logo)) {
		add_action('thematic_header','iusehelvetica_logo', 3);
	}
	else{
		add_action('thematic_header','iusehelvetica_blogtitle',3);
		add_action('thematic_header','iusehelvetica_blogdescription',5);
	}
}
add_action('init','add_iusehelvetica_logo');

function iusehelvetica_logo() {
	$logo = get_option('iusehelvetica_logo');
	$width=get_option('iusehelvetica_logo_width');
	if (empty($width)) $width = '150';
	if (!empty($logo)) { ?>
		<div id="logo"><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/timthumb.php?src=<?php echo $logo; ?>&amp;w=<?php echo $width; ?>&amp;zc=1" alt="<?php bloginfo('name') ?>" /></a></div>
		<?php
		}
	}
	
////////////////////////////////////// Create the blog title (i use helvetica) ///////////////////////////////////////////////////

function iusehelvetica_blogtitle() { ?>
<h1 id="blog-title" class="clearfix">
    <a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a>
</h1>
<?php }



///////////////////////////////////// Create the blog description (therefore i am cool) ///////////////////////////////////////////////

function iusehelvetica_blogdescription() {  ?>
<div id="blog-description" class="clearfix"><?php bloginfo('description') ?></div>
<?php }




////////////////////////////////////// Add Google Analytics Code if Available  //////////////////////////////////////////
 
function analytic_footer() {

	echo stripslashes(get_option('iusehelvetica_googleanalytics'));
}
add_filter ('thematic_after', 'analytic_footer');


///////////////////////////////////// Build upload field  //////////////////////////////////////////////////
 
function get_upload_field($id, $std = '') {
  $data = get_option($id);
  
  $field = '<input id="' . $id . '" type="file" name="attachment_' . $id . '" />' .
           '<span class="submit"><input name="save" type="submit" value="Upload" class="button panel-upload-save" /></span>' .
           '<div><input class="regular-text" type="text" class="" name="' . $id . '" value="' . ($data ? $data : $std) . '" readonly="readonly" /></div>';

  return $field;
}

///////////////////////////////////// Build image preview using timthumb.php  //////////////////////////////////////////
function get_upload_image_preview($data = '') {
  if (!empty($data)) {
    $img_preview = '<div class="img_preview">' .
                  '<img src="' . get_bloginfo('stylesheet_directory') . '/scripts/timthumb.php?src=' . $data . '&amp;w=300&amp;zc=1" alt="Thumbnail Preview">' .
                  '</div>';
				  
                    return $img_preview;
                   
    return $img_preview;
  }
  else {
    return;
  }
}
 
 

////////////////////////////////////////// css browser javascript  /////////////////////////////////////////

function iusehelvetica_css_browser_script($content) {
  
  $content .= "\t";
  $content .= '<script src="'. get_bloginfo('stylesheet_directory') .'/js/css_browser_selector.js" type="text/javascript"></script>';
  $content .= "\n\n";
  
  return $content;
}

add_filter('thematic_head_scripts', 'iusehelvetica_css_browser_script');



?>
