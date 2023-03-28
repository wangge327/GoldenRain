<?php

include_once 'vendor/autoload.php';

use Simcify\Application;
use Simcify\Database;
use Simcify\Mail;

define("EmailLog",$_SERVER['DOCUMENT_ROOT']."/reminder.log");

$app = new Application();

//$today = date("Y-m-d");
//$current_time = date("Y-m-d H:i:s");

$dt = new DateTime("now", new DateTimeZone('America/New_York'));
$current_time = $dt->format('Y-m-d H:i:s');


$folders = scandir('uploads/hosts', SCANDIR_SORT_DESCENDING);
$folders = array_values(array_diff($folders, [".", ".." , ".DS_Store"]));

foreach($folders as $folder){
    $folder_path = 'uploads/hosts/' . $folder;

    if(is_file($folder_path)){
        continue;
    }

    delete_old_files($folder_path);
    delete_last_files($folder_path);
}

function delete_old_files($file_path){
    $files = scandir($file_path, SCANDIR_SORT_DESCENDING);
    $files = array_values(array_diff($files, [".", ".." , ".DS_Store"]));
    usort($files, 'strnatcmp');
    if(count($files) < 10)
        return false;

    for($i = 0 ; $i < count($files)-10 ; $i++){
        delete_file($file_path . "/" . $files[$i]);
    }
}

function delete_last_files($file_path){
    global $current_time;
    $files = scandir($file_path, SCANDIR_SORT_DESCENDING);
    $files = array_values(array_diff($files, [".", ".." , ".DS_Store"]));
    usort($files, 'strnatcmp');

    for($i = 0 ; $i < count($files) ; $i++){
        $full_file_path = $file_path . "/" . $files[$i];
        $file_time = date ("Y-m-d H:i:s", filemtime($full_file_path));
        $compare_file_time = date('Y-m-d H:i:s', strtotime($file_time. ' +  1 hours'));
        if($current_time > $compare_file_time){
            echo $current_time."--".$compare_file_time."<br>";
            //delete_file($full_file_path);
        }
    }
}

function delete_file($file_pointer){
    if (!unlink($file_pointer)) {
        echo ("$file_pointer cannot be deleted due to an error");
    }
    else {
        echo ("$file_pointer has been deleted.<br/>");
    }
}