<?php
@include('init.php');


if(isset($_GET['image_id']))
{
    $image_id=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['image_id']), ENT_COMPAT, 'UTF-8'))))));

    
        //cloudfront CDN for imagepxl.images S3 bucket
        $remoteImage = "http://d3jfgmuje0a9yk.cloudfront.net/unassociated/$image_id";

    
        //if trying to display image
        if(strpos($image_id, '.')!==false)
        {
            $explode=explode('.', $image_id);
            $image_id=$explode[0];
            //counts view
            $myFile = "./views/".$image_id.".txt";
            $contents=file_get_contents($myFile);
            $contents=explode('|', $contents);

            //if new file
            if($contents[0]=="")
                $contents[0]=time();

            //updates every hour
            if(time()-$contents[0]>=3600)
            {
                //gets num views from file
                $num_views=sizeof($contents)-1;

                //gets num views for image
                $query=mysql_query("SELECT views FROM images WHERE image_id='$image_id' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $total_views=(int)($array[0]);

                    $total_views+=$num_views;

                    mysql_query("UPDATE images SET views=$total_views WHERE image_id='$image_id'");
                }
            }
            else
            {
                //if haven't viewed before
                if(array_search($_SERVER['REMOTE_ADDR'], $contents)===false)
                    $contents[]=$_SERVER['REMOTE_ADDR'];

                $contents=implode("|", $contents);
                file_put_contents($myFile, $contents);
            }
//            echo $contents."\n";
//            echo $myFile;
            $imginfo = getimagesize($remoteImage);
            header("Content-type: $imginfo[mime]");
            readfile($remoteImage);
        }
        else
        {
            header("Location: http://imagepxl.com/$image_id");
            exit();
        }
}
?>