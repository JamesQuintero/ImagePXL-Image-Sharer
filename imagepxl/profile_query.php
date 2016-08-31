<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');


$num=(int)($_POST['num']);

//gets images for display
if($num==1)
{
    $user_id=(int)($_POST['user_id']);
    $display=(int)($_POST['display']);
    $page=(int)($_POST['page'])*36;
    
    $username=get_username($user_id);

    $images=array();
    $exts=array();
    $num_likes=array();
    $num_dislikes=array();
    $timestamps=array();
    $comments=array();
    $scores=array();
    $nsfw=array();
       
    $query=mysql_query("SELECT image_id, ext, timestamp, num_likes, num_dislikes, comments, description, nsfw FROM images WHERE user_id=$user_id");
    if($query)
    {
        while($array=mysql_fetch_row($query))
        {
            $images[]=$array[0];
            $exts[]=$array[1];
            $timestamps[]=$array[2];
            $num_likes[]=$array[3];
            $num_dislikes[]=$array[4];
            $comments[]=explode('|^|*|', $array[5]);
            $descriptions[]=str_replace("'", "\'", $array[6]);
            $nsfw[]=$array[7];
        }

        //gets scores
        for($x = 0; $x < sizeof($images); $x++)
        {
           $temp_x=$num_likes[$x]-$num_dislikes[$x];

           if($temp_x>0)
               $temp_y=1;
           else if($temp_x==0)
               $temp_y=0;
           else if($temp_x<0)
               $temp_y=-1;

           if(abs($temp_x)>=1)
               $z=abs($temp_x);
           else if(abs($temp_x)<1)
               $z=1;

           $t=$timestamps[$x]-1360994400;

           //reddit's algorithm
           $f=log($z)+(($temp_y*$t)/45000);

           $scores[$x]=$f;
        }

        if($display==0)
        {
            $array_timestamps=$timestamps;
            rsort($timestamps, SORT_NUMERIC);
        }
        else
        {
            $array_scores=$scores;
            rsort($scores, SORT_NUMERIC);
        }

        //creates temporary data of arrays for future use
        $temp_images=array();
        $temp_exts=array();
        $temp_num_likes=array();
        $temp_num_dislikes=array();
        $temp_comments=array();
        $temp_timestamps=array();
        $temp_scores=array();
        $temp_descriptions=array();
        $temp_nsfw=array();

        
        $total_images=sizeof($images);
        if(sizeof($images)<=36)
        {
            $size=sizeof($images);
            $start=0;
            $displaying_all=true;
        }
        else if($page<sizeof($images))
        {
            $displaying_all=false;
            $size=$page;
            $start=$page-36;
        }
        else
        {
            $displaying_all=true;
            $size=$page-($page-sizeof($images));
            $start=$page-36;
        }

        //rearranges rest of data according to sorted timestamps compared to previously unsorted timestamps
        for($x = $start; $x < $size; $x++)
        {
            if(isset($scores[$x])||$display==0)
            {
                if($display==0)
                {
                   $number=array_search($timestamps[$x], $array_timestamps);
                   $array_timestamps[$number]='';
                }
                else
                {
                    $number=array_search($scores[$x], $array_scores);
                    $array_scores[$number]=-99999;
                }


                $temp_images[]=$images[$number];
                $temp_exts[]=$exts[$number];
                $temp_timestamps[]=$timestamps[$number];
                $temp_num_likes[]=$num_likes[$number];
                $temp_num_dislikes[]=$num_dislikes[$number];
                $temp_comments[]=$comments[$number];
                $temp_scores[]=$scores[$number];
                $temp_descriptions[]=$descriptions[$number];
                $temp_nsfw[]=$nsfw[$number];
            }
        }
        

        if(isset($_SESSION['id']))
        {
            //gets current user's likes and dislikes
            $query=mysql_query("SELECT likes, dislikes FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $likes=explode('|', $array[0]);
                $dislikes=explode('|', $array[1]);
            }
        }
        else
        {
            $likes=array();
            $dislikes=array();
        }
        
        $num_comments=array();
        $has_liked=array();
        $has_disliked=array();
        $thumbnails=array();
        for($x = 0; $x < sizeof($temp_images); $x++)
        {
            $temp_comments[$x]=explode('|%|&|', $temp_comments[$x]);

            //gets num comments
            if($temp_comments[$x][0]=='')
                $num_comments[]=0;
            else
                $num_comments[]=sizeof($temp_comments[$x]);
            
            //gets if has liked
            if(array_search($temp_images[$x], $likes)!==false)
                $has_liked[]=true;
            else
                $has_liked[]=false;
            
            //gets if has disliked
            if(array_search($temp_images[$x], $dislikes)!==false)
                $has_disliked[]=true;
            else
                $has_disliked[]=false;
            
            //gets thumbnails
            if($temp_nsfw[$x]=='false')
                $thumbnails[]="http://i.imagepxl.com/$username/thumbs/$temp_images[$x].$temp_exts[$x]";
            else
                $thumbnails[]="http://i.imagepxl.com/site/nsfw.png";
        }
    }
    else
      send_mail_error("profile_query.php: (1): ", mysql_error());
   
    $JSON=array();
    $JSON['images']=$temp_images;
    $JSON['exts']=$temp_exts;
    $JSON['points']=$temp_scores;
    $JSON['num_likes']=$temp_num_likes;
    $JSON['num_dislikes']=$temp_num_dislikes;
    $JSON['has_liked']=$has_liked;
    $JSON['has_disliked']=$has_disliked;
    $JSON['num_comments']=$num_comments;
    $JSON['timestamps']=$temp_timestamps;
    $JSON['descriptions']=$temp_descriptions;
    $JSON['displaying_all']=$displaying_all;
    $JSON['total_images']=$total_images;
    $JSON['thumbnails']=$thumbnails;
    $JSON['nsfw']=$temp_nsfw;
    echo json_encode($JSON);
    exit();
}

//gets albums
else if($num==2)
{
    $user_id=(int)($_POST['user_id']);
    
    $query=mysql_query("SELECT albums FROM user_data WHERE user_id=$user_id LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $temp_albums=explode('|', $array[0]);
        
        $albums=array();
        for($x = sizeof($temp_albums)-1; $x >= 0; $x--)
            $albums[]=$temp_albums[$x];
        
        //gets all album info
        $album_names=array();
        $image_ids=array();
        $image_exts=array();
        $num_images=array();
        for($x = 0; $x < sizeof($albums); $x++)
        {
            $query=mysql_query("SELECT album_name, image_ids, image_exts FROM albums WHERE album_id='$albums[$x]' LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $album_names[]=$array[0];
                $temp_ids=explode('|', $array[1]);
                $temp_exts=explode('|', $array[2]);
                
                //reverses images
                for($y = sizeof($temp_ids)-1; $y >= 0 ; $y--)
                {
                    $image_ids[$x][]=$temp_ids[$y];
                    $image_exts[$x][]=$temp_exts[$y];
                }
                
                if($array[1]!='')
                    $num_images[]=sizeof($image_ids[$x]);
                else
                    $num_images[]=0;
            }
            else if(!$query)
                send_mail_error("profile_query.php: (2:2): ", mysql_error());
        }
        
        
        $JSON=array();
        $JSON['album_ids']=$albums;
        $JSON['image_ids']=$image_ids;
        $JSON['image_exts']=$image_exts;
        $JSON['album_names']=$album_names;
        $JSON['num_images']=$num_images;
        echo json_encode($JSON);
        exit();
        
    }
    else if(!$query)
        send_mail_error("profile_query.php: (2:1): ", mysql_error());
}

//gets favorites
else if($num==3)
{
    $page=(int)($_POST['page'])*36;
    $user_id=(int)($_POST['user_id']);
    
    if(is_id($user_id))
    {
        $query=mysql_query("SELECT favorites, favorite_timestamps, likes, dislikes FROM user_data WHERE user_id=$user_id LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $favorites=explode('|', $array[0]);
            $favorite_timestamps=explode('|', $array[1]);
            $likes=explode('|', $array[2]);
            $dislikes=explode('|', $array[3]);
            
            
            
            $total_images=sizeof($favorites);
            if(sizeof($favorites)<=36)
            {
                $size=sizeof($favorites);
                $start=0;
                $displaying_all=true;
            }
            else if($page<sizeof($favorites))
            {
                $displaying_all=false;
                $size=$page;
                $start=$page-36;
            }
            else
            {
                $displaying_all=true;
                $size=$page-($page-sizeof($favorites));
                $start=$page-36;
            }
            
            $exts=array();
            $user_ids=array();
            $num_likes=array();
            $num_dislikes=array();
            $timestamps=array();
            $descriptions=array();
            $displaying_all=array();
            $comment_ids=array();
            $num_favorites=array();
            $temp_favorites=array();
            for($x = $start; $x < $size; $x++)
            {
                $temp_favorites[]=$favorites[$x];
                $query=mysql_query("SELECT * FROM images WHERE image_id='$favorites[$x]' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_array($query);
                    $descriptions[]=$array['description'];
                    $exts[]=$array['ext'];
                    $user_ids[]=$array['user_id'];
                    $num_likes[]=$array['num_likes'];
                    $num_dislikes[]=$array['num_dislikes'];
                    $comment_ids[]=explode('|', $array['comment_ids']);
                    $timestamps[]=$array['timestamps'];
                    $num_favorites[]=$array['num_favorites'];
                    
                    //gets has liked
                    if(array_search($favorites[$x], $likes)!==false)
                        $has_liked[]=true;
                    else
                        $has_liked[]=false;
                    
                    //gets has disliked
                    if(array_search($favorites[$x], $dislikes)!==false)
                        $has_disliked[]=true;
                    else
                        $has_disliked[]=false;
                    
                    //gets num comments
                    if($comment_ids[$x][0]!='')
                        $num_comments[]=sizeof($comment_ids[$x]);
                    else
                        $num_comments[]=0;
                }
            }
        }
        else if(!$query)
            send_mail_error("profile_query.php: (3:1): ", mysql_error());

        $JSON=array();
        $JSON['images']=$temp_favorites;
        $JSON['exts']=$exts;
        $JSON['num_likes']=$num_likes;
        $JSON['num_dislikes']=$num_dislikes;
        $JSON['has_liked']=$has_liked;
        $JSON['has_disliked']=$has_disliked;
        $JSON['num_comments']=$num_comments;
        $JSON['timestamps']=$timestamps;
        $JSON['descriptions']=$descriptions;
        $JSON['displaying_all']=$displaying_all;
        $JSON['total_images']=$total_images;
        echo json_encode($JSON);
        exit();
    }
}
?>