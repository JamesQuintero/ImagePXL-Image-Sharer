<?php
@include('init.php');


if(isset($_GET['image_id']))
{
    $image_id=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['image_id']), ENT_COMPAT, 'UTF-8'))))));

    //cloudfront CDN for imagepxl.images S3 bucket
    $remoteImage = "http://d3jfgmuje0a9yk.cloudfront.net/unassociated/thumbs/$image_id";
    $imginfo = getimagesize($remoteImage);
    header("Content-type: $imginfo[mime]");
    readfile($remoteImage);
    
}
else
    echo "Got here";
?>