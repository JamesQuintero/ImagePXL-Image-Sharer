<?php
@include('init.php');


if(isset($_GET['image_id']))
{
    $username=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['username']), ENT_COMPAT, 'UTF-8'))))));
    $image_id=str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($_GET['image_id']), ENT_COMPAT, 'UTF-8'))))));

    if($image_id!='banner')
    {
        $remoteImage = "http://CLOUDFRONT_ID.cloudfront.net/$username/$image_id";

    
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
                $query=mysql_query("SELECT views, user_id FROM images WHERE image_id='$image_id' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $total_views=(int)($array[0]);
                    $user_id=$array[1];

                    $total_views+=$num_views;

                    $query=mysql_query("UPDATE images SET views=$total_views WHERE image_id='$image_id'");
                    if($query)
                    {
                        //if user doesn't exist
                        if($user_id!='')
                        {
                            //gets num total views for user
                            $query=mysql_query("SELECT image_views FROM user_data WHERE user_id=$user_id LIMIT 1");
                            if($query&&mysql_num_rows($query)==1)
                            {
                                $array=mysql_fetch_row($query);
                                $image_views=(int)($array[0]);

                                $image_views+=$num_views;

                                $query=mysql_query("UPDATE user_data SET image_views=$image_views WHERE user_id=$user_id");
                                if($query)
                                {
                                    //deletes file
                                    unlink($myFile);
                                }
                            }
                        }
                    }
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
}