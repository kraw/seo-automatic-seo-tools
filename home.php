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
</style>

<div class="wrap">
<br />
<div id="dashboard-widgets-wrap">
<div id='dashboard-widgets' class='metabox-holder'>

<div class='postbox-container' style='width:60%;'>
<div id='normal-sortables' class='meta-box-sortables'>

<div id="main-admin-box" class="postbox">
<h3><span><img src="http://www.seoautomatic.com/favicon.ico" alt="SEO Automatic" /> SEO Automatic Admin</span></h3>
<div class="inside">

<p>SEO Automatic is more than one plugin, it's a proven system for getting the most out of WordPress, that was developed by Scott Hendison and several programmers for Search Commander, Inc.</p>
<p>Access any of your installed modules from the menu now added to the bottom left of your WP-admin.</p>
<p>Here are the modules you have installed:</p>

<?php
if (function_exists('autoseo_add_pages')){
	echo "<p><a href=\"?page=seo-automatic-plugin\">SEO Automatic</a></p>";
}
if (function_exists('sc_settings')) {
	echo "<p><a href=\"?page=seo-automatic-wp-core-tweaks/settings.php\">Core Tweaks</a></p>";
}
if (function_exists('affiliate_menu')) {
	echo "<p><a href=\"?page=affiliate/files/admin/index.php\">Affiliate Site Generator</a></p>";
}
if (function_exists('aw_paypal_add_pages')){
	echo "<p><a href=\"?page=paypal-credits-options\">PayPal Credits</a><br />".
	"<a href=\"?page=paypal-manage-users\">Manage Users</a><br />".
	"<a href=\"?page=import-users\">Import Users</a></p>";
}
if (function_exists('unf_content_filter')) {
	echo "<p><a href=\"?SEO-Automatic-No-Followizer/unf-admin-menu.php\">No Followizer</a></p>";
}
if (function_exists('seo_tools_admin')) {
	echo "<p><a href=\"?page=seo-automatic-seo-tools/settings.php\">SEO Tools</a></p>";
}
?>
</div></div>

<div id="wpsc_dashboard_widget" class="postbox" >
<h3><span>Latest news from the SEO Automatic blog ...</span></h3>
<div class="inside">
<p><div>
	<?php 
			$url="http://www.feedcommander.com/free/rssread.php?src=http://www.seoautomatic.com/feed&title=n&lines=5&boxpadding=10&b_width=0&b_height=0&h_bar=n&v_bar=n&mq=n&mq_di=DOWN&mq_n=3&mq_dy=200&b_color=none&b_style=none&b_b_color=ffffff&b_b_weight=thin&t_font=&t_s_bold=y&t_s_italic=n&t_s_underline=y&t_s_marquee=n&t_size=16&t_align=center&t_color=&i_max_char=0&i_font=&i_s_bold=y&i_s_italic=n&i_s_underline=n&i_s_marquee=n&i_size=11&i_color=&c_max_char=200&c_font=&c_s_bold=n&c_s_italic=n&c_s_underline=n&c_s_marquee=n&c_size=11&c_align=left&c_color=&html=y"; 
			$ch = curl_init($url); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$results=curl_exec($ch); 
			curl_close($ch); 
			print("$results"); 
	?></div></p>
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