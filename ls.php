<?php
//added this function:  get file size unit
//renamed the root file_directory to --> root_directory to be more descriptive.
//added percentage of file computation

function getFileSizeUnit($number) {
    $fileSize = $number;
    $sizeUnit = "b";
    //conversion method
    if($number>1023 && $number<1048576) {
        $fileSize = round($number/1024, 2);
        $sizeUnit = "KB";
    }
    else if($number>1048575 && $number<1073741823) {
        $fileSize = round($number/1048576, 2);
        $sizeUnit = "MB";   
    }
    else if($number>1073741823) {
        $fileSize = round($number/1073741824, 2);
        $sizeUnit = "GB";   
    }

    return $fileSize . " " . $sizeUnit;
}


$files = array();

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";

if (isset($_POST['directory'])) {
    $directory .= $_POST['directory'];
}

//Get files
$files = scandir($directory);


//Remove . and .. from list
$files = array_diff($files, array(".", ".."));


//BONUS: Get additional info
$results = array();
foreach ($files as $file) {
    $abs_path = $directory.$file;

    $percentage = round(filesize($abs_path) / disk_total_space($directory), 2) . "%";
    //$owner_info = posix_getpwuid(fileowner($abs_path));

    $file_info = array(
        'name' => $file,
        'size' => getFileSizeUnit(filesize($abs_path)),
        'percentage' => $percentage, //additional info
        'created' => date("Y-m-d H:i:s", filectime($abs_path)),
        'modified' => date("Y-m-d H:i:s", filemtime($abs_path)),
        //'owner' => $owner_info['name']
    );

    $results[] = $file_info;
}

echo json_encode($results);





