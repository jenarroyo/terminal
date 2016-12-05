<?php
//renamed the root file_directory to --> root_directory to be more descriptive.

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";

if (isset($_POST['currentDirectory']) || ($_POST['currentDirectory'] ===null))
{
    $directory .= $_POST['currentDirectory']; //make the directory to be long form
}

$success = false;
if (isset($_POST['newDirectory']))
{   //check if it is not null.
    $directory .= $_POST['newDirectory']; //make the directory to be long form

    if (is_dir($directory))
    { //check if it is a directory
        $success = true;
    }
}

echo json_encode($success);