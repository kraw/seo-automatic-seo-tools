<?php
/*
Plugin Name: SEO Automatic SEO Tools 
Plugin URI: http://www.seoautomatic.com/plugins/unique-seo-tools/
Description: Unique SEO tools for your visitors or employees to perform repetetive tasks efficiently, or to otherwise save time.  Created by Search Commander, Inc. for free distribution. <br />See <a href="?page=seo-automatic-options">SEO Automatic</a> > <a href="?page=seo-tools/settings.php">SEO Tools</a> for options. 
Version: 1.1
Author: Heather Barger
Author URI: http://www.plugin-central.org
*/

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
	$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><a href="http://www.seoautomatic.com/free-tools/bulk-url-checker/" target="_blank">Bulk Url Checker by SEO Automatic</a>';
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
	$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><a href="http://www.seoautomatic.com/free-tools/feedcommander/" target="_blank">Feed Commander by SEO Automatic</a>';
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
		 
		 
		   foreach( $list1 as $word1 ) {		 
			  foreach( $list2 as $word2 ) {		 		 
					$keywords = $keywords ."\n". '<a href="' . trim($word1) . '"' . $rel . $newwin . '>' . trim($word2) . '</a>';
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
			<td colspan="7" align="center" valign="top"><br />
			<input type="checkbox" name="nofollow" value="ON" checked> Add nofollow  &nbsp;<input type="checkbox" name="newtab" value="ON" checked> Open Links in New Window  
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
	$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><a href="http://www.seoautomatic.com/free-tools/link-variance/" target="_blank">Link Variance Tool by SEO Automatic</a>';
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
	$seotools = '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><a href="http://www.seoautomatic.com/free-tools/keyword-multiplier/" target="_blank">Keyword Multiplier by SEO Automatic</a>';
	return sc_get_keywordmarriage().$seotools;
}

add_shortcode('keyword-marriage', 'sc_add_keywordmarriage');

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
		add_menu_page('SEO Automatic by Search Commander, Inc.', 'SEO Automatic', 'activate_plugins', 'seo-automatic-options', 'seo_tools_home_page','http://www.seoautomatic.com/favicon.ico');
		add_submenu_page('seo-automatic-options', 'SEO Tools Admin', 'Admin', 'activate_plugins', 'seo-automatic-options', 'seo_tools_home_page');
	}
	add_submenu_page('seo-automatic-options', 'SEO Tools', 'SEO Tools', 'activate_plugins', dirname(__FILE__) . '/settings.php', 'seo_tools_settings_page');
}
function seo_tools_home_page(){
	include('home.php');
//	$url="http://www.seoautomatic.com/plugin-home/index.php"; 
//        $ch = curl_init($url); 
//        curl_setopt($ch, CURLOPT_HEADER, 0); 
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//        $results=curl_exec($ch); 
//        curl_close($ch); 
//        print("$results"); 
}
function seo_tools_settings_page(){
	include('settings.php');
}
?>