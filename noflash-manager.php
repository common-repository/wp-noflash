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
|	- Manage Your Noflash Slideshowa															|
|	- wp-content/plugins/wp-noflash/noflash-manager.php	|
|																								|
+-------------------------------------------------------------------+
*/
if (!defined('ABSPATH')) die('SECURITY');


### Check Whether User Can Manage Downloads
if(!current_user_can('manage_noflash')) {
	die('Access Denied');
}

// auxiliary functions
require_once(ABSPATH."wp-content/plugins/wp-noflash/functions_noflash.php");
$nf_home=get_option('home');
function nf_fixurl2($url)
{
	global $nf_home;
	$url = str_replace("%HOME%", $nf_home, $url);
	return($url);
}

### Variables Variables Variables
$base_name = plugin_basename('wp-noflash/noflash-manager.php');
$base_page = 'admin.php?page='.$base_name;
$mode = isset($_GET['mode'])?trim($_GET['mode']):'';
$slide_id = isset($_GET['id'])?intval($_GET['id']):'';
$slide_page = isset($_GET['page'])?intval($_GET['page']):1;
$slide_sortby = isset($_GET['by'])?trim($_GET['by']):'id';
$slide_sortby_text = '';
$slide_sortorder = isset($_GET['order'])?trim($_GET['order']):'asc';
$slide_sortorder_text = '';
$slide_perpage = isset($_GET['perpage'])?intval($_GET['perpage']):10;
$slide_sort_url = '';
$slide_search = isset($_GET['search'])?addslashes($_GET['search']):'';
$slide_search_query = '';


### Form Sorting URL
if(!empty($slide_sortby)) {
	$slide_sort_url .= '&amp;by='.$slide_sortby;
}
if(!empty($slide_sortorder)) {
	$slide_sort_url .= '&amp;order='.$slide_sortorder;
}
if(!empty($slide_perpage)) {
	$slide_sort_url .= '&amp;perpage='.$slide_perpage;
}


### Searching
if(!empty($slide_search)) {
	$slide_search_query = "AND (nf_name LIKE('%$slide_search%'))";
	$slide_sort_url .= '&amp;search='.stripslashes($slide_search);
}


### Get Order By
switch($slide_sortby) {
	case 'id':
		$slide_sortby = 'noflash_id';
		$slide_sortby_text = __('SlideShow ID', 'wp-noflash');
		break;
	case 'name':
	default:
		$slide_sortby = 'nf_name';
		$slide_sortby_text = __('Slideshow Name', 'wp-noflash');
		break;
}


### Get Sort Order
switch($slide_sortorder) {
	case 'desc':
		$slide_sortorder = 'DESC';
		$slide_sortorder_text = __('Descending', 'wp-noflash');
		break;
	case 'asc':
	default:
		$slide_sortorder = 'ASC';
		$slide_sortorder_text = __('Ascending', 'wp-noflash');
}


### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		// Edit
		case __('Edit', 'wp-noflash'):
		$nf_posts=trim($_POST['nf_posts']);
		$nf_random=intval($_POST['nf_random']);
		$nf_name=trim($_POST['nf_name']);
		$nf_color=trim($_POST['nf_color']);
		if ($nf_color=='') $nf_color="#000";
		$nf_exlength=trim($_POST['nf_exlength']);
		if ($nf_exlength=='') $nf_exlength=0;
		else $nf_exlength=intval($nf_exlength);
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
		$editfile = $wpdb->query("UPDATE $wpdb->noflash SET nf_color='$nf_color', nf_exlength=$nf_exlength, nf_name = '$nf_name', nf_posts = '$nf_posts', nf_random = $nf_random, nf_duration = $nf_dur, nf_delay = $nf_del, nf_width = $nf_width,  nf_height=$nf_height, nf_images='$nf_images', nf_alts='$nf_alts', nf_captions='$nf_caps', nf_fx='$nf_fx' WHERE noflash_id = $slide_id;");
		if(!$editfile) {
			$text = '<font color="red">'.__('Error In Editing Slideshow', 'wp-noflash').'</font>';
		} else {
			$text = '<font color="green">'.__('Slideshow Edited Successfully', 'wp-noflash').'</font>';
		}
		break;
		// Delete
		case __('Delete', 'wp-noflash');
			$slide_id  = intval($_GET['id']);
			$deleteslideshow = $wpdb->query("DELETE FROM $wpdb->noflash WHERE noflash_id = $slide_id");
			if(!$deletefile) {
				$text .= '<font color="red">'.__('Error In Deleting Slideshow', 'wp-noflash').'</font>';
			} else {
				$text .= '<font color="green">'.__('Slideshow Deleted Successfully', 'wp-noflash').'</font>';
			}
			break;
	}
}

### Determines Which Mode It Is
switch($mode) {
	// Edit A File
	case 'edit':
		$slide = $wpdb->get_row("SELECT * FROM $wpdb->noflash WHERE noflash_id = $slide_id");
		$nf_images=explode("<->",$slide->nf_images);
		$nf_fx=explode("<->",$slide->nf_fx);
		$nf_alts=explode("<->",$slide->nf_alts);
		$nf_captions=explode("<->",$slide->nf_captions);
		$nf_posts=$slide->nf_posts;
		$nf_random=$slide->nf_random;
		$nf_color=$slide->nf_color;
		$nf_exlength=$slide->nf_exlength;
		$nf_w=$slide->nf_width;
		$nf_h=$slide->nf_height;
		$nf_del=$slide->nf_delay;
		$nf_dur=$slide->nf_duration;
		$nf_name=$slide->nf_name;
		
?>
		<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
		<!-- Edit A File -->
		<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__).'&amp;mode=edit&amp;id='.intval($slide->noflash_id)); ?>">
			<input type="hidden" name="slide_id" value="<?php echo intval($slide->noflash_id); ?>" />
			<div class="wrap">
				<h2><?php _e('Edit Slideshow', 'wp-noflash'); ?></h2>
				<table class="form-table" id="nf-table-general">
					<tr>
						<td colspan=2 valign="middle"><strong><?php _e('Slideshow Name:', 'wp-noflash') ?></strong></td>
						<td colspan=2>
							<!-- Name -->
							<input type="text" size="100" name="nf_name" value="<?php echo $nf_name; ?>"/>
						</td>
					</tr>
					<tr>
						<td colspan=2 valign="middle"><strong><?php _e('Post ids:', 'wp-noflash') ?></strong></td>
						<td colspan=2>
							<!-- Posts -->
							<input type="text" size="100" name="nf_posts" value="<?php echo $nf_posts; ?>"/>
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Width (px):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Width -->
							<input type="text" size="10" name="nf_width" value="<?php echo $nf_w; ?>"/>
						</td>
						<td valign="middle"><strong><?php _e('Height (px):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- height -->
							<input type="text" size="10" name="nf_height" value="<?php echo $nf_h; ?>"/>
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Global Duration (secs):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Global Duration-->
							<input type="text" size="10" name="nf_global_duration" value="<?php echo $nf_dur; ?>" />
						</td>
						<td valign="middle"><strong><?php _e('Global Delay (secs):', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Global Delay -->
							<input type="text" size="10" name="nf_global_delay" value="<?php echo $nf_del; ?>" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Random Ordering:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Random -->
							<?php echo do_random($nf_random); ?>
						</td>
						<td valign="middle"><strong><?php _e('Back Color:', 'wp-noflash') ?></strong><br />
							<input type="text" size="10" name="nf_color" value="<?php echo $nf_color; ?>"/>
						</td>
						<td valign="middle"><strong><?php _e('Excerpt length (zero for default):', 'wp-noflash') ?></strong><br />
							<input type="text" size="10" name="nf_exlength" value="<?php echo $nf_exlength; ?>"/>
						</td>
					</tr>
					</table>
					<table class="form-table" id="nf-table-images">
					<?php for ($i=0; $i<count($nf_images); $i++) { ?>
					<tr>
					<td colspan=4>
					<table>
					<tr>
						<td valign="middle"><!-- Order -->
						<strong><?php _e('Order:', 'wp-noflash') ?></strong><br />
							<input type="text" size="5" name="nf_order[]" value="<?php echo $i+1; ?>"/></td>
						<td>
							<!-- Image -->
							<img id="<?php echo $i; ?>_img" src="<?php echo nf_fixurl2($nf_images[$i]); ?>" width="120px"/><br />
							<strong><?php _e('Image URL:', 'wp-noflash') ?></strong><br />
							<input type="text" size="100" id="<?php echo $i; ?>_upload_img" name="nf_images[]" value="<?php echo $nf_images[$i]; ?>" /><br />
							<input id="<?php echo $i; ?>_upload_button" class="upload-noflash" value="<?php _e('Set/Upload Image','wp-noflash'); ?>" type="button" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Alt/Title:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Alt -->
							<input type="text" size="100" name="nf_alts[]" value="<?php echo $nf_alts[$i]; ?>" />
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Caption:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Caption -->
							<textarea rows="5" name="nf_captions[]"><?php echo $nf_captions[$i]; ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="middle"><strong><?php _e('Fx:', 'wp-noflash') ?></strong></td>
						<td>
							<!-- Fx -->
							<table class="fx-table">
							<?php $nf_fxsingle=array(); $nf_array=explode(" ",$nf_fx[$i]);
							foreach ($nf_array as $foo1)
							{
								$foo2=explode("=",$foo1);
								$nf_fxsingle[$foo2[0]]=$foo2[1];
							}
							?>
							<tr>
								<td><?php _e('Transition:', 'wp-noflash') ?><br /><?php echo do_transition($nf_fxsingle['transition']);?></td>
								<td><?php _e('Ordering:', 'wp-noflash') ?><br /><?php echo do_ordering($nf_fxsingle['ordering']);?></td>
								<td><?php _e('Easing:', 'wp-noflash') ?><br /><?php echo do_easing($nf_fxsingle['easing']);?></td>
								<td><?php _e('Duration (secs):', 'wp-noflash') ?><br /><?php echo do_duration($nf_fxsingle['duration']);?></td>
								<td><?php _e('Delay (secs):', 'wp-noflash') ?><br /><?php echo do_delay($nf_fxsingle['delay']);?></td>
								<td><?php _e('Overlap (0-1)', 'wp-noflash') ?><br /><?php echo do_overlap($nf_fxsingle['overlap']);?></td>
								<td><?php _e('Rows:', 'wp-noflash') ?><br /><?php echo do_rows($nf_fxsingle['rows']);?></td>
								<td><?php _e('Columns:', 'wp-noflash') ?><br /><?php echo do_columns($nf_fxsingle['columns']);?></td>
							</tr>
							</table><br /><br />
							<a id="nf_link-<?php echo $i+1; ?>" href="#"><?php _e('Remove Image:', 'wp-noflash') ?></a>
						</td>
					</tr>
					</table>
					</td>
					</tr>
					<?php } ?>
				</table>
					<span style="text-align:center"><input type="submit" name="do" value="<?php _e('Edit', 'wp-noflash'); ?>"  class="button" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'wp-noflash'); ?>" class="button" onclick="javascript:history.go(-1)" />&nbsp;&nbsp;<a href="javascript:newimage();"><?php _e('Add New Image', 'wp-noflash') ?></a></span>
			</div>
		</form>
<script type="text/javascript">
// <![CDATA[
var nf_ii=<?php echo count($nf_images); ?>;
for (var i=1;i<=nf_ii;i++)
{
$("a#nf_link-"+i).click(function(){
removeimage($(this));
});
}
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
// ]]>
</script>
<?php
		break;
	case 'delete':
		$deleteslide = $wpdb->query("DELETE FROM $wpdb->noflash WHERE noflash_id = $slide_id");
		if(!$deleteslide) {
			$text .= '<font color="red">'.sprintf(__('Error In Deleting Slideshow \'%s\'', 'wp-noflash'), $slide_id).'</font>';
		} else {
			$text .= '<font color="green">'.__('Slideshow Deleted Successfully', 'wp-noflash').'</font>';
		}
	
	// Main Page
	default:
		### Get Total Slideshows
		$get_total_slides = $wpdb->get_var("SELECT COUNT(noflash_id) FROM $wpdb->noflash WHERE 1=1 $slide_search_query");
		$total_slides = $wpdb->get_var("SELECT COUNT(noflash_id) FROM $wpdb->noflash WHERE 1=1");

		### Checking $file_page and $offset
		if(empty($slide_page) || $slide_page == 0) { $slide_page = 1; }
		if(empty($offset)) { $offset = 0; }
		if(empty($slide_perpage) || $slide_perpage == 0) { $slide_perpage = 20; }

		### Determin $offset
		$offset = ($slide_page-1) * $slide_perpage;

		### Determine Max Number Of Polls To Display On Page
		if(($offset + $slide_perpage) > $get_total_slides) { 
			$max_on_page = $get_total_slides; 
		} else { 
			$max_on_page = ($offset + $slide_perpage); 
		}

		### Determine Number Of Polls To Display On Page
		if (($offset + 1) > ($get_total_slides)) { 
			$display_on_page = $get_total_slides; 
		} else { 
			$display_on_page = ($offset + 1); 
		}

		### Determing Total Amount Of Pages
		$total_pages = ceil($get_total_slides / $slide_perpage);

		### Get Files		
		$slides = $wpdb->get_results("SELECT noflash_id, nf_name FROM $wpdb->noflash WHERE 1=1 $slide_search_query ORDER BY $slide_sortby $slide_sortorder LIMIT $offset, $slide_perpage");
?>
<script type="text/javascript">
// <![CDATA[
function confirm_delete()
{
	return confirm('<?php _e("This will delete the whole slideshow. Are you sure you want to delete it?","wp-noflash"); ?>');
}
// ]]>
</script>
		<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
		<!-- Manage Slideshows -->
		<div class="wrap">
			<h2><?php _e('Manage Slideshows', 'wp-noflash'); ?></h2>
			<h3><?php _e('Slideshows', 'wp-noflash'); ?></h3>
			<p><?php printf(__('Displaying <strong>%s</strong> To <strong>%s</strong> Of <strong>%s</strong> Slideshows', 'wp-noflash'), number_format_i18n($display_on_page), number_format_i18n($max_on_page), number_format_i18n($get_total_slides)); ?></p>
			<p><?php printf(__('Sorted By <strong>%s</strong> In <strong>%s</strong> Order', 'wp-downloadmanager'), $slide_sortby_text, $slide_sortorder_text); ?></p>
			<table class="widefat">
				<thead>
					<tr>
						<th><?php _e('ID', 'wp-noflash'); ?></th>
						<th><?php _e('Name', 'wp-noflash'); ?></th>
						<th colspan="2"><?php _e('Action', 'wp-noflash'); ?></th>
					</tr>
				</thead>
			<?php
				if($slides) {;
					$i = 0;
					foreach($slides as $slide) {
						$slide_id = intval($slide->noflash_id);
						$slide_name = $slide->nf_name;
						if($i%2 == 0) {
							$style = '';
						}  else {
							$style = ' class="alternate"';
						}
						?>
						<tr <?php echo $style; ?>><td><?php echo $slide_id ?></td><td><?php echo $slide_name ?></td>
						<td style="text-align: center;"><a href="<?php echo $base_page; ?>&amp;mode=edit&amp;id=<?php echo $slide_id; ?>" class="edit"><?php _e('Edit', 'wp-noflash'); ?></a>&nbsp;&nbsp;
						<a href="<?php echo $base_page; ?>&amp;mode=delete&amp;id=<?php echo $slide_id; ?>" class="delete" onclick="return confirm_delete();"><?php _e('Delete', 'wp-noflash'); ?></a></td>						
						</tr>
						<?php
						$i++;		
					}
				} else {
					echo '<tr><td colspan="4" align="center"><strong>'.__('No Slideshows Found', 'wp-noflash').'</strong></td></tr>';
				}
			?>
			</table>
		<!-- <Paging> -->
		<?php
			if($total_pages > 1) {
		?>
		<br />
		<table class="widefat">
			<tr>
				<td align="<?php echo ('rtl' == $text_direction) ? 'right' : 'left'; ?>" width="50%">
					<?php
						if($slide_page > 1 && ((($slide_page*$slide_perpage)-($slide_perpage-1)) <= $get_total_slides)) {
							echo '<strong>&laquo;</strong> <a href="'.$base_page.'&amp;page='.($slide_page-1).$slide_sort_url.'" title="&laquo; '.__('Previous Page', 'wp-noflash').'">'.__('Previous Page', 'wp-noflash').'</a>';
						} else {
							echo '&nbsp;';
						}
					?>
				</td>
				<td align="<?php echo ('rtl' == $text_direction) ? 'left' : 'right'; ?>" width="50%">
					<?php
						if($slide_page >= 1 && ((($slide_page*$slide_perpage)+1) <= $get_total_slides)) {
							echo '<a href="'.$base_page.'&amp;page='.($slide_page+1).$slide_sort_url.'" title="'.__('Next Page', 'wp-noflash').' &raquo;">'.__('Next Page', 'wp-noflash').'</a> <strong>&raquo;</strong>';
						} else {
							echo '&nbsp;';
						}
					?>
				</td>
			</tr>
			<tr class="alternate">
				<td colspan="2" align="center">
					<?php _e('Pages', 'wp-noflash'); ?> (<?php echo number_format_i18n($total_pages); ?>):
					<?php
						if ($slide_page >= 4) {
							echo '<strong><a href="'.$base_page.'&amp;page=1'.$slide_sort_url.'" title="'.__('Go to First Page', 'wp-noflash').'">&laquo; '.__('First', 'wp-noflash').'</a></strong> ... ';
						}
						if($slide_page > 1) {
							echo ' <strong><a href="'.$base_page.'&amp;page='.($slide_page-1).$slide_sort_url.'" title="&laquo; '.__('Go to Page', 'wp-noflash').' '.number_format_i18n($slide_page-1).'">&laquo;</a></strong> ';
						}
						for($i = $slide_page - 2 ; $i  <= $slide_page +2; $i++) {
							if ($i >= 1 && $i <= $total_pages) {
								if($i == $slide_page) {
									echo '<strong>['.number_format_i18n($i).']</strong> ';
								} else {
									echo '<a href="'.$base_page.'&amp;page='.($i).$slide_sort_url.'" title="'.__('Page', 'wp-noflash').' '.number_format_i18n($i).'">'.number_format_i18n($i).'</a> ';
								}
							}
						}
						if($slide_page < $total_pages) {
							echo ' <strong><a href="'.$base_page.'&amp;page='.($slide_page+1).$slide_sort_url.'" title="'.__('Go to Page', 'wp-noflash').' '.number_format_i18n($slide_page+1).' &raquo;">&raquo;</a></strong> ';
						}
						if (($slide_page+2) < $total_pages) {
							echo ' ... <strong><a href="'.$base_page.'&amp;page='.($total_pages).$slide_sort_url.'" title="'.__('Go to Last Page', 'wp-noflash'), 'wp-noflash'.'">'.__('Last', 'wp-noflash').' &raquo;</a></strong>';
						}
					?>
				</td>
			</tr>
		</table>	
		<!-- </Paging> -->
		<?php
			}
		?>
	<br />
	<form action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>" method="get">
		<table class="widefat">
			<tr>
				<th><?php _e('Filter Options: ', 'wp-noflash'); ?></th>
				<td><?php _e('Keywords:', 'wp-noflash'); ?><input type="text" name="search" size="30" maxlength="200" value="<?php echo stripslashes($slide_search); ?>" /></td>
			</tr>
			<tr>
				<th><?php _e('Sort Options:', 'wp-noflash'); ?></th>
				<td>
					<input type="hidden" name="page" value="<?php echo $base_name; ?>" />
					<select name="by" size="1">
						<option value="id"<?php if($slide_sortby == 'noflash_id') { echo ' selected="selected"'; }?>><?php _e('Slideshow ID', 'wp-noflash'); ?></option>
						<option value="name"<?php if($slide_sortby == 'nf_name') { echo ' selected="selected"'; }?>><?php _e('Slideshow Name', 'wp-noflash'); ?></option>
					</select>
					&nbsp;&nbsp;&nbsp;
					<select name="order" size="1">
						<option value="asc"<?php if($slide_sortorder == 'ASC') { echo ' selected="selected"'; }?>><?php _e('Ascending', 'wp-noflash'); ?></option>
						<option value="desc"<?php if($slide_sortorder == 'DESC') { echo ' selected="selected"'; } ?>><?php _e('Descending', 'wp-noflash'); ?></option>
					</select>
					&nbsp;&nbsp;&nbsp;
					<select name="perpage" size="1">
					<?php
						for($k=10; $k <= 100; $k+=10) {
							if($slide_perpage == $k) {
								echo "<option value=\"$k\" selected=\"selected\">".__('Per Page', 'wp-noflash').": ".number_format_i18n($k)."</option>\n";
							} else {
								echo "<option value=\"$k\">".__('Per Page', 'wp-noflash').": ".number_format_i18n($k)."</option>\n";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="<?php _e('Go', 'wp-noflash'); ?>" class="button" /></td>
			</tr>
		</table>
	</form>
</div>
<p>&nbsp;</p>
<?php
} // End switch($mode)
?>
