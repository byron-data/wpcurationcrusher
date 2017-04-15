<?php
	if (!empty($_POST)) {
		if (!empty($_POST['wpcurationcrusher_ignore_list_save_btn'])) {
			update_option('wpcurationcrusher_ignore_list', $_POST['wpcurationcrusher_ignore_list']);
		}
	}
?>

<div class="wrap">
	<h2>CurationCrusher - Ignore List (SEO stop words)</h2>
	<form name="wpcurationcrusher_ignore_list_form" method="post" action="">
		<h3>Ignore List Words (separated by commas)</h3>
		<textarea style="width: 740px; height: 340px;" name="wpcurationcrusher_ignore_list"><?php echo stripslashes(get_option('wpcurationcrusher_ignore_list')); ?></textarea>

		<p class="submit">
		<input type="submit" name="wpcurationcrusher_ignore_list_save_btn" value="Save" />
		</p>
	</form>  
</div>