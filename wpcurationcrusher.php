<?php
/**
 * @package wpcurationcrusher
 * @version 0.5
 */
/*
Plugin Name: WPCurationCrusher
Plugin URI: http://www.wpcurationcrusher.com
Description: This plugin allows the user to: 1. Access Google Alert feeds, Pinterest feeds, Youtube videos and Flickr images to facilitate "curating" 2. Automatically insert Cloaked Affiliate Links 3. Auotmatically insert External Links (reference sites) 4. Upload and schedule content 5. Automatic internal "smart" linking 6. Automatic bolding and italicizing of keywords
Author: Byron Stuart
Version: 0.5
Author URI: http://www.wpcurationcrusher.com
*/

add_action('admin_menu', 'wpcurationcrusher_plugin_menu');
add_filter('the_content', 'wpcurationcrusher_content_filter');
add_option('wpcurationcrusher_ignore_list', build_ignore_list());

function wpcurationcrusher_plugin_menu() {
	add_menu_page('CurationCrusher : Settings', 'CurationCrusher', 'manage_options', 'wpcurationcrusher-handle', 'wpcurationcrusher_settings');
	add_submenu_page( 'wpcurationcrusher-handle', 'CurationCrusher : Settings', 'Settings', 'manage_options', 'wpcurationcrusher-handle', 'wpcurationcrusher_settings');
	add_submenu_page( 'wpcurationcrusher-handle', 'CurationCrusher : Content Helper', 'Content Helper', 'manage_options', 'wpcurationcrusher_alerts.php', 'wpcurationcrusher_alerts');
	add_submenu_page( 'wpcurationcrusher-handle', 'CurationCrusher : Page title', 'External Links', 'manage_options', 'wpcurationcrusher_external_links.php', 'wpcurationcrusher_external_links');
	add_submenu_page( 'wpcurationcrusher-handle', 'CurationCrusher : Page title', 'Ignore List', 'manage_options', 'wpcurationcrusher_ignore_list.php', 'wpcurationcrusher_ignore_list');
	add_submenu_page( 'wpcurationcrusher-handle', 'CurationCrusher : Page title', 'Add Content', 'manage_options', 'wpcurationcrusher_content.php', 'wpcurationcrusher_content');

	//add_menu_page('Curation Crusher', 'Curation Crusher', 'manage_options', 'wpcurationcrusher-handle', 'wpcurationcrusher_settings');
	//add_submenu_page( 'wpcurationcrusher-handle', 'Page title', 'Content Helper', 'manage_options', 'wpcurationcrusher_alerts.php', 'wpcurationcrusher_alerts');
	//add_submenu_page( 'wpcurationcrusher-handle', 'Page title', 'External Links', 'manage_options', 'wpcurationcrusher_external_links.php', 'wpcurationcrusher_external_links');
	//add_submenu_page( 'wpcurationcrusher-handle', 'Page title', 'Ignore List', 'manage_options', 'wpcurationcrusher_ignore_list.php', 'wpcurationcrusher_ignore_list');
	//add_submenu_page( 'wpcurationcrusher-handle', 'Page title', 'Add Content', 'manage_options', 'wpcurationcrusher_content.php', 'wpcurationcrusher_content');
}

function wpcurationcrusher_settings() {
	add_option('wpcurationcrusher_curation_smart', "1");
	add_option('wpcurationcrusher_min_internal');
	add_option('wpcurationcrusher_max_internal');
	add_option('wpcurationcrusher_min_cloaked');
	add_option('wpcurationcrusher_max_cloaked');
	//add_option('wpcurationcrusher_cloak_affiliate');
	add_option('wpcurationcrusher_min_external');
	add_option('wpcurationcrusher_max_external');
	add_option('wpcurationcrusher_new_window');
	add_option('wpcurationcrusher_nofollow');
	add_option('wpcurationcrusher_bold');
	add_option('wpcurationcrusher_italicize');

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
    require_once 'wpcurationcrusher_settings.php';
}

function wpcurationcrusher_alerts() {
	add_option('wpcurationcrusher_feeds');
	add_option('wpcurationcrusher_feed_limit');
	add_option('wpcurationcrusher_video_keywords', '');
	add_option('wpcurationcrusher_video_orderby', 'relevance');
	add_option('wpcurationcrusher_video_limit', '10');
	add_option('wpcurationcrusher_image_keywords', '');
	add_option('wpcurationcrusher_keywords_tags', 'any');

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

    require_once 'wpcurationcrusher_alerts.php';
}

function wpcurationcrusher_external_links() {
	add_option('wpcurationcrusher_external_links');

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
    require_once 'wpcurationcrusher_external_links.php';
}

function build_ignore_list() {
	$file = dirname(__FILE__)."/ignore_words.txt";
	if (!file_exists($file)) return array();
	
	$handle = fopen($file, "r");
	if ($handle) {
		if (!feof($handle)) $words = fgets($handle);
		while (!feof($handle)) {
			$words = $words.', '.fgets($handle);
		}
		$words = str_replace(array("\n","\r","\r\n"), '', $words);
	}
	return $words;
}

function wpcurationcrusher_ignore_list() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
    require_once 'wpcurationcrusher_ignore_list.php';
}

function wpcurationcrusher_content() {
	add_option('wpcurationcrusher_content_status', 'publish');
	add_option('wpcurationcrusher_content_type', 'post');
	add_option('wpcurationcrusher_content_keyword_source', 'automatic');
	add_option('wpcurationcrusher_content_keywords');
	add_option('wpcurationcrusher_content_category');
	add_option('wpcurationcrusher_content_number', 'multiple');
	add_option('wpcurationcrusher_content_start_time');
	add_option('wpcurationcrusher_content_finish_time');
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
    require_once 'wpcurationcrusher_content.php';
}

// Check that the keyword is not part of another word
function checkWholeword($contents, $keyword, $pos) {
	$otherChecks = array("<", ">");
	if ($pos == 0) $behindCheck = true; // start of keyword is first character in contents
	else {
		$behindChar = substr($contents, $pos-1, 1);
		$behindCheck = !ctype_alpha($behindChar) && !in_array($behindCheck, $otherChecks); // if behindChar is not in [A-Za-z] return true
	}
	
	if ($pos+strlen($keyword) == strlen($contents)) $aheadCheck = true; // end of keyword is first character in contents
	else {
		$aheadChar = substr($contents, $pos+strlen($keyword), 1);
		$aheadCheck = !ctype_alpha($aheadChar) && !in_array($aheadChar, $otherChecks); // if aheadChar is not in [A-Za-z] return true
	}

	//if ($keyword == 'weight') echo 'behindCheck='.$behindCheck.' aheadCheck='.$aheadCheck.'<br>';
	return ($behindCheck && $aheadCheck);
}

function keywordMatches($contents, $keyword) {
	// Get all images
	$regexp = '/<img\s[^>]*src\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>/siU';
	$numMatches = preg_match_all($regexp, $contents, $matches);
	if ($numMatches) {
		$links = $matches[0];
		$offset = 0;
		foreach ($links as $thisLink) {
			// Search each hyperlink to see if it contains the keyword
			if (($keywordPos = strpos(strtolower($thisLink), strtolower($keyword))) !== false) {
				if (($linkPos = strpos(strtolower($contents), strtolower($thisLink), $offset)) !== false) {
					$keywordLinks[] = $linkPos + $keywordPos;
					$offset = $linkPos + strlen($thisLink);
				}
			}
		}
	}

	// Get all hyperlinks
	$regexp = '/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>'.'(.*)'.'<\/a>/siU';
	$numMatches = preg_match_all($regexp, $contents, $matches);
	if ($numMatches) {
		$links = $matches[0];
		$offset = 0;
		foreach ($links as $thisLink) {
			// Search each hyperlink to see if it contains the keyword
			if (($keywordPos = strpos(strtolower($thisLink), strtolower($keyword))) !== false) {
				if (($linkPos = strpos(strtolower($contents), strtolower($thisLink), $offset)) !== false) {
					$keywordLinks[] = $linkPos + $keywordPos;
					$offset = $linkPos + strlen($thisLink);
				}
			}
		}
	}

	$offset = 0;
	for ($i=0; (($pos = strpos(strtolower($contents), strtolower($keyword), $offset)) !== false); $i++) {
		// If this instance of keyword is not contained in a link add it to match array
		if (!(isset($keywordLinks) && in_array($pos, $keywordLinks))) {
			// Check that the keyword match is not part of another word
			if (checkWholeword($contents, $keyword, $pos)) $match[] = $pos;
		}
		$offset = $pos + strlen($keyword);
	}

	return $match;
}

function wpcurationcrusher_content_filter($contents) {
	if (get_option('wpcurationcrusher_curation_smart') != "1") return $contents;

	$max_internal = intval(get_option('wpcurationcrusher_max_internal'));
	if ($max_internal == 0) return $contents; // nothing to do

	$min_internal = intval(get_option('wpcurationcrusher_min_internal'));
	$numlinks = rand($min_internal, $max_internal);
	if ($numlinks == 0) return $contents; // nothing to do
	
	global $post;
	$post_cats = wp_get_post_categories($post->ID);
	foreach($post_cats as $cat) {
		$cats = $cat.',';
		$category = get_category( $cat );
		//echo $category->name . ' ';
	}

	// Loop through all posts (at random) with the same category(s)
	$args = array( 'category' => $cats, 'orderby'=> 'rand', 'post_type' => 'post' );
	$myposts = get_posts($args);
	foreach ($myposts as $thispost) : setup_postdata($thispost);
		if ($thispost->ID != $post->ID) {
			// Get the tags for each post
			$post_tags = wp_get_post_tags($thispost->ID);
			unset($tags);
			foreach($post_tags as $tag) {
				$tags[] = $tag->name;
				//echo $tag->name.' ';
			}
			if (isset($tags))
				shuffle($tags);
			else
				continue;

			$keyword = $tags[0];

			$match = keywordMatches($contents, $keyword);
			$count = count($match);
			if ($count != 0) {
				// Use a random instance of the tag in the current post that matches
				// and change to a hyperlink pointing back to the other article
				$pick = rand(0, ($count-1)); // get a random pick of the keyword
				$firstLetter = substr($contents, $match[$pick], 1);
				if (ctype_upper($firstLetter)) $keyword = $firstLetter.substr($keyword, 1, strlen($keyword)-1);
				$contents = substr_replace($contents, '<a href="'.get_permalink($thispost->ID).'">'.$keyword.'</a>', $match[$pick], strlen($keyword));
				$links++;
				if ($links >= $numlinks) break;
			}
		}
	endforeach;
	
	return $contents;
}

// Section for affiliate links, these use a custom wordpress post type
add_action( 'init', 'affiliate_links_post_type' );
//add_action( 'save_post', 'save_affiliate_links', 1, 2 );
add_action( 'save_post', 'save_affiliate_links' );
add_action( 'manage_posts_custom_column', 'affiliate_links_custom_column' );
add_filter( 'manage_edit-afflinks_columns', 'affiliate_links_edit_columns' );
add_action( 'template_redirect', 'affiliate_links_template_redirect' );

function affiliate_links_post_type() {		
	$url_trigger = get_option("wpcurationcrusher_url_trigger");
	if ($url_trigger == '') {
		$url_trigger = 'recommends';
	}

	$labels = array( 'name' => __( 'Cloaked Links' ),
					 'singular_name' => __( 'Affiliate Link' ),
					 'add_new_item' => __( 'Add New Affiliate Link' ),
					 'edit_item' => __( 'Edit Affiliate Link' ),
					 'new_item' => __( 'New Affiliate Link' ),
					 'view_item' => __( 'View Affiliate Link' ),
					 'search_items' => __( 'Search Affiliate Links' ),
					 'not_found' => __( 'Affiliate Links not found' ),
					 'not_found_in_trash' => __( 'Affiliate Links not found in trash' ) );

	register_post_type( 'afflinks',
		array( 'labels' => $labels,
			'public' => true,
			//'show_in_menu' => false,
			'menu_position' => 80,/*15 below Links*/
			//'query_var' => true,
			'supports' => array( 'title' ),
			'register_meta_box_cb' => 'add_affiliate_links',
			'rewrite' => array( 'slug' => $url_trigger, 'with_front' => false ) ) );
}
	
function add_affiliate_links() {
	add_meta_box('afflinks', __('Affiliate Link URL', 'afflinks'), 'meta_box', 'afflinks', 'normal', 'high');
}

function meta_box() {
	global $post;

	// Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), '_afflinks_nonce');

	$clicks = isset($post->ID) ? get_post_meta($post->ID, '_afflinks_count', true) : 0;
	printf( '<p><label>The following URL has been clicked on <b>%d</b> times.</label></p>', $clicks);
	printf( '<p><input style="%s" type="text" name="%s" value="%s" /></p>', 'width: 98%;', '_afflinks_redirect', esc_attr(get_post_meta($post->ID, '_afflinks_redirect', true)) );
}

function save_affiliate_links($post_id/*, $post*/) {
	// Verify if this is an auto save routine, if yes our form has not been submitted, so we dont want to do anything.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	// Verify this came from the our screen with proper authorization, because save_post can be triggered at other times.
	if (!wp_verify_nonce($_POST['_afflinks_nonce'], plugin_basename( __FILE__ ))) return;

	// Check permissions
	if (!($_POST['post_type'] == 'afflinks' && current_user_can('manage_links', $post_id))) return;

	$destination = '_afflinks_redirect';
	if (isset($_POST[$destination])) {
		update_post_meta($post_id, $destination, $_POST[$destination]);
	} else {
		delete_post_meta($post_id, $destination);
	}
}

function affiliate_links_custom_column($column) {
	global $post;
	
	switch ($column) {
		case 'permalink' :
			echo make_clickable(get_permalink());
			break;
		case 'destination':
			echo make_clickable(esc_url(get_post_meta($post->ID, '_afflinks_redirect', true)));
			break;
		case 'clicks':
			$clicks = get_post_meta($post->ID, '_afflinks_count', true);
			echo $clicks ? $clicks : 0;
			break;
	}
}
	
function affiliate_links_edit_columns($columns) {
	return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title/Keyword'),
        'permalink' => __('Permalink'),
        'destination' => __('Destination'),
        'clicks' =>__( 'Clicks')
    );
}

function affiliate_links_template_redirect() {
	if (!is_singular('afflinks')) return;
	global $wp_query;
	
	$clicks = isset($wp_query->post->ID) ? get_post_meta($wp_query->post->ID, '_afflinks_count', true) : 0;
	update_post_meta($wp_query->post->ID, '_afflinks_count', ++$clicks);

	$redirect = isset($wp_query->post->ID) ? get_post_meta($wp_query->post->ID, '_afflinks_redirect', true) : '';
	if (empty( $redirect)) {
		wp_redirect(home_url());
		exit;
	} else {
		wp_redirect(esc_url_raw($redirect), 301);
		exit;
	}
}
?>