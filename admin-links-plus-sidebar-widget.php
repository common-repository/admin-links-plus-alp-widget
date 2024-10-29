<?php
/*
Plugin Name: Admin Links Plus (ALP) widget
Plugin URI: http://alicious.com/admin-links-plus-wp-sidebar-widget/
Original Plugin URI: http://kdmurray.net/2007/08/14/wordpress-plugin-admin-links-widget/
Description: 	Provide links to administrative functions from the sidebar. Since 1.2.0 including loginout link using wp_loginout() and login form when not logged-in.
Version: 1.3.0
Author: pbhj from an original work by kdmurray
Author URI: http://alicious.com/admin-links-plus-wp-sidebar-widget/
*/

function widget_adminlinksplus_init() {
  function widget_adminlinksplus($args) {
  global $user_level;

  if ($user_level == 10) {
    extract($args);
    echo '<!--';
    $blog_url = get_settings('home');
    echo '-->';
    $options	        = get_option('widget_adminlinksplus');

    $title	        = empty($options['title'])	? __('Admin Links+')	: $options['title'];
    $path	        = empty($options['path'])	? __('Admin Links+')	: $options['path'];

    $show_loginout	    = $options['show_loginout']		? '1'			: '0'; // pbhj
    $show_dashboard	    = $options['show_dashboard']	? '1'			: '0';
    $show_editthispost      = $options['show_editthispost']     ? '1'                   : '0';
    $show_editthispage      = $options['show_editthispage']     ? '1'                   : '0';
    $show_newpost	    = $options['show_newpost']		? '1'			: '0';
    $show_manplugins	= $options['show_manplugins']		? '1'			: '0';
    $show_mancomments	= $options['show_mancomments']		? '1'			: '0';
    $show_manthemes 	= $options['show_manthemes']		? '1'			: '0';
    $show_manwidgets	= $options['show_manwidgets']		? '1'			: '0';

    echo $before_widget;
    echo $before_title . $title . $after_title;
    echo '<ul>';
    if ($show_loginout) { // pbhj, wp_loginout is defined in general-template.php
      echo '<li>';
      wp_loginout();
      echo '</li>';
    }
    if ($show_dashboard) {
      echo '<li><a href="'.$blog_url.'/wp-admin/">dashboard</a></li>';
    }
    if (is_single() && $show_editthispost) {
      echo '<li><a href="'.$blog_url.'/wp-admin/post.php?action=edit&post=';
      echo the_id();
      echo '">edit this post</a></li>';
    }
    if (is_page() && $show_editthispage) {
      echo '<li><a href="'.$blog_url.'/wp-admin/page.php?action=edit&post=';
      echo the_id();
      echo '">edit this page</a></li>';
    }
    if ($show_newpost) { 
      echo '<li><a href="'.$blog_url.'/wp-admin/post-new.php">new post</a></li>';
    }
    if ($show_manplugins) { 
      echo '<li><a href="'.$blog_url.'/wp-admin/plugins.php">manage plugins</a></li>';
    }
    if ($show_mancomments) { 
      echo '<li><a href="'.$blog_url.'/wp-admin/edit-comments.php">manage comments</a></li>';
    }
    if ($show_manthemes) { 
      echo '<li><a href="'.$blog_url.'/wp-admin/themes.php">manage themes</a></li>';
    }
    if ($show_manwidgets) { 
      echo '<li><a href="'.$blog_url.'/wp-admin/widgets.php">manage widgets</a></li>';
    }
    echo '</ul>';
    echo $after_widget;
  } else { 
    // ELSE ================================  show following for non-logged-in users
    extract($args);
    $blog_url = get_settings('home');
    $options = get_option('widget_adminlinksplus');

    if ($options['show_loginout']) {
      $title = empty($options['title'])	? __('Admin Links+')	: $options['title'];
      echo $before_widget;
      echo $before_title . $title . $after_title;
//       echo '<ul>';
//       echo '<li>'.wp_loginout().'</li>';
//       echo '</ul>';
?>
<form method="post" action="<?php echo get_bloginfo('wpurl'); ?>/wp-login.php" id="alp_loginform" name="loginform">
	<p>
		<label>Username<br/>
		<input type="text" tabindex="10" size="20" value="" class="input" id="user_login" name="log"/></label>
	</p>
	<p>
		<label>Password<br/>
		<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="pwd"/></label>
	</p>
	<p class="forgetmenot"><label><input type="checkbox" tabindex="90" value="forever" id="rememberme" name="rememberme"/> Remember Me</label></p>
	<p class="submit">
		<input type="submit" tabindex="100" value="Log In" id="wp-submit" name="wp-submit"/>
		<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="redirect_to" class=""/>
		<input type="hidden" value="1" name="testcookie" class=""/>
	</p>
</form>
<?php
      echo $after_widget;
    }
  }
}

function widget_adminlinksplus_control() {
  $options = $newoptions = get_option('widget_adminlinksplus');
  if ( $_POST["admlink_submit"] ) {
    $newoptions['title']	= strip_tags(stripslashes($_POST["admlink_title"]));
    $newoptions['show_loginout']		= isset($_POST['admlink_show_loginout']);
    $newoptions['show_dashboard']		= isset($_POST['admlink_show_dashboard']);
    $newoptions['show_editthispost']	= isset($_POST['admlink_show_editthispost']);
    $newoptions['show_editthispage']	= isset($_POST['admlink_show_editthispage']);
    $newoptions['show_newpost']		= isset($_POST['admlink_show_newpost']);
    $newoptions['show_manplugins']		= isset($_POST['admlink_show_manplugins']);
    $newoptions['show_mancomments']		= isset($_POST['admlink_show_mancomments']);
    $newoptions['show_manthemes']		= isset($_POST['admlink_show_manthemes']);
    $newoptions['show_manwidgets']		= isset($_POST['admlink_show_manwidgets']);
  }
  if ( $options != $newoptions ) {
    $options = $newoptions;
    update_option('widget_adminlinksplus', $options);
  }
  $title		= htmlspecialchars($options['title'], ENT_QUOTES);
  $show_loginout	= $options['show_loginout'] ? 'checked="checked"' : '';
  $show_dashboard	= $options['show_dashboard'] ? 'checked="checked"' : '';
  $show_editthispost  	= $options['show_editthispost'] ? 'checked="checked"' : '';
  $show_editthispage  	= $options['show_editthispage'] ? 'checked="checked"' : '';
  $show_newpost	  	= $options['show_newpost'] ? 'checked="checked"' : '';
  $show_manplugins  	= $options['show_manplugins'] ? 'checked="checked"' : ''; 
  $show_mancomments 	= $options['show_mancomments'] ? 'checked="checked"' : ''; 
  $show_manthemes   	= $options['show_manthemes'] ? 'checked="checked"' : ''; 
  $show_manwidgets  	= $options['show_manwidgets'] ? 'checked="checked"' : ''; 
?>

<p>Title: <input style="width: 250px;" id="admlink_title" name="admlink_title" type="text" value="<?php echo $title; ?>" /></p>
<p style="text-align:right;margin-right:40px;">Show log in / log out? <input class="checkbox" type="checkbox" <?php echo $show_loginout; ?> id="admlink_show_loginout" 
name="admlink_show_loginout" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>dashboard</em>? <input class="checkbox" type="checkbox" <?php echo $show_dashboard; ?> id="admlink_show_dashboard" 
name="admlink_show_dashboard" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>edit this post</em>? <input class="checkbox" type="checkbox" <?php echo $show_editthispost; ?> id="admlink_show_editthispost" 
name="admlink_show_editthispost" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>edit this page</em>? <input class="checkbox" type="checkbox" <?php echo $show_editthispage; ?> id="admlink_show_editthispage" 
name="admlink_show_editthispage" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>write a post</em>? <input class="checkbox" type="checkbox" <?php echo $show_newpost; ?> id="admlink_show_newpost" 
name="admlink_show_newpost" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>manage plugins</em>? <input class="checkbox" type="checkbox" <?php echo $show_manplugins; ?> id="admlink_show_manplugins" 
name="admlink_show_manplugins" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>manage comments</em>? <input class="checkbox" type="checkbox" <?php echo $show_mancomments; ?>id="admlink_show_mancomments" 
name="admlink_show_mancomments" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>manage themes</em>? <input class="checkbox" type="checkbox" <?php echo $show_manthemes; ?>id="admlink_show_manthemes" 
name="admlink_show_manthemes" /></p>
<p style="text-align:right;margin-right:40px;">Show <em>manage widgets</em>? <input class="checkbox" type="checkbox" <?php echo $show_manwidgets; ?>id="admlink_show_manwidgets" 
name="admlink_show_manwidgets" /></p>
<input type="hidden" id="admlink_submit" name="admlink_submit" value="1" />

<?php
  }

  if (function_exists('register_sidebar_widget')) {
    register_sidebar_widget('Admin Links Plus', 'widget_adminlinksplus');
    register_widget_control('Admin Links Plus', 'widget_adminlinksplus_control', 300, 300);
  }
}

// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_adminlinksplus_init');

?>
