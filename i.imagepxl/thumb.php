<?php
@include('init.php');


if(isset($_GET['username'])&&isset($_GET['image_id']))
{
    $username=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['username']), ENT_COMPAT, 'UTF-8'))))));
    $image_id=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['image_id']), ENT_COMPAT, 'UTF-8'))))));

    //cloudfront CDN for bucket_name S3 bucket
    $remoteImage = "http://CLOUDFRONT_ID.cloudfront.net/$username/thumbs/$image_id";
    $imginfo = getimagesize($remoteImage);
    header("Content-type: $imginfo[mime]");
    readfile($remoteImage);
    
}