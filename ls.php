<?php

function getFormatSizeUnit($bytes)
{
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;

    if (($bytes >= 0) && ($bytes < $kb)) {
        return $bytes . ' B';
    } 
    elseif (($bytes >= $kb) && ($bytes < $mb)) {
        return round($bytes / $kb, 2) . ' KB';
    } 
    elseif (($bytes >= $mb) && ($bytes < $gb)) {
        return round($bytes / $mb, 2) . ' MB';
    } 
    elseif (($bytes >= $gb) && ($bytes < $tb)) {
        return round($bytes / $gb, 2) . ' GB';
    } 
    elseif ($bytes >= $tb) {
        return round($bytes / $tb, 2) . ' TB';
    } 
    else {
        return $bytes . ' B';
    }
}

function getFolderSize($dir)
{
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
        foreach($dir_array as $key=>$filename)
        {
            if($filename!=".." && $filename!=".")
            {
                if(is_dir($dir."/".$filename))
                {
                    $new_foldersize = getFolderSize($dir."/".$filename);
                    $count_size = $count_size+ $new_foldersize;
                }
                else if(is_file($dir."/".$filename))
                {
                  $count_size = $count_size + filesize($dir."/".$filename);
                  $count++;
                }
            }
        }
    return $count_size;
}

$files = array(); //placeholder for files

//Set directory
// dirname(__FILE__) allows you to get an absolute path (and thus avoid an include path search) without relying on the working directory
//__FILE__ is a native global variable that holds the path of the script that's being executed, and dirname(__FILE__) will return the directory part of that path
//The constant __FILE__ in PHP always returns the absolute path to the script file that’s currently active– the PHP file in which the code line is being run right now. 
//Function dirname() returns the directory part of the given path.

// dirname(__FILE__) equivalent to __DIR__  returns C:\xampp\htdocs\terminal-master

//$directory = dirname(__FILE__) . "/root_directory/"; 
//this code is more efficient version of the above. they are just the same
$directory = __DIR__. "/root_directory/";  //C:\xampp\htdocs\terminal-master/root_directory/


if (isset($_POST['directory'])) 
{ //this is not true if root directory since there is no need to modify.
    $directory .= $_POST['directory']; 
}

//Get files
$files = scandir($directory, SCANDIR_SORT_NONE); //gets the List of files and directories inside the specified path

//Remove . and .. from list
$files = array_diff($files, array(".", ".."));
//array_diff — Computes the difference of arrays = Compares array1 against one or more other arrays and returns the values in array1 that are not present in any of the other arrays.

//Additional attributes
$diskTotalSpace = getFormatSizeUnit(disk_total_space(__DIR__));

$diskFreeSpace = getFormatSizeUnit(disk_free_space (__DIR__));

$diskUsedSpace = getFormatSizeUnit(disk_total_space(__DIR__)-disk_free_space(__DIR__));

$folderSize = getFormatSizeUnit(getFolderSize($directory));

//BONUS: Get additional info
$results = array();
foreach ($files as $file) 
{
    $abs_path = $directory.$file; //C:\xampp\htdocs\terminal-master/root_directory/dog.txt

    $percentage = round(filesize($abs_path) / disk_total_space($directory), 2) . "%";
    //$owner_info = posix_getpwuid(fileowner($abs_path));

    $file_info = array(
        'name' => $file, //dog.txt
        'size' => getFormatSizeUnit(filesize($abs_path)),
        'percentage' => $percentage, //additional info
        'created' => date("Y-m-d H:i:s", filectime($abs_path)),
        'modified' => date("Y-m-d H:i:s", filemtime($abs_path)),
        //'owner' => $owner_info['name'],
        'diskUsedSpace' => $diskUsedSpace,
        'diskFreeSpace' => $diskFreeSpace,
        'folderSize' => $folderSize
    );
    clearstatcache();
    $results[] = $file_info;
    //[] means push - put the given argument as a new element on the end of the array
}
//handling if there are no files, must still pass the disk used space and free space
if(count($results)==0)
{
    $results[] = $diskUsedSpace;
    $results[] = $diskFreeSpace;
    $results[] = $folderSize;
}

echo json_encode($results, JSON_PRETTY_PRINT); //Returns the JSON representation of a value