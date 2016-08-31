<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);
$comment_id=(int)($_POST['comment_id']);

$errors="";
$num_likes=0;
$num_dislikes=0;

if($image_id!='')
{
    $query=mysql_query("SELECT comment_ids, comment_likes, comment_dislikes FROM images WHERE image_id='$image_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
       $array=mysql_fetch_row($query);
       $comment_ids=explode('|', $array[0]);
       $comment_likes=explode('|', $array[1]);
       $comment_dislikes=explode('|', $array[2]);

       //if comment exists
       $index=array_search($comment_id, $comment_ids);
       if($index!==false)
       {
           //explodes likes and dislikes
            $comment_likes[$index]=explode('^', $comment_likes[$index]);
            $comment_dislikes[$index]=explode('^', $comment_dislikes[$index]);

            //if haven't already liked
            if(array_search($_SESSION['id'], $comment_likes[$index])===false)
            {
                //adds like
                if($comment_likes[$index][0]=='')
                {
                    $comment_likes[$index][0]=$_SESSION['id'];
                    $num_likes=1;
                }
                else
                {
                    $comment_likes[$index][]=$_SESSION['id'];
                    $num_likes=sizeof($comment_likes[$index]);
                }

                //removes dislike if it exists
                $dislike_index=array_search($_SESSION['id'], $comment_dislikes[$index]);
                if($dislike_index!==false)
                {
                    unset($comment_dislikes[$index][$dislike_index]);
                    $comment_dislikes = array_values($comment_dislikes);
                }

                //gets num dislikes
                if($comment_dislikes[$index][0]=='')
                   $num_dislikes=0;
                else
                   $num_dislikes=sizeof($comment_dislikes[$index]);

                $comment_likes[$index]=implode('^', $comment_likes[$index]);
                $comment_likes=implode('|', $comment_likes);
                $comment_dislikes[$index]=implode('^', $comment_dislikes[$index]);
                $comment_dislikes=implode('|', $comment_dislikes);
                $query=mysql_query("UPDATE images SET comment_likes='$comment_likes', comment_dislikes='$comment_dislikes' WHERE image_id='$image_id'");
                if(!$query)
                {
                    $errors.="Something went wrong. We are working on fixing it";
                    send_mail_error("like_comment.php: (1): ", mysql_error());
                }
            }
       }
       else
           $errors.="Comment doesn't exist";
    }
    else
    {
       $errors.="Something went wrong. We are working on fixing it";
         send_mail_error("like_comment.php: (2): ", mysql_error());
    }
}

$JSON=array();
$JSON['errors']=$errors;
$JSON['num_likes']=$num_likes;
$JSON['num_dislikes']=$num_dislikes;
echo json_encode($JSON);
exit();

?>