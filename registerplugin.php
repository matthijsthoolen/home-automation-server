<?php

//$developerkey = $_REQUEST['key'];
//$plugininfo = $_REQUEST['info'];

require_once('general.php');

start();


/*
 *
 */
function start() {
	
	$data = getData();
	
	$returndata = getVersionFile($data);
	
	$returndata = createFolder($returndata);
	//TODO: CHECK IF PLUGIN ALREADY EXISTS!
	
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
 * Read from file, checks if is readable and if the json is an array. 
 * @param {String} filename
 * @return {json}
 */
function readFileContent($filename) {
	
	if (!is_readable($filename)) {
		output(true, null, 'File is not accessible. File:' . $filename);
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
		output(true, null, 'Can\'t write to file: ' . $filename);
	}
	
	file_put_contents($filename, json_encode($content));
	
	return;
}


/*
 * Create a new folder for the plugin
 *
 * @param {array} data
 */
function createFolder($data) {
	$id = $data['id'];
	
	$path = '/home/cabox/workspace/home-automation-server/plugins/';
	
	if (!checkPermission($path, '0777', true)) {
		output(true, null, 'We can not create a folder for the plugin due to permission problems');
	}
	
	$newfolder = $path . $id;
	
	if (!is_dir($newfolder)) {
		try {
    		mkdir($newfolder, 0777, true);
		} catch (Exception $e) {
			output(true, null, $e);
		}
	}
	
	$data['folder'] = $id;
	
	return $data;
}


/*
 * Check the permission of a file or item and try to fix it
 *
 * @param {string} path
 * @param {permission} permission
 * @param {boolean} fix: try to fix it? (default false)
 */
function checkPermission($path, $permission, $fix = false) {
	
	$current = substr(sprintf('%o', fileperms($path)), -4);
	
	//Check if equals
	if ($permission !== $current) {		
		return false;
	}
	
	return true;	
	
} 
?>