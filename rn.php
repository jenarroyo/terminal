<?php
//renamed the root file_directory to --> root_directory to be more descriptive.

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";
if (isset($_POST['directory'])) {
    $directory .= $_POST['directory'];
}


$success = false;
if (isset($_POST['old_file']) && isset($_POST['new_file'])) {

    $old_file = $directory . $_POST['old_file'];
    $new_file = $directory . $_POST['new_file'];

    if (file_exists($old_file)) {
        $success = rename(
            $old_file,
            $new_file
        );
    }

}

echo json_encode($success);





