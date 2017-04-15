<?php
	function minInternal() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_min_internal'];
		else
			return get_option('wpcurationcrusher_min_internal');
	}
	
	function maxInternal() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_max_internal'];
		else
			return get_option('wpcurationcrusher_max_internal');
	}
	
	function minCloaked() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_min_cloaked'];
		else
			return get_option('wpcurationcrusher_min_cloaked');
	}
	
	function maxCloaked() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_max_cloaked'];
		else
			return get_option('wpcurationcrusher_max_cloaked');
	}
	
	function minExternal() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_min_external'];
		else
			return get_option('wpcurationcrusher_min_external');
	}
	
	function maxExternal() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_max_external'];
		else
			return get_option('wpcurationcrusher_max_external');
	}
	
	if (!empty($_POST)) {
		if (!empty($_POST['wpcurationcrusher_save_settings_btn'])) {
			if (intval($_POST['wpcurationcrusher_min_internal']) || $_POST['wpcurationcrusher_min_internal'] == '0') {
				update_option('wpcurationcrusher_min_internal', $_POST['wpcurationcrusher_min_internal']);
				$minInternalMsg = '';
			} else
				$minInternalMsg = ' Min internal must be numeric ';
			if (intval($_POST['wpcurationcrusher_max_internal']) || $_POST['wpcurationcrusher_max_internal'] == '0') {
				update_option('wpcurationcrusher_max_internal', $_POST['wpcurationcrusher_max_internal']);
				$maxInternalMsg = '';
			} else
				$maxInternalMsg = ' Max internal must be numeric ';

			if (intval($_POST['wpcurationcrusher_min_cloaked']) || $_POST['wpcurationcrusher_min_cloaked'] == '0') {
				update_option('wpcurationcrusher_min_cloaked', $_POST['wpcurationcrusher_min_cloaked']);
				$minCloakedMsg = '';
			} else
				$minCloakedMsg = ' Min cloaked must be numeric ';
			if (intval($_POST['wpcurationcrusher_max_cloaked']) || $_POST['wpcurationcrusher_max_cloaked'] == '0') {
				update_option('wpcurationcrusher_max_cloaked', $_POST['wpcurationcrusher_max_cloaked']);
				$maxCloakedMsg = '';
			} else
				$maxCloakedMsg = ' Max cloaked must be numeric ';

			if (intval($_POST['wpcurationcrusher_min_external']) || $_POST['wpcurationcrusher_min_external'] == '0') {
				update_option('wpcurationcrusher_min_external', $_POST['wpcurationcrusher_min_external']);
				$minExternalMsg = '';
			} else
				$minExternalMsg = ' Min external must be numeric ';
			if (intval($_POST['wpcurationcrusher_max_external']) || $_POST['wpcurationcrusher_max_external'] == '0') {
				update_option('wpcurationcrusher_max_external', $_POST['wpcurationcrusher_max_external']);
				$maxExternalMsg = '';
			} else
				$maxExternalMsg = ' Max external must be numeric ';
			
			if (isset($_POST['wpcurationcrusher_new_window'])) update_option('wpcurationcrusher_new_window', "1"); else update_option('wpcurationcrusher_new_window', "");
			if (isset($_POST['wpcurationcrusher_nofollow'])) update_option('wpcurationcrusher_nofollow', "1"); else update_option('wpcurationcrusher_nofollow', "");
			//if (isset($_POST['wpcurationcrusher_cloak_affiliate'])) update_option('wpcurationcrusher_cloak_affiliate', "1"); else update_option('wpcurationcrusher_cloak_affiliate', "");

			if (isset($_POST['wpcurationcrusher_curation_smart'])) update_option('wpcurationcrusher_curation_smart', "1"); else update_option('wpcurationcrusher_curation_smart', "");
			if (isset($_POST['wpcurationcrusher_bold'])) update_option('wpcurationcrusher_bold', "1"); else update_option('wpcurationcrusher_bold', "");
			if (isset($_POST['wpcurationcrusher_italicize'])) update_option('wpcurationcrusher_italicize', "1"); else update_option('wpcurationcrusher_italicize', "");
		}
	}
?>

<div class="wrap">
	<h2>CurationCrusher - Settings</h2>

	<form name="wpcurationcrusher_settings_form" method="post" action="<?php echo $PHP_SELF;?>">
		<hr />
		<h3>Linking and SEO</h3>
		<table><tr>
		<td>Smart internal linking on</td><td>&nbsp;<input type="checkbox" name="wpcurationcrusher_curation_smart" <?php if (get_option('wpcurationcrusher_curation_smart')=="1") echo checked; ?>></td>
		</tr><tr>
		<td>Number of internal links per post/page</td><td><b>Min:</b> <input type="text" name="wpcurationcrusher_min_internal" value="<?php echo minInternal(); ?>" size="4"></td>
		 <td><b>Max:</b> <input type="text" name="wpcurationcrusher_max_internal" value="<?php echo maxInternal(); ?>" size="4"></td>
			<td><?php echo '<font color="red">'.$minInternalMsg.$maxInternalMsg.'</font><br>'; ?></td>
		</tr><tr>
		<td>&nbsp;</td>
		</tr><tr>
		<td colspan="4"><b>Note the max links used is limited to how many you've defined (e.g. if you've only setup 2 cloaked links then effective Max is 2)</b></td>
		</tr><tr>
		<td>&nbsp;</td>
		</tr><tr>
			<td><a class="add-new-h2" href="edit.php?post_type=afflinks">Cloaked Links</a>
			    <a class="add-new-h2" href="post-new.php?post_type=afflinks">Add New Cloaked Link</a></td>
		</tr><tr>
		<td>Number of cloaked links per post/page</td><td><b>Min:</b> <input type="text" name="wpcurationcrusher_min_cloaked" value="<?php echo minCloaked(); ?>" size="4"></td>
		 <td><b>Max:</b> <input type="text" name="wpcurationcrusher_max_cloaked" value="<?php echo maxCloaked(); ?>" size="4"></td>
			<td><?php echo '<font color="red">'.$minCloakedMsg.$maxCloakedMsg.'</font><br>'; ?></td>
		</tr><tr>
		<td>&nbsp;</td>
		</tr><tr>
		<td>Number of external links per post/page</td><td><b>Min:</b> <input type="text" name="wpcurationcrusher_min_external" value="<?php echo minExternal(); ?>" size="4"></td>
		 <td><b>Max:</b> <input type="text" name="wpcurationcrusher_max_external" value="<?php echo maxExternal(); ?>" size="4"></td>
			<td><?php echo '<font color="red">'.$minExternalMsg.$maxExternalMsg.'</font><br>'; ?></td>
		</tr><tr>
		<!--<td>&nbsp;</td>-->
		</tr><tr>
		<td>External links</td><td>&nbsp;<input type="checkbox" name="wpcurationcrusher_new_window" <?php if (get_option('wpcurationcrusher_new_window')=="1") echo checked; ?>><b> &nbsp;open in a new window</b></td>
		 <td>&nbsp;<input type="checkbox" name="wpcurationcrusher_nofollow" <?php if (get_option('wpcurationcrusher_nofollow')=="1") echo checked; ?>><b> &nbsp;have nofollow attribute</b></td>
		<!--</tr><tr>
		<td>Cloak affiliate links</td><td>&nbsp;<input type="checkbox" name="wpcurationcrusher_cloak_affiliate" <?php if (get_option('wpcurationcrusher_cloak_affiliate')=="1") echo checked; ?>></td>-->
		</tr><tr>
		<td>&nbsp;</td>
		</tr><tr>
		<td>Bold keywords</td><td>&nbsp;<input type="checkbox" name="wpcurationcrusher_bold" <?php if (get_option('wpcurationcrusher_bold')=="1") echo checked; ?>></td>
		</tr><tr>
		<td>Italicize keywords</td><td>&nbsp;<input type="checkbox" name="wpcurationcrusher_italicize" <?php if (get_option('wpcurationcrusher_italicize')=="1") echo checked; ?>></td>
		</tr></table>
		<br><hr />
		
		<p class="submit">  
		<input type="submit" name="wpcurationcrusher_save_settings_btn" value="Save Settings" />  
		</p>  
	</form>  
</div>