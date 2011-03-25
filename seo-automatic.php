<?php
/*
Plugin Name: SEO Tools URL Review Lite Add-on (activate separately)
Plugin URI: http://seoautomatic.com/
Description: This is the SECONDARY plugin required for the SEO Automatic URL review to function properly. Change tool definitions and priorities from SEO Automatic -> <a href="admin.php?page=seo-automatic-plugin">SEO Automatic Plugin</a>. To use, simply insert code <code>[seotool]</code> into any post or page.
Version: 3.1.3
*/

/*
* Load default options on install
*/

register_activation_hook(__FILE__,'autoseo_activate');

function autoseo_activate(){
	if (!get_option('autoseo_options'))
		seoauto_import(ABSPATH . PLUGINDIR . '/seo-automatic-seo-tools/default-settings.xml');
}

/*
* Load Widgets
*/
include('seo-widgets.php');

/*
* Add additional items in the header
*/
function autoseo_wp_head(){
	?>
	<link rel="stylesheet" href="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/seo-automatic-styles.css" type="text/css" media="screen, projection, tv" />	
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/themes/seoinspector/css/seoinspector-ie.css" media="screen, projection, tv" />
    <![endif]-->
	<script type="text/javascript">
	var seourl = "<?php echo plugins_url(); ?>/seo-automatic-seo-tools/index.php";
	</script>
<?php	
}

/*
* Add scripts to run tool
*/
add_action('wp_head', 'autoseo_wp_head');
if (!is_admin()){
	wp_enqueue_script('htmltooltip', plugins_url() . '/seo-automatic-seo-tools/themes/seoinspector/js/htmltooltip.js', array('jquery'));
	wp_enqueue_script('seoinspector', plugins_url() . '/seo-automatic-seo-tools/themes/seoinspector/js/seoinspector.js', array('jquery'));
}

/*
* Runs the tool
*/
function seoautomatic_run(){
	global $seoresults_tpl;
	set_include_path(ABSPATH . PLUGINDIR . '/seo-automatic-seo-tools/');
	include('index.php'); //loads smarty stuff and all options from admin are stored in $settings
	
	if ($seoresults_tpl)
		$template = 'index-results.tpl';
	elseif (!empty($settings['misc']['results-page'])) // $settings is set with the include of index.php
		$template = 'index-noajax.tpl';
	else
		$template = 'index.tpl';

	$return = $smarty->fetch($template);

	if ($settings['misc']['fixed-table']){ 
		$return ="<style>\n".
		"#results table{table-layout:fixed}\n".
		"#result table#overview{table-layout:automatic}\n". //the overview table should never expand larger than the theme allows
		"</style>\n" . $return;
	}
	if ( isset($_REQUEST['seoprint']) && isset($_REQUEST['reportid']) ){
		$return .= $smarty->fetch('partials/print-results.tpl');
	}
	return $return;
}


/*
* Save reports
*/

function autoseo_save_report($results, $url){
	global $user_ID;
	if (function_exists('aw_paypal_user_has_credits')){
		if (empty($user_ID))
			return;
		$postarray = array( 'post_author' =>  $user_ID,
		  'post_content' =>  base64_encode(serialize($results)), 
		  'post_title' => $url, 
		  'post_type' => 'seoreport'
		  );
	} else {
		$postarray = array( // we don't set post_author if the pp plugin is not installed
		  'post_content' =>  base64_encode(serialize($results)), 
		  'post_title' => $url, 
		  'post_type' => 'seoreport'
		  );
	}
	return wp_insert_post($postarray);
}

/*
* Retrieve Printer Friendly Reports
*/

function autoseo_get_report(){
	set_include_path(ABSPATH . PLUGINDIR . '/seo-automatic-seo-tools/');
	require('index.php'); //index.php does all the dirty work
	$smarty->display('partials/print-results.tpl');
}
// load printer friendly theme
function autoseo_template_filter($theme){
	global $user_ID;
	if(!isset($_GET['seoprint']))
		return $theme;
	
	$theme = 'seo-print-theme';
	return $theme;
}
add_filter('stylesheet','autoseo_template_filter');
add_filter('template','autoseo_template_filter');

/*
* Short Code
*/
function autoseo_shortcode() {
	$options = get_option('autoseo_options');
	if(isset($options['paypal']['require']))
		return seoautomatic_paypal_run($options);
	else
		return seoautomatic_run();
}
function autoseo_results_shortcode(){
	global $seoresults_tpl;
	$seoresults_tpl = true;
	return autoseo_shortcode();
}
add_shortcode('seotool', 'autoseo_shortcode');
add_shortcode('seoresults', 'autoseo_results_shortcode');

/*
* Tracking
*/
function autoseo_tracking($url){
	$newurl =  str_replace("http://", "", $url); //incase someone entered the http://
	if (strpos($newurl, 'www.') === 0){ //get rid of the www. so we don't have repeated domains but we still allow for subdomains
		$newurl = substr($newurl, 4);
	}
	$slashpos = strpos($newurl, '/'); // get rid of everything after the /
	if ($slashpos !== false){
		$newurl = substr($newurl, 0,$slashpos);
	}
	$quepos = strpos($newurl, '?'); // get rid of everything after the ?
	if ($quepos !== false){
		$newurl = substr($newurl, 0,$quepos);
	}
	$urls = get_option('autoseo_urls'); //existing urls
	if(!is_array($urls)){
		$urls = array();
	}
	if (array_key_exists($newurl, $urls)){ //check if this url has been searched for
		$urls[$newurl] = $urls[$newurl]+1;
	} else {
		$newurl = array($newurl => 1);
		$urls = array_merge($urls, $newurl);
	}
	arsort($urls);
	update_option('autoseo_urls', $urls);
	
	// update count of how many times tool has been run
	update_option('autoseo_count', get_option('autoseo_count')+1);
}

/*
* Admin Menu
*/
add_action('admin_menu', 'autoseo_add_pages', 1);

function autoseo_add_pages() { // Add the menu
	global $menu;
	foreach ($menu as $i) {
		$key = array_search('toplevel_page_seo-automatic-options', $i);
		if ($key != '') {
			$menu_added = true;
		}
	}
	if (isset($menu_added)) {
	} else {
    add_menu_page('SEO Automatic by Search Commander, Inc.', 'SEO Automatic', 'activate_plugins', 'seo-automatic-options', 'autoseo_home_page',plugins_url() . '/seo-automatic-seo-tools/images/favicon.ico');
	add_submenu_page('seo-automatic-options', 'Admin', 'Admin', 'activate_plugins', 'seo-automatic-options', 'autoseo_home_page');
	}

	$autoseo_page = add_submenu_page('seo-automatic-options', 'SEO Automatic by Search Commander, Inc.', 'URL Checker', 'activate_plugins', 'seo-automatic-plugin', 'autoseo_options_page');
	add_action( "admin_print_scripts-$autoseo_page", 'autoseo_admin_scripts' );
	add_action( "admin_head-$autoseo_page", 'autoseo_admin_head' );


}
function autoseo_home_page(){
	include('home.php');
}
function autoseo_admin_scripts(){
	//wp_enqueue_script('seoeffects', plugins_url() . '/seo-automatic-seo-tools/themes/seoinspector/js/jquery-ui-personalized-1.6rc2.min.js', array('jquery'));
	wp_enqueue_script('tablesorter', plugins_url() . '/seo-automatic-seo-tools/themes/seoinspector/js/jquery.tablesorter.min.js', array('jquery'));
	wp_enqueue_script('jqcheckbox', plugins_url() . '/seo-automatic-seo-tools/themes/seoinspector/js/jquery.checkbox.js', array('jquery'));
}
function autoseo_admin_head(){
	echo '<link rel="stylesheet" href=" ' . plugins_url() . '/seo-automatic-seo-tools/themes/seoinspector/css/tables-style.css" type="text/css" media="print, projection, screen" />';
	?>
	<script src="http://ui.jquery.com/latest/ui/effects.core.js"></script>
	<script src="http://ui.jquery.com/latest/ui/effects.slide.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function() {
	  jQuery('a#show-table').click(function() {
		jQuery('#show-table').hide();
		showtable();
		return false;
	  });
	  function showtable(){jQuery('.table-more').show();}
	});
	</script>
	<style>
	.table-more{display:none}
	td h4{margin-top:1.6em;margin-bottom:0;padding-bottom:0} 
	.problem{background-color:#FFFFCC} 
	.important{background-color:#FFCCCC} 
	.correct{background-color:#CCFFCC} 
	.checkbox{padding-left:30px}
	</style>
<?php
}

function autoseo_options_page() {
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

<div class="wrap seoautoreview">
<br />
<div id="dashboard-widgets-wrap">
<div id='dashboard-widgets' class='metabox-holder'>

<div class='postbox-container' style='width:60%;'>
<div id='normal-sortables' class='meta-box-sortables'>

<div id="main-admin-box" class="postbox">
<h3><span><img src="<?php echo plugins_url();?>'/seo-automatic-seo-tools/images/favicon.ico" alt="SEO Automatic" /> SEO Tool Add-on - URL Review Lite (activate separately)</span></h3>
<div class="inside">
<?php
	if (isset($_POST['seoupload'])){
		if(seoauto_import($_FILES['seofile']['tmp_name']))
			$message = "Options Successfully Imported!";
		else
			$message = "<span style='border:1px solid red;font-weight:bold'>There was an error importing the file.  Your settings have not been changed.</span>";
	}
	if (isset($_POST['info_update'])) {
		update_option('autoseo_options', stripslashes_deep($_POST));
		$message = "Options Updated!";
	}
	if (isset($message)){
	?><div id="message" class="updated fade"><p><strong><?php echo $message; ?></strong></p></div><?php
	}
	$settings = get_option('autoseo_options');	
	// below is what displays on the options page  
	?>	
		<?php if (!is_writable(ABSPATH . PLUGINDIR . '/seo-automatic-seo-tools/writable')){?>
		<div style="width:100%;text-align:center;border:1px solid red;">In order for this tool to work this folder must be writable by the server:<br /><strong><?php echo ABSPATH . PLUGINDIR . '/seo-automatic-seo-tools/writable';?></strong></div>
		<?php } ?> 
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
			<input type="hidden" name="info_update" id="info_update" value="true" />
			<input type="hidden" name="app[theme]" value="seoinspector" />
	<h3>Instructions:</h3>
	
<p>Define how you'd like to refer to the good and bad results in the boxes below, as Correct or Acceptable etc.</p>
<p>Individual item definitions go in the far left box, the positive result in the center, and the negative comments on the right.</p>
	<p>Define an item as higher priority by checking the box to the right of the negative comment, which should shade that box red.</p>
	<p>To display the tool, simply place <code>[seotool]</code> within the body of any page or post from the admin / edit screen, USING THE .HTML TAB.</p>
	<p>Results appear by default on the same page where you've placed the <code>[seotool]</code>.<p/> 

<p>Please note that a very few domains, well under 2%, will fail without explanation and say "Enter a valid URL".</p>
<p>Usually, this is the result of some sort of redirect on the customers url, and you need to copy / paste out of the address bar, but occasionally, some failures simply cannot be explained - sort of like MS Windows ;) <p/>

<br />

<table>
		<tr>
			<td><h3>Report Headers</h3></td>
		</tr>
		<tr>
			<td title="Explain the importance of the item."><input type="text" value="<?php echo $settings['heading']['overview'];?>" name="heading[overview]" class="overview" /></td>
		</tr>
		<tr>
			<td title="Result is correct."><input type="text" value="<?php echo $settings['heading']['correct'];?>" name="heading[correct]" class="correct" /></td>
		</tr>
		<tr>
			<td title="Result is incorrect and worth reviewing."><input type="text" value="<?php echo $settings['heading']['problem'];?>" name="heading[problem]" class="problem" /></td>
		</tr>
		<tr>
			<td title="Result is incorrect and demands immediate attention."><input type="text" value="<?php echo $settings['heading']['critical'];?>" name="heading[critical]" class="important" /></td>
		</tr>
		<tr>
			<td><br /><h3>Misc.</h3></td>
		</tr>
		<tr>
		<td><p><b>You can force a message to display above the tool box anywhere that the [seotool] short code is displayed by typing it into the box below.</p></td></tr>
		<tr>
			<td><h4 style="padding-top:0;margin-top:0">Message Above Form</h4></td>
		</tr>
		<tr>
			<td title="The message that is displayed above the URL form.">
			<textarea rows="3" cols="40" name="misc[top-message]"><?php echo $settings['misc']['top-message'];?></textarea></td>
		</tr>
		<tr>
			<td><h4>Submit Button text</h4></td>
		</tr>
		<tr>
			<td><input type="text" value="<?php echo $settings['misc']['button'];?>" name="misc[button]" class="button-text" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><p><input type="checkbox" name="misc[fixed-table]" <?php if($settings['misc']['fixed-table']){echo 'checked';}?> /> Fixed Tables (Play with this checkbox if you have weird theme issues with the results tables)</p></td>
		</tr>
</table><br /><table>
		<tr>
			<td><h3>Results Text</h3></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><h4 style="margin-top:0;padding-top:0">Title Tag</h4></td>
		</tr>
		<tr>
			<td>Enable&nbsp;&nbsp;<input type="checkbox" name="locale[title][enable]" id="title-enable" class="tog-enable" <?php if($settings['locale']['title']['enable']){echo 'checked';}?> /></td>
		</tr>
		<tr id="e-title-enable" <?php if(!$settings['locale']['title']['enable']){echo 'style="display:none"';}?>>
			<td><textarea name="locale[title][tooltip]" rows="5" cols="40"><?php echo $settings['locale']['title']['tooltip']; ?></textarea></td>
			<td><textarea name="locale[title][correct]" rows="5" class="correct" cols="40"><?php echo $settings['locale']['title']['correct']; ?></textarea></td>
			<td><textarea name="locale[title][problem]" id="p-title" class="problem<?php if($settings['locale']['title']['important']){echo ' important';}?>" rows="5" cols="40"><?php echo $settings['locale']['title']['problem']; ?></textarea></td>
			<td class="checkbox"><input type="checkbox" name="locale[title][important]" id="title" class="tog-imp" <?php if(isset($settings['locale']['title']['important'])){echo 'checked';}?> /></td>
		</tr>
		<tr>
			<td><h4>Description Tag</h4></td>
		</tr>
		<tr>
			<td>Enable&nbsp;&nbsp;<input type="checkbox" name="locale[description][enable]" id="description-enable" class="tog-enable" <?php if($settings['locale']['description']['enable']){echo 'checked';}?> /></td>
		</tr>
		<tr id="e-description-enable" <?php if(!$settings['locale']['description']['enable']){echo 'style="display:none"';}?>>
			<td><textarea name="locale[description][tooltip]" rows="5" cols="40"><?php echo $settings['locale']['description']['tooltip']; ?></textarea></td>
			<td><textarea name="locale[description][correct]" rows="5" class="correct" cols="40"><?php echo $settings['locale']['description']['correct']; ?></textarea></td>
			<td><textarea name="locale[description][problem]" id="p-description" class="problem<?php if($settings['locale']['description']['important']){echo ' important';}?>" rows="5" cols="40"><?php echo $settings['locale']['description']['problem']; ?></textarea></td>
			<td class="checkbox"><input type="checkbox" name="locale[description][important]" id="description" class="tog-imp" <?php if(isset($settings['locale']['description']['important'])){echo 'checked';}?> /></td>
		</tr>
		<tr>
			<td><h4>H1 Tag</h4></td>
		</tr>
		<tr>
			<td>Enable&nbsp;&nbsp;<input type="checkbox" name="locale[h1_status][enable]" id="h1_status-enable" class="tog-enable" <?php if($settings['locale']['h1_status']['enable']){echo 'checked';}?> /></td>
		</tr>
		<tr id="e-h1_status-enable" <?php if(!$settings['locale']['h1_status']['enable']){echo 'style="display:none"';}?>>
			<td><textarea name="locale[h1_status][tooltip]" rows="5" cols="40"><?php echo $settings['locale']['h1_status']['tooltip']; ?></textarea></td>
			<td><textarea name="locale[h1_status][correct]" rows="5" class="correct" cols="40"><?php echo $settings['locale']['h1_status']['correct']; ?></textarea></td>
			<td><textarea name="locale[h1_status][problem]" id="p-h1_status" class="problem<?php if($settings['locale']['h1_status']['important']){echo ' important';}?>" rows="5" cols="40"><?php echo $settings['locale']['h1_status']['problem']; ?></textarea></td>
			<td class="checkbox"><input type="checkbox" name="locale[h1_status][important]" id="h1_status" class="tog-imp" <?php if(isset($settings['locale']['h1_status']['important'])){echo 'checked';}?> /></td>
		</tr>
		<tr>			<td><h4>Keyword Meta Tag</h4></td>		</tr>
		<tr>
			<td>Enable&nbsp;&nbsp;<input type="checkbox" name="locale[keywords][enable]" id="keywords-enable" class="tog-enable" <?php if($settings['locale']['keywords']['enable']){echo 'checked';}?> /></td>
		</tr>
		<tr id="e-keywords-enable" <?php if(!$settings['locale']['keywords']['enable']){echo 'style="display:none"';}?>>
			<td><textarea name="locale[keywords][tooltip]" rows="5" cols="40"><?php echo $settings['locale']['keywords']['tooltip']; ?></textarea></td>
			<td><textarea name="locale[keywords][correct]" rows="5" class="correct" cols="40"><?php echo $settings['locale']['keywords']['correct']; ?></textarea></td>
			<td><textarea name="locale[keywords][problem]" id="p-keywords" class="problem<?php if($settings['locale']['keywords']['important']){echo ' important';}?>" rows="5" cols="40"><?php echo $settings['locale']['keywords']['problem']; ?></textarea></td>
			<td class="checkbox"><input type="checkbox" name="locale[keywords][important]" id="keywords" class="tog-imp" <?php if(isset($settings['locale']['keywords']['important'])){echo 'checked';}?> /></td>
		</tr>
		<tr>			<td><h4>Image ALT Tags</h4></td>		</tr>
		<tr>
			<td>Enable&nbsp;&nbsp;<input type="checkbox" name="locale[alt_attributes][enable]" id="alt_attributes-enable" class="tog-enable" <?php if($settings['locale']['alt_attributes']['enable']){echo 'checked';}?> /></td>
		</tr>
		<tr id="e-alt_attributes-enable" <?php if(!$settings['locale']['alt_attributes']['enable']){echo 'style="display:none"';}?>>
			<td><textarea name="locale[alt_attributes][tooltip]" rows="5" cols="40"><?php echo $settings['locale']['alt_attributes']['tooltip']; ?></textarea></td>
			<td><textarea name="locale[alt_attributes][correct]" class="correct" rows="5" cols="40"><?php echo $settings['locale']['alt_attributes']['correct']; ?></textarea></td>
			<td><textarea name="locale[alt_attributes][problem]" id="p-alt_attributes" class="problem<?php if($settings['locale']['alt_attributes']['important']){echo ' important';}?>" rows="5" cols="40"><?php echo $settings['locale']['alt_attributes']['problem']; ?></textarea></td>
			<td class="checkbox"><input type="checkbox" name="locale[alt_attributes][important]" id="alt_attributes" class="tog-imp" <?php if(isset($settings['locale']['alt_attributes']['important'])){echo 'checked';}?> /></td>
		</tr>
		</table>
		<p><input type="submit" value="Update Settings" class="button" /></p>
		</form>
<br />
		<h3>Import / Export Settings</h3>
		<h4>Import data (above) from another export (below) on another installation</h4>
		<form name="seoimport" enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
			<p title="Choose an xml file to import"><input name="seofile" type="file" /></p>
			<input type="hidden" name="seoupload" value="true" />
			<input type="submit" value="Import" class="button" title="Submit" />
		</form>
		<h4>EXPORT to SAVE your work from above!</h4>
		<p title="Save a backup of your settings"><a class="button" target="_blank" href="?page=seo-automatic-plugin&seoexport=true">Download XML</a></p>
<br />
		<h3>Stats</h3>
		<?php $urls = get_option('autoseo_urls');
		if (!$urls){ ?>
		<p>It doesn't look like the tool has been used yet.  Check back later for stats.</p>
		<?php } else { ?>
		<p>wow! This tool has already been run <strong><?php echo get_option('autoseo_count'); ?> times</strong> to check <strong><?php echo count($urls); ?> domains</strong>. Can you believe that?</p>
		<p>Domains checked:</p>
		<table id="urls" class="tablesorter">
		<thead>
		<tr>
			<th>Domain</th>
			<th style="width:50px">Count</th>
		</tr>
		</thead>
		<?php
		$i=0;
		foreach ($urls as $domain => $count){ ?>
		<tr<?php if ($i > 9){echo ' class="table-more"';}?>>
			<td style="border:1px solid black"><?php echo $domain; ?></td>
			<td style="border:1px solid black"><?php echo $count; ?></td>
		</tr>
		<?php $i++;} //end foreach ?>
		</table>
		<?php if ($i > 10){echo '<a id="show-table" href="#">Show all ' . $i . ' domains.</a>';} //only print more text if we have 11 or more urls
		 } //end if urls 
		 ?>

</div></div>

</div></div>

<div class='postbox-container' style='width:35%;'>
<div id='side-sortables' class='meta-box-sortables'>

<div id="about-plugins" class="postbox " >
<h3><span>About</span></h3>
<div class="inside">
<a href="http://www.seoautomatic.com/plugins/" target="_blank"><img src="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/images/logo-2010.jpg" alt="SEO Automatic" width="262" height="166" /></a>
<br />
<ul>
	<li style="margin-left: -4px;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="5701868">
<input type="image" src="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/images/donate.jpg" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" onclick="this.form.target='_blank';return true;">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</li>
</ul>

</div></div>

<div id="resources" class="postbox" >
<h3><span>Resources</span></h3>
<div class="inside">
<ul>
	<li><img src="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/images/favicon.ico" height="16" width="16" alt="" /> <a href="http://www.seoautomatic.ourtoolbar.com/" target="_blank">Search Commander, Inc. Toolbar</a></li>
	<li><img src="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/images/favicon.ico" height="16" width="16" alt="SEO Automatic" /> <a href="http://www.seoautomatic.com/unique-tools/" target="_blank"> SEO Automatic Tools</a></li>
	<li><img src="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/images/favicon.ico" height="16" width="16" alt="SEO Automatic" /> <a href="http://www.seoautomatic.com/pricing-plans/white-label/" target="_blank"> White Label Options</a></li>
	<li><img src="<?php echo plugins_url(); ?>/seo-automatic-seo-tools/images/favicon.ico" height="16" width="16" alt="SEO Automatic" /> <a href="http://www.seoautomatic.com/tip-of-the-week/" target="_blank"> Automation Tip of the Week</a></li>
</ul>
</div></div>

<div id="resources" class="postbox" >
<h3><span>Recommended Affiliates</span></h3>
<div class="inside">
<?php
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed('http://www.seoautomatic.com/category/rec/feed');
if (!is_wp_error( $rss ) ) : 
    $maxitems = $rss->get_item_quantity(5); 
    $rss_items = $rss->get_items(0, $maxitems); 
endif;
?>

<ul>
    <?php if ($maxitems == 0) echo '<li>No items.</li>';
    else
    foreach ( $rss_items as $item ) : ?>
    <li>
        <a href='<?php echo $item->get_permalink(); ?>' target='_blank' title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
        <?php echo $item->get_title(); ?></a><br />
    </li>
    <?php endforeach; ?>
</ul>
</div></div>

<div id="seoautofeed" class="postbox" >
<h3><span>Latest news from the SEO Automatic blog ...</span></h3>
<div class="inside">
<?php
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed('http://www.seoautomatic.com/feed');
if (!is_wp_error( $rss ) ) : 
    $maxitems = $rss->get_item_quantity(5); 
    $rss_items = $rss->get_items(0, $maxitems); 
endif;
?>

<ul>
    <?php if ($maxitems == 0) echo '<li>No items.</li>';
    else
    foreach ( $rss_items as $item ) : ?>
    <li>
        <a href='<?php echo $item->get_permalink(); ?>'
        title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
        <?php echo $item->get_title(); ?></a><br />
		<?php //echo $item->get_description(); ?>
    </li>
    <?php endforeach; ?>
</ul>
</div></div>

</div></div>
<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->
<?php
}

/*
* Import / Export Helpers
*/

function seoauto_import($xmlpath){
	if (empty($xmlpath))
		return false;
	include('libs/assoc_array2xml.php');
	$converter = new assoc_array2xml;
	$xml = file_get_contents($xmlpath);
	$newsettings = $converter->xml2array($xml);
	$newsettings = $newsettings['array'];
	array_walk_recursive($newsettings, 'seoauto_prepare_import');
	if(!is_array($newsettings['locale']['title'])) //validate it is an actual seoauto export
		return false;

	$oldsettings = get_option('autoseo_options');
	
	if(is_array($oldsettings['paypal']))
		$newsettings['paypal']['require'] = $oldsettings['paypal']['require']; //We don't export this setting to avoid conflicts so we will keep the original setting
	else
		unset($newsettings['paypal']['require']);
	
	update_option('autoseo_options', $newsettings);
	return true;
}
function seoauto_export(){
	if (isset($_GET['seoexport']) && current_user_can('activate_plugins')){
		include('libs/assoc_array2xml.php');
		header('Content-type: text/xml');
		header('Content-disposition: attachment; filename=seo-settings.xml');
		$array = get_option('autoseo_options');
		unset($array['paypal']['require']); // This could cause conflicts with people who do not have the paypal plugin if we left it set.
		array_walk_recursive($array, 'seoauto_prepare_export');
		$converter = new assoc_array2xml;
		$xml = $converter->array2xml($array);
		echo $xml;
		die;
	}
}
// walker functions
function seoauto_prepare_export(&$var, $key){
	$var = base64_encode($var);
}
function seoauto_prepare_import(&$var, $key){
	$var = base64_decode($var);
}
add_action('init', 'seoauto_export',99);

?>