<?php

require_once('general.php');

$pluginid = $_POST['pluginid'];
$pluginname = $_POST['pluginname'];
$version = $_POST['pluginversion'];
$developer = $_POST['developername'];
$key = $_POST['developerkey'];

$pluginpath = '/home/cabox/workspace/home-automation-server/plugins/';
$targetfolder = $pluginid;
$targetfile = $version . '.tar.gz';

$target = $pluginpath . $targetfolder . '/' . $targetfile;

if (file_exists($target)) {
	output(true, null, 'This version has already been uploaded!');
}

move_uploaded_file($_FILES['plugin']['tmp_name'], $target);

output(false, 'Upload of ' . $pluginname . ' (' . $version . ') succesfull!', null);

?>