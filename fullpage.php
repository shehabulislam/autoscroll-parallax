<?php
/*
Plugin Name: Auto Scroll Parallax
Plugin URI: http://www.facebook.com/shehabulislam0
Author: Shehabul Islam Raju
Author URI: http://www.facebook.com/shehabulislam0
Version: 1.0.0
Description: Full Page Parrallax Website Helper
Text Domain: fullpage-parallax


*/

class RjFullpage{

	public function __construct(){
		add_action("plugin_loaded", array($this, "rj_fullpage_load_text_domain"));
		add_action("wp_enqueue_scripts", array($this, 'rj_fullpage_assets'));
		add_action("admin_enqueue_scripts", array($this, "rj_fullpage_admin_assets"));
		add_action("wp_head", array($this, "rj_fullpage_add_style"));
		add_action("wp_footer", array($this, "rj_fullpage_add_script_in_footer"),9999);
		add_action("admin_head", array($this, "rj_fullpage_add_script"));
		add_action("admin_menu", array($this, "rj_fullpage_add_menu"));
	}

	public function rj_fullpage_load_text_domain(){
		load_plugin_textdomain("fullpage-parallax", false, plugin_dir_path(__FILE__)."/languages");
	}

	public function rj_fullpage_assets(){
		wp_enqueue_style("rj-fullpage", plugin_dir_url(__FILE__).'assets/css/fullpage.css');
		wp_enqueue_style("rj-fullpage-style", plugin_dir_url(__FILE__).'assets/css/style.css');
		


		wp_enqueue_script("rj-fullpage", plugin_dir_url(__FILE__).'assets/js/fullpage.js','','', true);
		wp_enqueue_script("rj-fullpage-script", plugin_dir_url(__FILE__).'assets/js/main.js',array('jquery'),time(), true);

		//send data to main.js file
		$autoScrolling = get_option('rj_fullpage_autoscrolling');
		$colors =  get_option("rj_fullpage_section_color_add");
		$menus =  get_option("rj_fullpage_menu_add");

		$data = [
			"autoScrolling" => $autoScrolling,
			"sectionColors" => $colors,
			"menu"			=> str_replace(' ', '', $menus)
		];
		wp_localize_script('rj-fullpage-script', 'data', $data);

	}

	public function rj_fullpage_admin_assets($hook){
	
		if($hook == 'settings_page_fullpage-parallax'){
			wp_enqueue_style("rj_fullpage_section_color_add", plugin_dir_url(__FILE__)."assets/admin/style.css");
			//wp_enqueue_style("rj_fullpage_section_color_add", plugin_dir_url(__FILE__)."assets/admin/color-picker/colorPick.min.css");

			wp_enqueue_script("rj_fullpage-tiny-color-picker", plugin_dir_url(__FILE__)."assets/admin/color-picker/jqColorPicker.min.js", array('jquery'), '', true);
			wp_enqueue_script("rj_fullpage-date-duplicator", plugin_dir_url(__FILE__)."assets/admin/jquery.duplicate.min.js", array('jquery'), '', true);
			wp_enqueue_script("rj_fullpage-add-more", plugin_dir_url(__FILE__)."assets/admin/add-more.js", array('jquery'), '', true);
			//wp_enqueue_script("rj_fullpage-tiny-colors-js", plugin_dir_url(__FILE__)."assets/admin/color-picker/colors.js", array('jquery'), '', true);

			//wp_enqueue_script("rj_fullpage-section-color-add", plugin_dir_url(__FILE__)."assets/admin/section-color-add/section-color-add.js", array('jquery'), '', true);

			wp_enqueue_script("rj_fullpage-color-picker-main", plugin_dir_url(__FILE__)."assets/admin/color-picker/main.js", array('jquery', 'rj_fullpage-tiny-color-picker'), '', true);

			
		}
	}

	public function rj_fullpage_add_style(){
		$menu_visibility = get_option("rj_fullpage_menu_visibility");
		if($menu_visibility != 'none'){

		}
		$menu_style = get_option("rj_fullpage_menu_style");
		?>
		<style type="text/css">

		#menus {
			<?php echo $menu_style['position'] ?>: 50px;
		}
		#menus li a {
			font-size: <?php echo $menu_style['font-size'] ?>;
			color: <?php echo $menu_style['color'] ?>;
			font-weight: <?php echo $menu_style['font-style'] ?>;
			background: <?php echo $menu_style['background-color'] ?>;
			border-radius: <?php echo $menu_style['border-radius'] ?>;
			padding-right: <?php echo $menu_style['padding'] ?>;
			padding-left: <?php echo $menu_style['padding'] ?>;
		}
		#menus li a:hover {
			color: <?php echo $menu_style['hover'] ?>;
			background: <?php echo $menu_style['hover-background'] ?>;
		}
		</style>
		<?php
	}

	public function rj_fullpage_add_script_in_footer(){
		$menu_visibility = get_option("rj_fullpage_menu_visibility");
		$menu_visibility = !empty($menu_visibility)?$menu_visibility:'everypage';
		$menus = get_option("rj_fullpage_menu_add");
		$menus = $menus?$menus:array();
		if($menu_visibility != "none"){
			if($menu_visibility == 'home' && is_front_page()){
				echo '<ul id="menus">';
				foreach($menus as $menu){
					$anchor = str_replace(" ", "", $menu);
					printf("<li data-menuanchor='%s'><a href='#%s'>%s</a></li>",$anchor,$anchor,$menu);
				}
				 	
				echo "</ul>";
			} else if($menu_visibility == 'home' && !is_home()){
				return false;
			}else {
				echo '<ul id="menus">';
				foreach($menus as $menu){
					$anchor = str_replace(" ", "", $menu);
					printf("<li data-menuanchor='%s'><a href='#%s'>%s</a></li>",$anchor,$anchor,$menu);
				}
				 	
				echo "</ul>";
			}
			
		}


	}

	public function rj_fullpage_add_script($hook){

	}

	public function rj_fullpage_add_menu(){
		add_options_page(__("Fullpage Settings", "fullpage-parallax"), __("FullPage Settings", "fullpage-parallax"), 'manage_options', 'fullpage-parallax', array($this, 'rj_fullpage_menu_callback'));
	}

	public function rj_fullpage_menu_callback(){
		?>
		<h1>Fullpage Settings</h1>
		<form action="options.php" method="post">
			
			<?php settings_fields('rj_fullpage_section'); ?>
			<?php //settings_fields('rj_fullpage_color_section'); ?>
			<?php do_settings_sections('fullpage-parallax'); ?>
			<?php submit_button(); ?>
		</form>
		<?php
	}
	





}
new RjFullpage();


// Create Field
function rj_fullpage_create_field(){
	// Sections
	add_settings_section("rj_fullpage_section", __("<span style='color:#ff0000;'>Wrap Every Section by 'section' Class</span>", "fullpage-parallax"), "rj_fullpage_section_callback", "fullpage-parallax");
	//add_settings_field("rj_fullpage_section_color", __("Section Color", "fullpage-parallax"), "rj_fullpage_sectioncolor_callback", "fullpage-parallax", "rj_fullpage_color_section" );
	add_settings_field("rj_fullpage_autoscrolling", "Auto Scrolling", "rj_fullpage_autoscrolling_callback", "fullpage-parallax", "rj_fullpage_section");
	register_setting('rj_fullpage_section', 'rj_fullpage_autoscrolling', array("sanitize_callback" => 'esc_attr'));

	add_settings_field("rj_fullpage_section_color_add", __("Section Color Add", "fullpage-parallax"), "rj_fullpage_sectioncolor_add_callback", "fullpage-parallax", "rj_fullpage_section" );
	register_setting("rj_fullpage_section", "rj_fullpage_section_color_add");

	add_settings_field("rj_fullpage_menu_add", __("Menu Add", "fullpage-parallax"), "rj_fullpage_menu_add_callback", "fullpage-parallax", "rj_fullpage_section" );
	register_setting("rj_fullpage_section", "rj_fullpage_menu_add");


	add_settings_field("rj_fullpage_menu_visibility", __("Menu Visibility", "fullpage-parallax"), "rj_fullpage_menu_visibility_callback", "fullpage-parallax", "rj_fullpage_section" );
	register_setting("rj_fullpage_section", "rj_fullpage_menu_visibility");

	add_settings_field("rj_fullpage_menu_style", __("Menu Style", "fullpage-parallax"), "rj_fullpage_menu_style_callback", "fullpage-parallax", "rj_fullpage_section" );
	register_setting("rj_fullpage_section", "rj_fullpage_menu_style");

	// Register Settings
	
	//register_setting("rj_fullpage_color_section", "rj_fullpage_section_color", array("sanitize_callback" => 'esc_attr'));

	
}
//fullpage section callback
function rj_fullpage_section_callback(){}
function rj_fullpage_color_section_callback(){}

function rj_fullpage_menu_visibility_callback(){
	$active_option = get_option('rj_fullpage_menu_visibility');
	$options = array("Home", "Every Page", "None");
	printf("<select name='%s'>", 'rj_fullpage_menu_visibility');
	
	foreach($options as $option){
		$selected = '';
		$value = strtolower(str_replace(" ", "", $option));
		if($value == $active_option){
			$selected = "selected";
		}
		printf("<option value='%s' %s>%s</option>", $value, $selected, $option);

	}
	printf("</select>");
}

function rj_fullpage_menu_style_callback(){
	$menu_style = get_option("rj_fullpage_menu_style");
	$menu_style = is_array($menu_style)?$menu_style:array();
	
	// menu position
	echo "<table class='menu-style'>";
	echo "<tr>";
	$positions = ["left", "right"];
	echo "<td><h3 class='menu-style'>Menu Position</h3></td>";
	printf("<td><select name='%s'>", 'rj_fullpage_menu_style[position]');
	foreach($positions as $position){
		$selected = '';
		if(in_array($position, $menu_style)){
			$selected = "selected";
		}
		echo $selected;
		printf("<option value='%s' %s>%s</option>",$position, $selected, $position);
	}
	printf("</select></td></tr> <br />");


	//menu color
	echo "<tr><td><h3 class='menu-style'>Menu Text Color</h3></td>";
	printf("<td><input type='text' class='color-picker' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[color]',$menu_style["color"]);
	
	//menu Background color
	echo "<tr><td><h3 class='menu-style'>Menu Background Color</h3></td>";
	printf("<td><input type='text' class='color-picker' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[background-color]',$menu_style["background-color"]);
	

	//Border Radius
	echo "<tr><td><h3 class='menu-style'>Border Radius</h3></td>";
	printf("<td><input type='text' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[border-radius]',$menu_style["border-radius"]);
	

	//menu Hover color
	echo "<tr><td><h3 class='menu-style'>Menu Hover Color</h3></td>";
	printf("<td><input type='text' class='color-picker' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[hover]',$menu_style["hover"]);
	

	//menu Hover color
	echo "<tr><td><h3 class='menu-style'>Menu Hover Background</h3></td>";
	printf("<td><input type='text' class='color-picker' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[hover-background]',$menu_style["hover-background"]);
	

	//menu font size
	echo "<tr><td><h3 class='menu-style'>Menu font size</h3></td>";
	printf("<td><input type='text' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[font-size]',$menu_style["font-size"]);

	//menu item padding
	echo "<tr><td><h3 class='menu-style'>Menu item padding</h3></td>";
	printf("<td><input type='text' name='%s' value='%s' /></td></tr>",'rj_fullpage_menu_style[padding]',$menu_style["padding"]);
	

	//menu font style
	echo "<tr><td><h3 class='menu-style'>Menu font Style</h3></td>";
	$font_styles = ["normal", "bold"];
	printf("<td><select name='%s'>", 'rj_fullpage_menu_style[font-style]');
	foreach($font_styles as $font_style){
		$selected = '';
		if(in_array($font_style, $menu_style)){
			$selected = "selected";
		}
		printf("<option value='%s' %s>%s</option>",$font_style, $selected, $font_style);

	}
	printf("</select></td></tr> <br />");


	echo "</table>";
?>
	

	<?php
}

function rj_fullpage_menu_add_callback(){
	?>
	<div class="wp-core-ui">
		<input type="text" id="menu-name" placeholder="Name">
		<input type="button" class="add-menu button button-primary" value="Add">
	</div>
	<?php
	$menus = get_option("rj_fullpage_menu_add");	
	$menus = $menus?$menus:array("Home");
	$i = 1;
	echo "<table class='menu-add'>";
	foreach($menus as $menu){
		printf("<tr class='tr'>");
		printf('<td><input type="checkbox" name="menu"></td>');
		//printf("<td><h3> Section ".$i."</h3></td>");
		printf("<td><input type='hidden' name='rj_fullpage_menu_add[]' value='%s' /></td>",$menu);
		printf("<td><h3>%s</h3></td>", $menu);
		printf("</tr>");

		//printf("<label>Section ".$i." </label>");
		//printf(" <input type='text' name='rj_fullpage_section_color_add[]' value='%s' /></br >",$color);

		$i++;
	}
	echo "</table><br />";
	echo '<button type="button" class="delete-menu button button-primary">Delete</button>';
}
// rj_fullpage_sectioncolor_add_callback
function rj_fullpage_sectioncolor_add_callback(){
	?>
	<div class="wp-core-ui">
		<input type="text" id="name" placeholder="Name">
	    <input type="text" id="color" class="color-picker" placeholder="Color">
		<input type="button" class="add-row button button-primary" value="Add">
	</div>
	<?php
	$colors = get_option("rj_fullpage_section_color_add");
	if(empty($colors)){
		$colors = array("#ddd");
	}	
	$i = 1;
	echo "<table class='section-color'>";
	foreach($colors as $color){
		printf("<tr class='tr'>");
		printf('<td><input type="checkbox" name="section"></td>');
		printf("<td><h3> Section ".$i."</h3></td>");
		printf("<td><input type='text' name='rj_fullpage_section_color_add[]' value='%s' /></td>",$color);
		printf("</tr>");

		//printf("<label>Section ".$i." </label>");
		//printf(" <input type='text' name='rj_fullpage_section_color_add[]' value='%s' /></br >",$color);

		$i++;
	}
	echo "</table><br />";
	echo '<button type="button" class="delete-section button button-primary">Delete</button>';
}
// rj_fullpage_sectioncolor_callback
function rj_fullpage_sectioncolor_callback(){
	$color = get_option('rj_fullpage_section_color');
	printf("<input type='text' name='%s' value='%s' />", 'rj_fullpage_section_color', $color);
}
// auto Scrolling true/false callback
function rj_fullpage_autoscrolling_callback(){
	$active_option = get_option('rj_fullpage_autoscrolling');
	$options = array("true", "false");
	printf("<select name='%s'>", 'rj_fullpage_autoscrolling');
	
	foreach($options as $option){
		$selected = '';
		if($option == $active_option){
			$selected = "selected";
		}
		printf("<option value='%s' %s>%s</option>", $option, $selected, $option);

	}
	printf("</select>");
	echo "<br />Autoscrolling will not work in mobile device";
}
add_action("admin_init", "rj_fullpage_create_field");








/**
 * Filter slugs
 * @since 1.1.0
 * @return void
 */
/*function wisdom_filter_tracked_plugins() {
  global $typenow;
  global $wp_query;
    //if ( $typenow == 'tracked-plugin' ) { // Your custom post type slug
      $plugins = array( 'uk-cookie-consent', 'wp-discussion-board', 'discussion-board-pro' ); // Options for the filter select field
      $current_plugin = '';
      if( isset( $_GET['slug'] ) ) {
        $current_plugin = $_GET['slug']; // Check if option has been selected
      } ?>
      <select name="slug" id="slug">
        <option value="all" <?php selected( 'all', $current_plugin ); ?>><?php _e( 'All', 'wisdom-plugin' ); ?></option>
        <?php foreach( $plugins as $key=>$value ) { ?>
          <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $current_plugin ); ?>><?php echo esc_attr( $key ); ?></option>
        <?php } ?>
      </select>
  <?php //}
}
add_action( 'restrict_manage_posts', 'wisdom_filter_tracked_plugins' );*/