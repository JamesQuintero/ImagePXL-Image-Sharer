<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include("security_checks.php");

$num=(int)($_POST['num']);

//gets the alerts
if($num==1)
{
    $page=(int)($_POST['page'])*20;
    $timezone=(int)($_POST['timezone']);
    
    $query=mysql_query("SELECT * FROM alerts WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_array($query);
        $image_ids=explode('|', $array['image_ids']);
        $comment_ids=explode('|', $array['comment_ids']);
        
        //sets new comments to 0
        mysql_query("UPDATE alerts SET new_comments=0 WHERE user_id=$_SESSION[id]");
        
        //gets the comments
        $comments=array();
        $comment_likes=array();
        $comment_dislikes=array();
        $comment_user_ids=array();
        $comment_timestamps=array();
        $image_exts=array();
        $user_ids=array();
        $descriptions=array();
        for($x = 0; $x < $page; $x++)
        {
            if(isset($image_ids[$x])&&$x>=$page-20)
            {
                $query=mysql_query("SELECT user_id, description, ext, comment_ids, comments, comment_likes, comment_dislikes, comment_user_ids, comment_timestamps FROM images WHERE image_id='$image_ids[$x]' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_array($query);
                    $one=explode('|', $array['comment_ids']);
                    $two=explode('|^|*|', $array['comments']);
                    $three=explode('|', $array['comment_likes']);
                    $four=explode('|', $array['comment_dislikes']);
                    $five=explode('|', $array['comment_user_ids']);
                    $six=explode('|', $array['comment_timestamps']);
                    $image_exts[]=$array['ext'];
                    $user_ids[]=$array['user_id'];
                    $descriptions[]=$array['description'];
                    
                    $index=array_search($comment_ids[$x], $one);
                    if($index!==false)
                    {
                        $comments[]=$two[$index];
                        $comment_likes[]=$three[$index];
                        $comment_dislikes[]=$four[$index];
                        $comment_user_ids[]=$five[$index];
                        $comment_timestamps[]=get_time_since($six[$index], $timezone);
                    }
                }
                else
                    echo "Something went wrong: ".mysql_error();
            }
        }
        
        //gets the comment info
        $profile_pictures=array();
        $usernames=array();
        $image_usernames=array();
        $has_liked=array();
        $has_disliked=array();
        for($x = 0; $x < $page; $x++)
        {
            if(isset($comments[$x]))
            {
                //gets profile pictures
                $profile_pictures[]=get_profile_picture($comment_user_ids[$x]);
                
                //gets usernames of image uploaders
                $image_usernames[]=get_username($user_ids[$x]);

                //gets usernames
                $usernames[]=get_username($comment_user_ids[$x]);
                
                $comment_likes[$x]=explode('^', $comment_likes[$x]);
                $comment_dislikes[$x]=explode('^', $comment_dislikes[$x]);

                //gets num_likes
                if($comment_likes[$x][0]!='')
                    $num_likes[]=sizeof($comment_likes[$x]);
                else
                    $num_likes[]=0;

                //gets num dislikes
                if($comment_dislikes[$x][0]!='')
                    $num_dislikes[]=sizeof($comment_dislikes[$x]);
                else
                    $num_dislikes[]=0;
                
                //gets has liked
                if(array_search($_SESSION['id'], $comment_likes[$x])!==false)
                    $has_liked[]=true;
                else
                    $has_liked[]=false;
                
                //gets has disliked
                if(array_search($_SESSION['id'], $comment_dislikes[$x])!==false)
                    $has_disliked[]=true;
                else
                    $has_disliked[]=false;
            }
        }
        
        $JSON=array();
        $JSON['image_ids']=$image_ids;
        $JSON['image_exts']=$image_exts;
        $JSON['comment_ids']=$comment_ids;
        $JSON['comments']=$comments;
        $JSON['profile_pictures']=$profile_pictures;
        $JSON['image_usernames']=$image_usernames;
        $JSON['usernames']=$usernames;
        $JSON['num_likes']=$num_likes;
        $JSON['num_dislikes']=$num_dislikes;
        $JSON['has_liked']=$has_liked;
        $JSON['has_disliked']=$has_disliked;
        $JSON['comment_timestamps']=$comment_timestamps;
        $JSON['comment_user_ids']=$comment_user_ids;
        $JSON['descriptions']=$descriptions;
        echo json_encode($JSON);
        exit();
    }
}

//gets new comment stuff for header
else if($num==2)
{
    $query=mysql_query("SELECT new_comments FROM alerts WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $new_comments=$array[0];
        
        $JSON=array();
        $JSON['new_comments']=$new_comments;
        echo json_encode($JSON);
        exit();
    }
}