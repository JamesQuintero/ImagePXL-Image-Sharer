<?php
ob_start();
ini_set('session.cookie_httponly', true);
session_start();
session_cache_limiter();


$key="upload_progress_upload";
if(empty($_SESSION[$key]))
    $percentage=100;
else
{
    $bytes_processed=$_SESSION[$key]['bytes_processed'];
    $content_length=$_SESSION[$key]['content_length'];

    $percentage=number_format(($bytes_processed/$content_length)*100);
}
session_write_close();


$JSON=array();
$JSON['percentage']=$percentage;
echo json_encode($JSON);
exit();