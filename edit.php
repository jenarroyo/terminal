<?php

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";

//get data from post:
if (isset($_POST['file']) && isset($_POST['mode']) && isset($_POST['currentDirectory'])) {
    $mode = $_POST['mode'];
    $file = $_POST['file'];
    $currentDirectory = $_POST['currentDirectory'];
}

$fileContents = "";
if(is_file($directory . $file)){
	$directory = $directory . $file;
	if($mode==1) {
		$fileContents = file_get_contents($directory,FILE_USE_INCLUDE_PATH);
	}

	else if($mode==3 && isset($_POST['content'])) {
		$content = $_POST['content'];
		$fileContents = file_put_contents($directory,$content);
	}

}
else if (is_file($directory . $currentDirectory . $file)){
	$directory = $directory . $currentDirectory . $file;
	if($mode==1) {
		$fileContents = file_get_contents($directory,FILE_USE_INCLUDE_PATH);
	}
	else if($mode==3 && isset($_POST['content'])) {
		$content = $_POST['content'];
		$fileContents = file_put_contents($directory,$content);
	}

}

echo json_encode($fileContents);