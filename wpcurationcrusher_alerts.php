<div class="wrap">
<?php
	function displayRSS($uri, $items)
	{
		$content = file_get_contents( $uri );
		$atom = new SimpleXmlElement($content);
		if ($items == 0) $items = count($atom);
		for ($i = 0; $i < $items; $i++) :
			$entry = $atom -> entry[$i];
			if ($entry -> content == "") break;
			
			$link = $entry -> link['href'];
			parse_str($link, $linkPieces);
			$link = $linkPieces['q'];
			$alertcode = htmlspecialchars("<a href='$link' target='_blank' rel='nofollow'>Original story</a>");
			$alertcodecopy = str_replace("'", "<xyz>", $alertcode);
			
			printf( "<li style='list-style:none; border-bottom:1px solid #CCCCCC;'>" );
			printf( "<p><span class='entry-title'><b>Title: </b></span>%s <span class='entry-title'><b>Published: </b></span>%s</p><p><span class='entry-title'><b>Link: </b></span><span class='entry-url'><a href=%s target=_blank>Click here</a></span><span class='entry-title'> <b>Author: </b></span>%s</p><p><span class='entry-title'>Content: </span>%s</p><span onclick='copyToClipboard(&quot;%s&quot;)'><p><b>HTML:</b> %s</p></span></li>",
			$entry -> title, date("d M Y H:i", strtotime($entry -> published)), $link, $entry -> author -> name, strip_tags($entry -> content), $alertcodecopy, $alertcode );
		endfor;
	}

	function displayRSSpin($uri, $items)
	{
		$content = file_get_contents( $uri );
		$atom = new SimpleXmlElement($content);
		if ($items == 0) $items = count($atom->channel->item);
		for ($i = 0; $i < $items; $i++) :
			$entry = $atom->channel->item[$i];
			
			$imagecode = $entry -> description;
			// Get all hyperlinks
			$regexp = '/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>'.'(.*)'.'<\/a>/siU';
			$numMatches = preg_match($regexp, $imagecode, $matches);
			if ($numMatches) {
				$linkback = "http://pinterest.com".$matches[2];
			}
			// Get all images
			$regexp = '/<img\s[^>]*src\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>/siU';
			$numMatches = preg_match($regexp, $imagecode, $matches);
			if ($numMatches) {
				$img = $matches[2];
			}
			//echo htmlspecialchars($linkback).' '.htmlspecialchars($img).'<br><br>';
			$imagecode = htmlspecialchars("<a href='".$linkback."' target='_blank' rel='nofollow'><img src='".$img."' alt='' height='120' width='120'></a>");
			$imagecodecopy = str_replace("'", "<xyz>", $imagecode);
			$imgdisp = "<img src='".$img."'>";

			printf( "<li style='list-style:none; border-bottom:1px solid #CCCCCC;'>" );
			printf( "<p><span class='entry-title'><b>Title: </b></span>%s <span class='entry-title'><b>Published: </b></span>%s <span class='entry-title'><b>Link: </b></span><span class='entry-url'><a href=%s target=_blank>Click here</a></span><span class='entry-title'> <b>Author: </b></span>%s</p><span onclick='copyToClipboard(&quot;%s&quot;)'><p>%s</p><p><b>HTML:</b> %s</p></span></li>",
			$entry -> title, date("d M Y H:i", strtotime($entry -> pubDate)), $entry -> link, $atom->channel->title, $imagecodecopy, $imgdisp, $imagecode );
		endfor;
	}

	function displayRSSvideo($uri, $items) {
		$content = file_get_contents( $uri );
		$atom = new SimpleXmlElement($content);
		echo $atom->title.'<br><hr />';

		// iterate over entries in feed
		foreach ($atom->entry as $entry) {
			/*echo $entry->published.'<br>';
			echo $entry->title.'<br>';
			//echo $entry->content['src'].'<br>';
			echo $entry->link['href'].'<br>';*/			
			
			// get nodes in media: namespace for media information
			$media = $entry->children('http://search.yahoo.com/mrss/'); //$media->group->description;
		  
			// get video player URL
			$attrs = $media->group->player->attributes();
			$watch = $attrs['url'];
			$watch = str_replace("https", "http", $watch);
			$splitEntry = explode("&", $watch);
			$watch = $splitEntry[0];
			$embedcode = "[embed height='240' width='240']".$watch."[/embed]";
			$embedcodecopy = str_replace("'", "<xyz>", $embedcode);

			// get video thumbnail
			$attrs = $media->group->thumbnail[0]->attributes();
			$thumbnail = $attrs['url'];

			// get <yt:duration> node for video length
			$yt = $media->children('http://gdata.youtube.com/schemas/2007');
			$attrs = $yt->duration->attributes();
			$length = $attrs['seconds'];

			if (get_option('wpcurationcrusher_video_orderby') != "published") {
				// get <yt:stats> node for viewer statistics
				$yt = $entry->children('http://gdata.youtube.com/schemas/2007');
				$attrs = $yt->statistics->attributes();
				$viewCount = $attrs['viewCount'];
			}

			printf( "<li style='list-style:none; border-bottom:1px solid #CCCCCC;'>" );
			printf( "<p><b>Title:</b> %s <b>Author:</b> %s <b>Length (mins):</b> %0.2f <b>Views:</b> %s</p>", $media->group->title, $entry->author->name, $length/60, $viewCount );
			printf( "<p><a href='%s' target='_blank'><img src='%s'></a></p><span onclick='copyToClipboard(&quot;%s&quot;)'><p><b>embed code:</b> %s</p></span></li>", $watch, $thumbnail, $embedcodecopy, $embedcode );
		}
	}
	
	function displayRSSimg($uri, $items, $keywords)
	{
		$content = file_get_contents( $uri );
		$atom = new SimpleXmlElement($content);
		if ($items == 0) $items = count($atom);

		for ($i = 0; $i < $items; $i++) :
			$entry = $atom -> entry[$i];
			if ($entry -> content == "") break;
			// Get all images
			$regexp = '/<img\s[^>]*src\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>/siU';
			$imagecode = $entry -> content;
			$numMatches = preg_match($regexp, $imagecode, $matches);
			if ($numMatches) {
				$entry2 = $matches[2];
			}
			//$entry2 = $entry->link[1];

			//$imagecode = htmlspecialchars("<a href='".$entry -> link['href']."' target='_blank' rel='nofollow'><img src='".$entry2['href']."' alt='".$keywords."' height='120' width='120'></a>");
			$imagecode = htmlspecialchars("<a href='".$entry -> link['href']."' target='_blank' rel='nofollow'><img src='".$entry2."' alt='".$keywords."' height='120' width='120'></a>");
			$imagecodecopy = str_replace("'", "<xyz>", $imagecode);
			printf( "<li style='list-style:none; border-bottom:1px solid #CCCCCC;'><p><span class='entry-title'><b>Title: </b></span>%s <span class='entry-title'><b>Published: </b></span>%s</p><p><span class='entry-title'></span><span class='entry-img'><a href=%s target=_blank><img src='%s' height='120'> Click to view original</a></span></p><span onclick='copyToClipboard(&quot;%s&quot;)'><p><b>HTML:</b> %s</p></span></li>",
			//$entry -> title, date( "d M Y H:i", strtotime($entry -> published) ), $entry -> link['href'], $entry2['href'], $imagecodecopy, $imagecode );
			$entry -> title, date( "d M Y H:i", strtotime($entry -> published) ), $entry -> link['href'], $entry2, $imagecodecopy, $imagecode );
		endfor;
	}

	if (empty($_POST)) {
		require_once 'wpcurationcrusher_alerts_feeds.php';
	} else {
		if (empty($_POST['wpcurationcrusher_feed_limit']) || intval($_POST['wpcurationcrusher_feed_limit'])) {
			update_option('wpcurationcrusher_feed_limit', $_POST['wpcurationcrusher_feed_limit']);
			$feedLimitMsg = '';
		} else
			$feedLimitMsg = ' Feed limit must be blank or numeric';

		update_option('wpcurationcrusher_video_keywords', $_POST['wpcurationcrusher_video_keywords']);
		update_option('wpcurationcrusher_video_orderby', $_POST['wpcurationcrusher_video_orderby']);
		if (intval($_POST['wpcurationcrusher_video_limit']) || $_POST['wpcurationcrusher_video_limit'] == '0') {
			update_option('wpcurationcrusher_video_limit', $_POST['wpcurationcrusher_video_limit']);
		}
		update_option('wpcurationcrusher_image_keywords', $_POST['wpcurationcrusher_image_keywords']);
		update_option('wpcurationcrusher_keywords_tags', $_POST['wpcurationcrusher_keywords_tags']);
		if (!empty($_POST['wpcurationcrusher_new_feed_btn'])) {
			if (!empty($_POST['wpcurationcrusher_new_feed'])) {
				$found = false;
				$wpcurationcrusher_feeds = get_option('wpcurationcrusher_feeds');
				if (!empty($wpcurationcrusher_feeds)) {
					foreach ($wpcurationcrusher_feeds as $i => $value) {
						if ($wpcurationcrusher_feeds[$i] == $_POST['wpcurationcrusher_new_feed']) {
							$found = true;
							break;
						}
					}
				}
				if (!$found) {
					$wpcurationcrusher_feeds[] = $_POST['wpcurationcrusher_new_feed'];
					update_option('wpcurationcrusher_feeds', $wpcurationcrusher_feeds);
				}
			}
			require_once 'wpcurationcrusher_alerts_feeds.php';
		}

		if (!empty($_POST['wpcurationcrusher_delete_feed_btn'])) {
			if (!empty($_POST['wpcurationcrusher_feeds_listbox'])) {
				$wpcurationcrusher_feeds = get_option('wpcurationcrusher_feeds');
				foreach ($wpcurationcrusher_feeds as $i => $value) {
					if ($wpcurationcrusher_feeds[$i] != $_POST['wpcurationcrusher_feeds_listbox']) {
						$wpcurationcrusher_feeds_new[] = $wpcurationcrusher_feeds[$i];
					}
				}
				update_option('wpcurationcrusher_feeds', $wpcurationcrusher_feeds_new);
			}
			require_once 'wpcurationcrusher_alerts_feeds.php';
		}
		
		if (!empty($_POST['wpcurationcrusher_view_feed_btn'])) {
			if (!empty($_POST['wpcurationcrusher_feeds_listbox']) || trim($_POST['wpcurationcrusher_video_keywords']) != '' || trim($_POST['wpcurationcrusher_image_keywords']) != '') {
				require_once 'wpcurationcrusher_alerts_show.php';
			} else {
				require_once 'wpcurationcrusher_alerts_feeds.php';
			}
		}
	}
?>
<script type="text/javascript">
function copyToClipboard(text) {
	window.prompt("Copy to clipboard: Ctrl+C then Enter or OK", text.replace(/<xyz>/g, "\'"));
}
</script>
</div>