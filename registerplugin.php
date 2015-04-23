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
	
	$returndata = addNewPlugin($data);
	
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
function getUniqueID($data) {
	
	$file = 'plugins/numbers.json';
	
	$id = readFileContent($file);	
	
	$id['last'] = $id['last'] + 11;
	
	writeFileContent($file, $id);
	
	return $id['last'] . substr($data['plugin']['name'], 0, 5);
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
?>