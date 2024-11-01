<?php require_once('../../../wp-load.php'); ?>
 <div id="noflash-form">
	<table id="noflash-table" class="form-table">
			<tr>
				<th><label for="noflash-id">Select Slideshow:</label></th>
				<td>
				<select name="noflash-id" id="noflash-id">
					<option value="0">Slideshow ... </option>
					<?php 
							//global $wpdb;
							$wpdb->noflash = $wpdb->prefix.'noflash';
							$slides = $wpdb->get_results("SELECT noflash_id, nf_name FROM $wpdb->noflash WHERE 1=1");
					?>
					<?php foreach($slides as $slide): ?>
					<option value="<?php echo $slide->noflash_id; ?>"><?php echo $slide->nf_name; ?></option>
					<?php endforeach; ?>
				</select>
				</td>
			</tr>
	</table>
	<input type="submit" id="noflash-submit" class="button-primary" value="Embed Slideshow" name="submit" />
</div>
<script type="text/javascript">
jQuery.noConflict();
jQuery(function(){
form = jQuery('#noflash-form');
submit = jQuery('#noflash-submit');
table = jQuery('#noflash-table');
//form.hide();
// handles the click event of the submit button
submit.click(function(){	
	// defines the options and their default values
	// again, this is not the most elegant way to do this
	// but well, this gets the job done nonetheless
	var options = { 
		'id'    : '0'
		};
	var shortcode = '[noflash';
	
	for( var index in options) {
		var value = table.find('#noflash-' + index).val();
			shortcode += ' ' + index + '="' + value + '"';
	}
	
	shortcode += ']';
	
	// inserts the shortcode into the active editor
	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
	// closes Thickbox
	tb_remove();
});
});
</script>
