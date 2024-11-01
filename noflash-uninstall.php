<?php
/*
+-------------------------------------------------------------------+
|																								|
|	WordPress Plugin: NoFlash Slidehow 1.3							|
|	Nikos M.										|
|																								|
|	File Written By:																		|
|	- Nikos M.																|
|	- http://nikos-web-development.netai.net																|
|																								|
|	File Information:																		|
|	- Uninstall WP-Noflash													|
|	- wp-content/plugins/wp-noflash/noflash-uninstall.php	|
|																								|
+-------------------------------------------------------------------+
*/
if (!defined('ABSPATH')) die('SECURITY');


### Check Whether User Can Manage noflash
if(!current_user_can('manage_noflash')) {
	die('Access Denied');
}


### Variables Variables Variables
$base_name = plugin_basename('wp-noflash/noflash-manager.php');
$base_page = 'admin.php?page='.$base_name;
$mode = isset($_GET['mode'])?trim($_GET['mode']):'';
$noflash_tables = array($wpdb->noflash);
//$noflash_settings = array();


### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		//  Uninstall WP-NoFlash
		case __('UNINSTALL WP-NoFlash', 'wp-noflash') :
			if(trim($_POST['uninstall_noflash_yes']) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				foreach($noflash_tables as $table) {
					$wpdb->query("DROP TABLE {$table}");
					echo '<font style="color: green;">';
					printf(__('Table \'%s\' has been deleted.', 'wp-noflash'), "<strong><em>{$table}</em></strong>");
					echo '</font><br />';
				}
				echo '</p>';
				/*echo '<p>';
				foreach($noflash_settings as $setting) {
					$delete_setting = delete_option($setting);
					if($delete_setting) {
						echo '<font color="green">';
						printf(__('Setting Key \'%s\' has been deleted.', 'wp-noflash'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					} else {
						echo '<font color="red">';
						printf(__('Error deleting Setting Key \'%s\'.', 'wp-noflash'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					}
				}
				echo '</p>';*/
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


### Determines Which Mode It Is
switch($mode) {
		//  Deactivating WP-DownloadManager
		case 'end-UNINSTALL':
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=wp-noflash/wp-noflash.php';
			if(function_exists('wp_nonce_url')) { 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_wp-noflash/wp-noflash.php');
			}
			echo '<div class="wrap">';
			echo '<h2>'.__('Uninstall WP-NoFlash', 'wp-noflash').'</h2>';
			echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And WP-NoFlash Will Be Deactivated Automatically.', 'wp-noflash'), $deactivate_url).'</strong></p>';
			echo '</div>';
			break;
	// Main Page
	default:
?>
<!-- Uninstall WP-DownloadManager -->
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<div class="wrap">
	<h2><?php _e('Uninstall WP-NoFlash', 'wp-noflash'); ?></h2>
	<p style="color: red">
		<strong><?php _e('WARNING:', 'wp-noflash'); ?></strong><br />
		<?php _e('Once uninstalled, this cannot be undone. All created slideshows will be deleted. You should use a Database Backup plugin of WordPress to back up all the data first.', 'wp-noflash'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('The following WordPress Options/Tables will be DELETED:', 'wp-noflash'); ?></strong><br />
	</p>
	<table class="widefat">
		<thead>
			<tr>
				<th><!--<?php _e('WordPress Options', 'wp-noflash'); ?>--></th>
				<th><strong><?php _e('WordPress Tables', 'wp-noflash'); ?></th>
			</tr>
		</thead>
		<tr>
			<td valign="top">
				<!--<ol>
				<?php
				/*
					foreach($noflash_settings as $settings) {
						echo '<li>'.$settings.'</li>'."\n";
					}*/
				?>
				</ol>-->
			</td>
			<td valign="top" class="alternate">
				<ol>
				<?php
					foreach($noflash_tables as $tables) {
						echo '<li>'.$tables.'</li>'."\n";
					}
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<p style="text-align: center;">
		<input type="checkbox" name="uninstall_noflash_yes" value="yes" />&nbsp;<?php _e('Yes', 'wp-noflash'); ?><br /><br />
		<input type="submit" name="do" value="<?php _e('UNINSTALL WP-NoFlash', 'wp-noflash'); ?>" class="button" onclick="return confirm('<?php _e('You Are About To Uninstall WP-NoFlash From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'wp-noflash'); ?>')" />
	</p>
</div>
</form>
<?php
} // End switch($mode)
?>
