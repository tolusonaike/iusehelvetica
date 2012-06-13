<?php

// Widget: iusehelvetica recent-posts
function widget_iusehelvetica_recent_posts($args) {
	extract($args);
	// These are our own options
	$options = get_option('widget_iusehelvetica_recent_posts');
	$title = $options['title'];  // Title in sidebar for widget
	$show = (int)$options['show'];  // # of Posts we are showing
	$excerpt = $options['excerpt'];  // Showing the excerpt or not
	$exclude = $options['exclude'];  // Categories to exclude
	
	if ($show<1) $show = 1;
	if ($exclude=="") $exclude = "0";
	


	$recent_posts = new WP_Query();
	$recent_posts->query(array(
	'showposts' => $show,
	'category__not_in' => split(",",$exclude)
	));


?>
	<?php echo $before_widget ?>
		<?php echo thematic_before_title() . $title . thematic_after_title(); ?>
		<ul>
<?php
	    if (!empty($recent_posts)) {
 					
        	  while ($recent_posts->have_posts()) : $recent_posts->the_post();  
 					 
				// format a date for the posts
				global $post;

				//var_dump($num_comments);
				$post->post_date = date("j.F.Y",strtotime($post->post_date));
				// if we want to display an excerpt, get it/generate it if no excerpt found
				if ($excerpt) {
					 if (empty($post->post_excerpt)) {
						 $post->post_excerpt = explode(" ",strrev(substr(strip_tags($post->post_content), 0, 105)),2);
						 $post->post_excerpt = strrev($post->post_excerpt[1]);
						 $post->post_excerpt.= "...";
					 }
					 else {
						if(strlen($post->post_excerpt) > 105){
						    $post->post_excerpt = explode(" ",strrev(substr(strip_tags($post->post_excerpt), 0, 105)),2);
						    $post->post_excerpt = strrev($post->post_excerpt[1]);
						    $post->post_excerpt.= "...";
						}
					    
					 }
				}
				

?>							
							
				<li>
					<?php echo '<h5><a  rel="bookmark" href="'.get_permalink($post->ID).'">'?>
					<?php echo $post->post_title .'</a></h5>'; ?>
<?php							
					if ($excerpt) echo '<p class="excerpt">'.strip_tags($post->post_excerpt). '</p>';			
?>					<?php echo '<div class="meta-data clearfix"><span class="date">'.$post->post_date.'</span>
					<span class="comments">'.get_comments_number($post_id).'</span></div>'; ?>	
					<?php endwhile; ?>
				<br></li>
<?php					
				} else echo "<li>No recent Posts</li>";
?>
		</ul>
		<?php echo $after_widget ?>

<?php
}

// Widget control: iusehelvetica recent-posts
function widget_iusehelvetica_recent_posts_control () {
		// Get options
		$options = get_option('widget_iusehelvetica_recent_posts');
		// options exist? if not set defaults
		if ( !is_array($options) )
			$options = array('title'=>'Recent Posts', 'show'=>'3', 'excerpt'=>'1','exclude'=>'');
		
		// form posted?
		if ( $_POST['recent_posts-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['recent_posts-title']));
			$options['show'] = strip_tags(stripslashes($_POST['recent_posts-show']));
			$options['excerpt'] = strip_tags(stripslashes($_POST['recent_posts-excerpt']));
			$options['exclude'] = strip_tags(stripslashes($_POST['recent_posts-exclude']));
			update_option('widget_iusehelvetica_recent_posts', $options);
		}

		// Get options for form fields to show
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$show = htmlspecialchars($options['show'], ENT_QUOTES);
		$excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
		$exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);
		
		// The form fields
		echo '<p style="text-align:right;">
				<label for="recent_posts-title">' . __('Title:') . ' 
				<input style="width: 200px;" id="recent_posts-title" name="recent_posts-title" type="text" value="'.$title.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="recent_posts-show">' . __('Show:') . ' 
				<input style="width: 200px;" id="recent_posts-show" name="recent_posts-show" type="text" value="'.$show.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="recent_posts-excerpt">' . __('Show excerpt:') . ' 
				<input style="width: 200px;" id="recent_posts-excerpt" name="recent_posts-excerpt" type="text" value="'.$excerpt.'" />
				</label></p>';
		echo '<p>Enter the categories to exclude below, this must be a comma seperated list of category id\'s!</p>';	
		echo '<p style="text-align:right;">
				<label for="recent_posts-exclude">' . __('Exclude:') . ' 
				<input style="width: 200px;" id="recent_posts-exclude" name="recent_posts-exclude" type="text" value="'.$exclude.'" />
				</label></p>';
		echo '<input type="hidden" id="recent_posts-submit" name="recent_posts-submit" value="1" />';
	
    
}

//update_option('sidebars_widgets',$null); 

// Register widget for use
register_sidebar_widget(array('My Recent Posts', 'thematic'), 'widget_iusehelvetica_recent_posts');

// Register settings for use, 300x100 pixel form
register_widget_control(array('My Recent Posts', 'thematic'), 'widget_iusehelvetica_recent_posts_control', 300, 200);

?>
