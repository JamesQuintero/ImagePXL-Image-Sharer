<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);
$comment=clean_string($_POST['comment']);

sleep(2);
if($comment!='')
{
    if($image_id!='')
    {
        if(strlen($comment)<=1000)
        {
            $query=mysql_query("SELECT user_id, comment_ids, comments, comment_likes, comment_dislikes, comment_user_ids, comment_timestamps FROM images WHERE image_id='$image_id' LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
               $array=mysql_fetch_array($query);
               $user_id=$array['user_id'];
               $comment_ids=explode('|', $array['comment_ids']);
               $comments=explode('|^|*|', str_replace("'", "\'", $array['comments']));
               $comment_likes=explode('|', $array['comment_likes']);
               $comment_dislikes=explode('|', $array['comment_dislikes']);
               $comment_user_ids=explode('|', $array['comment_user_ids']);
               $comment_timestamps=explode('|', $array['comment_timestamps']);

               //gets current date
               $current=get_date();

               //checks if user isn't spamming
               $clear=true;
               for($x = sizeof($comment_user_ids)-1; $x >= 0; $x--)
               {
                   if($comment_user_ids[$x]==$_SESSION['id'])
                   {
                       if($current-$comment_timestamps[$x]<=10)
                           $clear=false;
                   }

               }

               if($clear)
               {
                    if($array['comments']!='')
                    {
                        $new_comment_id=$comment_ids[sizeof($comment_ids)-1]+1;
                        $comment_ids[]=$new_comment_id;
                        $comments[]=$comment;
                        $comment_likes[]='';
                        $comment_dislikes[]='';
                        $comment_user_ids[]=$_SESSION['id'];
                        $comment_timestamps[]=$current;
                    }
                    else
                    {
                        $new_comment_id=1;
                        $comment_ids[0]=$new_comment_id;
                        $comments[0]=$comment;
                        $comment_likes[0]='';
                        $comment_dislikes[0]='';
                        $comment_user_ids[0]=$_SESSION['id'];
                        $comment_timestamps[0]=$current;
                    }

                    $comment_ids=implode('|', $comment_ids);
                    $comments=implode('|^|*|', $comments);
                    $comment_likes=implode('|', $comment_likes);
                    $comment_dislikes=implode('|', $comment_dislikes);
                    $comment_user_ids=implode('|', $comment_user_ids);
                    $comment_timestamps=implode('|', $comment_timestamps);

                    $query=mysql_query("UPDATE images SET comment_ids='$comment_ids', comments='$comments', comment_likes='$comment_likes', comment_dislikes='$comment_dislikes', comment_user_ids='$comment_user_ids', comment_timestamps='$comment_timestamps' WHERE image_id='$image_id'");
                    if($query)
                    {
                        ////adds alert////
                        if($user_id!=$_SESSION['id'])
                        {
                            $query=mysql_query("SELECT image_ids, comment_ids, new_comments FROM alerts WHERE user_id=$user_id LIMIT 1");
                            if($query&&mysql_num_rows($query)==1)
                            {
                                $array=mysql_fetch_row($query);
                                $image_ids=explode('|', $array[0]);
                                $comment_ids=explode('|', $array[1]);
                                $new_comments=$array[2];

                                if($array[0]!='')
                                {
                                    $image_ids[]=$image_id;
                                    $comment_ids[]=$new_comment_id;
                                }
                                else
                                {
                                    $image_ids[0]=$image_id;
                                    $comment_ids[0]=$new_comment_id;
                                }
                                
                                $new_comments++;
                                $image_ids=implode('|', $image_ids);
                                $comment_ids=implode('|', $comment_ids);

                                $query=mysql_query("UPDATE alerts SET image_ids='$image_ids', comment_ids='$comment_ids', new_comments=$new_comments WHERE user_id=$user_id");
                                if(!$query)
                                {
                                    echo "Something went wrong. We are working on fixing it";
                                    send_mail_error("comment.php: (4): ", mysql_error());
                                }
                            }
                            else if(!$query)
                            {
                                echo "Something went wrong. We are working on fixing it";
                                send_mail_error("comment.php: (3): ", mysql_error());
                            }
                        }
                    }
                    else 
                    {
                        echo "Something went wrong. We are working on fixing it";
                        send_mail_error("comment.php: (2): ", mysql_error());
                    }
               }
               else
                   echo "Please wait a few seconds before commenting";
            }
            else
            {
               echo "Something went wrong. We are working on fixing it";
                send_mail_error("comment.php: (1): ", mysql_error());       
            }
        }
        else
            echo "Comment needs to be under 1000 characters";
    }
    else
        echo "Invalid image id";
}
else
    echo "Comment can't be empty";
?>