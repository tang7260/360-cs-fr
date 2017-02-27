<?php
include 'db.php';
extract($_POST);
$db = new db();
//$db->insert_photo($filename, $datetime, $filesize, $width, $height);
//$result = shell_exec("/usr/local/bin/workon cv && /usr/bin/python analysis_image.py dev16.asuscomm.com/picam ".$filename);
$result = shell_exec("python analysis_image.py 192.168.1.124/picam ".$filename. " 2>&1 1> /dev/null");
echo $result;
?>