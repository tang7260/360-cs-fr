<?php

include 'db.php';

$db = new db();
$json_array = $db->get_video_report_list();
$db->close();
echo json_encode($json_array);
