<?php 
// RUN A C PROGRAM
// using execute function

//Set directory
$directory = dirname(__FILE__) . "/root_directory/";

if (isset($_POST['file'])) 
{
    $file .= $_POST['file'];
}

$success = true;

// wala pa laman
//need to get the c file
// var_dump($_POST);

// if (isset($_POST['file'])) 
// {
//     //get the "file" from the directory
//     $file = $directory . $_POST['file'];

//     $output = shell_exec($file);
//     return $output;

// }
// var_dump($output);
// print($output);


// testing output
    // $output = shell_exec('hello.c');
    $output = exec('hello.c');
    // echo $output;
    var_dump($output);
    // printf($output);

    // echo '<pre>';
    // print_r($output);
    // echo '</pre>';

echo json_encode($success);

// references
// Execute command via shell and return the complete output as a string
// http://stackoverflow.com/questions/5555912/call-a-c-program-from-php-and-read-program-output