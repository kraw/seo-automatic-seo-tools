<?php
include('thisplugin.php');
if (function_exists('plugins_url')) {
	$path=trailingslashit(plugins_url(basename(dirname(__FILE__))));
	} else {
	$path = dirname(__FILE__);
	$path = str_replace("\\","/",$path);
	$path = trailingslashit(get_bloginfo('wpurl')) . trailingslashit(substr($path,strpos($path,"wp-content/")));
}
	$blogpath = get_bloginfo('url');
	if (substr($blogpath, -1) != '/') {
		$blogpath.="/";
	}	
if (get_bloginfo('version') < 2.8) {
	echo '<style>.postbox-container { float: left; } #side-sortables { padding-left: 20px; }';
} else {
	echo '<style>';
}
?>
.postbox .inside { padding: 8px !important; }
#about-plugins a, #resources a {text-decoration: none;}
#about-plugins img, #resources img {float: left; padding-right: 3px;}
#success li, #success h3 {color: #006600; }
#fail li, #fail h3 {color: #ff0000; }
#resources li { clear: both; }
#about-plugins li { clear: both; }
</style>

<div class="wrap">
<br />
<div id="dashboard-widgets-wrap">
<div id='dashboard-widgets' class='metabox-holder'>

<div class='postbox-container' style='width:60%;'>
<div id='normal-sortables' class='meta-box-sortables'>

<div id="main-admin-box" class="postbox">
<h3><span><img src="<?php echo plugins_url();?>/seo-automatic-seo-tools/images/favicon.ico" alt="SEO Automatic" /> SEO Tools</span></h3>
<div class="inside">
<p>This is your own version of the described tools available at SEO Automatic, to run on your site.</p>
<p>Add the short code which corresponds to the tools below into the .html tab of any post or page where you 
would like the tool to display.&nbsp; </p>
<p>Please note that Use of these tools does require your theme to have a minimum body width of 500 pixels to 
display the results. </p>
<p><a href="admin.php?page=seo-automatic-seo-tools/add-tool-pages.php">You can automatically add tool pages from here.</a></p>
<p><hr /></p>
<p><b>URL Review Lite</b> <a href="http://www.seoautomatic.com/unique-tools/instant-seo-review/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>Your visitors can get a quick look at some on-page organic search ranking factors, with a "Lite" review, showing YOUR definitions and advice.</p>
<p>This tool covers 5 on-page search ranking factors instantly, then summarizes with a definition, commentary and specific solutions that you are free to edit from the "<a href="admin.php?page=seo-automatic-plugin">settings screen</a>"</p>
<p>For the full version, allowing you to review 18+ ranking factors, see the <a href="http://www.seoautomatic.com/products-page/pricing/seo-review-plugin/" target="_blank">full URL review tool</a> or take a look at all of the "<a href="http://www.seoautomatic.com/pricing-plans/white-label/" target="_blank">white label options</a>."</p>
<p><b><font color="#009900">To use, first go to your <a href="plugins.php">plugins page</a> and activate the plugin named: SEO Tool Add-on - URL Review Lite.</font></b></p>
<p><b>Then, edit your <a href="admin.php?page=seo-automatic-plugin">settings page</a> with your own ranking factor definitions and explanations.</b></p>
<p><b>To make the tool appear, use the shortcode<code>[seotool]</code>from the .html tab while editing any post or page.</b></p>
<p><hr /></p>
<p><b>Keyword List Multiplier</b> <a href="http://www.seoautomatic.com/unique-tools/keyword-multiplier/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>Allow your site visitors to easily and instantly create a combination of keyword lists to &quot;cover all their 
bases&quot; for all the different variations of cities, states, categories etc. when setting up a PPC campaign, including google match types. </p>
<p>There is an additional option (not to be used with Adwords) that will keep any spaces or other characters you 
may add, such as the pipe | or spaces. When checked, the tool&nbsp;will not add its own spaces. This option makes this tool suitable for nearly any other need, such as 
inserting options into content spinning software.</p>
<p><b>To use, add the shortcode: [keyword-marriage]</b></p>
<p><hr /></p>
<p><b>Bulk URL checker</b> <a href="http://www.seoautomatic.com/unique-tools/bulk-url-checker/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>Allow your site visitors to check the server response of just one or a large batch of URL's to see which ones 
might be redirected or which ones might come up 404 not found, and then make that list available for download. The longer the list of URL's, the longer the tool will take 
to run.</p>
<p><b>To use, add the shortcode: [urlchecker]</b></p>
<p><hr /></p>
<p><b>Link Variance</b> <a href="http://www.seoautomatic.com/unique-tools/link-variance/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>Allow your site visitors to put a list of URLs on one side, then a list of varied anchor text on the other 
side, press a button, and get a complete list of every possible variation of text link and landing page. This list can then be given to bloggers, authors, programmers, etc. 
to use throughout your content.</p>
<p><b>To use, add the shortcode: [link-variance]</b></p>
<p><hr /></p>
<p><b>RSS Feed Commander</b> <a href="http://www.seoautomatic.com/unique-tools/feedcommander/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>Allow your site visitors to format any valid RSS feed to display as they wish, using the generated code on any website they like, while YOU have the anchor text backlink that you want.</p>
<p><b>To use, add the shortcode: [feedcommander]</b></p>

<?php
if ($_REQUEST['set_linkback'] == 'yes') {
	update_option('seo_tools_linkback_url', $_REQUEST['linkbackurl']);
	update_option('seo_tools_linkback_on', $_REQUEST['linkback']);
	update_option('seo_tools_linkback_text', $_REQUEST['linkbacktxt']);
} else {
	if (get_option('seo_tools_linkback_text') == 'add RSS feeds to any website') {
		update_option('seo_tools_linkback_text', 'change this anchor text in the SEO Tools admin');
		$make_link = get_bloginfo('wpurl').'/wp-admin/admin.php?page=seo-automatic-seo-tools/settings.php';
		update_option('seo_tools_linkback_url', $make_link);
	}
}

if (get_option('seo_tools_linkback_on') == 'on' ) {
	$a = ' checked';
	$c = '';
} else { 
	$b = ' checked';
	$c = ' readonly';
}
?>
<div style="border: 1px solid #E0DFE3; padding: 15px;"><form method="post" action=""><input type="hidden" name="set_linkback" value="yes" /><p><b>Link Back:</b> <input type="radio" value="on" name="linkback"<?php echo $a;?>>On <input type="radio" value="off" name="linkback"<?php echo $b;?>>Off</p>
	<p>Url: <input type="text" name="linkbackurl" size="40" value="<?php echo get_option('seo_tools_linkback_url');?>"<?php echo $c;?>></p>
	<p>Anchor Text: <input type="text" name="linkbacktxt" size="40" value="<?php echo get_option('seo_tools_linkback_text');?>"<?php echo $c;?>> <input type="submit" value="Set" name="setlinkback"></p>
</form>
<p>Current display: <a href="<?php echo get_option('seo_tools_linkback_url');?>" target="_blank"><?php echo get_option('seo_tools_linkback_text');?></a></p></div>

<p><hr /></p>
<p><b>Landing Page Determinator</b> <a href="http://www.seoautomatic.com/unique-tools/best-page-determinator/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>This tool uses Google's API to tell you which page on your site ranks highest organically, and therefore, is also the most likely to be given a higher Quality Score by the AdWords team.</p>
<p><b>To use, add the shortcode: [lpd-tool]</b></p>

</div></div>

</div></div>

<?php include('seoauto-sidebar.php'); ?>

<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div></div><!-- wrap -->