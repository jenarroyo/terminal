<?php


function deleteDirectory($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
    rmdir($dirPath);
    }
}

function delete_dir($src) { 
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                delete_dir($src . '/' . $file); 
            } 
            else { 
                unlink($src . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
    rmdir($src);
}

function delTree($dir) { 
    $files = array_diff(scandir($dir), array('.', '..')); 

    foreach ($files as $file) { 
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    }

    return rmdir($dir); 
} 

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
            //$success = rmdir($file); //this only deletes empty folder
            delete_dir($file);
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
