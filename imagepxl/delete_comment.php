<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);
$comment_id=(int)($_POST['comment_id']);

if($image_id!='')
{
    $query=mysql_query("SELECT comment_ids, comments, comment_likes, comment_dislikes, comment_user_ids, comment_timestamps FROM images WHERE image_id='$image_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
       $array=mysql_fetch_row($query);
       $comment_ids=explode('|', $array[0]);
       $comments=explode('|^|*|', $array[1]);
       $comment_likes=explode('|', $array[2]);
       $comment_dislikes=explode('|', $array[3]);
       $comment_user_ids=explode('|', $array[4]);
       $comment_timestamps=explode('|', $array[5]);

       $index=array_search($comment_id, $comment_ids);
       if($index!==false)
       {
            //if user is deleting their comment
            if($comment_user_ids[$index]==$_SESSION['id'])
            {

               unset($comment_ids[$index]);
               $comment_ids = array_values($comment_ids);

               unset($comments[$index]);
               $comments = array_values($comments);

               unset($comment_likes[$index]);
               $comment_likes = array_values($comment_likes);

               unset($comment_dislikes[$index]);
               $comment_dislikes = array_values($comment_dislikes);

               unset($comment_user_ids[$index]);
               $comment_user_ids = array_values($comment_user_ids);

               unset($comment_timestamps[$index]);
               $comment_timestamps = array_values($comment_timestamps);

               $comment_ids=implode('|', $comment_ids);
               $comments=implode('|^|*|', str_replace("'", "\'", $comments));
               $comment_likes=implode('|', $comment_likes);
               $comment_dislikes=implode('|', $comment_dislikes);
               $comment_user_ids=implode('|', $comment_user_ids);
               $comment_timestamps=implode('|', $comment_timestamps);

               $query=mysql_query("UPDATE images SET comment_ids='$comment_ids', comments='$comments', comment_likes='$comment_likes', comment_dislikes='$comment_dislikes', comment_user_ids='$comment_user_ids', comment_timestamps='$comment_timestamps' WHERE image_id='$image_id'");
               if($query)
                   echo "success";
               else
               {
                   echo "Something went wrong. We are working on fixing it";
                   send_mail_error("delete_comment.php: (2): ", mysql_error());
               }
            }
            else
               echo "You cannot delete a comment that isn't yours";
       }
    }
    else
    {
       echo "Something went wrong. We are working on fixing it";
       send_mail_error("delete_comment.php: (1): ", mysql_error());
    }
}
?>