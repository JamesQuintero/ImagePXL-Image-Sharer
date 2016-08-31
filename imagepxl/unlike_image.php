<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);

$num_likes=0;
$num_dislikes=0;
$errors="";

if($image_id!='')
{
    $query=mysql_query("SELECT likes FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $likes=explode('|', $array[0]);
        
        $index=array_search($image_id, $likes);
        if($index!==false)
        {
            unset($likes[$index]);
            $likes = array_values($likes);
            
            $likes=implode('|', $likes);
            $query=mysql_query("UPDATE user_data SET likes='$likes' WHERE user_id=$_SESSION[id]");
            if($query)
            {
                $query=mysql_query("SELECT num_likes, num_dislikes FROM images WHERE image_id='$image_id' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $num_likes=(int)($array[0]);
                    $num_dislikes=(int)($array[1]);

                    $num_likes--;

                    $query=mysql_query("UPDATE images SET num_likes=$num_likes WHERE image_id='$image_id'");
                    if(!$query)
                    {
                        $errors.="Something went wrong. We are working on fixing it";
                        send_mail_error("unlike_image.php: (4): ", mysql_error());
                    }
                }
                else
                {
                   $errors.="Something went wrong. We are working on fixing it";
                   send_mail_error("unlike_image.php: (3): ", mysql_error());
                }
            }
            else
            {
                $errors.="Something went wrong. We are working on fixing it";
                send_mail_error("unlike_image.php: (2): ", mysql_error());
            }
        }
        else
            $errors.="You never liked the image";
    }
    else
    {
        $errors.="Something went wrong. We are working on fixing it";
        send_mail_error("unlike_image.php: (1): ", mysql_error());
    }
}

$JSON=array();
$JSON['num_likes']=$num_likes;
$JSON['num_dislikes']=$num_dislikes;
$JSON['errors']=$errors;
echo json_encode($JSON);
exit();
?>