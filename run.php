<?php 
include 'ChromePhp.php';
// RUN A C PROGRAM
// using execute function
ChromePhp::log("Run Entrance.");
//Set directory
$directory = dirname(__FILE__) . "/root_directory/";

$success = false;
$output = null;
if (isset($_POST['file']) && isset($_POST['currentDirectory'])) {

	if(is_dir($directory . $_POST['currentDirectory'] . $_POST['file'])) {
        $directory = $directory . $_POST['currentDirectory'] . $_POST['file'];
        ChromePhp::log("Input is a directory1: $directory");
        $success = false;
    }
    else if (is_file($directory . $_POST['currentDirectory'] . $_POST['file'])) {
        $directory = $directory . $_POST['currentDirectory'] . $_POST['file'];
        ChromePhp::log("Input is a file1: $directory");
        $output = exec($directory);
        $success = true;
    }
    else if (is_dir($directory . $_POST['file'])) {
        $directory = $directory . $_POST['file'];
        ChromePhp::log("Input is a directory2: $directory");
        $success = false;
    }
    else if (is_file($directory . $_POST['file'])) {
        $directory = $directory . $_POST['file'];
        ChromePhp::log("Input is a file2: $directory.");
        $output = exec($directory);
        $success = true;
    }
    else {
        ChromePhp::log("File is not found");
        $success = false; //file is not found.
    }

}

echo json_encode($success);
