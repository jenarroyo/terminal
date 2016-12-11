<?php 

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
//get data from POST

 if (isset($_POST['file']) && isset($_POST['sourceDirFile']) && isset($_POST['destDirFile']) && isset($_POST['currentDirectory'])) {
    $currentDirectory = $_POST['currentDirectory'];
    $sourceDirFile = $_POST['sourceDirFile'];
    $destDirFile = $_POST['destDirFile'];
    $fileTobeMoved = $_POST['file'];
}

$success = false;
//must check the following before formulating long form directory
//check the characteristics of source param:
$sourceSlashIndex = strpos($sourceDirFile, "/");
if($sourceSlashIndex===0) { //
    $sourceDirFile = substr($sourceDirFile, 1); //remove the slash if it is root.
}

if($sourceSlashIndex<0) {
    //know if it is a file or directory
    if(is_file($directory . $currentDirectory . $sourceDirFile)){
        $sourceDirFile = $directory . $currentDirectory . $sourceDirFile;
    }
    else if(is_dir($directory . $currentDirectory . $sourceDirFile)){
        $success = -2; //source param is not a file. it is a directory
    }
}
else {
    //check all combinations
    if(is_file($directory . $sourceDirFile)){
        $success = true;
        $sourceDirFile = $directory . $sourceDirFile;

    }
    else if(is_dir($directory . $sourceDirFile)){
        $success = -2; //source param is a directory
    }
    else if(is_file($directory . $currentDirectory . $sourceDirFile)){
        $success = true;
        $sourceDirFile = $directory . $sourceDirFile;

    }
    else if(is_dir($directory . $currentDirectory . $sourceDirFile)){
        $success = -2; //source param is a directory
    }
    else{
        $success = -3; //file to be moved does not exist
    }

}


if($success===true){

    $destSlashIndex = strpos($destDirFile, "/");
    if($destSlashIndex===0) { //
        $destDirFile = substr($destDirFile, 1); //remove the slash if it is root.
    }
    else if($destSlashIndex<0) {
        $success = -4;
        //invalid because:
        //if dest param is a directory only: filename is not specified (E.g. nested)
        //if dest param is a file only, directory to move is not specified (E.g. gat.txt)
    }

    //get the directory of where to move the file:
    //2 possibilities

    if(is_dir($directory . substr($destDirFile, 0, strrpos($destDirFile,"/")))){

         $destDir = $directory . substr($destDirFile, 0, strrpos($destDirFile,"/"));

         if(is_dir($directory . $destDirFile)){ //check if the input is a directory itself (e.g. nested/f.1)
            $success = -5; //destination is a valid directory
        }
        else if(is_file($directory . $destDirFile)){
            $destDir = $destDir . "/";
            //file exists so do not move the file.
            //duplicate the file
            $destDirFile = incrementFileName($destDir, $fileTobeMoved);
            $success = rename($sourceDirFile, $destDirFile);
            $success = -6;
        }
        //file does not exist in dest directory
        else {
            $destDirFile = $directory . $destDirFile;
            $success = rename($sourceDirFile, $destDirFile);
            touch($destDirFile);
        }
    }
    else if (is_dir($directory . $currentDirectory . substr($destDirFile, 0, strrpos($destDirFile,"/")))) {

         $destDir = $directory . $currentDirectory . substr($destDirFile, 0, strrpos($destDirFile,"/"));

         if(is_dir($directory . $currentDirectory . $destDirFile)){ //check if the input is a directory itself (e.g. nested/f.1)
            $success = -5; //destination is a valid directory
        }
        else if(is_file($directory . $currentDirectory . $destDirFile)){
            $destDir = $destDir . "/";
            //file exists so do not move the file.
            //duplicate the file
            $destDirFile = incrementFileName($destDir, $fileTobeMoved);
            $success =rename($sourceDirFile, $destDirFile);
            $success = -6;
        }
        //file does not exist in dest directory
        else {
            $destDirFile = $directory . $currentDirectory . $destDirFile;
            $success = rename($sourceDirFile, $destDirFile);
            touch($destDirFile);
        }
    }
    else {
        $success = -4; //destination is not an existing directory
    }
}



echo json_encode($success);