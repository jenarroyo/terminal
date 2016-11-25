<?php

//Set directory
$directory = dirname(__FILE__) . "/file_directory/";
if (isset($_POST['directory'])) {
    $directory .= $_POST['directory'];
}


$success = false;
if (isset($_POST['file'])) {

    $file = $_POST['file'];
    if (file_exists($directory . $file)) {
        $success = unlink($directory . $file);
    }
}

echo json_encode($success);





