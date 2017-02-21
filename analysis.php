<?php
include 'db.php';
extract($_POST);
$db = new db();
$db->insert_photo($filename, $datetime, $filesize, $width, $height);
shell_exec();
$result = shell_exec('workon cv && python analysis_image.py 192.168.1.100/picam '.$filename);
?>