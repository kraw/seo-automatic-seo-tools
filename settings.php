<?php
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
hr { color: #E2E2E2; border: 1px solid #E4E4E4; background-color: #E6E6E6; margin: 25px 0; }
</style>

<div class="wrap">
<br />
<div id="dashboard-widgets-wrap">
<div id='dashboard-widgets' class='metabox-holder'>

<div class='postbox-container' style='width:60%;'>
<div id='normal-sortables' class='meta-box-sortables'>

<div id="main-admin-box" class="postbox">
<h3><span><img src="http://www.seoautomatic.com/favicon.ico" alt="SEO Automatic" /> SEO Tools</span></h3>
<div class="inside">
<p>This is your own version of the described tools available at SEO Automatic, to run on your site.</p>
<p>Add the short code which corresponds to the tools below into the .html tab of any post or page where you 
would like the tool to display.&nbsp; </p>
<p>Please note that Use of these tools does require your theme to have a minimum body width of 500 pixels to 
display the results. </p>
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
<p>Allow your site visitors to format any valid RSS feed to display as they wish. They may then use the 
generated code on any website they like.</p>
<p><b>To use, add the shortcode: [feedcommander]</b></p>

<?php
if ($_REQUEST['set_linkback'] == 'yes') {
	update_option('seo_tools_linkback_url', $_REQUEST['linkbackurl']);
	update_option('seo_tools_linkback_on', $_REQUEST['linkback']);
	update_option('seo_tools_linkback_text', $_REQUEST['linkbacktxt']);
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
<p>Current display: <a href="<?php echo get_option('seo_tools_linkback_url');?>" target="_blank"><?php echo get_option('seo_tools_linkback_text');?></a></p>
</div>

</div></div>

</div></div>

<div class='postbox-container' style='width:35%;'>
<div id='side-sortables' class='meta-box-sortables'>

<div id="about-plugins" class="postbox " >
<h3><span>About</span></h3>
<div class="inside">
<a href="http://www.seoautomatic.com/plugins/" target="_blank"><img src="http://www.seoautomatic.com/plugin-home/images/logo-2010.jpg" alt="SEO Automatic" width="262" height="166" /></a>
<br />
<ul>
	<li><img src="http://www.seoautomatic.com/favicon.ico" alt="SEO Automatic" /> <a href="http://www.seoautomatic.com/white-label/" target="_blank"> White Label Options</a></li>
</ul>

</div></div>

<div id="resources" class="postbox" >
<h3><span>Resources</span></h3>
<div class="inside">
<ul>
	<li><img src="http://www.seoautomatic.com/favicon.ico" alt="" /> <a href="http://www.seoautomatic.ourtoolbar.com/" target="_blank">Search Commander, Inc. Toolbar</a></li>
	<li><img src="http://www.seoautomatic.com/favicon.ico" alt="SEO Automatic" /> <a href="http://www.searchcommander.com/seo-tools/" target="_blank"> SEO Automatic Tools</a></li>
	<li><img src="http://www.seoautomatic.com/favicon.ico" alt="" /> <a href="http://www.getwordpressed.com/about/" target="_blank">Site Matched WP Themes</a></li>
</ul>
</div></div>

</div></div>
<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->