<?php
	function feedLimit() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_feed_limit'];
		else
			return get_option('wpcurationcrusher_feed_limit');
	}
?>

<h2>CurationCrusher - Content Helper</h2>

<form name="wpcurationcrusher_content_form" method="post" action="<?php echo $PHP_SELF;?>">
	<hr />
	<h3>Google Alerts (<a href="http://www.google.com/alerts/manage" target="_blank">Manage</a>
	 - Get a <a href="https://accounts.google.com" target="_blank">Google Account</a>) / Pinterest Feeds (<a href="http://pinterest.com" target="_blank">Pinterest</a>)</h3>
	<b>Number of items to display (leave blank for all)</b>
	<input type="text" name="wpcurationcrusher_feed_limit" value="<?php echo feedLimit(); ?>" size="4">
	<?php echo '<font color="red">'.$feedLimitMsg.'</font><br>'; ?><br>

	<select style="width:530px; height:100px" size="5" name="wpcurationcrusher_feeds_listbox">	
	<?php
		$wpcurationcrusher_feeds = get_option('wpcurationcrusher_feeds');
		if (!empty($wpcurationcrusher_feeds))
			foreach ($wpcurationcrusher_feeds as $i => $value) {echo '<option value="'.$wpcurationcrusher_feeds[$i].'">'.$wpcurationcrusher_feeds[$i].'</option>';}
	?>
	</select>
	<br>

	<table><tr>
	<td><b>New feed </b><input type="text" style="width:420px" name="wpcurationcrusher_new_feed"></td>
	<td class="submit"><input type="submit" name="wpcurationcrusher_new_feed_btn" value="New" /></td>
	<td class="submit"><input type="submit" name="wpcurationcrusher_delete_feed_btn" value="Delete" onClick="javascript: return confirm('Are you sure you want to delete this?');"/></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><b>Video keywords (comma separated)</b></td></tr>
	<tr>
	<td><input type="text" style="width:476px" name="wpcurationcrusher_video_keywords" value="<?php echo stripslashes(htmlentities(get_option('wpcurationcrusher_video_keywords'))); ?>" ></td>
	<td><select style="width:108px" size="1" name="wpcurationcrusher_video_orderby">
	<option value=""><?php echo esc_attr(__('Select Orderby')); ?></option> 
	<?php
		echo '<option value="relevance"';if (get_option('wpcurationcrusher_video_orderby') == "relevance") echo ' selected';echo '>relevance</option>';
		echo '<option value="published"';if (get_option('wpcurationcrusher_video_orderby') == "published") echo ' selected';echo '>published</option>';
		echo '<option value="viewCount"';if (get_option('wpcurationcrusher_video_orderby') == "viewCount") echo ' selected';echo '>viewCount</option>';
		echo '<option value="rating"';   if (get_option('wpcurationcrusher_video_orderby') == "rating")    echo ' selected';echo '>rating</option>';
	?>
	</select></td>
	<td><input type="text" style="width:40px" name="wpcurationcrusher_video_limit" value="<?php echo get_option('wpcurationcrusher_video_limit'); ?>" ></td>
	</tr><tr><td>&nbsp;</td></tr>
	<tr><td><b>Image keywords (comma separated)</b></td></tr>
	<tr>
	<td><input type="text" style="width:476px" name="wpcurationcrusher_image_keywords" value="<?php echo stripslashes(htmlentities(get_option('wpcurationcrusher_image_keywords'))); ?>" ></td>
	<td>&nbsp;<input type="Radio" name="wpcurationcrusher_keywords_tags" value="any" onclick=";" 
		<?php if (get_option('wpcurationcrusher_keywords_tags')=="any") echo checked; ?>><b>Any Keywords</b></td>
	<td>&nbsp;<input type="Radio" name="wpcurationcrusher_keywords_tags" value="all" onclick=";"
		<?php if (get_option('wpcurationcrusher_keywords_tags')=="all") echo checked; ?>><b>All Keywords</b></td>
	</tr>
	<tr><td class="submit"><input type="submit" name="wpcurationcrusher_view_feed_btn" value="View Feeds, Videos & Images / Save Values" /></td>
	</tr></table>
</form>