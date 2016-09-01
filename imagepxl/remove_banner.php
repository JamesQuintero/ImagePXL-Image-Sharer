<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

include("requiredS3.php");


$query=mysql_query("UPDATE customization SET banner_top=0, has_banner='false' WHERE user_id=$_SESSION[id]");
if($query)
{
    $path=$_SESSION['username']."/banner.jpg";
    $s3->deleteObject("bucket_name", $path);
}
else
{
    echo "Something went wrong. We are working on fixing it";
    log_error("remove_banner.php: (1): ", mysql_error());
}