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
 * Get the data from the version file
 */
function getVersionFile() {
	$file = 'plugins/version.json';

	$content = readFileContent($file);
	
	return $content;
}


/*
 *
 */
function setVersionFile($content) {
	$file = 'plugins/version.json';
	
	writeFileContent($file, $content);
}


/*
 * Add a new plugin
 */
function addNewPlugin($data) {
	$content = getVersionFile();
	
	$id = getUniqueID($data);
	
	$info['name'] = $data['plugin']['name'];
	$info['description'] = $data['plugin']['description'];
	$info['version'] = $data['plugin']['version'];
	$info['developer'] = $data['developer']['name'];	
	
	$content['server'][$id] = $info;
	
	$data['id'] = $id;
	
	setVersionFile($content);
}


/*
 * Update version
 */
function setVersion($id, $version) {
	$content = getVersionFile();
	
	$curVersion = $content['server'][$id]['version'];
	
	if ($curVersion >= $version) {
		output(true, null, 'There is already a newer version available (' . $curVersion. ')');
	}
	
	$content['server'][$id]['version'] = $version;
	
	setVersionFile($content);
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