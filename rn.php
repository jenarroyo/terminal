<?php

//renamed the root file_directory to --> root_directory to be more descriptive.

function incrementFileName($file_path,$filename){
  $array = explode(".", $filename);
  $file_ext = end($array);
  $root_name = str_replace(('.'.$file_ext),"",$filename);
  $file = $file_path.$filename;
  $i = 1;
  while(file_exists($file)){
    $file = $file_path.$root_name.'('.$i.')'.'.'.$file_ext;
    $i++;
  }
  return $file;
}

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";



if (isset($_POST['currentDirectory'])) {
    $directory .= $_POST['currentDirectory'];
}


$success = false;
if (isset($_POST['old_file']) && isset($_POST['new_file'])) {

    $old_file = $directory . $_POST['old_file'];
    $new_file = $directory . $_POST['new_file'];

    if (file_exists($old_file)) {

        if(file_exists($new_file)){
            $newfileName = incrementFileName($directory, $_POST['new_file']);
            $success = copy($old_file, $newfileName);
            //unlink or remove the old file
            $success = unlink($old_file);
            $success = -2; //made a new copy instead
            //modify date modified only
            touch($new_file);
        }
        else {
            $success = rename($old_file, $new_file); //renaming successful
            //modify date modified only
            touch($new_file);
        }
    }
    else {
        //create a new empty file
        $new_file = fopen($old_file, "w");
        $success = -3;  //old file does not exist
    }

}

echo json_encode($success);





