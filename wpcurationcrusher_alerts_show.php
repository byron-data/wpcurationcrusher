<h2>CurationCrusher - Content Helper</h2>

<form name="wpcurationcrusher_content_alerts_form" method="post" action="<?php echo $PHP_SELF;?>">
	<?php
		if (!empty($_POST['wpcurationcrusher_feeds_listbox'])) {
			$wpcurationcrusher_feeds[] = $_POST['wpcurationcrusher_feeds_listbox'];
			if (get_option('wpcurationcrusher_feed_limit') == "")
				$feed_limit = 0;
			else
				$feed_limit = intval(get_option('wpcurationcrusher_feed_limit'));

			$count = count($wpcurationcrusher_feeds);
			for ($i = 0; $i < $count; $i++) {
				$uri = $wpcurationcrusher_feeds[$i];
				if (strpos($uri, "http://") === false) $uri = "http://".$uri;
				if (strpos($uri, 'pinterest')) {
					echo '<hr /><h3>Pinterest (copy HTML code to use, change height & width as required)</h3><hr />';
					displayRSSpin($uri, $feed_limit);
				} else {
					echo '<hr /><h3>Google alerts (copy HTML to use)</h3>';
					displayRSS($uri, $feed_limit);
				}
			}
		}
	?>

	<?php
		$keywords = strtolower(trim($_POST['wpcurationcrusher_video_keywords']));
		if ($keywords != '') {
			$keywords = strtolower(trim($_POST['wpcurationcrusher_video_keywords']));
			$keywordArray = explode(",", $keywords);
			$keywords = '';
			foreach ($keywordArray as $keyword) {
				if ($keywords != '') $keywords = $keywords.",";
				$keywords = $keywords.str_replace(" ", "-", trim($keyword));
			}
			
			$limit = $_POST['wpcurationcrusher_video_limit'];
			echo '<hr /><h3>Youtube videos (copy embed code to use)</h3>';
			$wpcurationcrusher_images = "https://gdata.youtube.com/feeds/api/videos?q=".$keywords."&max-results=".$limit."&v=2"."&orderby=".$_POST['wpcurationcrusher_video_orderby'];
			displayRSSvideo($wpcurationcrusher_images, 0, $keywords);
		}
	?>

	<?php
		$keywords = strtolower(trim($_POST['wpcurationcrusher_image_keywords']));
		if ($keywords != '') {
			echo '<hr /><h3>Flickr images (copy HTML to use, check usage rights before using, change height & width as required)</h3>';
			// http://www.flickr.com/creativecommons
			// l=4 attribution
			// l=6 attribution and no derivative works
			// l=5 attribution and share alike distribution
			// e.g. http://api.flickr.com/services/feeds/photos_public.gne?tags=keyword1,keyword2&tagmode=any&l=4,5,6
			$keywordArray = explode(",", $keywords);
			$keywords = '';
			foreach ($keywordArray as $keyword) {
				if ($keywords != '') $keywords = $keywords.",";
				$keywords = $keywords.str_replace(" ", "-", trim($keyword));
			}
			$wpcurationcrusher_images = "http://api.flickr.com/services/feeds/photos_public.gne?tags=".$keywords."&tagmode=".$_POST['wpcurationcrusher_keywords_tags']."&l=4";
			displayRSSimg($wpcurationcrusher_images, 0, $keywords);
		}
	?>
</form>