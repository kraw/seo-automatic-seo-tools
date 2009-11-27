<?php
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
if(isset($_POST['download'])){
	$urls = $_POST['download'];
	$urls2 = explode(' ', $urls);
	$urllisttext = $urls;

	foreach ($urls2 as $url){
		      $url = trim($url);
			  if ($url != ''){
			  $status = getHttpResponseCode($url);
	  if (!preg_match('/:|:/', $status)){
	$data[] = array("url" => $url , "status" => $status, "Redirect" => "");
} else {
	$status2 = explode(':|:', $status);
	$data[] = array("url" => $url , "status" => $status2[0] , "Redirect" => $status2[1]);	
}
}
}


$filename = "404check_" . date('Ymd') . ".xls"; 

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
		



$flag = false; 
foreach($data as $row) { 
	if(!$flag) { 
		# display field/column names as first row 
		echo implode("\t", array_keys($row)) . "\n"; $flag = true;
	}
array_walk($row, 'cleanData');
echo implode("\t", array_values($row)) . "\n"; } 
exit;

}
?>