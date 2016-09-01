<?php
@include('init.php');

//updated viewcount


$files=scandir("./views");

//gets rid of "." and ".."
unset($files[0]);
unset($files[1]);
$files = array_values($files);

print_r($files);


$final_files=array();
for($x = 0; $x < sizeof($files); $x++)
{
    //if stored incorrect image_id
    if(strlen($files[$x])==11)
    {
        $image_id=substr($files[$x], 0, strlen($files[$x])-4);
        
        $contents=file_get_contents("./views/".$files[$x]);
        $contents=explode('|', $contents);
        
        //if older than 1 hour
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
                            unlink("./views/".$files[$x]);
                        }
                    }
                    else if(mysql_num_rows($query)==0)
                        unlink("./views/".$files[$x]);
                }
            }
            else if(mysql_num_rows($query)==0)
                unlink("./views/".$files[$x]);
        }
        else
            $final_files[]=$files[$x];
    }
    else
        unlink("./views/".$files[$x]);
}

print_r($final_files);