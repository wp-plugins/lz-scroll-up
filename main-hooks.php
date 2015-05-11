<?php
/*
Plugin Name: LZ Scroll Up
Plugin URI: http://lumianszone.com/plugins/lz-scroll-up
Description: This plugin will add a Scroll To Up button in your site footer right. Here is very easy to setup for change color option and many more. First install the plugin and let's see.
Author: Nazmul Islam
Author URI: http://nazmulislam.info
Version: 1.0
*/


// jQuery from Wordpress
function lz_scroll_up_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'lz_scroll_up_jquery');



// Some Set-up
define('LZ_SCROLL_UP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );



// Adding Plugin javascript file
wp_enqueue_script('lz-scroll-js', LZ_SCROLL_UP.'js/jquery.scrollUp.js', array('jquery'), '1.0', false);


// Adding menu in wordpress dashboard
function lzscrollup_menu_setting_framwrork() {
	add_options_page('Lz Scroll Up Options', 'LZ Scroll Up', 'manage_options', 'lzscrollup-settings','lzscrollup_default_options_framwrork');

}
add_action('admin_menu', 'lzscrollup_menu_setting_framwrork');

// Default options values
$lzscrollup_default_options = array(
	'background_color' => '#666666',
	'hover_color' => '#424242',
	'border_radius' => '5px',
	'scroll_speed' => '300',
	'scroll_icons_color' => '#ffffff',
	'scroll_icons' => 'fa-angle-up'
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function scrollup_lz_color_pickr_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'lz-script-handle', plugins_url('js/color-pickr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'scrollup_lz_color_pickr_function' );


function lzscrollup_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'lzscrollup_p_options', 'lzscrollup_default_options', 'lzscrollup_validate_options' );
}

add_action( 'admin_init', 'lzscrollup_register_settings' );


// Function to generate options page
function lzscrollup_default_options_framwrork() {
	global $lzscrollup_default_options, $auto_hide_mode;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap">

	
	<h2>LZ Scroll Up Option</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'lzscrollup_default_options', $lzscrollup_default_options ); ?>
	
	<?php settings_fields( 'lzscrollup_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>

	
	<table class="form-table"><!-- Grab a hot cup of coffee, yes we're using tables! -->

		<tr valign="top">
			<th scope="row"><label for="background_color">Scroll Up Button Background</label></th>
			<td>
				<input id="background_color" type="text" name="lzscrollup_default_options[background_color]" value="<?php echo stripslashes($settings['background_color']); ?>" class="lz-color-field" /><p class="description">Choice a Background color for your Scroll Button. You can use here HTML HEX color code.</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="hover_color">Scroll Up Button Hover Color</label></th>
			<td>
				<input id="hover_color" type="text" name="lzscrollup_default_options[hover_color]" value="<?php echo stripslashes($settings['hover_color']); ?>" class="lz-color-field"/><p class="description">Choice a Hover color for your Scroll Button. You can use here HTML HEX color code.</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="border_radius">Scroll Up Button border radius</label></th>
			<td>
				<input id="border_radius" type="text" name="lzscrollup_default_options[border_radius]" value="<?php echo stripslashes($settings['border_radius']); ?>" /><p class="description"> You can adjast here your Scroll Up border radius using px. Like: 5px</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="scroll_speed">Scroll Up Speed</label></th>
			<td>
				<input id="scroll_speed" type="text" name="lzscrollup_default_options[scroll_speed]" value="<?php echo stripslashes($settings['scroll_speed']); ?>" /><p class="description">You can manage your Scroll Up Speed. default Speed: 300</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="scroll_icons">Scroll Up Icons</label></th>
			<td>
				<input id="scroll_icons" type="text" name="lzscrollup_default_options[scroll_icons]" value="<?php echo stripslashes($settings['scroll_icons']); ?>" /><p class="description">You can set your own icon from <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a> </p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="scroll_icons_color">Scroll Up Icons</label></th>
			<td>
				<input id="scroll_icons_color" type="text" name="lzscrollup_default_options[scroll_icons_color]" value="<?php echo stripslashes($settings['scroll_icons_color']); ?>" class="lz-color-field" /><p class="description">You Can Change your Icon Color from here. You also use HTML HEX color code  </p>
			</td>
		</tr>
		
	</table>

	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>
	
	
	</form>

	</div>

	<?php
}
function lzscrollup_validate_options( $input ) {
	global $lzscrollup_default_options, $auto_hide_mode;

	$settings = get_option( 'lzscrollup_default_options', $lzscrollup_default_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['background_color'] = wp_filter_post_kses( $input['background_color'] );
	$input['hover_color'] = wp_filter_post_kses( $input['hover_color'] );
	$input['border_radius'] = wp_filter_post_kses( $input['border_radius'] );
	$input['scroll_speed'] = wp_filter_post_kses( $input['scroll_speed'] );
	$input['scroll_icons'] = wp_filter_post_kses( $input['scroll_icons'] );
	$input['scroll_icons_color'] = wp_filter_post_kses( $input['scroll_icons_color'] );


	return $input;
}

endif;  // EndIf is_admin()



function lz_scroll_up_active(){

?>

<?php global $lzscrollup_default_options; $lzscrollup_settings = get_option( 'lzscrollup_default_options', $lzscrollup_default_options ); ?>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript">
		jQuery(document).ready(function(){

			  jQuery.scrollUp({
				scrollName: 'scrollUp', // Element ID
				topDistance: '300', // Distance from top before showing element (px)
				topSpeed: <?php echo $lzscrollup_settings['scroll_speed']; ?>, // Speed back to top (ms)
				animation: 'fade', // Fade, slide, none
				animationInSpeed: 200, // Animation in speed (ms)
				animationOutSpeed: 200, // Animation out speed (ms)
				scrollText: '<i class="fa <?php echo $lzscrollup_settings['scroll_icons']; ?>"></i>', // Text for element
				activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
			  });
		  
		  }); 
	</script>
	
	<style type="text/css">
		a#scrollUp {
			background-color:<?php echo $lzscrollup_settings['background_color']; ?>;  
			-moz-border-radius: 5px;  
			-webkit-border-radius: 5px;
			border-radius: <?php echo $lzscrollup_settings['border_radius']; ?>; 
			bottom: 15px;  
			padding: 6px 11px;  
			right: 15px;  
			text-align: center
		}
		
		a#scrollUp i {
			color: <?php echo $lzscrollup_settings['scroll_icons_color']; ?>;  
			display: inline-block;  
			font-size: 28px;  
			text-shadow: 0 1px 0 #000
		}
		
		a#scrollUp:focus {
			outline: none; 
		}
		
		a#scrollUp:hover{
			background-color: <?php echo $lzscrollup_settings['hover_color']; ?>
		}
		
	</style>
	
<?php
}
add_action('wp_head', 'lz_scroll_up_active');

?>