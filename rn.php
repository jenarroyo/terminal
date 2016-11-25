<?php

//Set directory
$directory = dirname(__FILE__) . "/file_directory/";
if (isset($_POST['directory'])) {
    $directory .= $_POST['directory'];
}


$success = false;
if (isset($_POST['old_file']) && isset($_POST['new_file'])) {

    $old_file = $_POST['old_file'];
    $new_file = $_POST['new_file'];

    if (file_exists($old_file) && file_exists($new_file)) {
        $success = rename(
            $directory . $_POST['old_file'], 
            $directory . $_POST['new_file']
        );
    }
}

echo json_encode($success);





