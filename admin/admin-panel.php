<?php


/* -------------------------------------------------------------- 
   
   Form/Actions in admin panel
   
   Extended from Child Theme Options Framework 
   http://wptheming.com/2009/10/thematic-theme-options-panel/
   
   which:
   
   Theme options adapted from "A Theme Tip For WordPress Theme Authors"
   http://literalbarrage.org/blog/archives/2007/05/03/a-theme-tip-for-wordpress-theme-authors/
  
-------------------------------------------------------------- */



/*
 * Remove Thematic Admin Panel
 */
 
function remove_thematic_panel() {
  remove_action('admin_menu' , 'mytheme_add_admin');
}
add_action('init', 'remove_thematic_panel');

// Hook in new admin panel
add_action('admin_menu' , 'iusehelvetica_add_admin'); 

/*
 * Hook in styling for the theme panel.
 */
function iusehelvetica_panel_styles() {
  echo '<link media="all" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/admin/admin-panel-styles.css" rel="stylesheet" />';
}

add_action('admin_head', 'iusehelvetica_panel_styles');

/*
 * Create theme panel.
 */
function iusehelvetica_add_admin() {
  global  $my_options;
  
  if ($_GET['page'] == basename(__FILE__)) {
    
    if ('save' == $_REQUEST['action']) {
      foreach ($my_options as $value) {
        $id = $value['id'];
                
        if ($value['type'] == 'upload') {
          if (!empty($_FILES['attachment_' . $id]['name'])) {

            // New Upload
            $whitelist = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
            $filetype = $_FILES['attachment_' . $id]['type'];

            if (in_array($filetype, $whitelist)) {
              $upload = wp_handle_upload($_FILES['attachment_' . $id], array('test_form' => false));
              $upload['option_name'] = $value['name'];
              update_option($id, $upload['url']);
            }
            else {
              $error = '&error=1';
            }
          }
          elseif (isset($_REQUEST[$id])) {
            // No new file, just the url
            update_option($id, $_REQUEST[$id]);
          }
          else {
            // Delete unwanted data
            delete_option($id);
          }
        }
        elseif ($value['type'] == 'checkbox') {
          if (isset($_REQUEST[$id])) {
            update_option($id, 'true');
          }
          else {
            update_option($id, 'false');
          }
        }
        else {
          if (isset($_REQUEST[$id])) {
            update_option($id, $_REQUEST[$id]);
          }
          else {
            delete_option($id);
          }          
        }                
      }
    
      header('Location: themes.php?page=' . basename(__FILE__) . '&saved=true' . $error);
      die;
    }
    else if ('reset' == $_REQUEST['action']) {
      foreach ($my_options as $value) {
        delete_option($value['id']);
      }
      header('Location: themes.php?page=' . basename(__FILE__) . '&reset=true');
      die;
    }
    else if ('reset_widgets' == $_REQUEST['action']) {
      $null = null;
      update_option('sidebars_widgets', $null);
      header('Location: themes.php?page=' . basename(__FILE__) . '&reset=true');
      die;
    }
    
  }

  if (function_exists(add_object_page)) {
    add_theme_page('Page Title', 'iusehelvetica Options', 8, basename(__FILE__), 'iusehelvetica_admin');
  }
  else {
    add_theme_page('Page Title', 'iusehelvetica Options', 8, basename(__FILE__), 'iusehelvetica_admin');
  }
  
 // Default
}

// Set up admin panel
function iusehelvetica_admin() {
  global   $my_options;
  
  if ($_REQUEST['saved']) {
    echo '<div id="message" class="updated fade"><p><strong> iusehelvetica' . ' ' . __('settings saved.', 'thematic') . '</strong></p></div>';
  }
  if ($_REQUEST['reset']) {
    echo '<div id="message" class="updated fade"><p><strong> iusehelvetica' .  ' ' . __('settings reset.', 'thematic') . '</strong></p></div>';
  }
  if ($_REQUEST['reset_widgets']) {
    echo '<div id="message" class="updated fade"><p><strong> iusehelvetica' . ' ' . __('widgets reset.', 'thematic') . '</strong></p></div>';
  }
  if ($_REQUEST['error']) {
    echo '<div id="message" class="updated fade"><p><strong>The file you submitted was not a valid image type.</strong></p></div>';
  }
?>
<div id="panel" class="wrap">
  <div id="iusehelvetica_theme_icon_32" class="icon32"></div>
  <h2><?php print 'iusehelvetica' ?></h2>

  <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <table class="form-table">
      <tbody>
        <?php iusehelvetica_admin_get_my_options(); ?>
      </tbody>
    </table>

    <p class="submit">
      <input name="save" type="submit" value="<?php _e('Save changes','thematic'); ?>" />    
      <input type="hidden" name="action" value="save" />
    </p>
    
  </form>
  <form method="post" action="">
    <p class="submit">
      <input name="reset" type="submit" value="<?php _e('Reset','thematic'); ?>" />
      <input type="hidden" name="action" value="reset" />
    </p>
  </form>
  <!--
  <form method="post" action="">
    <p class="submit">
      <input name="reset_widgets" type="submit" value="<?php _e('Reset Widgets','thematic'); ?>" />
      <input type="hidden" name="action" value="reset_widgets" />
    </p>
  </form>
  -->

</div>
<?php
}

/*
 * This function does the actual work building out the theme options.
 * 
 * The switch statement below detects the type of each option and builds the form fields.
 * 
 * @todo split out each option into a unique function, drop functions into switch statement.
 */
function iusehelvetica_admin_get_my_options() {
  global  $my_options;
  
  foreach ($my_options as $value) {
    switch ($value['type']) {
      case 'text':
        ?>
        <tr valign="top"> 
          <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'thematic'); ?></label></th>
          <td>
            <input class="regular-text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
            <span class="description"><?php echo __($value['desc'],'thematic'); ?></span>
          </td>
        </tr>
        <?php
        break;
      case 'select':
        ?>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'thematic'); ?></label></th>
            <td>
              <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
              <?php foreach ($value['options'] as $option) { ?>
              <option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; }elseif (!get_option($value['id']) && $value['std'] == $option) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
              <?php } ?>
            </select>
            <span class="description"><?php echo __($value['desc'],'thematic'); ?></span>
          </td>
        </tr>
        <?php
        break;
      case 'textarea':
        ?>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'thematic'); ?></label></th>
          <td>
            <textarea id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" cols="30" rows="5"><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])); } else { echo $value['std']; } ?></textarea>
            <span class="description"><?php echo __($value['desc'],'thematic'); ?></span>
          </td>
        </tr>
        <?php
        break;
      case 'checkbox':                   
           $checked = '';
           $val = get_option($value['id']);
            
            if (!empty($val)) {
              $checked = ($val == 'true' ? 'checked="checked"' : '');
            }
            elseif ($value['std'] == 'true') {
              $checked = 'checked="checked"';
            }
            else {
              $checked = '';
            }
        ?>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'thematic'); ?></label></th>
          <td>
            <input id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" type="checkbox" value="true" <?php print $checked; ?> />
            <span class="description"><?php echo __($value['desc'],'thematic'); ?></span>
          </td>
        </tr>
        <?php
        break;
      case 'radio':
        $val = get_option($value['id']);
        ?>
        <tr valign="top">
          <th scope="row"><label><?php echo __($value['name'],'thematic'); ?></label></th>
          <td>
            <?php foreach ($value['options'] as $option) {
              $id = $option . '_' . uniqid(md5(time()));
              $checked = '';
              
              if (!empty($val)) {
                $checked = ($val == $option ? 'checked="checked"' : '');
              }
              elseif ($value['std'] == $option) {
                $checked = 'checked="checked"';
              }
              else {
                $checked = '';
              }
            ?>
                <div class="radio-button">
                  <input id="<?php print $id; ?>" type="radio" name="<?php echo $value['id']; ?>" value="<?php print $option; ?>" <?php print $checked; ?> />
                  <label for="<?php print $id; ?>"><?php print $option; ?></label>
                </div>
            <?php } ?>
            <span class="description"><?php echo __($value['desc'],'thematic'); ?></span>
          </td>
        </tr>
        <?php
        break;
      case 'upload':
        ?>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'thematic'); ?></label></th>
          <td>
            <?php print get_upload_field($value['id'], $value['std']); ?>
            <span class="description"><?php echo __($value['desc'],'thematic'); ?></span>
            <?php print get_upload_image_preview(get_option($value['id'])); ?>
          </td>
        </tr>
        <?php
        break;
    }
  }
}

?>
