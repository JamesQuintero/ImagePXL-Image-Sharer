<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);

$num_likes=0;
$errors="";

if($image_id!='')
{
    $query=mysql_query("SELECT likes, dislikes FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $likes=explode('|', $array[0]);
        $dislikes=explode('|', $array[1]);

        if(array_search($image_id, $likes)===false)
        {
            if($array[0]!='')
                $likes[]=$image_id;
            else
                $likes[0]=$image_id;

            //if has disliked, remove from dislikes
            $dislike_sql="";
            $index=array_search($image_id, $dislikes);
            if($index!==false)
            {
                unset($dislikes[$index]);
                $dislikes = array_values($dislikes);
                $dislikes=implode('|', $dislikes);
                $dislike_sql=", dislikes='$dislikes' ";
            }

            $likes=implode('|', $likes);
            $query=mysql_query("UPDATE user_data SET likes='$likes' ".$dislike_sql." WHERE user_id=$_SESSION[id]");
            if($query)
            {
                $query=mysql_query("SELECT num_likes, num_dislikes FROM images WHERE image_id='$image_id' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                   $array=mysql_fetch_row($query);
                   $num_likes=(int)$array[0];
                   $num_dislikes=(int)$array[1];

                    //increments num likes
                    $num_likes++;

                    //if has to remove from dislikes
                    if($dislike_sql!='')
                        $num_dislikes--;

                    $query=mysql_query("UPDATE images SET num_likes=$num_likes, num_dislikes=$num_dislikes WHERE image_id='$image_id'");
                    if(!$query)
                    {
                       $errors.="Something went wrong. We are working on fixing it";
                       log_error("like_image.php: (4): ", mysql_error());
                    }
                }
                else
                {
                   $errors.="Something went wrong. We are working on fixing it";
                  log_error("like_image.php: (3): ", mysql_error());
                }
            }
            else
            {
                $errors.="Something went wrong. We are working on fixing it";
                log_error("like_image.php: (2): ", mysql_error());
            }
        }
    }
    else
    {
        $errors.="Something went wrong. We are working on fixing it";
      log_error("like_image.php: (1): ", mysql_error());
    }



    $JSON=array();
    $JSON['num_likes']=$num_likes;
    $JSON['num_dislikes']=$num_dislikes;
    $JSON['errors']=$errors;
    echo json_encode($JSON);
    exit();
}