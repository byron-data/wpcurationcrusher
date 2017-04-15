Installation

Note this plugin requires WordPress version 2.9 or later.

1. To install the plugin log in to your blog and choose Plugins -> Add New
2. Choose Upload and then Browse, select blog-curation.zip
3. Select blog-curation.zip
4. Choose Install Now

Brief Summary

The plugin allows the user to do the following:

 1. Content helper that interfaces to Google Alert content, Pinterest feeds, Youtube videos and Flickr images to facilitate "curating"
 2. Automatically inserts cloaked Affiliate Links (incl tracking statistics)
 3. Automatically inserts External Links (reference sites etc)
 4. Allows uploading and scheduling of content, single (incl curated) or multiple content pieces at a time, drip feed with "natural" posting times or mass upload, automatic or manual keywords
 5. Automatic internal "smart" linking between posts with shared tags
 6. Automatic bolding and italicizing of keywords

Settings

1. Choose Blog Curation from left hand menu
 a. Select the checkbox next to "Smart internal linking on" if you want to use this feature, unselect it to turn it off
 b. The minimum and maximum number of internal, cloaked (affiliate), and external links (authority sites, other sites you own etc)
 c. Whether you want external links to open in a new window and/or have the nofollow attribute
 d. Whether you want to bold or italicize keywords

Content Helper

1. Choose Content Helper from the left hand menu.
2. Enter the Google feed(s) as per www.google.com/alerts/manage by typing in the text box and choosing New. Or enter a Pinterest feed in the text box and choose New. Enter the number of items you want to display per Google or Pinterest feed.
 a. For Pinterest you can view a USER RSS feed by going to the user’s Pinterest URL, and adding /feed.rss to the end of the link.
 Example: http://pinterest.com/username/feed.rss
 View a specific BOARD feed by going to the board URL, replace the final / with .rss
 Example: http://pinterest.com/username/boardname.rss
3. Delete a Google or Pinterest feed by selecting one from the list and choosing Delete.
4. View a selected Google or Pinterest feed by pressing "View Feeds, Videos & Images" (no feed will display if nothing is selected from the list).
5. Enter keyword matches for Youtube videos, you can choose to order by relevance (default), published, viewCount or rating and how many video results (default 10) you want to view.
6. You can view matching Youtube videos by pressing "View Feeds & Images" (no videos will display if you have not typed in any keywords).
7. Enter keyword matches for Flickr images, you can choose to match on any keyword you type in or by all keywords (may display less images).
8. You can view matched images by pressing "View Feeds & Images" (no images will display if you have not typed in any keywords).

Using content from Google Alerts, Pinterest Feeds, Youtube Videos & Flickr Images

To use content from a Google feed item simply click where it says "Click Here" to get the original piece of content. You can then copy and paste text from the original content into WordPress using the Blog Curation "Add Content" page (see the Add Content section below) or you can go straight to the default WordPress add new post or add new page editor. Be sure to insert a link back to the original article.

To use content from a Pinterest feed item copy the HTML code below the image (in its entirety), do this by clicking on the picture or the HTML below it and copying the selected text to the clipboard. Note this requires javascript to be enabled otherwise you will have to manually select the HTML before copying to the clipboard. You can then add that html code using the Blog Curation "Add Content" page or by using the default WordPress add new post or add new page editor. Modify the height and width as you wish.

To use a Youtube video copy the HTML code below the image (in its entirety), do this by clicking on the embed code below the video thumbnail and copying the selected text to the clipboard. Note this requires javascript to be enabled otherwise you will have to manually select the embed code before copying to the clipboard. You can then add that html code using the Blog Curation "Add Content" page or by using the default WordPress add new post or add new page editor. Modify the height and width as you wish. Before embedding videos make sure that you have the WordPress "Auto-embeds" feature enabled, check that the "Auto-embeds" check box is selected in Administration > Settings > Media SubPanel.

To use a Flickr image first you will need to click on the displayed image and check it's license. If it's OK to use then you should copy the HTML code below the image (in its entirety) which includes a link back to the original (as per the Flickr terms of use). Do this by clicking on the HTML below the image and copying the selected text to the clipboard. Note this requires javascript to be enabled otherwise you will have to manually select the HTML before copying to the clipboard.

You can then add that html code using the Blog Curation "Add Content" page or by using the default WordPress add new post or add new page editor. Modify the alt text, height and width as you wish.

Cloaked Links

Cloaked or affiliate links will apear to the reader as if the link is actually on your site. When a cloaked link is selected it will redirect to the destination page, number of clicks are stored for each cloaked link.

1. Enter a cloaked link by choosing Cloaked Links -> Add New from the left hand menu or from the Settings page
2. Enter your keyword where it says Enter title here and your Affiliate Link URL
3. Choose Publish when ready

If you find that the cloaked Links are not redirecting, for example you are getting a  "WP not found error" please see the Troubleshooting section at the bottom of this document.

Ignore List

This set of words are used when the Automatic Keywords option is used on the Content page. You can add new Ignore List Words or remove ones (be sure to keep a comma between each word) and choose Save when you are finished.

Add Content

This page is used to upload content to your blog. To make use of the automatic insertion of affiliate links, automatic insertion of external links, automatic keywords and bolding/italicizing of keywords your content must be added through this page. The automatic internal "smart" linking between posts with shared tags is done regardless of whether the content came through this page or not.

1. You can select your content to be uploaded with a status of "Publish" or "Draft" ("Draft" will not be seen by blog visitors)
2. You can select your content to be uploaded with a type of "Post" or "Page"
3. You can select Automatic keywords or Manual keywords (comma separated)
 a. Automatic keywords will use any words in the content title that are not in the ignore list
 b. Manual keywords will use any words you entered in the text box
4. Select a Category if your content type is "Post" or create a new one by entering it into the text box
5. Select "Single" or "Multiple" content pieces
 a. If "Single" then a textarea will apear to type in your content, if you are doing curated content then this is the place to type your text and get the benefit of the automated links.
    If you want to do any additional editing use the standard WordPress Posts or Pages interface to edit your post.
 b. If "Multiple" then you will be able to select a zip file that contains your content files
6. Enter the Starting Time and Finishing Time for your content, the times used will be randomly spaced between these 2 times giving a natural look
7. Choose the "Post" button to upload the content or "Save" to keep the values on the page

Overview of external linking and SEO

Each individual instance of a keyword can only be assigned to one URL so a priority has been defined for the different link types. Each time a link is assigned to an instance of a keyword that means the word is not available for any other lower priority assignments, the process continues until all links have been assigned or there are no "free" keywords left.

The order of priority for assigning keywords to links, bolding and italicizing is as follows (this is done for every keyword):
1. A random number of affiliate links between the min and max defined are assigned to the keywords
2. A random number of external links between the min and max defined are assigned to the keywords
   For example you can choose authority type sites to link to as the search engines seem to reward websites that actively link to informative and authoritative pages
3. A max of one bold keyword or one italics keyword will be assigned (depending on the settings)

Automatic Internal "Smart" Linking

1. The internal linking is done via a silo structure, that is internal posts will only link to other internal posts that have a matching category. This is done between all posts, not just posts added through the Blog Curation Add Content page.
2. Internal linking is dynamic, that is the internal links are assigned as the page or post is viewed and therefore they can be different each viewing.
3. Internal linking can only be done on keywords that have not already been assigned to an external link (see above "Overview of external linking and SEO").
4. Note that this functionality may interfere with other plugins that attempt to do smart linking. You can turn this feature off by unchecking the "Smart internal linking on" checkbox on the Blog Curation Settings page. Alternatively if you wish to keep the smart linking provided by this plugin then you may need to change the settings or even deactivate conflicting plugins.

Troubleshooting

If you find that the cloaked Links are not redirecting it may be due to a Wordpress problem. If Cloaked Links are not working then try resetting the Permalinks. To do this Choose "Settings" -> "Permalinks" from the left hand menu and note the permalink settings. Next pick any option from Common Settings different to the one you already have and then press "Save Changes". After you have done this put the permalinks back to the way they were and press "Save Changes" again, this should fix the problem.