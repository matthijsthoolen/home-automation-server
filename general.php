<?php
/* 
 *
 */
function output($error, $stdout, $stderr) {
	$json = array();
	
	$json['err'] = $error;
	$json['stdout'] = $stdout;
	$json['stderr'] = $stderr;
	//$json['data']['id'] = 1;

	header('Content-type: application/json');
	
	echo json_encode($json);
	exit;
}

?>