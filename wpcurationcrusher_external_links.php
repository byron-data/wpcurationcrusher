<div class="wrap">
<?php
	if (!empty($_POST)) {
		if (!empty($_POST['wpcurationcrusher_new_EL_btn'])) {
			if (!empty($_POST['wpcurationcrusher_external_links_key']) && !empty($_POST['wpcurationcrusher_external_links_link'])) {
				$found = false;
				$wpcurationcrusher_external_links = get_option('wpcurationcrusher_external_links');
				if (!empty($wpcurationcrusher_external_links)) {
					foreach ($wpcurationcrusher_external_links as $i => $value) {
						if ($wpcurationcrusher_external_links[$i] == $_POST['wpcurationcrusher_external_links_key'].' = '.$_POST['wpcurationcrusher_external_links_link']) {
							$found = true;
							break;
						}
					}
				}
				if (!$found) {
					$wpcurationcrusher_external_links[] = $_POST['wpcurationcrusher_external_links_key'].' = '.$_POST['wpcurationcrusher_external_links_link'];
					update_option('wpcurationcrusher_external_links', $wpcurationcrusher_external_links );
				}
			}
		}

		if (!empty($_POST['wpcurationcrusher_delete_EL_btn'])) {
			if (!empty($_POST['wpcurationcrusher_external_links_listbox'])) {
				$wpcurationcrusher_external_links = get_option('wpcurationcrusher_external_links');
				foreach ($wpcurationcrusher_external_links as $i => $value) {
					if ($wpcurationcrusher_external_links[$i] != $_POST['wpcurationcrusher_external_links_listbox']) {
						$wpcurationcrusher_external_links_new[] = $wpcurationcrusher_external_links[$i];
					}
				}
				update_option('wpcurationcrusher_external_links', $wpcurationcrusher_external_links_new );
			}
		}
		
		/*if (!empty($_POST['wpcurationcrusher_modify_EL_btn'])) {
			if (!empty($_POST['wpcurationcrusher_external_links_listbox'])) {
				//echo '<script type="text/javascript">';
				//echo 'window.open("'.'/wp-admin/admin.php?page=wpcurationcrusher_create_content.php"'.')';
				//echo '</script>';
			}
		}*/
	}
	require_once 'wpcurationcrusher_external_links_form.php';
?>
</div>