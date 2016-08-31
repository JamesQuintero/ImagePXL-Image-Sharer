<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);

if($image_id!='')
{
    $query=mysql_query("SELECT favorites, favorite_timestamps FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $favorites=explode('|', $array[0]);
        $favorite_timestamps=explode('|', $array[1]);
        
        //if favorited
        $index=array_search($image_id, $favorites);
        if($index!==false)
        {
            unset($favorites[$index]);
            $favorites=array_values($favorites);
            
            unset($favorite_timestamps[$index]);
            $favorite_timestamps=array_values($favorite_timestamps);
            
            $favorites=implode('|', $favorites);
            $favorite_timestamps=implode('|', $favorite_timestamps);
            $query=mysql_query("UPDATE user_data SET favorites='$favorites', favorite_timestamps='$favorite_timestamps' WHERE user_id=$_SESSION[id]");
            if($query)
            {
                $query=mysql_query("SELECT num_favorites FROM images WHERE image_id='$image_id' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $num_favorites=$array[0];
                    
                    $num_favorites--;
                    
                    $query=mysql_query("UPDATE images SET num_favorites=$num_favorites WHERE image_id='$image_id'");
                    if(!$query)
                    {
                        echo "Something went wrong. We are working on fixing it";
                        send_mail_error("unfavorite.php: (4): ", mysql_error());
                    }
                }
                else if(!$query)
                {
                    echo "Something went wrong. We are working on fixing it";
                    send_mail_error("unfavorite.php: (3): ", mysql_error());
                }
            }
            else
            {
                echo "Something went wrong. We are working on fixing it";
                send_mail_error("unfavorite.php: (2): ", mysql_error());
            }
        }
    }
    else if(!$query)
    {
        echo "Something went wrong. We are working on fixing it";
        send_mail_error("unfavorite.php: (1): ", mysql_error());
    }
}
?>