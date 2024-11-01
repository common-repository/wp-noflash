<?php
/*
Plugin Name: Noflash Slideshow for WordPress
Plugin URI: http://nikos-web-development.netai.net/blog/downloads/
Description: Used to create a customizable rotating image slideshow with noflash jquery plugin anywhere within your WordPress site.
Version: 1.3.2.1
Author: Nikos M.
Author URI: http://nikos-web-development.netai.net
*/

/*
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

if (!defined('ABSPATH')) die('SECURITY');

if (!defined('NOFLASH_HTTP_PATH'))
	define( 'NOFLASH_HTTP_PATH' , WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__) , "" , plugin_basename(__FILE__) ) );
if (!defined('NOFLASH_ABSPATH'))
	define( 'NOFLASH_ABSPATH' , WP_PLUGIN_DIR . '/' . str_replace(basename( __FILE__) , "" , plugin_basename(__FILE__) ) );

// enqueue media upload scripts and styles
function noflash_admin_scripts() 
{ 
	wp_enqueue_script('media-upload'); 
	wp_enqueue_script('thickbox'); 
}  

function noflash_admin_styles() 
{ 
	wp_enqueue_style('thickbox'); 
}  

function noflash_fe_head()
{
	wp_enqueue_script( 'jquery.animation.easing' , NOFLASH_HTTP_PATH . 'js/jquery.animation.easing.js' );
	wp_enqueue_script( 'jquery.noflash.min' , NOFLASH_HTTP_PATH . 'js/jquery.noflash.min.js' );
}

function noflash_frontend_js() {
	wp_register_script( 'jquery.animation.easing', NOFLASH_HTTP_PATH . 'js/jquery.animation.easing.js',array('jquery'),'1.3',1);
	wp_register_script( 'noflash-slideshow', NOFLASH_HTTP_PATH . 'js/jquery.noflash.min.js',array('jquery','jquery.animation.easing'),'1.3',1);
	wp_enqueue_script('jquery.animation.easing'); 
}
add_action('wp_head','noflash_frontend_js');

function noflash_unload_frontend_js() {
 
	wp_dequeue_script('jquery.animation.easing');
}
add_action('wp_footer','noflash_unload_frontend_js');
add_action('admin_print_scripts', 'noflash_admin_scripts'); 
add_action('admin_print_styles', 'noflash_admin_styles');

// Init noflash plugin
function init_noflash() {
	load_plugin_textdomain('wp-noflash', false, 'wp-noflash/languages');
}
add_action('init', 'init_noflash');

// init database tables
global $wpdb;
$wpdb->noflash = $wpdb->prefix.'noflash';

function noflash_create_table()
{
	// do NOT forget this global
	global $wpdb;
 
	// this if statement makes sure that the table doe not exist already
	if($wpdb->get_var("show tables like '".$wpdb->noflash."'") != $wpdb->noflash) 
	{
		$charset_collate = '';
		if($wpdb->supports_collation()) {
			if(!empty($wpdb->charset)) {
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if(!empty($wpdb->collate)) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}
		// Create noflash Table
		$create_table = "CREATE TABLE IF NOT EXISTS ".$wpdb->noflash." (".
								"noflash_id int(10) NOT NULL auto_increment,\n".
								"nf_exlength int(5) NOT NULL default 0,\n".
								"nf_name varchar(200) NOT NULL,\n".
								"nf_posts varchar(100) NOT NULL,\n".
								"nf_color varchar(50) NOT NULL,\n".
								"nf_random tinyint NOT NULL,\n".
								"nf_duration int(5) NOT NULL,\n".
								"nf_delay int(5) NOT NULL,\n".
								"nf_width int(5) NOT NULL,\n".
								"nf_height int(5) NOT NULL,\n".
								"nf_images tinytext NOT NULL,\n".
								"nf_captions mediumtext NOT NULL,\n".
								"nf_alts mediumtext NOT NULL,\n".
								"nf_fx mediumtext NOT NULL,\n".
								"PRIMARY KEY (noflash_id)) $charset_collate;";
	}
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($create_table);
	
	// noflash Options
	// null for now
	// Set 'manage_noflash' Capabilities To Administrator	
	$role = get_role('administrator');
	if(!$role->has_cap('manage_noflash')) {
		$role->add_cap('manage_noflash');
	}
}
add_action('activate_wp-noflash/wp-noflash.php', 'noflash_create_table');

function noflash_admin_init() {
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_buttons', 'noflash_filter_mce_button',5 );
		add_filter( 'mce_external_plugins', 'noflash_filter_mce_plugin',5 );
	}
}
add_action('admin_init','noflash_admin_init');

function noflash_filter_mce_button( $buttons ) {
	array_push( $buttons, 'separator','noflash_button' );
	return $buttons;
}

function noflash_filter_mce_plugin( $plugins ) {
	$plugins['noflash'] = NOFLASH_HTTP_PATH . 'noflash-editor.js';
	return $plugins;
}

function noflash_script() {
	?>
	<script type="text/javascript">
	//<![CDATA[
		userSettings.noflash_plugin_dir = '<?php echo NOFLASH_HTTP_PATH; ?>';
	//]]>
</script> 
	<?php
}
add_action('admin_head','noflash_script');


// Function: No flash Administration Menu
function noflash_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page(__('NoFlash', 'wp-noflash'), __('NoFlash', 'wp-noflash'), 'manage_noflash', 'wp-noflash/noflash-manager.php', '', plugins_url('wp-noflash/noflash.png'));
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('wp-noflash/noflash-manager.php', __('Manage Slideshows', 'wp-noflaah'), __('Manage Slideshows', 'wp-noflash'), 'manage_noflash', 'wp-noflash/noflash-manager.php');
		add_submenu_page('wp-noflash/noflash-manager.php', __('New Slideshow', 'wp-noflash'), __('New Slideshow', 'wp-noflash'), 'manage_noflash', 'wp-noflash/new-slideshow.php');
		add_submenu_page('wp-noflash/noflash-manager.php', __('Uninstall WP-NoFlash', 'wp-noflash'), __('Uninstall WP-NoFlash', 'wp-noflash'), 'manage_noflash', 'wp-noflash/noflash-uninstall.php');
		add_submenu_page('wp-noflash/noflash-manager.php', __('Documentation', 'wp-noflash'), __('Documentation', 'wp-noflash'), 'manage_noflash', 'wp-noflash/noflash-documentation.php');
	}
}
add_action('admin_menu', 'noflash_menu');

function disable_filters_for($hook)
{
	global $wp_filter;
	if (isset($wp_filter[$hook]))
	{
	$wp_filter_back=$wp_filter[$hook];
	$wp_filter[$hook]=array();
	}
	else
	$wp_filter_back=array();
	return($wp_filter_back);
}

function re_enable_filters_for($hook,$back)
{
	global $wp_filter;
	$wp_filter[$hook]=$back;
}

$nf_home=get_option('home');
function nf_fixurl($url)
{
	global $nf_home;
	$url = str_replace("%HOME%", $nf_home, $url);
	return($url);
}

function noflash_slideshow($id,$echo=true)
{
	global $wpdb,$post;
	wp_print_scripts('noflash-slideshow');
	
	$id=intval($id);
	$slide = $wpdb->get_row("SELECT * FROM $wpdb->noflash WHERE noflash_id = $id");
	$output='';
	if ($slide)
	{
		$output.="<!-- NOFLASH SLIDESHOW PLUGIN STARTS HERE -->\n";
		
		// load slideshow specific css if exists
		$output.="<style type='text/css'>";
		if (file_exists(NOFLASH_ABSPATH . 'css/noflash-'.$id.'.css'))
		{	
			//$output.=file_get_contents(NOFLASH_ABSPATH . 'css/noflash-'.$id.'.css');
			$output.='@import url("'.NOFLASH_HTTP_PATH . 'css/noflash-'.$id.'.css");';
		}
		else
			$output.='@import url("'.NOFLASH_HTTP_PATH . 'css/noflash.css");';
		
		$output.="</style>\n";
		
		$nf_exlength=intval($slide->nf_exlength);
		$nf_images=explode("<->",$slide->nf_images);
		$nf_fx=explode("<->",$slide->nf_fx);
		$nf_alts=explode("<->",$slide->nf_alts);
		$nf_captions=explode("<->",$slide->nf_captions);
		if (!empty($slide->nf_posts) && trim($slide->nf_posts)!='')
			$nf_posts=explode(",",trim($slide->nf_posts));
		else
			$nf_posts=array();
		$nf_random=intval($slide->nf_random);
		$nf_w=intval($slide->nf_width);
		$nf_h=intval($slide->nf_height);
		$nf_del=intval($slide->nf_delay);
		$nf_dur=intval($slide->nf_duration);
		$nf_name=$slide->nf_name;
		$nf_color=$slide->nf_color;
		$output.="<div style='position:relative;width:{$nf_w}px;height:{$nf_h}px' id='noflash-slideshow-$id'>\n";
		$cp=count($nf_posts);
		if ($cp>0)
		{
			$back_filters_ex=disable_filters_for('the_excerpt');
			$back_filters_c=disable_filters_for('the_content');
			//$cposts = $wpdb->get_results("SELECT count(*) FROM $wpdb->posts WHERE ID IN ($slide->nf_posts) AND post_status='publish' AND (post_type='post' OR post_type='page')");
			$posts=new WP_Query(array( 'post__in' => $nf_posts ) );
			//$ii=(int)min($posts->found_posts,count($nf_images));
			if ($nf_exlength>0)
			{
				$custom_excerpt_func = create_function('$length', "return $nf_exlength;");
				add_filter( 'excerpt_length', $custom_excerpt_func, 10 );
			}
		}
		//else
			//$ii=count($nf_images);
		$ii=count($nf_images);

		for ($i=0;$i<$ii; $i++)
		{
			$dopost=false;
			if ($cp>0 && $posts->found_posts>0 && $posts->have_posts() && isset($nf_posts[$i]) && $nf_posts[$i]>0)
			{
				$posts->the_post();
				$dopost=true;
			}
			$nf_images[$i]=trim($nf_images[$i]);
			if ($nf_images[$i]!='')
			{
				$nf_images[$i]=nf_fixurl($nf_images[$i]);
				$output.="<div class='$nf_fx[$i]'>\n";
				$nf_alts[$i]=trim($nf_alts[$i]);
				if ($dopost)
				{
					$nf_alts[$i]=get_the_title();
					$output.="<img src='$nf_images[$i]' alt='$nf_alts[$i]' title='$nf_alts[$i]' />\n";
				}
				else
				{
					if ($nf_alts[$i]!='')
						$output.="<img src='$nf_images[$i]' alt='$nf_alts[$i]' title='$nf_alts[$i]' />\n";
					else
						$output.="<img src='$nf_images[$i]' />\n";
				}
				if ($dopost)
				{
					$nf_captions[$i]=get_the_excerpt();//substr(strip_tags($post->post_content),0,150);
					$output.="<span>$nf_captions[$i]</span>\n";
				}
				else
				{
					$nf_captions[$i]=trim($nf_captions[$i]);
					if ($nf_captions[$i]!='')
						$output.="<span>$nf_captions[$i]</span>\n";
				}
				$output.="</div>\n";
			}
		}
		$output.="</div>\n";
		
		//$output.="<script type='text/javascript'>\/\/ <![CDATA[\n";
		$output.="<script type='text/javascript'>\n";
		$output.="jQuery('#noflash-slideshow-$id').noflash({\n";
		$output.="rows:1,\n";
		$output.="columns:1,\n";
		$output.="delay:$nf_del,\n";
		$output.="duration:$nf_dur,\n";
		$output.="caption:true,\n";
		$output.="controls:true,\n";
		$output.="transition:'random',\n";
		$output.="easing:'linear',\n";
		$output.="ordering:'random',\n";
		$output.="randomOrder:".($nf_random==1)?"true":"false".",\n";
		$output.="overlap:0.9,\n";
		$output.="preload:false,\n";
		$output.="preloaderClass:'noflash-preloader',\n";
		$output.="captionClass:'noflash-caption',\n";
		$output.="controlsClass:'noflash-controls',\n";
		$output.="backColor:'$nf_color',\n";
		$output.="width:$nf_w,\n";
		$output.="height:$nf_h\n";
		$output.="});\n";
		//$output.="\/\/ ]]></script>\n";
		$output.="</script>\n";
		
		$output.="<!-- NOFLASH SLIDESHOW PLUGIN ENDS HERE -->\n";
		if ($cp>0 && $nf_exlength>0)
		{
			remove_filter( 'excerpt_length', $custom_excerpt_func, 10 );
		}
		if ($cp>0)
		{
			re_enable_filters_for('the_excerpt',$back_filters_ex);
			re_enable_filters_for('the_content',$back_filters_c);
		}
	}
	if ($echo)
		echo $output;
	else return($output);
}

function noflash_shortcode($atts) {
	extract( shortcode_atts( array(
		'id' => '0'
	), $atts ) );

	$id=intval(trim($id));
	
	if ($id>0)
		return(noflash_slideshow($id,false));
	else
		return '';
}
add_shortcode('noflash', 'noflash_shortcode');
?>