<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$num=(int)($_POST['num']);

//searching
if($num==1)
{
    $type=clean_string($_POST['type']);
    $param_type=clean_string($_POST['param_type']);
    $page=(int)($_POST['page'])*30;
    $timezone=(int)($_POST['timezone']);
    $sort=(int)($_POST['sort']);
    
    //searches for users
    if($type=='images')
    {
        $param=clean_string(str_replace("'", "\'", $_POST['param']));
        
        if($sort==0)
            $order_by="(log(num_likes-num_dislikes)+((timestamp-1360994400)/45000))";
        else
            $order_by="timestamp";
        
        //search queries
        if($param_type=='description')
            $query=mysql_query("SELECT * FROM images WHERE description LIKE '%$param%' ORDER BY ".$order_by." DESC LIMIT $page");
        else
            $query=false;
        
        
        if($query)
        {
            $image_ids=array();
            $descriptions=array();
            $exts=array();
            $user_ids=array();
            $timestamps=array();
            $album_ids=array();
            $likes=array();
            $dislikes=array();
            $num_likes=array();
            $num_dislikes=array();
            $comment_ids=array();
            $num_favorites=array();
            $num_views=array();
            
            while($array=mysql_fetch_array($query))
            {
                $image_ids[]=$array['image_id'];
                $descriptions[]=$array['description'];
                $exts[]=$array['ext'];
                $user_ids[]=$array['user_id'];
                $timestamps[]=get_time_since($array['timestamp'], $timezone);
                $album_ids[]=$array['album_id'];
                $likes[]=explode('|', $array['likes']);
                $dislikes[]=explode('|', $array['dislikes']);
                $num_likes[]=$array['num_likes'];
                $num_dislikes[]=$array['num_dislikes'];
                $comment_ids[]=explode('|', $array['comment_ids']);
                $num_favorites[]=$array['num_favorites'];
                $num_views[]=$array['views'];
            }
            
            
            $has_liked=array();
            $has_disliked=array();
            $num_comments=array();
            $num_followers=array();
            $is_following=array();
            $usernames=array();
            for($x = 0; $x < sizeof($image_ids); $x++)
            {
                //gets has liked
                if(array_search($_SESSION['id'], $likes[$x])!==false)
                    $has_liked[]=true;
                else
                    $has_liked[]=false;
                
                //gets has disliked
                if(array_search($_SESSION['id'], $dislikes[$x])!==false)
                    $has_disliked[]=true;
                else
                    $has_disliked[]=false;
                
                //gets num comments
                if($comment_ids[$x][0]!='')
                    $num_comments[]=sizeof($comment_ids[$x]);
                else
                    $num_comments[]=0;
                
                $usernames[]=get_username($user_ids[$x]);
            }
            
            $JSON=array();
            $JSON['image_ids']=$image_ids;
            $JSON['descriptions']=$descriptions;
            $JSON['exts']=$exts;
            $JSON['user_ids']=$user_ids;
            $JSON['usernames']=$usernames;
            $JSON['timestamps']=$timestamps;
            $JSON['album_ids']=$album_ids;
            $JSON['has_liked']=$has_liked;
            $JSON['has_disliked']=$has_disliked;
            $JSON['num_likes']=$num_likes;
            $JSON['num_dislikes']=$num_dislikes;
            $JSON['comment_ids']=$comment_ids;
            $JSON['num_favorites']=$num_favorites;
            $JSON['num_views']=$num_views;
            echo json_encode($JSON);
            exit();
        }
        else
        {
            $JSON=array();
            $JSON['image_ids']=array();
            $JSON['descriptions']=array();
            $JSON['exts']=array();
            $JSON['user_ids']=array();
            $JSON['usernames']=array();
            $JSON['timestamps']=array();
            $JSON['album_ids']=array();
            $JSON['has_liked']=array();
            $JSON['has_disliked']=array();
            $JSON['num_likes']=array();
            $JSON['num_dislikes']=array();
            $JSON['comment_ids']=array();
            $JSON['num_favorites']=array();
            $JSON['num_views']=array();
            echo json_encode($JSON);
            exit();
        }
    }
    
    //searches for users
    else if($type=='users')
    {
        $param=clean_string(str_replace("'", "\'", $_POST['param']));
        
        //search queries
        if($sort==0)
            $order_by="num_followers";
        else
            $order_by="date_joined";
        
        if($param_type=='description')
            $query=mysql_query("SELECT * FROM user_data WHERE description LIKE '%$param%' ORDER BY $order_by DESC LIMIT $page");
        else if($param_type=='username')
            $query=mysql_query("SELECT * FROM user_data WHERE username LIKE '%$param%' ORDER BY $order_by DESC LIMIT $page");
        else if($param_type=='real_name')
            $query=mysql_query("SELECT * FROM user_data WHERE real_name LIKE '%$param%' ORDER BY $order_by DESC LIMIT $page");
        else
            $query=false;
        
        
        if($query)
        {
            $user_ids=array();
            $usernames=array();
            $num_followers=array();
            $names=array();
            $num_images=array();
            $image_views=array();
            $descriptions=array();

            $index=0;
            while($array=mysql_fetch_array($query))
            {
                //only gets most recent
                if($index>=$page-30)
                {
                    $user_ids[]=$array['user_id'];
                    $usernames[]=$array['username'];
                    $num_followers[]=$array['num_followers'];
                    $names[]=$array['name'];
                    $num_images[]=$array['num_images'];
                    $image_views[]=$array['image_views'];
                    $descriptions[]=$array['description'];
                }
                
                $index++;
            }
            
            //gets extra info
            $profile_pictures=array();
            for($x = 0; $x < sizeof($user_ids); $x++)
            {
                $profile_pictures[]=get_profile_picture($user_ids[$x]);
            }

            $JSON=array();
            $JSON['user_ids']=$user_ids;
            $JSON['usernames']=$usernames;
            $JSON['num_followers']=$num_followers;
            $JSON['is_following']=$is_following;
            $JSON['names']=$names;
            $JSON['num_images']=$num_images;
            $JSON['image_views']=$image_views;
            $JSON['descriptions']=$descriptions;
            $JSON['profile_pictures']=$profile_pictures;
            echo json_encode($JSON);
            exit();
        }
        else
        {
            $JSON=array();
            $JSON['user_ids']=array();
            $JSON['usernames']=array();
            $JSON['num_followers']=array();
            $JSON['is_following']=array();
            $JSON['names']=array();
            $JSON['num_images']=array();
            $JSON['image_views']=array();
            $JSON['descriptions']=array();
            $JSON['profile_pictures']=array();
            echo json_encode($JSON);
            exit();
        }
    }
    
    //searches for albums
    else if($type=='albums')
    {
        $param=clean_string(str_replace("'", "\'", $_POST['param']));
        
        //search queries
        if($param_type=='name')
            $query=mysql_query("SELECT * FROM albums WHERE album_name LIKE '%$param%' LIMIT $page");
        else
            $query=false;
        
        
        if($query)
        {
            $album_ids=array();
            $album_names=array();
            $user_ids=array();
            $timestamps=array();
            $updated=array();
            $image_ids=array();
            $image_exts=array();
            
            while($array=mysql_fetch_array($query))
            {
                $album_ids[]=$array['album_id'];
                $album_names[]=$array['album_name'];
                $user_ids[]=$array['user_id'];
                $timestamps[]=get_time_since($array['timestamp'], $timezone);
                $updated[]=$array['updated'];
                $image_ids[]=explode('|', $array['image_ids']);
                $image_exts[]=explode('|', $array['image_exts']);
            }
            
            //gets extra info
            $album_thumbnails=array();
            $album_thumbnail_exts=array();
            $num_images=array();
            $usernames=array();
            for($x = 0; $x < sizeof($album_ids); $x++)
            {
                //gets usernames
                $usernames[]=get_username($user_ids[$x]);
                
                //gets num images
                if($image_ids[$x][0]!='')
                    $num_images[]=sizeof($image_ids[$x]);
                else
                    $num_images[]=0;
                
                //gets album thumbnails
                for($y = 0; $y < 5; $y++)
                {
                    if(isset($image_ids[$x][$y]))
                    {
                        $album_thumbnails[$x][]=$image_ids[$x][$y];
                        $album_thumbnail_exts[$x][]=$image_exts[$x][$y];
                    }
                    else
                    {
                        $album_thumbnails[$x][]='';
                        $album_thumbnail_exts[$x][]='';
                    }
                }
            }
            
            
            $JSON=array();
            $JSON['album_ids']=$album_ids;
            $JSON['album_names']=$album_names;
            $JSON['user_ids']=$user_ids;
            $JSON['usernames']=$usernames;
            $JSON['timestamps']=$timestamps;
            $JSON['updated']=$updated;
            $JSON['num_images']=$num_images;
            $JSON['album_thumbnails']=$album_thumbnails;
            $JSON['album_thumbnail_exts']=$album_thumbnail_exts;
            echo json_encode($JSON);
            exit();
        }
        else
        {
            $JSON=array();
            $JSON['album_ids']=array();
            $JSON['album_names']=array();
            $JSON['user_ids']=array();
            $JSON['usernames']=array();
            $JSON['timestamps']=array();
            $JSON['updated']=array();
            $JSON['num_images']=array();
            $JSON['album_thumbnails']=array();
            $JSON['album_thumbnail_exts']=array();
            echo json_encode($JSON);
            exit();
        }
    }
}
?>