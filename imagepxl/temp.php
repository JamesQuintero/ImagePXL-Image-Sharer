<?php
@include('init.php');
include('universal_functions.php');





//function reverse_recursive_explode($array, $string)
//{
//    $temp_array=array();
//    
//    $array=explode($string, $array);
//    
//    //takes off a | from string
//    $modified_string=substr($string, 0, strlen($string)-1);
//    for($x = 0; $x < sizeof($array); $x++)
//    {
//        //if still needs to be exploded
//        if(strpos($array[$x], '|')!==false)
//            $temp_array[]=reverse_recursive_explode($array[$x], $modified_string);
//        else
//            $temp_array[]=$array[$x];
//    }
//    
//    return $temp_array;
//}
//
//function recursive_explode($array, $string)
//{
//    //gets biggest child explode
//    $new_string="";
//    $new_replace_string="";
//    while(1==1)
//    {
//        if(strpos($array, $new_string."|")!==false)
//        {
//            $new_string.="|";
//            $new_replace_string.="<^&*>";
//        }
//        else
//            break;
//    }
//    
//    if($new_string!='')
//    {
//        //replaces || with <^&*><^&*> when exploding | 
//        while($string!=$new_string)
//        {
//            $array=str_replace($new_string, $new_replace_string, $array);
//            $new_string=substr($new_string, 0, strlen($new_string)-1);
//            $new_replace_string=substr($new_replace_string, 0, strlen($new_replace_string)-5);
//        }
//
//        //explodes 
//        $temp_array=explode($string, $array);
//
//        echo "String: ".$string."\n";
//        echo "Modified array: ".$array."\n\n";
//    //    print_r($temp_array);
//
//        $new_temp_array=array();
//        for($x = 0; $x < sizeof($temp_array); $x++)
//        {
//            $temp_array[$x]=str_replace("<^&*>", "|", $temp_array[$x]);
//            $temp_array[$x]=recursive_explode($temp_array[$x], $string."|");
//    //        $blah=array();
//    //        $blah=
//    //        $new_temp_array[$x]['comment']=str_replace("<^&*>", "|", $temp_array[$x]);
//        }
//
//    //    print_r($temp_array);
//    }
//    else
//        $temp_array=$array;
//    
//    return $temp_array;
//        
//}
//
//
//
//
//
//
//
//$comments="test comment||child to test|another test comment||child to second test|||child to second tests child|||second child to second tests child";
//
////$what_it_can_be=array();
////$what_it_can_be[0][0]="test_comment";
////$what_it_can_be[0][1][0]="child to test";
////$what_it_can_be[1][0]="another test comment";
////$what_it_can_be[1][1][0]="child to second test";
////$what_it_can_be[1][1][1][0]="child to second tests child";
////
////print_r($what_it_can_be);
////
////$what_it_can_be2=array();
////$what_it_can_be2[0]="test comment";
////$what_it_can_be2[1][0]="child to test";
////$what_it_can_be2[2]="another test comment";
////$what_it_can_be2[3][0]="child to second test";
////$what_it_can_be2[3][1][0]="child to second tests child";
////
////print_r($what_it_can_be2);
////
////$what_it_can_be3=array();
////$what_it_can_be3[0]['comment']="test comment";
////$what_it_can_be3[0]['child']['comment']="child to test";
////$what_it_can_be3[1]['comment']="another test comment";
////$what_it_can_be3[1]['child']['comment']="child to second test";
////$what_it_can_be3[1]['child']['child']['comment']="child to second tests child";
////
////print_r($what_it_can_be3);
//
////gets biggest child explode
//$string="";
//while(1==1)
//{
//    if(strpos($comments, $string."|")!==false)
//        $string.="|";
//    else
//        break;
//}
//
//echo "Comment: ".$comments."\n";
//echo "String: ".$string."\n";
//
////$comments=reverse_recursive_explode($comments, $string);
//$comments=recursive_explode($comments, "|");
//
//print_r($comments);


ini_set('display_errors', 'on');

//echo $_SERVER['REMOTE_ADDR'];
?>



<?php
//@include('init.php');
//include('universal_functions.php');
//$allowed="all";
//include('security_checks.php');


//$type=clean_string($_POST['type']);
//$content_type=clean_string($_POST['content_type']);
//$page=(int)($_POST['page'])*25;
//$timezone=(int)($_POST['timezone']);

$type='rising';
$content_type='images';
$page=25;
$timezone=420;


//gets current user's data
if(isset($_SESSION['id']))
{
    $query=mysql_query("SELECT likes, dislikes, following, favorites FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $following=explode('|', $array[0]);
        $likes=explode('|', $array[1]);
        $dislikes=explode('|', $array[2]);
        $favorites=explode('|', $array[3]);
    }
    else
    {
        $following=array();
        $likes=array();
        $dislikes=array();
        $favorites=array();
    }
}
else
{
    $following=array();
    $likes=array();
    $dislikes=array();
    $favorites=array();
}
    
if($content_type=='images')
{
    if($type=='rising')
        $query=mysql_query("SELECT image_id, description, ext, user_id, timestamp, album_id, num_likes, num_dislikes, views, num_favorites, nsfw FROM images ORDER BY ((log(num_likes+1-num_dislikes)*2)+((timestamp-1360994400)/45000)+(views/1000)) DESC LIMIT ".$page);
    else if($type=='all_time')
        $query=mysql_query("SELECT image_id, description, ext, user_id, timestamp, album_id, num_likes, num_dislikes, views, num_favorites, nsfw FROM images ORDER BY (num_likes-num_dislikes) DESC LIMIT ".$page);
    else if($type=='new')
        $query=mysql_query("SELECT image_id, description, ext, user_id, timestamp, album_id, num_likes, num_dislikes, views, num_favorites, nsfw FROM images ORDER BY timestamp DESC LIMIT ".$page);


    if($query)
    {
        $image_ids=array();
        $descriptions=array();
        $exts=array();
        $user_ids=array();
        $timestamps=array();
        $num_likes=array();
        $num_dislikes=array();
        $views=array();
        $has_liked=array();
        $has_disliked=array();
        $usernames=array();
        $is_following=array();
        $has_favorited=array();
        $num_favorites=array();
        $nsfw=array();
        $thumbnails=array();
        $album_ids=array();

        $index=0;
        while($array=mysql_fetch_array($query))
        {
            //only gets latest 25
            if($index>=$page-25)
            {
                $image_ids[]=$array['image_id'];
                $descriptions[]=$array['description'];
                $exts[]=$array['ext'];
                $user_ids[]=$array['user_id'];
                $timestamps[]=get_time_since($array['timestamp'], $timezone);
                $num_likes[]=$array['num_likes'];
                $num_dislikes[]=$array['num_dislikes'];
                $views[]=number_format($array['views']);
                $num_favorites[]=$array['num_favorites'];
                if($array['user_id']!=0)
                    $username=get_username($array['user_id']);
                else
                    $username='';
                
                $usernames[]=$username;
                $nsfw[]=$array['nsfw'];
                $album_ids[]=$array['album_id'];
                
                //gets thumbnails
                if($array['nsfw']=='false')
                {
                    if($username!='')
                        $thumbnails[]="http://i.imagepxl.com/$username/thumbs/".$array['image_id'].".".$array['ext'];
                    else
                        $thumbnails[]="http://i.imagepxl.com/thumbs/".$array['image_id'].".".$array['ext'];
                }
                else
                    $thumbnails[]="http://i.imagepxl.com/site/nsfw.png";
            }

            $index++;
        }
        
        //gets current user's likes and dislikes
        if($_SESSION['id'])
        {
            $query=mysql_query("SELECT likes, dislikes FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $likes=explode('|', $array[0]);
                $dislikes=explode('|', $array[1]);
                
                for($x = 0; $x < sizeof($image_ids); $x++)
                {
                    //gets has liked
                    if(array_search($image_ids[$x], $likes)!==false)
                        $has_liked[]=true;
                    else
                        $has_liked[]=false;

                    //gets has disliked
                    if(array_search($image_ids[$x], $dislikes)!==false)
                        $has_disliked[]=true;
                    else
                        $has_disliked[]=false;

                    //if is following user
                    if(array_search($image_ids[$x], $following)!==false)
                        $is_following[]=true;
                    else
                        $is_following[]=false;

                    //gets has favorites
                    if(array_search($image_ids[$x], $favorites)!==false)
                        $has_favorited[]=true;
                    else
                        $has_favorited[]=false;
                }
            }
            else if(!$query)
                send_mail_error("top_query.php: (1): ", mysql_error());
        }
        else
        {
            $has_liked[]=false;
            $has_disliked[]=false;
            $is_following[]=false;
            $has_favorited[]=false;
        }

        //gets num followers
        $num_followers=array();
        for($x = 0; $x < sizeof($user_ids); $x++)
        {
            if($user_ids[$x]!=0)
            {
                $query=mysql_query("SELECT num_followers FROM user_data WHERE user_id=$user_ids[$x] LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $num_followers[]=$array[0];
                }
                else
                    $num_followers[]=0;
            }
            else
                $num_followers[]=0;
        }
        
        //gets album info
        $album_names=array();
        $num_album_images=array();
        $album_image_thumbnails=array();
        $album_image_ids=array();
        $album_image_exts=array();
        for($x = 0; $x < sizeof($album_ids); $x++)
        {
            //if image is part of an album
            if($album_ids[$x]!='')
            {
                $query=mysql_query("SELECT album_name, image_ids, image_exts FROM albums WHERE album_id='$album_ids[$x]' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_array($query);
                    $album_names[$x]=$array['album_name'];
                    $album_image_ids[$x]=explode('|', $array['image_ids']);
                    $album_image_exts[$x]=explode('|', $array['image_exts']);
                    
                    //gets parameters for thumbnail loop
                    $num=sizeof($album_image_ids[$x])-1;
                    if($num>=5)
                        $end=$num-5;
                    else
                        $end=0;
                    
                    
                    for($y = $num; $y >= $end; $y--)
                    {
                        //gets thumbnails
                        $query=mysql_query("SELECT nsfw FROM images WHERE image_id='".$album_image_ids[$x][$y]."' LIMIT 1");
                        if($query&&mysql_num_rows($query)==1)
                        {
                            $array=mysql_fetch_row($query);
                            $nsfw=$array[0];
                            
                            //if image isn't NSFW
                            if($nsfw=='false')
                            {
                                //if an actual user uploaded it
                                if($usernames[$x]!='')
                                    $album_image_thumbnails[$x][]="http://i.imagepxl.com/".$usernames[$x]."/thumbs/".$album_image_ids[$x][$y].".".$album_image_ids[$x][$y];
                                else
                                    $album_image_thumbnails[$x][]="http://i.imagepxl.com/thumbs/".$album_image_ids[$x][$y].".".$album_image_ids[$x][$y];
                            }
                            else
                                $album_image_thumbnails[$x][]="http://i.imagepxl.com/site/nsfw.png";
                        }
                        else
                            $album_image_thumbnails[$x][]="http://i.imagepxl.com/site/no_image.png";
                    }
                }
            }
            else
            {
                $album_names[$x]='';
                $num_album_images[$x]=0;
                $album_image_thumbnails[$x]=array();
                $album_image_ids[$x]=array();
            }
        }

//        $JSON=array();
//        $JSON['image_ids']=$image_ids;
//        $JSON['descriptions']=$descriptions;
//        $JSON['exts']=$exts;
//        $JSON['user_ids']=$user_ids;
//        $JSON['usernames']=$usernames;
//        $JSON['timestamps']=$timestamps;
//        $JSON['has_liked']=$has_liked;
//        $JSON['has_disliked']=$has_disliked;
//        $JSON['num_likes']=$num_likes;
//        $JSON['num_dislikes']=$num_dislikes;
//        $JSON['views']=$views;
//        $JSON['num_followers']=$num_followers;
//        $JSON['is_following']=$is_following;
//        $JSON['has_favorited']=$has_favorited;
//        $JSON['num_favorites']=$num_favorites;
//        $JSON['thumbnails']=$thumbnails;
//        
//        $JSON['album_ids']=$album_ids;
//        $JSON['album_names']=$album_names;
//        $JSON['num_album_images']=$num_album_images;
//        $JSON['album_image_thumbnails']=$album_image_thumbnails;
//        $JSON['album_image_ids']=$album_image_ids;
//        echo json_encode($JSON);
//        exit();
        
        print_r($image_ids);
        print_r($album_ids);
        print_r($album_names);
        print_r($album_image_thumbnails);
        print_r($album_image_ids);
    }
}

else if($content_type=='users')
{
    
    if($type=='all_time')
        $query=mysql_query("SELECT user_id, username, num_followers, description, real_name, image_views, profile_views, num_images, date_joined FROM user_data ORDER BY num_followers DESC LIMIT ".$page);
    else if($type=='new')
        $query=mysql_query("SELECT user_id, username, num_followers, description, real_name, image_views, profile_views, num_images, date_joined FROM user_data ORDER BY date_joined DESC LIMIT ".$page);
    
    if($query)
    {
        $user_ids=array();
        $usernames=array();
        $num_followers=array();
        $descriptions=array();
        $real_names=array();
        $image_views=array();
        $profile_views=array();
        $num_images=array();
        $date_joined=array();
        $is_following=array();
        $profile_pictures=array();
        
        $index=0;
        while($array=mysql_fetch_array($query))
        {
            //only gets latest 25
            if($index>=$page-25)
            {
                $user_ids[]=$array['user_id'];
                $usernames[]=$array['username'];
                $num_followers[]=$array['num_followers'];
                $descriptions[]=$array['description'];
                $real_names[]=$array['real_name'];
                $image_views[]=$array['image_views'];
                $profile_views[]=$array['profile_views'];
                $num_images[]=$array['num_images'];
                $date_joined[]=get_time_since($array['date_joined'], $timezone);
                $profile_pictures[]=get_profile_picture($array['user_id']);
                
                if(array_search($array['user_id'], $following)!==false)
                    $is_following[]=true;
                else
                    $is_following[]=false;
            }
            
            $index++;
        }
        
        $JSON=array();
        $JSON['user_ids']=$user_ids;
        $JSON['usernames']=$usernames;
        $JSON['num_followers']=$num_followers;
        $JSON['descriptions']=$descriptions;
        $JSON['real_names']=$real_names;
        $JSON['image_views']=$image_views;
        $JSON['profile_views']=$profile_views;
        $JSON['num_images']=$num_images;
        $JSON['date_joined']=$date_joined;
        $JSON['profile_pictures']=$profile_pictures;
        $JSON['is_following']=$is_following;
        echo json_encode($JSON);
        exit();
    }
}
?>