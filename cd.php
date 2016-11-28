<?php
//renamed the root file_directory to --> root_directory to be more descriptive.

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";

if (isset($_POST['directory'])) {   
    $directory .= $_POST['directory'];  
}


$success = false;
if (isset($_POST['file'])) {

    $file = $directory . $_POST['file'];

    if (file_exists($file)) {

        if (is_dir($file)) {
            $success = true;
        }
        
    }
}

echo json_encode($success);





