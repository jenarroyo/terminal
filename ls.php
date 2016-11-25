<?php
$files = array();

//Set directory
$directory = dirname(__FILE__) . "/file_directory/";
if (isset($_POST['directory'])) {
    $directory .= $_POST['directory'];
}

//echo shell_exec("ls -lU " . $directory);

//Get files
$files = scandir($directory);


//Remove . and .. from list
$files = array_diff($files, array(".", ".."));

//BONUS: Get additional info
$results = array();
foreach ($files as $file) {
    $abs_path = $directory.$file;
    // $owner_info = posix_getpwuid(fileowner($abs_path));
    $file_info = array(
        'name' => $file,
        'size' => filesize($abs_path),
        'created' => date("Y-m-d H:i:s", filectime($abs_path)),
        'modified' => date("Y-m-d H:i:s", filemtime($abs_path)),
        // 'owner' => $owner_info['name']
    );

    $results[] = $file_info;
}

echo json_encode($results);





