<h2>CurationCrusher - External Links</h2>

<form name="wpcurationcrusher_external_links_form" method="post" action="<?php echo $PHP_SELF;?>">
	<select style="width:740px; height:400px" size="5" name="wpcurationcrusher_external_links_listbox">
	<?php
		$wpcurationcrusher_external_links = get_option('wpcurationcrusher_external_links');
		if (!empty($wpcurationcrusher_external_links))
			foreach ($wpcurationcrusher_external_links as $i => $value) {echo '<option value="'.$wpcurationcrusher_external_links[$i].'">'.$wpcurationcrusher_external_links[$i].'</option>';}
	?>
	</select>
	<br>
	
	<table><tr>
	<td><b>New keyword </b><input type="text" style="width:160px" name="wpcurationcrusher_external_links_key">
	<b>New link </b><input type="text" style="width:300px" name="wpcurationcrusher_external_links_link"></td>
	<td class="submit"><input type="submit" name="wpcurationcrusher_new_EL_btn" value="New" /></td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<!--<td class="submit"><input type="submit" name="wpcurationcrusher_modify_EL_btn" value="Modify" /></td>-->
	<td class="submit"><input type="submit" name="wpcurationcrusher_delete_EL_btn" value="Delete" onClick="javascript: return confirm('Are you sure you want to delete this?');"/></td>
	</tr></table>
</form>