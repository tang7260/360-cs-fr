<?php
include 'db.php';
extract($_POST);

$fs_url = 'http://localhost:5000/image';
$vars = 'src_url=' . $file_url . '&filename=' . $filename . '&thimg=' . $thimg;

$ch = curl_init( $fs_url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );

echo $response;
?>