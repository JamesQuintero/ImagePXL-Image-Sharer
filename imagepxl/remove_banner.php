<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

//gets all the necessary AWS schtuff
if (!class_exists('S3'))
    require_once('S3.php');
if (!defined('awsAccessKey'))
    define('awsAccessKey', ACCES_KEY);
if (!defined('awsSecretKey'))
    define('awsSecretKey', SECRET_KEY);

//creates S3 item with schtuff
$s3 = new S3(awsAccessKey, awsSecretKey);


$query=mysql_query("UPDATE customization SET banner_top=0, has_banner='false' WHERE user_id=$_SESSION[id]");
if($query)
{
    $path=$_SESSION['username']."/banner.jpg";
    $s3->deleteObject("imagepxl.images", $path);
}
else
{
    echo "Something went wrong. We are working on fixing it";
    send_mail_error("remove_banner.php: (1): ", mysql_error());
}
?>