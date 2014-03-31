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
<p>This plugin has five tools you can run for your clients, and the main URL Checker has its own admin page <a href="admin.php?page=seo-automatic-plugin">here</a>.</p>
<p>The URL checker provided with this plugin lets you edit five on page SEO factors, and a pro version that covers neary 20 is available from <a href="http://www.seoautomatic.com/pricing-plans/" target="_blank">SEO Automatic</a>.</p>
<p><strong>Short Codes:</strong><br />Just add the short code which corresponds to the tools below into the .html tab of any post or page where you would like the tool to display.</p>
<p>Please note that Use of these tools does require your theme to have a minimum body width of 500 pixels to display the results.</p>
<p>If you don't know how to add the shortcodes yourself, you can automatically add a new page for each of the tools from <a href="admin.php?page=seo-automatic-seo-tools/add-tool-pages.php">here</a>, then edit them as you would any other page, you'll see the short codes in place.</p>

<?php
if(!get_option('seo_tools_linkback_seotools')) {
	update_option('seo_tools_linkback_seotools', 'off');
}

if ($_REQUEST['set_all_linkbacks'] == 'yes') {
	update_option('seo_tools_linkback_seotools', $_REQUEST['linkback_seotools']);
} 

if (get_option('seo_tools_linkback_seotools') == 'on' ) {
	$lon = ' checked';
} 
?>

<form method="post" action=""><input type="hidden" name="set_all_linkbacks" value="yes" /><p><b>In compliance with the rules set by WordPress, any backlink to us is turned off, but you can show you care by checking this box... <br /><input type="checkbox" value="on" name="linkback_seotools"<?php echo $lon;?>></b> <input type="submit" value="Save"><br /><b>No? Okay, then how about a measly Facebook like?</b></p>
</form>
<p><hr /></p>
<p><b>URL Checker</b> <a href="http://www.seoautomatic.com/unique-tools/instant-seo-review/" rel="nofollow" target="_blank" style="text-decoration: none;">(Sample)</a></p>
<p>Your visitors can get a quick look at some on-page organic search ranking factors, with a "Lite" review, showing YOUR definitions and advice.</p>
<p>This tool covers 5 on-page search ranking factors instantly, then summarizes with a definition, commentary and specific solutions that you are free to edit from the "<a href="admin.php?page=seo-automatic-plugin">settings screen</a>"</p>
<p>For the full version, allowing you to review 18+ ranking factors, see the <a href="http://www.seoautomatic.com/products-page/pricing/seo-review-plugin/" target="_blank">full URL review tool</a> or take a look at all of the "<a href="http://www.seoautomatic.com/pricing-plans/white-label/" target="_blank">white label options</a>."</p>
<p><b>To use, edit your <a href="admin.php?page=seo-automatic-plugin">settings page</a> with your own ranking factor definitions and explanations.</b></p>
<p><b>To make the tool appear, use the shortcode<code>[urlchecker]</code>from the .html tab while editing any post or page.</b></p>
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
<p><b>To use, add the shortcode: [bulkurlchecker]</b></p>
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
<div style="border: 1px solid #E0DFE3; padding: 15px;"><form method="post" action=""><input type="hidden" name="set_linkback" value="yes" /><p><b>Link back to you after the displayed feeds from the sites that use your code:</b><br /><input type="radio" value="on" name="linkback"<?php echo $a;?>>Yes <input type="radio" value="off" name="linkback"<?php echo $b;?>>No</p>
	<p>Url: <input type="text" name="linkbackurl" size="40" value="<?php echo get_option('seo_tools_linkback_url');?>"<?php echo $c;?>></p>
	<p>Anchor Text: <input type="text" name="linkbacktxt" size="40" value="<?php echo get_option('seo_tools_linkback_text');?>"<?php echo $c;?>> <input type="submit" value="Set" name="setlinkback"></p>
</form>
<p>Current display: <a href="<?php echo get_option('seo_tools_linkback_url');?>" target="_blank"><?php echo get_option('seo_tools_linkback_text');?></a></p></div>

</div></div>

</div></div>

<?php include('seoauto-sidebar.php'); ?>

<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div></div><!-- wrap -->