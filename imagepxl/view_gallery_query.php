<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$num=(int)($_POST['num']);

//gets all images in album
if($num==1)
{
    $album_id=clean_string($_POST['album_id']);
    $timezone=(int)($_POST['timezone']);
    
    $query=mysql_query("SELECT image_ids, image_exts FROM albums WHERE album_id='$album_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_array($query);
        $image_ids=explode('|', $array['image_ids']);
        $image_exts=explode('|', $array['image_exts']);
        
        $temp_image_ids=array();
        $temp_image_exts=array();
        for($x = sizeof($image_ids)-1; $x >= 0; $x--)
        {
            $temp_image_ids[]=$image_ids[$x];
            $temp_image_exts[]=$image_exts[$x];
        }
        $image_ids=$temp_image_ids;
        $image_exts=$temp_image_exts;
        
        
        //gets info for some images
        $descriptions=array();
        $timestamps=array();
        $num_likes=array();
        $num_dislikes=array();
        $comment_ids=array();
        $num_favorites=array();
        $views=array();
        $nsfw=array();
        $num_comments=array();
        
        $final_image_ids=array();
        $final_image_exts=array();
        $has_liked=array();
        $has_disliked=array();
        $has_favorited=array();
        for($x = 0; $x < sizeof($image_ids); $x++)
        {
            $final_image_ids[]=$image_ids[$x];
            $final_image_exts[]=$image_exts[$x];
            
            $query=mysql_query("SELECT description, timestamp, num_likes, num_dislikes, comment_ids, num_favorites, views, nsfw FROM images WHERE image_id='$image_ids[$x]' LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_array($query);
                $descriptions[]=$array['description'];
                $timestamps[]=get_time_since($array['timestamp'], $timezone);
                $num_likes[]=$array['num_likes'];
                $num_dislikes[]=$array['num_dislikes'];
                $comment_ids[$x]=explode('|', $array['comment_ids']);
                $num_favorites[]=$array['num_favorites'];
                $views[]=$array['views'];
                $nsfw[]=$array['nsfw'];
                
                if($array['comment_ids']!='')
                    $num_comments[]=sizeof($comment_ids[$x]);
                else
                    $num_comments[]=0;
                
                //gets has liked and has disliked
                $query=mysql_query("SELECT likes, dislikes, favorites FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $likes=explode('|', $array[0]);
                    $dislikes=explode('|', $array[1]);
                    $favorites=explode('|', $array[2]);
                    
                    //if has liked
                    if(array_search($image_ids[$x], $likes)!==false)
                        $has_liked[]=true;
                    else
                        $has_liked[]=false;
                    
                    //if has disliked
                    if(array_search($image_ids[$x], $dislikes)!==false)
                        $has_disliked[]=true;
                    else
                        $has_disliked[]=false;
                    
                    //if has liked
                    if(array_search($image_ids[$x], $favorites)!==false)
                        $has_favorited[]=true;
                    else
                        $has_favorited[]=false;
                }
                else
                {
                    $has_liked[]=false;
                    $has_disliked[]=false;
                    $has_favorited[]=false;
                }
            }
            else
            {
                $descriptions[]='';
                $timestamps[]='';
                $num_likes[]=0;
                $num_dislikes[]=0;
                $comment_ids[]=array();
                $num_favorites[]=0;
                $views[]=0;
                $nsfw[]=false;
            }
        }
        
        
        
        $JSON=array();
        $JSON['all_image_ids']=$image_ids;
        $JSON['all_image_exts']=$image_exts;
        $JSON['image_ids']=$final_image_ids;
        $JSON['image_exts']=$final_image_exts;
        $JSON['descriptions']=$descriptions;
        $JSON['timestamps']=$timestamps;
        $JSON['num_likes']=$num_likes;
        $JSON['num_dislikes']=$num_dislikes;
        $JSON['comment_ids']=$comment_ids;
        $JSON['num_favorites']=$num_favorites;
        $JSON['views']=$views;
        $JSON['nsfw']=$nsfw;
        $JSON['has_liked']=$has_liked;
        $JSON['has_disliked']=$has_disliked;
        $JSON['has_favorited']=$has_favorited;
        $JSON['total_num_images']=sizeof($image_ids);
        echo json_encode($JSON);
        exit();
    }
    else if(!$query)
        log_error("view_gallery_query.php: (1:1): ", mysql_error());
    
    //script only gets here if error
    $JSON=array();
    $JSON['all_image_ids']=array();
    $JSON['all_image_exts']=array();
    $JSON['image_ids']=array();
    $JSON['image_exts']=array();
    $JSON['descriptions']=array();
    $JSON['timestamps']=array();
    $JSON['num_likes']=array();
    $JSON['num_dislikes']=array();
    $JSON['comment_ids']=array();
    $JSON['num_favorites']=array();
    $JSON['views']=array();
    $JSON['nsfw']=array();
    $JSON['has_liked']=array();
    $JSON['has_disliked']=array();
    $JSON['has_favorited']=array();
    $JSON['total_num_images']=array();
    echo json_encode($JSON);
    exit();
}