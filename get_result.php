<?php
include 'db.php';

$name = '';
$video_position = '';
$type = '';
$jsonarray = array();

if (isset($_GET["type"]) && isset($_GET["filename"])){
    $db = new db();
    $name = $_GET["filename"];
    $type = $_GET["type"];
    if($type == 'image'){
        $jsonarray = $db->get_image_result($name);
    }else if ($type == 'video'){
        if(isset($_GET["video_position"])){
            $video_position = $_GET["video_position"];
            $jsonarray = $db->get_video_lpos($name,$video_position);
            
        }else{
            $jsonarray = $db->get_video_result($name);
        }
    }
    $db->close();
}

echo json_encode($jsonarray);
?>