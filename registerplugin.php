<?php

//$developerkey = $_REQUEST['key'];
//$plugininfo = $_REQUEST['info'];

start();


/*
 *
 */
function start() {
	$data = getData();
	$returndata = getVersionFile($data);
	
	//TODO: CHECK IF PLUGIN ALREADY EXISTS!
	
	//TODO: make downloadfolder with unique id
	
	output(false, $returndata, null);
}


/*
 *
 */
function getData() {
	$data = json_decode(file_get_contents('php://input'), true);
	
	if (!is_array($data)) {
		output(true, null, 'JSON is invalid');
		return;
	}
	
	return $data;
}


/*
 *
 */
function getVersionFile($data) {
	$file = 'plugins/version.json';

	$content = readFileContent($file);
	
	$id = getUniqueID($data);
	
	$info['name'] = $data['plugin']['name'];
	$info['description'] = $data['plugin']['description'];
	$info['version'] = $data['plugin']['version'];
	$info['developer'] = $data['developer']['name'];	
	
	$content[$id] = $info;
	
	$data['id'] = $id;
	
	writeFileContent($file, $content);
	
	return $data;
}


/*
 * 
 */
function getUniqueID($data) {
	
	$file = 'plugins/numbers.json';
	
	$id = readFileContent($file);	
	
	$id['last'] = $id['last'] + 11;
	
	writeFileContent($file, $id);
	
	return $id['last'] . substr($data['plugin']['name'], 0, 5);
}


/* 
 *
 */
function output($error, $stdout, $stderr) {
	$json = array();
	
	$json['error'] = $error;
	$json['data'] = $stdout;
	$json['stderr'] = $stderr;
	//$json['data']['id'] = 1;

	echo json_encode($json);
	exit;
}


/*
 * Read from file, checks if is readable and if the json is an array. 
 * @param {String} filename
 * @return {json}
 */
function readFileContent($filename) {
	
	if (!is_readable($filename)) {
		output(true, null, 'File is not accessible.');
	}
	
	$content = json_decode(file_get_contents($filename), true);
	
	if (!is_array($content)) {
		output(true, null, 'JSON in file is invalid');
		return;
	}
	
	return $content;
}


/*
 * write to file, checks if is readable and if the json is an array. 
 * @param {String} filename
 * @return {json}
 */
function writeFileContent($filename, $content) {
	if (!is_writable($filename)) {
		output(true, null, 'Can\'t write to version file');
	}
	
	file_put_contents($filename, json_encode($content));
	
	return;
}
?>