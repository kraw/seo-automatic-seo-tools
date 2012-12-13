<?php
/*
Plugin Name: SEO Tools by SEO Automatic 
Plugin URI: http://www.seoautomatic.com/plugins/unique-seo-tools/
Description: Unique SEO tools for your visitors or employees to perform repetetive tasks efficiently, or to otherwise save time.  Created by Search Commander, Inc. for free distribution. <br />See <a href="admin.php?page=seo-automatic-options">SEO Automatic</a> > <a href="admin.php?page=seo-automatic-seo-tools/settings.php">SEO Tools</a> for options. 
Version: 3.1.10
Author: cyber49
Author URI: http://www.seoautomatic.com/plugins/unique-seo-tools/
*/

//make sure I change

//bulk url checker
function cleanData(&$str) { 
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\n/", "\\n", $str);
}

    function getHttpResponseCode($url)
    {
        $ch = @curl_init($url);
        @curl_setopt($ch, CURLOPT_HEADER, TRUE);
        @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $status = array();
        $response = @curl_exec($ch);
        preg_match('/HTTP\/.* ([0-9]+) .*/', $response, $status);
        if ($status[1] == '301'){
	        preg_match('/location: ([^\s]+)/i', $response, $redirect);
			$statusredirect = $status[1].':|:'.$redirect[1];
		return $statusredirect;	
	
		} elseif ($response == ''){
		return 'NoResponse';

		} else 
		return trim($status[1]);
    }

function sc_404_header_scripts() {
	$sc_plugin_dir =  get_option('siteurl').'/wp-content/plugins/seo-automatic-seo-tools/sc-bulk-url-checker/';
	echo '<link rel="stylesheet" href="'.$sc_plugin_dir.'tablesorter/themes/blue/style.css" type="text/css" id="bulkurl" media="print, projection, screen" />';
}

add_action('wp_head', 'sc_404_header_scripts');

function sc_get_404_page(){
	ob_start();
		require_once(ABSPATH.PLUGINDIR.'/seo-automatic-seo-tools/sc-bulk-url-checker/index.php');
		$bulkpage = new Bulk404Object;
		$bulkpage->sc_404_page();
	$return = ob_get_contents();
	ob_end_clean();
	return $return;
}


function sc_add_404_page(){
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/free-tools/bulk-url-checker/" target="_blank">This Bulk Url Checker was provied by SEO Automatic</a></small>';
	} else {
		$seottools = 'This Bulk Url Checker was provied by SEO Automatic';
	}
	return sc_get_404_page().$seotools;
}

add_shortcode('urlchecker', 'sc_add_404_page');


//feedcommander
function sc_get_feedcommander(){
ob_start();
    require_once(ABSPATH.'wp-content/plugins/seo-automatic-seo-tools/feedcommander/'.'feedcommander.php');
	$bulkpage = new feedcommander_free;
	$bulkpage->sc_feedcommander_free();
$return = ob_get_contents();
ob_end_clean();
return $return;
}


function sc_add_feedcommander(){
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/free-tools/feedcommander/" target="_blank">This Feed Commander was provided by SEO Automatic</a></small>';
	} else {
		$seotools = 'This Feed Commander was provided by SEO Automatic';
	}
	return sc_get_feedcommander().$seotools;
}

add_shortcode('feedcommander', 'sc_add_feedcommander');


//link variance
class linkvariance {
	function sc_linkvariance(){
		if ($_REQUEST['run'] == "yes") {
			
			extract($_REQUEST);
			if ($nofollow == "ON") { $rel = ' rel="nofollow"'; }
			if ($newtab == "ON") { $newwin = ' target="_blank"'; }
			$keywords = "";
			 
			$list1 = preg_replace('/\r\n|\r/', ',', $input1);
			$list2 = preg_replace('/\r\n|\r/', ',', $input2);
			$list1 = explode( ",", $list1 );
			$list2 = explode( ",", $list2 );
		 
			if ($novary == "ON") { 
				$nvx = 0;
				$nv = count($list2);
				while ($nvx < $nv):
					$keywords = $keywords ."\n". '<a href="' . trim($list1[$nvx]) . '"' . $rel . $newwin . '>' . trim($list2[$nvx]) . '</a>';
					$nvx++;
				endwhile;	
			} else {		 
			   foreach( $list1 as $word1 ) {		 
				  foreach( $list2 as $word2 ) {		 		 
						$keywords = $keywords ."\n". '<a href="' . trim($word1) . '"' . $rel . $newwin . '>' . trim($word2) . '</a>';
					 }			 
				}	
			}
		}
?>

	<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" name="linkvariance">
	<input type="hidden" name="run" value="yes" />
	  <div align="center">
		
		<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="482">
		  <tr>
			<td width="100" align="center" valign="top">URL List</td>
			<td width="10" align="center" valign="top">&nbsp;</td>
			<td width="100" align="center" valign="top">Anchor Text</td>
		 </tr>
		  <tr>
			<td width="100" align="center" valign="top">
			<textarea rows="23" id="input1" name="input1" cols="24"><?php echo $input1;?></textarea></td>
			<td width="10" align="center" valign="top">&nbsp;</td>
			<td width="100" align="center" valign="top">
			<textarea rows="23" id="input2" name="input2" cols="24"><?php echo $input2;?></textarea></td>
		  </tr>
		  <tr>
			<td colspan="3" align="center" valign="top"><br />
			<input type="checkbox" name="nofollow" value="ON" checked> Add nofollow  &nbsp;<input type="checkbox" name="newtab" value="ON" checked> Open Links in New Window<br /><input type="checkbox" name="novary" value="ON"> Do not vary phrases <font size="1">(This will create just one .html link per phrase, retaining the order in which they're entered.)</font>
			<br /><input type="submit" value="Process" name="submit"> <input type="reset" value="Reset" name="B2"></td>
		  </tr>
		</table>
		
	  </div>
	  <p align="center"><textarea rows="23" id="links" name="links" cols="59"><?php echo $keywords;?></textarea></p>
	  <p>&nbsp;</p>
	</form>
<?php
	}
}

function sc_get_linkvariance(){
ob_start();
	$bulkpage = new linkvariance;
	$bulkpage->sc_linkvariance();
$keywords = ob_get_contents();
ob_end_clean();
return $keywords;
}


function sc_add_linkvariance(){
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/free-tools/link-variance/" target="_blank">This Link Variance Tool was provided by SEO Automatic</a></small>';
	} else {
		$seotools = 'This Link Variance Tool was provided by SEO Automatic';
	}
	return sc_get_linkvariance().$seotools;
}

add_shortcode('link-variance', 'sc_add_linkvariance');


//keyword marriage
class keywordmarriage {
function sc_keywordmarriage(){
	if ($_REQUEST['run'] == "yes") {
	$_REQUEST['keywords'] = preg_replace('/\r\n|\r/', ",", $_REQUEST['keywords']);
	$keywords = explode(',', $_REQUEST['keywords']);
	$_REQUEST['addkeywords'] = preg_replace('/\r\n|\r/', ",", $_REQUEST['addkeywords']);
	$addkeywords = explode(',', $_REQUEST['addkeywords']);
	$_REQUEST['addkeywords2'] = preg_replace('/\r\n|\r/', ",", $_REQUEST['addkeywords2']);
	$addkeywords2 = explode(',', $_REQUEST['addkeywords2']);
	$_REQUEST['addkeywords3'] = preg_replace('/\r\n|\r/', ",", $_REQUEST['addkeywords3']);
	$addkeywords3 = explode(',', $_REQUEST['addkeywords3']);

	if ($_REQUEST['links'] == "ON") { $space = ""; } else { $space = " "; }

	for ($i = 0; $i < count($keywords); $i++) { 
		if ($keywords[$i] == '*keep~going*') {
		} else {
			for ($j = 0; $j < count($addkeywords); $j++) {
				if ($addkeywords[$j] == '*keep~going*') {
				} else {
					for ($k = 0; $k < count($addkeywords2); $k++) {
						if ($addkeywords2[$k] == '*keep~going*') {
						} else {
							for ($l = 0; $l < count($addkeywords3); $l++) {
								if ($addkeywords3[$l] == '*keep~going*') {
									} else {
										if (($_REQUEST['addkeywords2'] != '') && ($_REQUEST['addkeywords3'] == '')) {
											$result = $result . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords2[$k] . "\n";
											if ($_REQUEST['phrase'] == "ON") {
												$result = $result . "\"" . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords2[$k] . "\"\n";
											}
											if ($_REQUEST['exact'] == "ON") {
												$result = $result . "[" . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords2[$k] . "]\n";
											}
										} elseif (($_REQUEST['addkeywords2'] == '') && ($_REQUEST['addkeywords3'] != '')) {
											$result = $result . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords3[$l] . "\n";
											if ($_REQUEST['phrase'] == "ON") {
												$result = $result . "\"" . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords3[$l] . "\"\n";
											}
											if ($_REQUEST['exact'] == "ON") {
												$result = $result . "[". $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords3[$l] . "]\n";
											}
										} elseif (($_REQUEST['addkeywords2'] == '') && ($_REQUEST['addkeywords3'] == '')) {
											$result = $result . $keywords[$i] . $space . $addkeywords[$j] . "\n";
											if ($_REQUEST['phrase'] == "ON") {
												$result = $result . "\"" . $keywords[$i] . $space . $addkeywords[$j] . "\"\n";
											}
											if ($_REQUEST['exact'] == "ON") {
												$result = $result . "[" . $keywords[$i] . $space . $addkeywords[$j] . "]\n";
											}
										} else {
										$result = $result . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords2[$k] . $space . $addkeywords3[$l] . "\n";
										if ($_REQUEST['phrase'] == "ON") {
											$result = $result . "\"" . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords2[$k] . $space . $addkeywords3[$l] . "\"\n";
										}
										if ($_REQUEST['exact'] == "ON") {
											$result = $result . "[" . $keywords[$i] . $space . $addkeywords[$j] . $space . $addkeywords2[$k] . $space . $addkeywords3[$l] . "]\n";
										}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	$result = substr_replace($result,"",-1);
	$result = str_replace("\\","", $result); 
	if ( $result{0} == " " ) { $result = substr($result, 1); }
						}
	?>
<script type="text/javascript">
function uncheck(){
	var x=document.forms.keywordmarriage
	x['phrase'].checked=false
	x['phrase'].disabled= !x['phrase'].disabled
	var y=document.forms.keywordmarriage
	x['exact'].checked=false
	x['exact'].disabled= !x['exact'].disabled
}
</script>
	<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" name="keywordmarriage">
	<input type="hidden" name="run" value="yes" />
	  <div align="center">
		
		<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="454" height="1017">
		  <tr>
			<td width="216" align="center" valign="top">Primary Keyword Phrases</td>
			<td width="24" align="center" valign="top">&nbsp;</td>
			<td width="213" align="center" valign="top">Desired Variables (city, state etc.)</td>
			<td width="4" align="center" valign="top">&nbsp;</td>
		 </tr>
		  <tr>
			<td width="216" align="center" valign="top" height="324">
			<textarea rows="20" id="keywords" name="keywords" cols="24"></textarea></td>
			<td width="24" align="center" valign="top" height="324">&nbsp;</td>
			<td width="213" align="center" valign="top" height="324">
			<textarea rows="20" name="addkeywords" cols="24"></textarea></td>
			<td width="4" align="center" valign="top" height="324">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="216" align="center" valign="top" height="34">
			&nbsp;</td>
			<td width="24" align="center" valign="top" height="34">&nbsp;</td>
			<td width="213" align="center" valign="top" height="34">
			&nbsp;</td>
			<td width="4" align="center" valign="top" height="34">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="216" align="center" valign="top">Desired Variables (city, state etc.)</td>
			<td width="24" align="center" valign="top">&nbsp;</td>
			<td width="213" align="center" valign="top">Desired Variables (city, state etc.)</td>
			<td width="4" align="center" valign="top">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="216" align="center" valign="top" height="324">
			<textarea rows="20" name="addkeywords2" cols="24"></textarea></td>
			<td width="24" align="center" valign="top" height="324">&nbsp;</td>
			<td width="213" align="center" valign="top" height="324">
			<textarea rows="20" name="addkeywords3" cols="24"></textarea></td>
			<td width="4" align="center" valign="top" height="324">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="4" align="left" valign="top" height="221" width="457"><br />
<b>If you're using this for Google Ad Words, you likely want to leave these boxes checked.</b><br />
			<input type="checkbox" name="phrase" value="ON" checked> Add Phrase Match &nbsp;<input type="checkbox" name="exact" value="ON" checked> Add Exact Match <br /><br />
			<input type="checkbox" name="links" value="ON" onclick="uncheck()"> &nbsp;Checking this box CHANGES this tool, so it will generate EXACTLY what you input. Do do not use with the Adwords match types. 
<br /><br />
This option KEEPS any spaces or other characters you may add, like pipes, spaces etc. and will NOT add its own spaces for use in Adwords. 
<br />
 <br /><input type="submit" value="Create List of Keywords" name="submit"> <input type="reset" value="Reset" name="B2"></td>
		  </tr>
		</table>
		
	  </div>
	  <p align="center"><textarea rows="23" id="newkeywords" name="newkeywords" cols="50"><?php print_r ($result);?></textarea></p>
	  <p>&nbsp;</p>
	</form>
<?php
}}

function sc_get_keywordmarriage(){
ob_start();
	$bulkpage = new keywordmarriage;
	$bulkpage->sc_keywordmarriage();
$return = ob_get_contents();
ob_end_clean();
return $return;
}


function sc_add_keywordmarriage(){
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/free-tools/keyword-multiplier/" target="_blank">This Keyword Multiplier was provided by SEO Automatic</a></small>';
	} else {
		$seotools = 'This Keyword Multiplier was provided by SEO Automatic';
	}
	return sc_get_keywordmarriage().$seotools;
}

add_shortcode('keyword-marriage', 'sc_add_keywordmarriage');


//Landing Page Determinator
function sc_get_lpd(){
ob_start();
    require_once(ABSPATH.'wp-content/plugins/seo-automatic-seo-tools/lpd/'.'index.php');
	$bulkpage = new lpd;
	$bulkpage->sc_lpd();
$return = ob_get_contents();
ob_end_clean();
return $return;
}


function sc_add_lpd(){
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/unique-tools/best-page-determinator/" target="_blank">This Landing Page Determinator was provided by SEO Automatic</a></small>';
	} else {
		$seotools = 'This Landing Page Determinator was provided by SEO Automatic';
	}
	return sc_get_lpd().$seotools;
}

add_shortcode('lpd-tool', 'sc_add_lpd');

add_action('admin_menu', 'seo_tools_admin', 1);

function seo_tools_admin() { // Add the menu
	global $menu;
	foreach ($menu as $i) {
		$key = array_search('toplevel_page_seo-automatic-options', $i);
		if ($key != '') {
			$menu_added = true;
		}
	}
	if ($menu_added) {
	} else {
		add_menu_page('SEO Automatic by Search Commander, Inc.', 'SEO Automatic', 'activate_plugins', 'seo-automatic-options', 'seo_tools_home_page',plugins_url() . '/seo-automatic-seo-tools/images/favicon.png');
		add_submenu_page('seo-automatic-options', 'SEO Tools Admin', 'Admin', 'activate_plugins', 'seo-automatic-options', 'seo_tools_home_page');
	}
	add_submenu_page('seo-automatic-options', 'SEO Tools', 'SEO Tools', 'activate_plugins', dirname(__FILE__) . '/settings.php', 'seo_tools_settings_page');
	add_submenu_page(null, '', ' - add tool pages', 'activate_plugins', dirname(__FILE__) . '/add-tool-pages.php');
}
function seo_tools_home_page(){
	include('home.php');
}
function seo_tools_settings_page(){
	include('settings.php');
}

function seo_tools_set_linkback() {
	if (!get_option('seo_tools_linkback_url')) {
		$url = get_option('siteurl');
		update_option('seo_tools_linkback_url', $url);
	}
	if (!get_option('seo_tools_linkback_on')) {
		update_option('seo_tools_linkback_on', 'on');
	}
	if (!get_option('seo_tools_linkback_text')) {
		update_option('seo_tools_linkback_text', 'add RSS feeds to any website');
	}
}

add_action('admin_menu', 'seo_tools_set_linkback');

register_activation_hook(__FILE__,'autoseo_activate');


$directaccess = 'no';

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
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/unique-tools/instant-seo-review/" target="_blank">This URL Review Lite was provided by SEO Automatic</a></small>';
	} else {
		$seotools = 'This URL Review Lite was provided by SEO Automatic';
	}
	if(isset($options['paypal']['require']))
		return seoautomatic_paypal_run($options).$seotools;
	else
		return seoautomatic_run().$seotools;
}
function autoseo_results_shortcode(){
	global $seoresults_tpl;
	$seoresults_tpl = true;
	if(get_option('seo_tools_linkback_seotools') == 'on') {
		$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><small><a href="http://www.seoautomatic.com/unique-tools/instant-seo-review/" target="_blank">This URL Review Lite was provided by SEO Automatic</a></small>';
	} else {
		$seotools = 'This URL Review Lite was provided by SEO Automatic';
	}
	return autoseo_shortcode().$seotools;
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
    add_menu_page('SEO Automatic by Search Commander, Inc.', 'SEO Automatic', 'activate_plugins', 'seo-automatic-options', 'autoseo_home_page',plugins_url() . '/seo-automatic-seo-tools/images/favicon.png');
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

<div class="wrap seoautoreview">
<br />
<div id="dashboard-widgets-wrap">
<div id='dashboard-widgets' class='metabox-holder'>

<div class='postbox-container' style='width:60%;'>
<div id='normal-sortables' class='meta-box-sortables'>

<div id="main-admin-box" class="postbox">
<h3><span><img src="<?php echo plugins_url();?>/seo-automatic-seo-tools/images/favicon.png" alt="SEO Automatic" /> SEO Tool Add-on - URL Review (activate separately)</span></h3>
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
	
<p>You need to edit and personalize your "advice" below, and you can also uncheck each ranking factor if you'd like the report to be shorter.</p>
<p>Individual factor definitions go in the far left box, "positive feedback" result in the center, and negative items are on the the right.&nbsp;</p>
<p>Note that the boxes DO accept .html code.</p>
<p>You may define an area as a "higher priority" item by checking the box to the right of the negative comment section, which will shade those boxes red for your quick identification.</p>
<p>Also, for a few of the factors, such as page size or outbound links, use the small box to the right of each one for editing those variables to a size or quantity that YOU deem to be too large.</p>
<p>To display the tool for the end user, simply place [seotool] within the body of any page or post from the admin / edit screen, USING THE .HTML TAB.</p>
<p>The tool appears on any page where you've placed the [seotool] shortcode, and that's where the results will display.</p>
<p>Please note that sometimes, some URLs (should be under 2%) will simply fail without explanation. We're sorry, but that's the way it is.&nbsp;</p>
<p>Sometimes this is the result of some sort of redirect on the url, which is resolved after a copy / paste out of the address bar.&nbsp;</p>
<p>Other times, different web hosts have their security cranked up, and will block our scanning too for YOUR protection.&nbsp;</p>
<p>Finally, Some failures simply cannot be explained in certain situations - sort of like MS Windows. When that happens, you can usually (and inexplicably) run the report from a second installation on another domain / host of your own.  Go figure.</p>
<p>If you do need help, please feel free to contact Scott Hendison via Twitter @shendison, create a support post at SEO Automatic, or phone 877-241-4453.</p>

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

<?php include('seoauto-sidebar.php'); ?>
<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div></div><!-- wrap -->
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