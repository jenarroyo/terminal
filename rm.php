<?php

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";


if (isset($_POST['directory'])) {
    $directory .= $_POST['directory'];
}


$success = false;
if (isset($_POST['file'])) 
{
    //get the "file" from the directory
    $file = $directory . $_POST['file']; //add the last part to the directory

    // if the file or directory exists
    if (file_exists($file)) {
        //is the file a directory?
        if (is_dir($file)){ 
            //remove directory or folder

            $success = rmdir($file); //this only deletes empty folder
            $success = 2;
        }
        else {
            //remove file itself

            $success = unlink($file);
            clearstatcache(); 
        }
        
    }
}

echo json_encode($success);
