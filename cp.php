<?php 

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";
//get data from POST

 if (isset($_POST['file']) && isset($_POST['sourceDirFile']) && isset($_POST['destDirFile'])) {
    $sourceDirFile = $_POST['sourceDirFile'];
    $destDirFile = $_POST['destDirFile'];
    $fileToBeCopied = $_POST['file'];
}

$success = false;
//must check the following before formulating long form directory

//check if source param is a directory or a file existing
//check first if the dest Dir is root:
if($sourceDirFile{0} =="/"){
    $sourceDirFile = substr($sourceDirFile, 1); //remove the slash if it is root.

}

if(is_file($directory . $sourceDirFile)){
    $success = true;
    $sourceDirFile = $directory . $sourceDirFile;

}
else if(is_dir($directory . $sourceDirFile)){
    $success = -2; //source param is a directory
}
else{
    $success = -3; //file to be copied does not exist
}


if($success===true){
    //check if dest param has an existing directory
    //check first if the dest Dir is root:
    if($destDirFile{0} =="/"){
        $destDirFile = substr($destDirFile, 1); //remove the slash if it is root.
    }

    $destDir = $directory . substr($destDirFile, 0, strrpos($destDirFile,"/"));

    if(is_dir($destDir)){
        if(is_dir($directory . $destDirFile)){ //check if the input is a directory itself

            $success = -4; //destination is a directory
        }
        else if(is_file($directory . $destDirFile)){
            //duplicate the file
            $newFileName = "Copy of " . $fileToBeCopied;
            $destDirFile = $destDir . "/" .  $newFileName;

            $success =copy($sourceDirFile, $destDirFile);

        }
        //file does not exist in dest directory
        else {
            $destDirFile = $directory . $destDirFile;
            $success = copy($sourceDirFile, $destDirFile);

        }
    }
    else {
        $success = -5; //destination is not an existing directory
    }
}

echo json_encode($success);