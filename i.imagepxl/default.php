<?php
@include('init.php');

if(isset($_GET['first']))
{
    $first=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['first']), ENT_COMPAT, 'UTF-8'))))));
    if(isset($_GET['second']))
        $second="/".str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['second']), ENT_COMPAT, 'UTF-8'))))));
    else
        $second="";

    //cloudfront CDN for bucket_name S3 bucket
    $remoteImage = "http://CLOUDFRONT_ID.cloudfront.net/default/".$first.$second;
    $imginfo = getimagesize($remoteImage);
    header("Content-type: $imginfo[mime]");
    readfile($remoteImage);
    
}