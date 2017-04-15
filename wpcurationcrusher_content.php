<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

	global $linkInserted;

	function schedulePosts($start_time, $finish_time, $num_posts) {
		$finish_time = strtotime($finish_time);
		$start_time = strtotime($start_time);
		$interval_avg = ($finish_time - $start_time) / $num_posts;

		for ($i = 0; $i < $num_posts; $i++) :
			$this_start = $start_time + ($i * $interval_avg);
			$this_time = $this_start + rand(0, $interval_avg);
			$post_times[] = date("Y-m-d H:i", $this_time);
		endfor;
		return $post_times;
	}
	
	function checkDateTime($date, $which) {
		if (preg_match('/\\A(?:^((\\d{2}(([02468][048])|([13579][26]))[\\-\\/\\s]?((((0?[13578])|(1[02]))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])))))|(\\d{2}(([02468][1235679])|([13579][01345789]))[\\-\\/\\s]?((((0?[13578])|(1[02]))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\\-\\/\\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\\s(((0?[0-9])|(1[0-9])|(2[0-3]))\\:([0-5][0-9])((\\s)|(\\:([0-5][0-9])))?))?$)\\z/', $date)) {
	    	return '';
		} else {
			return ' Invalid '.$which.' time';
		}
	}
	
	function startTime() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_content_start_time'];
		else
			return get_option('wpcurationcrusher_content_start_time');
	}
	
	function finishTime() {
		if (!empty($_POST))
			return $_POST['wpcurationcrusher_content_finish_time'];
		else
			return get_option('wpcurationcrusher_content_finish_time');
	}
	
	function updateOptions() {
		update_option('wpcurationcrusher_content_status', $_POST['wpcurationcrusher_content_status']);
		update_option('wpcurationcrusher_content_type', $_POST['wpcurationcrusher_content_type']);
		update_option('wpcurationcrusher_content_keyword_source', $_POST['wpcurationcrusher_content_keyword_source']);
		update_option('wpcurationcrusher_content_keywords', $_POST['wpcurationcrusher_content_keywords']);
		update_option('wpcurationcrusher_content_category', $_POST['wpcurationcrusher_content_category']);
		update_option('wpcurationcrusher_content_number', $_POST['wpcurationcrusher_content_number']);
		
		if (($startTimeMsg = checkDateTime($_POST['wpcurationcrusher_content_start_time'], 'starting')) == '') {
			update_option('wpcurationcrusher_content_start_time', $_POST['wpcurationcrusher_content_start_time']);
		}
		if (($finishTimeMsg = checkDateTime($_POST['wpcurationcrusher_content_finish_time'], 'finishing')) == '') {
			update_option('wpcurationcrusher_content_finish_time', $_POST['wpcurationcrusher_content_finish_time']);
		}
		return $startTimeMsg.$finishTimeMsg;
	}
	
    require_once 'wpcurationcrusher_Browser.php';

	function uploadAndUnzipFiles() {
		$filename = $_FILES['uploadedfile']['name'];
		$type = $_FILES['uploadedfile']['type'];
		$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/s-compressed');

		foreach($accepted_types as $mime_type) {
			if($mime_type == $type) {
				$okay = true;
				break;
			}
		}
		
		$browser = new Browser();
		$name = explode('.', $filename);
		//if ($browser->getBrowser() == Browser::BROWSER_SAFARI || $browser->getBrowser() == Browser::BROWSER_CHROME) {
			if (!$okay) $okay = strtolower($name[1]) == 'zip' ? true : false;
		//}
		if (!$okay) {
			$fileuploadmsg = "Please choose a zip file!";
		} else {
			$targetfile = dirname(__FILE__)."/uploads";
			if (!file_exists($targetfile)) mkdir($targetfile, 0755);
			$targetfile = $targetfile."/".basename( $_FILES['uploadedfile']['name']);

			if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $targetfile)) {
				$fileuploadmsg = "The file ".basename( $_FILES['uploadedfile']['name'])." has been uploaded";
			} else {
				$fileuploadmsg = "There was an error uploading the file, please try again!";
			}
		}
	
		$zip = new ZipArchive();
		$zipOpen = $zip->open($targetfile);
		if ($zipOpen === true) {
			$extract = dirname(__FILE__)."/extract";
			if (!file_exists($extract)) {
				mkdir($extract, 0755);
			} else {
				$files = scandir($extract);
				foreach ($files as $file) {
					if ($file !== '.' && $file !== '..') {
						unlink($extract."/".$file);
					}
				}
			}
			$zip->extractTo($extract."/");
			$zip->close();
	  
			unlink($targetfile);
		} else {
			$fileuploadmsg = "There was an error extracting the file, please try again!";
		}
		
		return $fileuploadmsg;
	}
	
	function insertLinks($contents, $linkDefinition, $splitter, $addAttributes) {
		$splitEntry = explode($splitter, $linkDefinition);
		$keyword = trim($splitEntry[0]);
		$link = trim($splitEntry[1]);
		
		$match = keywordMatches($contents, $keyword);
		$count = count($match);
		if ($count == 0) {
			$linkInserted = false;
			return $contents;
		}

		$linkInserted = true;
		if ($addAttributes) {
			if (get_option('wpcurationcrusher_new_window')=="1")
				$newWindow = ' target="_blank"';
			if (get_option('wpcurationcrusher_nofollow')=="1")
				$noFollow = ' rel="nofollow"';
		} else {
			$newWindow = ' target="_blank"';
			$noFollow = ' rel="nofollow"';
		}
		$pick = rand(0, ($count-1)); // get a random pick of the keyword
		$firstLetter = substr($contents, $match[$pick], 1);
		if (ctype_upper($firstLetter)) $keyword = $firstLetter.substr($keyword, 1, strlen($keyword)-1);
		$contents = substr_replace($contents, '<a href="'.$link.'"'.$newWindow.$noFollow.'>'.$keyword.'</a>', $match[$pick], strlen($keyword));
		
		return $contents;
	}
	
	function findCloakedLinks($contents, $max, $min) {
		$splitter = '*';
		$max = intval(get_option($max));
		if ($max != 0) {
			$min = intval(get_option($min));
			$numlinks = rand($min, $max);
			if ($numlinks != 0) {
				// Loop through all cloaked posts (at random)
				$args = array( 'orderby'=> 'rand', 'post_type' => 'afflinks' );
				$myposts = get_posts($args);
				foreach ($myposts as $thispost) : setup_postdata($thispost);
					$links[] = $thispost->post_title.$splitter.get_permalink($thispost->ID);
				endforeach;
				if (!empty($links)) {
					foreach ($links as $linkDefinition) {
						$contents = insertLinks($contents, $linkDefinition, $splitter, false);
						if ($linkInserted) {
							$links++;
							if ($links >= $numlinks) break;
						}
					}
				}
			}
		}

		return $contents;
	}

	function findLinks($contents, $max, $min, $links) {
		$max = intval(get_option($max));
		if ($max != 0) {
			$min = intval(get_option($min));
			$numlinks = rand($min, $max);
			if ($numlinks != 0) {
				$links = get_option($links);
				if (!empty($links)) {
					shuffle($links);
					foreach ($links as $linkDefinition) {
						$contents = insertLinks($contents, $linkDefinition, '=', true);
						if ($linkInserted) {
							$links++;
							if ($links >= $numlinks) break;
						}
					}
				}
			}
		}
		
		return $contents;
	}
	
	function getKeywords($content_title) {
		if (get_option('wpcurationcrusher_content_keyword_source')=="manual") {
			$keywords = array($_POST['wpcurationcrusher_content_keywords']);
		} else {
			$array1 = explode(" ", strtolower($content_title));
			$array2 = explode(", ", get_option('wpcurationcrusher_ignore_list'));
			$keywords = array_diff($array1, $array2);
		}

		return $keywords;
	}

	function transformKeywords($contents, $keyword) {
		if (get_option('wpcurationcrusher_bold') == "1" || get_option('wpcurationcrusher_italicize') == "1") {
			$match = keywordMatches($contents, $keyword);
			$count = count($match);
			if ($count == 0) return $contents;
		
			if (get_option('wpcurationcrusher_bold') == "1" && get_option('wpcurationcrusher_italicize') == "1") {
				if (rand(0, 1) == 0) $add = "b"; else $add = "i";
				$both = true;
			} else {
				if (get_option('wpcurationcrusher_bold') == "1") $add = "b"; else $add = "i";
				$both = false;
			}
			
			$pick = rand(0, ($count-1));
			$firstLetter = substr($contents, $match[$pick], 1);
			if (ctype_upper($firstLetter))
				$contents = substr_replace($contents, "<".$add.">".$firstLetter.substr($keyword, 1, strlen($keyword)-1)."</".$add.">", $match[$pick], strlen($keyword));
			else
				$contents = substr_replace($contents, "<".$add.">".$keyword."</".$add.">", $match[$pick], strlen($keyword));
			
			if ($both == true) {
				$match = keywordMatches($contents, $keyword);
				$count = count($match);
				if ($count == 0) return $contents;

				if ($add == "b") $add = "i"; else $add = "b";
				
				$pick = rand(0, ($count-1));
				$firstLetter = substr($contents, $match[$pick], 1);
				if (ctype_upper($firstLetter))
					$contents = substr_replace($contents, "<".$add.">".$firstLetter.substr($keyword, 1, strlen($keyword)-1)."</".$add.">", $match[$pick], strlen($keyword));
				else
					$contents = substr_replace($contents, "<".$add.">".$keyword."</".$add.">", $match[$pick], strlen($keyword));
			}
		}

		return $contents;
	}

	function cleanupContents($contents) {
		$contents = str_replace("`", "'", $contents);

		// Fix up smart quotes “ ” ‘ ’
		$search = array(chr(145), 
						chr(146), 
						chr(147), 
						chr(148), 
						chr(151)); 

		$replace = array("'", 
						 "'", 
						 '"', 
						 '"', 
						 '-'); 

		$contents = str_replace($search, $replace, $contents);
		
		//$contents = htmlspecialchars($contents, ENT_QUOTES);
		return $contents;
	}
	
	function keywordsAndLinking($content_title, $contents, $cat_id, $post_times, $count/*, $_POST*/) {
		$contents = findCloakedLinks($contents, 'wpcurationcrusher_max_cloaked', 'wpcurationcrusher_min_cloaked');
		$contents = findLinks($contents, 'wpcurationcrusher_max_external', 'wpcurationcrusher_min_external', 'wpcurationcrusher_external_links');
		$keywords = getKeywords($content_title);
		foreach ($keywords as $keyword) {
			$contents = transformKeywords($contents, $keyword);
		}
		
		$contents = cleanupContents($contents);

		$args = array(
			'post_category' => array($cat_id), 'post_content' => $contents, 'post_date' => $post_times[$count],
			'post_status' => $_POST['wpcurationcrusher_content_status'], 'post_title' => $content_title,
			'post_type' => $_POST['wpcurationcrusher_content_type'], 'tags_input' => $keywords
		);
		wp_insert_post($args);
		return $contents;
	}

	$plugin = plugin_dir_url( __FILE__ );
	echo '<script src="'.$plugin.'datetimepicker_css.js'.'"></script>';
	echo "<script language=javascript>SetImageFilesPath('".$plugin."')</script>";

	if (!empty($_POST)) {
		if (!empty($_POST['wpcurationcrusher_content_save_btn'])) {
			$timeMsg = updateOptions();
		} else if (!empty($_POST['wpcurationcrusher_content_post_btn'])) {
			$timeMsg = updateOptions();
			if (!empty($_POST['wpcurationcrusher_content_category_new']))
				$cat_id = wp_create_category($_POST['wpcurationcrusher_content_category_new']);
			else {
				if ($_POST['wpcurationcrusher_content_type'] == "page")
					$cat_id = 1; // Just set it to Uncategorized, is not used anyway
				else if (!empty($_POST['wpcurationcrusher_content_category']))
					$cat_id = get_cat_ID($_POST['wpcurationcrusher_content_category']);
				else
					$cat_id = false;
			}
			
			if (!$cat_id) {
				$fileuploadmsg = "You must select a category or enter a valid new one";
			} else if (isset($_POST['wpcurationcrusher_content_number']) && $_POST['wpcurationcrusher_content_number'] == 'multiple' && empty($_FILES['uploadedfile']['name'])) {
				$fileuploadmsg = "You must select a zip file to upload";
			} else if ($startTimeMsg != '' || $finishTimeMsg != '') {
			} else if (isset($_POST['wpcurationcrusher_content_number']) && $_POST['wpcurationcrusher_content_number'] == 'multiple' && !empty($_FILES['uploadedfile']['name'])) {
				$fileuploadmsg = uploadAndUnzipFiles();

				$extract = dirname(__FILE__)."/extract/";
				if (isset($extract)) {
					$num_posts = 0;
					$allow_extensions = array("txt");
					$files = scandir($extract);
					foreach ($files as $file) {
						$content_chunks = explode(".", $file);
						$ext = $content_chunks[count($content_chunks) - 1];
						// only include files with desired extensions
						if ($file !== '.' && $file !== '..' && in_array($ext, $allow_extensions)) $contentFiles[] = $file;
					}
					$count = count($contentFiles);
					if ($count > 0) {
						$post_times = schedulePosts($_POST['wpcurationcrusher_content_start_time'], $_POST['wpcurationcrusher_content_finish_time'], $count);
						shuffle($post_times);
						$fileuploadmsg = $fileuploadmsg." and the content has been posted";
					}

					$count = 0;
					foreach ($contentFiles as $file) {
						$handle = fopen($extract.$file, "r");
						if (!feof($handle))
							$line1 = trim(fgets($handle));
						else
							continue;

						if (strtolower($line1) == strtolower($content_chunks[0]))
							$content_title = $line1;
						else {
							if (strpos($line1, ".") === false)
								$content_title = $line1;
							else {
								$content_title = $content_chunks[0];
								rewind($handle);
							}
						}
						
						$contents = fread($handle, filesize($extract.$file));
						fclose($handle);
						
						$contents = keywordsAndLinking($content_title, $contents, $cat_id, $post_times, $count++);
					}
				}
			} else if (isset($_POST['wpcurationcrusher_content_number']) && $_POST['wpcurationcrusher_content_number'] == 'single') {
				if (!empty($_POST['wpcurationcrusher_content_single'])) {
					$contents = stripslashes($_POST['wpcurationcrusher_content_single']);
					if (preg_match("/<title>(.*)<\/title>/", $contents, $matches)) {
						$titlemsg = '';
						$post_times = schedulePosts($_POST['wpcurationcrusher_content_start_time'], $_POST['wpcurationcrusher_content_finish_time'], 1);

						$content_title = $matches[1];
						$rest = strpos($contents, "</title>") + strlen("</title>");
						$contents = substr($contents, $rest);
						
						$contents = keywordsAndLinking($content_title, $contents, $cat_id, $post_times, 0);
						$fileuploadmsg = "The content has been posted";
					} else {
						$titlemsg = 'No title, define one inside '.htmlspecialchars('<title>My Title</title>').' tags';
					}					
				}
			}
		}
	} else {
		$fileuploadmsg = '';
	}
?>

<div class="wrap">
	<h2>CurationCrusher - Add Content</h2>
	<form enctype="multipart/form-data" name="wpcurationcrusher_content_form" method="post" action="">
		<br><table><tr>
		<td>Select Content Status</td><td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_status" value="publish" <?php if (get_option('wpcurationcrusher_content_status')=="publish") echo checked; ?>><b> &nbsp;Publish</b></td>
		 <td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_status" value="draft" <?php if (get_option('wpcurationcrusher_content_status')=="draft") echo checked; ?>><b> &nbsp;Draft</b></td>
		</tr><td>Select Content Type</td><td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_type" value="post" <?php if (get_option('wpcurationcrusher_content_type')=="post") echo checked; ?>><b> &nbsp;Post</b></td>
		 <td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_type" value="page" <?php if (get_option('wpcurationcrusher_content_type')=="page") echo checked; ?>><b> &nbsp;Page</b></td>
		</tr></table>
		
		<h3>Keywords</h3>
		<table><tr><td>Keywords used</td><td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_keyword_source" value="automatic" 
		 onclick="document.getElementById('wpcurationcrusher_content_keywords').style.display='none';" 
		 <?php if (get_option('wpcurationcrusher_content_keyword_source')=="automatic") echo checked; ?>><b> &nbsp;Automatic</b></td>
		 <td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_keyword_source" value="manual" 
		 onclick="document.getElementById('wpcurationcrusher_content_keywords').style.display='block';"
		 <?php if (get_option('wpcurationcrusher_content_keyword_source')=="manual") echo checked; ?>><b> &nbsp;Manual (separated by commas)</b></td>
		</tr></table><br>
		<div id="wpcurationcrusher_content_keywords" <?php if (get_option('wpcurationcrusher_content_keyword_source')=="automatic") echo 'style="display:none;"' ?>>
		<textarea style="width: 740px; height: 40px;" name="wpcurationcrusher_content_keywords"><?php echo get_option('wpcurationcrusher_content_keywords'); ?></textarea>
		</div>

        <br><table><tr>
		<td><b>Category</b></td>
		<td><select style="width:200px" size="1" name="wpcurationcrusher_content_category">
		<option value=""><?php echo esc_attr(__('Select Category')); ?></option> 
		<?php 
			$categories = get_categories('hide_empty=0'); 
			foreach ($categories as $category) {
				echo '<option value="'.$category->category_nicename.'"';
				if ($_POST['wpcurationcrusher_content_category'] == $category->category_nicename) echo ' selected';
				echo '>'.$category->name.'</option>';
			}
		?>
		</select></td>
		<td><input type="text" style="width:200px" name="wpcurationcrusher_content_category_new"></td>
		<td>Select existing or type in a new category</td>
		</tr></table>

		<h3>Content To Use</h3>
		<table><tr><td>Number of content pieces</td><td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_number" value="single" 
		 onclick="document.getElementById('wpcurationcrusher_content_single').style.display='block'; document.getElementById('wpcurationcrusher_content_zip_file').style.display='none';" 
		 <?php if (get_option('wpcurationcrusher_content_number')=="single") echo checked; ?>><b> &nbsp;Single</b></td>
		 <td>&nbsp;<input type="Radio" name="wpcurationcrusher_content_number" value="multiple" 
		 onclick="document.getElementById('wpcurationcrusher_content_single').style.display='none'; document.getElementById('wpcurationcrusher_content_zip_file').style.display='block';"
		 <?php if (get_option('wpcurationcrusher_content_number')=="multiple") echo checked; ?>><b> &nbsp;Multiple</b></td>
		 <td id="wpcurationcrusher_content_zip_file" <?php if (get_option('wpcurationcrusher_content_number')=="single") echo 'style="display:none;"' ?>>
		 <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
		 &nbsp;&nbsp;Select Zip File <input name="uploadedfile" type="file" />
		 </td>
		</tr></table>
		<?php echo '<font color="red">'.$fileuploadmsg.$titlemsg.'</font><br>'; ?>
		<div id="wpcurationcrusher_content_single" <?php if (get_option('wpcurationcrusher_content_number')=="multiple") echo 'style="display:none;"' ?>>
		<?php echo '<font color="red">Put title on first line using "'.htmlspecialchars('<title>My Title</title>').'", if curating include a link to the source.</font>'; ?>
		<textarea style="width: 740px; height: 300px;" name="wpcurationcrusher_content_single"><?php echo ltrim(stripslashes($_POST['wpcurationcrusher_content_single'])); ?></textarea>
		</div>

		<h3>Scheduling Options (format yyyy-mm-dd 24:mm)</h3>
		<table><tr>
		<td>
			<label for="demo1"><b>Starting Time:</b> </label>
			<input type="Text" name="wpcurationcrusher_content_start_time" value="<?php echo startTime(); ?>" id="demo1" maxlength="16" size="16"/>
			<img src="<?php echo $plugin.'images2/cal.gif';?>" onclick="javascript:NewCssCal('demo1','yyyyMMdd','arrow',true,'24')" style="cursor:pointer"/>
		</td>
		<td>
			<label for="demo2"><b>Finishing Time:</b> </label>
			<input type="Text" name="wpcurationcrusher_content_finish_time" value="<?php echo finishTime(); ?>" id="demo2" maxlength="16" size="16"/>
			<img src="<?php echo $plugin.'images2/cal.gif';?>" onclick="javascript:NewCssCal('demo2','yyyyMMdd','arrow',true,'24')" style="cursor:pointer"/>
			<?php echo '<font color="red">'.$timeMsg.'</font>'; ?>
		</td>
		</tr></table>

		<p class="submit">
		<input type="submit" name="wpcurationcrusher_content_post_btn" value="Post" />
		<input type="submit" name="wpcurationcrusher_content_save_btn" value="Save" />
		</p>
	</form>  
</div>