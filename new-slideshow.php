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
|	File Information:																	|
|	- Add New Slideshow																|
|	- wp-content/plugins/wp-noflash/new-slideshow.php		|
|																							|
+----------------------------------------------------------------+
*/

if (!defined('ABSPATH')) die('SECURITY');

### Check Whether User Can Manage Downloads
if(!current_user_can('manage_noflash')) {
	die('Access Denied');
}


// auxiliary functions
require_once(ABSPATH."wp-content/plugins/wp-noflash/functions_noflash.php");

### Variables Variables Variables
$base_name = plugin_basename('wp-noflash/noflash-manager.php');
$base_page = 'admin.php?page='.$base_name;

### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		// Add new Slideshow
		case __('New Slideshow', 'wp-noflash'):
		$nf_posts=trim($_POST['nf_posts']);
		$nf_random=intval($_POST['nf_random']);
		$nf_color=trim($_POST['nf_color']);
		if ($nf_color=='') $nf_color="#000";
		$nf_exlength=trim($_POST['nf_exlength']);
		if ($nf_exlength=='') $nf_exlength=0;
		else $nf_exlength=intval($nf_exlength);
		$nf_name=trim($_POST['nf_name']);
		$nf_width=intval($_POST['nf_width']);
		$nf_height=intval($_POST['nf_height']);
		$nf_del=intval($_POST['nf_global_delay']);
		$nf_dur=intval($_POST['nf_global_duration']);
		$images=(array)$_POST['nf_images'];
		$order=(array)($_POST['nf_order']);
		// sort while maintaining key associations
		asort($order,SORT_NUMERIC);
		$alts=(array)($_POST['nf_alts']);
		$caps=(array)($_POST['nf_captions']);
		
		$foo_fx=(array)$_POST['nf_fx'];
		$foo_ovr=(array)$_POST['nf_overlap'];
		$foo_ease=(array)$_POST['nf_ease'];
		$foo_ord=(array)$_POST['nf_ord'];
		$foo_cols=(array)$_POST['nf_columns'];
		$foo_rows=(array)$_POST['nf_rows'];
		$foo_del=(array)$_POST['nf_delay'];
		$foo_dur=(array)$_POST['nf_duration'];
		$nf_fx_array=array();
		for ($i=0;$i<count($images);$i++)
		{
			$nf_fx_array[]="transition=$foo_fx[$i] easing=$foo_ease[$i] ordering=$foo_ord[$i] delay=$foo_del[$i] duration=$foo_dur[$i] rows=$foo_rows[$i] columns=$foo_cols[$i] overlap=$foo_ovr[$i]";
		}
		$alts2=array();
		$caps2=array();
		$images2=array();
		$nf_fx_array2=array();
		foreach ($order as $key=>$val)
		{
			$alts2[]=$alts[$key];
			$caps2[]=$caps[$key];
			$images2[]=$images[$key];
			$nf_fx_array2[]=$nf_fx_array[$key];
		}
		$nf_alts=implode("<->",$alts2);
		$nf_caps=implode("<->",$caps2);
		$nf_images=implode("<->",$images2);
		$nf_fx=implode("<->",$nf_fx_array2);
		$addslide = $wpdb->query("INSERT INTO $wpdb->noflash VALUES (0, $nf_exlength,'$nf_name', '$nf_posts', '$nf_color', $nf_random, $nf_dur, $nf_del, $nf_width, $nf_height, '$nf_images', '$nf_caps', '$nf_alts', '$nf_fx')");
		if(!$addslide) {
			$text = '<font color="red">'.__('Error In Creating Slideshow', 'wp-noflash').'</font>';
		} else {
			$slide_id = intval($wpdb->insert_id);
			$text = '<font color="green">'.__('Slideshow Created Succesfully', 'wp-noflash').'</font>';
		}
		break;
	}
}
?>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
<!-- Add A Slideshow -->
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
	<div class="wrap">
		<h2><?php _e('Add A Slideshow', 'wp-noflash'); ?></h2>
				<table class="form-table" id="nf-table-general">
					<tr>
						<td colspan=2 valign="middle"><strong><?php _e('Slideshow Name:', 'wp-noflash') ?></strong></td>
						<td colspan=2>
							<!-- Name -->
							<input type="text" size="100" name="nf_name" />
						</td>
					</tr>
					<tr>
						<td colspan=2 valign="middle"><strong><?php _e('Post ids (leave blank if no posts are included):', 'wp-noflash') ?></strong></td>
						<td colspan=2>
							<!-- Posts -->
							<input type="text" size="100" name="nf_posts" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Width (px):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Width -->
							<input type="text" size="50" name="nf_width" />
						</td>
						<td valign="middle"><strong><?php _e('Height (px):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- height -->
							<input type="text" size="50" name="nf_height" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Global Duration (secs):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Global Duration-->
							<input type="text" size="10" name="nf_global_duration" />
						</td>
						<td valign="middle"><strong><?php _e('Global Delay (secs):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Global Delay -->
							<input type="text" size="10" name="nf_global_delay" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Random Ordering:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Random -->
							<?php echo do_random(); ?>
						</td>
						<td valign="middle"><strong><?php _e('Back Color:', 'wp-noflash') ?></strong><br />
							<input type="text" size="10" name="nf_color" value="#000"/>
						</td>
						<td valign="middle"><strong><?php _e('Excerpt length (zero for default):', 'wp-noflash') ?></strong><br />
							<input type="text" size="10" name="nf_exlength" value="0"/>
						</td>
					</tr>
					</table>
					<table class="form-table" id="nf-table-images">
					<tr>
						<td colspan=4>
						<table>
						<tr>
						<td><!-- Order -->
						<strong><?php _e('Order:', 'wp-noflash') ?></strong><br />
							<input type="text" size="5" name="nf_order[]" value="1"/></td>
						<td>
							<!-- Image -->
							<strong><?php _e('Image URL:', 'wp-noflash') ?></strong><br />
							<input type="text" size="100" id="<?php echo "1"; ?>_upload_img" name="nf_images[]" value="" /><br />
							<input id="<?php echo "1"; ?>_upload_button" class="upload-noflash" value="<?php _e('Set/Upload Image','wp-noflash'); ?>" type="button" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Alt/Title:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Alt -->
							<input type="text" size="100" name="nf_alts[]" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Caption:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Caption -->
							<textarea rows="5" name="nf_captions[]"></textarea>
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Fx:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Fx -->
							<table class="fx-table">
							<tr>
								<td><?php _e('Transition:', 'wp-noflash') ?><br /><?php echo do_transition();?></td>
								<td><?php _e('Ordering:', 'wp-noflash') ?><br /><?php echo do_ordering();?></td>
								<td><?php _e('Easing:', 'wp-noflash') ?><br /><?php echo do_easing();?></td>
								<td><?php _e('Duration (secs):', 'wp-noflash') ?><br /><?php echo do_duration();?></td>
								<td><?php _e('Delay (secs):', 'wp-noflash') ?><br /><?php echo do_delay();?></td>
								<td><?php _e('Overlap (0-1)', 'wp-noflash') ?><br /><?php echo do_overlap();?></td>
								<td><?php _e('Rows:', 'wp-noflash') ?><br /><?php echo do_rows();?></td>
								<td><?php _e('Columns:', 'wp-noflash') ?><br /><?php echo do_columns();?></td>
							</tr>
							</table><br /><br />
							<a id="nf_link-1" href="#"><?php _e('Remove Image:', 'wp-noflash') ?></a>
						</td>
					</tr>
					</table>
					</td>
					</tr>
				</table>
				<span style="text-align:center"><input type="submit" name="do" value="<?php _e('New Slideshow', 'wp-noflash'); ?>"  class="button" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'wp-noflash'); ?>" class="button" onclick="javascript:history.go(-1)" />&nbsp;&nbsp;<a href="javascript:newimage();"><?php _e('Add New Image', 'wp-noflash') ?></a></span>
	</div>
</form>
<script type="text/javascript">
// <![CDATA[
var nf_ii=1;
$("a#nf_link-"+nf_ii).click(function(){
removeimage($(this));
});
function appendrows(rows)
{
$('#nf-table-images').append(rows);
$("a#nf_link-"+nf_ii).click(function(){
removeimage($(this));
});
}
function removeimage($what)
{
	$what.closest('table').parent('td').parent('tr').remove();
}
function attachUploadClicks()
{
var uploadid=-1;
jQuery('.upload-noflash').unbind('click').click(function() {
uploadid = parseInt(jQuery(this).attr('id'));
tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
return false;
});
 
window.send_to_editor = function(html) {
var imgurl = jQuery('img',html).attr('src');
jQuery('#'+uploadid+'_upload_img').val(imgurl);
jQuery('#'+uploadid+'_img').attr('src',imgurl);
tb_remove();
}
}
function newimage()
{
nf_ii++;
var rows1='<tr><td colspan=4><table><tr><td valign="middle"><strong><?php _e("Order:", "wp-noflash") ?></strong><br /><input type="text" size="5" name="nf_order[]" value="'+nf_ii+'"/></td><td><strong><?php _e("Image URL:", "wp-noflash") ?></strong><br /><input type="text" size="100" id="'+nf_ii+'_upload_img" name="nf_images[]" value="" /><br /><input id="'+nf_ii+'_upload_button" class="upload-noflash" value="<?php _e('Set/Upload Image','wp-noflash'); ?>" type="button" /></td></tr><tr><td valign="top"><strong><?php _e("Alt/Title:", "wp-noflash") ?></strong></td><td><input type="text" size="100" name="nf_alts[]" /></td></tr><tr><td valign="middle"><strong><?php _e("Caption:", "wp-noflash") ?></strong></td><td><textarea rows="5" name="nf_captions[]"></textarea></td></tr>';
var rows="<tr><td valign='middle'><strong><?php _e('Fx:', 'wp-noflash') ?></strong></td><td><table class='fx-table'><tr><td><?php _e('Transition:', 'wp-noflash') ?><br /><?php echo do_transition();?></td><td><?php _e('Ordering:', 'wp-noflash') ?><br /><?php echo do_ordering();?></td><td><?php _e('Easing:', 'wp-noflash') ?><br /><?php echo do_easing();?></td><td><?php _e('Duration (secs):', 'wp-noflash') ?><br /><?php echo do_duration();?></td><td><?php _e('Delay (secs):', 'wp-noflash') ?><br /><?php echo do_delay();?></td><td><?php _e('Overlap (0-1):', 'wp-noflash') ?><br /><?php echo do_overlap();?></td><td><?php _e('Rows:', 'wp-noflash') ?><br /><?php echo do_rows();?></td><td><?php _e('Columns:', 'wp-noflash') ?><br /><?php echo do_columns();?></td></tr></table><br /><br /><a id='nf_link-"+nf_ii+"' href='#'><?php _e('Remove Image:', 'wp-noflash') ?></a></td></tr></table></td></tr>";
appendrows(rows1+rows);
attachUploadClicks();
}
jQuery(document).ready(attachUploadClicks);
//jQuery('#nf-table-images').tableDnD();
// ]]>
</script>
