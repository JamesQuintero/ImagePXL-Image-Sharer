<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');




$num=(int)($_POST['num']);
//$num=1;

if($num==1)
{
   $timezone=(int)($_POST['timezone']);
   $page=(int)($_POST['page'])*30;
   $user_id=(int)($_POST['user_id']);
   
   
   if(is_id($user_id))
   {
        $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
           $array=mysql_fetch_row($query);
           $following=explode('|', $array[0]);
           if($array[0]!='')
               $following[]=$_SESSION['id'];
           else
               $following[0]=$_SESSION['id'];
           
           //if specified user and is following
           if($user_id!=-1)
           {
               $following=array();
               $following[0]=$user_id;
           }

           $image_ids=array();
           $descriptions=array();
           $exts=array();
           $user_ids=array();
           $timestamps=array();
           $album_ids=array();
           $num_likes=array();
           $num_dislikes=array();
           $comment_ids=array();
           $num_favorites=array();
           $views=array();
           $nsfw=array();


           //gets at most 100 images from each following
           for($x = 0; $x < sizeof($following); $x++)
           {
               $query=mysql_query("SELECT * FROM images WHERE user_id=$following[$x] ORDER BY timestamp DESC LIMIT 200");
               if($query&&mysql_num_rows($query)>=1)
               {
                   while($array=mysql_fetch_array($query))
                   {
                         $image_ids[]=$array['image_id'];
                         $descriptions[]=$array['description'];
                         $exts[]=$array['ext'];
                         $user_ids[]=$array['user_id'];
                         $timestamps[]=$array['timestamp'];
                         $album_ids[]=$array['album_id'];
                         $num_likes[]=$array['num_likes'];
                         $num_dislikes[]=$array['num_dislikes'];
                         $comment_ids[]=explode('|', $array['comment_ids']);
                         $num_favorites[]=$array['num_favorites'];
                         $views[]=$array['views'];
                         $nsfw[]=$array['nsfw'];
                   }
               }
               else if(!$query)
                   send_mail_error("home_query.php: (1:1): ", mysql_error());
           }

           //sorts things in chronological order
             $array_timestamps=$timestamps;
             sort($timestamps, SORT_NUMERIC);

             $temp_image_ids=array();
             $temp_descriptions=array();
             $temp_exts=array();
             $temp_user_ids=array();
             $temp_album_ids=array();
             $temp_num_likes=array();
             $temp_num_dislikes=array();
             $temp_comment_ids=array();
             $temp_num_favorites=array();
             $temp_views=array();
             $temp_nsfw=array();


             //rearranges rest of data according to sorted timestamps compared to previously unsorted timestamps
             for($x = 0; $x < sizeof($timestamps); $x++)
             {
                 $number=array_search($timestamps[$x], $array_timestamps);
                 $array_timestamps[$number]='';


                 $temp_image_ids[]=$image_ids[$number];
                 $temp_descriptions[]=$descriptions[$number];
                 $temp_exts[]=$exts[$number];
                 $temp_user_ids[]=$user_ids[$number];
                 $temp_album_ids[]=$album_ids[$number];
                 $temp_num_likes[]=$num_likes[$number];
                 $temp_num_dislikes[]=$num_dislikes[$number];
                 $temp_comment_ids[]=$comment_ids[$number];
                 $temp_num_favorites[]=$num_favorites[$number];
                 $temp_views[]=$views[$number];
                 $temp_nsfw[]=$nsfw[$number];
             }

             $image_ids=$temp_image_ids;
             $descriptions=$temp_descriptions;
             $exts=$temp_exts;
             $user_ids=$temp_user_ids;
             $album_ids=$temp_album_ids;
             $num_likes=$temp_num_likes;
             $num_dislikes=$temp_num_dislikes;
             $comment_ids=$temp_comment_ids;
             $num_favorites=$temp_num_favorites;
             $views=$temp_views;
             $nsfw=$temp_nsfw;


             ////////////////converts a group of images to albums
             $longest=array();
             $type=array();
             $index=0;
             for($x = 0; $x < sizeof($user_ids); $x++)
             {
                 $longest[$index][]=$x;

                 //if there are no more albums by the same user
                 if($album_ids[$x]!=$album_ids[$x+1]||$user_ids[$x]!=$user_ids[$x+1])
                     $index++;
             }



             //sets all grouped images to 'album'
             for($x = 0; $x < sizeof($longest); $x++)
             {
                 for($y = 0; $y < sizeof($longest[$x]); $y++)
                 {
                     if(sizeof($longest[$x])>=2)
                         $type[$longest[$x][$y]]='album';
                     else
                         $type[$longest[$x][$y]]='image';
                 }
             }


             $temp_image_ids=array();
             $temp_descriptions=array();
             $temp_exts=array();
             $temp_user_ids=array();
             $temp_album_ids=array();
             $temp_num_likes=array();
             $temp_num_dislikes=array();
             $temp_comment_ids=array();
             $temp_num_favorites=array();
             $temp_views=array();
             $temp_timestamps=array();
             $temp_nsfw=array();


             $index=0;
             for($x = 0; $x < sizeof($type); $x++)
             {
                 if($type[$x]=='album')
                 {
                     $temp_type[$index]="album";
                     $temp_album_ids[$index]=$album_ids[$x];
                     $temp_image_ids[$index][]=$image_ids[$x];
                     $temp_descriptions[$index][]=$descriptions[$x];
                     $temp_exts[$index][]=$exts[$x];
                     $temp_user_ids[$index]=$user_ids[$x];
                     $temp_usernames[$index]=get_username($user_ids[$x]);
                     $temp_timestamps[$index][]=$timestamps[$x];
                     $temp_num_likes[$index][]=$num_likes[$x];
                     $temp_num_dislikes[$index][]=$num_dislikes[$x];
                     $temp_num_favorites[$index][]=$num_favorites[$x];
                     $temp_views[$index][]=$views[$x];
                     $temp_nsfw[$index][]=$nsfw[$x];

                     if((isset($type[$x+1])&&$type[$x+1]!='album')||(isset($album_ids[$x+1])&&$album_ids[$x+1]!=$album_ids[$x]))
                         $index++;
                 }
                 else
                 {
                     $temp_type[$index]="image";
                     $temp_album_ids[$index]=$album_ids[$x];
                     $temp_image_ids[$index][0]=$image_ids[$x];
                     $temp_descriptions[$index][0]=$descriptions[$x];
                     $temp_exts[$index][0]=$exts[$x];
                     $temp_user_ids[$index]=$user_ids[$x];
                     $temp_usernames[$index]=get_username($user_ids[$x]);
                     $temp_timestamps[$index][0]=$timestamps[$x];
                     $temp_num_likes[$index][0]=$num_likes[$x];
                     $temp_num_dislikes[$index][0]=$num_dislikes[$x];
                     $temp_num_favorites[$index][0]=$num_favorites[$x];
                     $temp_views[$index][0]=$views[$x];
                     $temp_nsfw[$index][0]=$nsfw[$x];
                     $index++;
                 }
             }

             //cuts down to $page size
             $type=array();
             $album_ids=array();
             $image_ids=array();
             $descriptions=array();
             $exts=array();
             $user_ids=array();
             $usernames=array();
             $timestamps=array();
             $num_likes=array();
             $num_dislikes=array();
             $num_favorites=array();
             $views=array();
             $nsfw=array();
             for($x = sizeof($temp_type); $x > sizeof($temp_type)-$page; $x--)
             {
                 $type[]=$temp_type[$x];
                 $album_ids[]=$temp_album_ids[$x];
                 $image_ids[]=$temp_image_ids[$x];
                 $descriptions[]=$temp_descriptions[$x];
                 $exts[]=$temp_exts[$x];
                 $user_ids[]=$temp_user_ids[$x];
                 $usernames[]=$temp_usernames[$x];
                 $timestamps[]=$temp_timestamps[$x];
                 $num_likes[]=$temp_num_likes[$x];
                 $num_dislikes[]=$temp_num_dislikes[$x];
                 $num_favorites[]=$temp_num_favorites[$x];
                 $nsfw[]=$temp_nsfw[$x];
             }
             
             //gets current user's likes and dislikes
             $query=mysql_query("SELECT likes, dislikes FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
             if($query&&mysql_num_rows($query)==1)
             {
                 $array=mysql_fetch_row($query);
                 $likes=explode('|', $array[0]);
                 $dislikes=explode('|', $array[1]);
                 
             }


             $album_names=array();
             $has_liked=array();
             $has_disliked=array();
             $num_album_images=array();
             $profile_pictures=array();
             $temp_timestamps=array();
             $thumbnails=array();
             for($x = 0; $x < sizeof($image_ids); $x++)
             {
                 //gets profile pictures
                 $profile_pictures[]=get_profile_picture($user_ids[$x]);

                 //gets album names for images
                 if($album_ids[$x]!='')
                 {
                     $query=mysql_query("SELECT album_name, image_ids, image_exts FROM albums WHERE album_id='$album_ids[$x]' LIMIT 1");
                     if($query&&mysql_num_rows($query)==1)
                     {
                         $array=mysql_fetch_row($query);
                         $album_names[]=$array[0];
                         $album_images=explode('|', $array[1]);
                         $album_image_exts=explode('|', $array[2]);

                         //gets num album images
                         if($array[1]!='')
                             $num_album_images[]=sizeof($album_images);
                         else
                             $num_album_images[]=0;
                     }
                 }
                 else
                 {
                     $album_names[]='';
                     $num_album_images[]=0;
                     $album_images[]=array();
                     $album_image_exts[]=array();
                 }

                 for($y = 0; $y < sizeof($image_ids[$x]); $y++)
                 {
                     //gets has liked
                     if(array_search($image_ids[$x][$y], $likes)!==false)
                         $has_liked[$x][]=true;
                     else
                         $has_liked[$x][]=false;

                     //gets has disliked
                     if(array_search($image_ids[$x][$y], $dislikes)!==false)
                         $has_disliked[$x][]=true;
                     else
                         $has_disliked[$x][]=false;

                     //gets timestamps
                     $temp_timestamps[$x][]=get_time_since($timestamps[$x][$y], $timezone);
                 }
                 
                 //gets thumbnails
                 for($y = 0; $y < sizeof($image_ids[$x]); $y++)
                 {
                    if($nsfw[$x][$y]=="true")
                        $thumbnails[$x][$y]="http://i.imagepxl.com/site/nsfw.png";
                    else
                        $thumbnails[$x][$y]="http://i.imagepxl.com/".$usernames[$x]."/thumbs/".$image_ids[$x][$y].".".$exts[$x][$y];
                 }
             }



              $JSON=array();
              $JSON['type']=$type;
              $JSON['image_ids']=$image_ids;
              $JSON['image_exts']=$exts;
     //         $JSON['album_image_ids']=$album_image_ids;
     //         $JSON['album_exts']=$album_exts;
              $JSON['user_ids']=$user_ids;
              $JSON['usernames']=$usernames;
              $JSON['descriptions']=$descriptions;
              $JSON['num_views']=$views;
              $JSON['timestamps']=$temp_timestamps;
     //         $JSON['album_belongs_thumbnails']=$album_belongs_thumbnails;
     //         $JSON['album_belongs_thumbnails_exts']=$album_belongs_thumbnails_exts;
              $JSON['num_likes']=$num_likes;
              $JSON['num_dislikes']=$num_dislikes;
              $JSON['has_liked']=$has_liked;
              $JSON['has_disliked']=$has_disliked;
              $JSON['album_ids']=$album_ids;
              $JSON['num_album_images']=$num_album_images;
              $JSON['album_names']=$album_names;
              $JSON['profile_pictures']=$profile_pictures;
              $JSON['thumbnails']=$thumbnails;
              $JSON['nsfw']=$nsfw;
              echo json_encode($JSON);
              exit();




           }
           else
               send_mail_error("home_query.php: (1:2): ", mysql_error());
   }
}

//gets following
else if($num==2)
{
    $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $following=explode('|', $array[0]);
        
        //gets extra info
        $profile_pictures=array();
        $usernames=array();
        $num_followers=array();
        for($x = 0; $x < sizeof($following); $x++)
        {
            $profile_pictures[]=get_profile_picture($following[$x]);
            
            //gets username and num_followers
            $query=mysql_query("SELECT username, num_followers FROM user_data WHERE user_id=$following[$x] LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $usernames[]=$array[0];
                $num_followers[]=$array[1];
            }
        }
        
        $JSON=array();
        $JSON['following']=$following;
        $JSON['usernames']=$usernames;
        $JSON['profile_pictures']=$profile_pictures;
        $JSON['num_followers']=$num_followers;
        echo json_encode($JSON);
        exit();
    }
}












//$num=(int)($_POST['num']);
////$num=1;
//
//if($num==1)
//{
//   $sort=(int)($_POST['sort']);
//   $timezone=(int)($_POST['timezone']);
//   $page=(int)($_POST['page'])*30;
////    $sort=1;
////    $page=30;
////    $timezone=420;
//   
//   $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
//   if($query&&mysql_num_rows($query)==1)
//   {
//      $array=mysql_fetch_row($query);
//      $following=explode('|', $array[0]);
//      if($array[0]!='')
//        $following[]=$_SESSION['id'];
//      else
//          $following[0]=$_SESSION['id'];
//      
//      //finds images belonging to everyone in following array
//      $sql_string="";
//      for($x = 0; $x < sizeof($following); $x++)
//      {
//          if($x==sizeof($following)-1)
//              $sql_string.="user_id=$following[$x]";
//          else
//                $sql_string.="user_id=$following[$x] OR ";
//      }
//      
//      //gets all image information from newest number $page
//      
//      if($sort==0||$sort==1)
//        $query=mysql_query("SELECT * FROM albums WHERE ".$sql_string." AND image_ids!='' ORDER BY timestamp DESC LIMIT ".$page);
//      
//      if($query)
//      {
//         $album_ids=array();
//         $user_ids=array();
//         $timestamps=array();
//         
//         if(mysql_num_rows($query)!=0)
//         {
//            $index=0;
//           while($array=mysql_fetch_array($query))
//           {
//               if($index>=$page-30)
//               {
//                   $album_ids[]=$array['album_id'];
//               }
//
//               $index++;
//            }
//         }
//         else
//             $album_ids[0]="";
//         
//         //gets all images
//         $image_ids=array();
//         $descriptions=array();
//         $exts=array();
//         $likes=array();
//         $dislikes=array();
//         $num_likes=array();
//         $num_dislikes=array();
//         $num_favorites=array();
//         $views=array();
//         $album_belongs=array();
//         
//         for($x = 0; $x < sizeof($album_ids); $x++)
//         {
//             $query=mysql_query("SELECT * FROM images WHERE album_id='$album_ids[$x]' LIMIT 5");
//             if($query&&mysql_num_rows($query)>=1)
//             {
//                 while($array=mysql_fetch_array($query))
//                 {
//                    $image_ids[]=$array['image_id'];
//                    $descriptions[]=$array['description'];
//                    $exts[]=$array['ext'];
//                    $user_ids[]=$array['user_id'];
//                    $timestamps[]=$array['timestamp'];
//                    $likes[]=explode('|', $array['likes']);
//                    $dislikes[]=explode('|', $array['dislikes']);
//                    $num_likes[]=$array['num_likes'];
//                    $num_dislikes[]=$array['num_dislikes'];
//                    $num_favorites[]=$array['num_favorites'];
//                    $views[]=$array['views'];
//                    $album_belongs[]=$album_ids[$x];
//                 }
//             }
//         }
//         
//         
//        //sorts things in chronological order
//        $array_timestamps=$timestamps;
//        sort($timestamps, SORT_NUMERIC);
//
//
//        $temp_image_ids=array();
//        $temp_descriptions=array();
//        $temp_exts=array();
//        $temp_user_ids=array();
//        $temp_timestamps=array();
//        $temp_likes=array();
//        $temp_dislikes=array();
//        $temp_num_likes=array();
//        $temp_num_dislikes=array();
//        $temp_num_favorites=array();
//        $temp_views=array();
//        $temp_album_belongs=array();
//
//
//        //rearranges rest of data according to sorted timestamps compared to previously unsorted timestamps
//        for($x = 0; $x < sizeof($timestamps); $x++)
//        {
//            $number=array_search($timestamps[$x], $array_timestamps);
//            $array_timestamps[$number]='';
//            
//
//            $temp_image_ids[$x]=$image_ids[$number];
//            $temp_descriptions[$x]=$descriptions[$number];
//            $temp_exts[$x]=$exts[$number];
//            $temp_user_ids[$x]=$user_ids[$number];
//            $temp_likes[$x]=$likes[$number];
//            $temp_dislikes[$x]=$dislikes[$number];
//            $temp_num_likes[$x]=$num_likes[$number];
//            $temp_num_dislikes[$x]=$num_dislikes[$number];
//            $temp_num_favorites[$x]=$num_favorites[$number];
//            $temp_views[$x]=$views[$number];
//            $temp_album_belongs[$x]=$album_belongs[$number];
//        }
//        
//        $image_ids=$temp_image_ids;
//        $descriptions=$temp_descriptions;
//        $exts=$temp_exts;
//        $user_ids=$temp_user_ids;
//        $likes=$temp_likes;
//        $dislikes=$temp_dislikes;
//        $num_likes=$temp_num_likes;
//        $num_dislikes=$temp_num_dislikes;
//        $num_favorites=$temp_num_favorites;
//        $views=$temp_views;
//        $album_belongs=$temp_album_belongs;
//        
//        
//        
//        
//        ////////////////converts a group of images to albums
//        $longest=array();
//        $type=array();
//        $index=0;
//        for($x = 0; $x < sizeof($user_ids); $x++)
//        {
//            $longest[$index][]=$x;
//
//            //if there are no more photos by the same user
//            if($album_belongs[$x]!=$album_belongs[$x+1])
//                $index++;
//        }
//        
//
//
//        //sets all grouped photos to 'group_photo'
//        for($x = 0; $x < sizeof($longest); $x++)
//        {
//                for($y = 0; $y < sizeof($longest[$x]); $y++)
//                {
//                    if(sizeof($longest[$x])>=4)
//                        $type[$longest[$x][$y]]='album';
//                    else
//                        $type[$longest[$x][$y]]='image';
//                }
//        }
//
//
//        $temp_image_ids=array();
//        $temp_descriptions=array();
//        $temp_exts=array();
//        $temp_user_ids=array();
//        $temp_timestamps=array();
//        $temp_likes=array();
//        $temp_dislikes=array();
//        $temp_num_likes=array();
//        $temp_num_dislikes=array();
//        $temp_num_favorites=array();
//        $temp_views=array();
//        $temp_album_belongs=array();
//        $temp_album_image_ids=array();
//        $temp_type=array();
//        $temp_usernames=array();
//
//        $index=0;
//        for($x = 0; $x < sizeof($type); $x++)
//        {
//            if($type[$x]=='album')
//            {
//                $temp_type[$index]="album";
//                $temp_image_ids[$index]=$album_belongs[$x];
//                $temp_descriptions[$index]='';
//                $temp_exts[$index]='';
//                $temp_user_ids[$index]=$user_ids[$x];
//                $temp_usernames[$index]=get_username($user_ids[$x]);
//                $temp_timestamps[$index]=$timestamps[$x];
//                $temp_likes[$index]='';
//                $temp_dislikes[$index]='';
//                $temp_num_likes[$index]=0;
//                $temp_num_dislikes[$index]=0;
//                $temp_num_favorites[$index]='';
//                $temp_views[$index]=0;
//                $temp_album_belongs[$index]=$album_belongs[$x];
//                $temp_album_image_ids[$index][]=$image_ids[$x];
//                $temp_album_exts[$index][]=$exts[$x];
//
//                if((isset($type[$x+1])&&$type[$x+1]!='album')||(isset($album_belongs[$x+1])&&$album_belongs[$x+1]!=$album_belongs[$x]))
//                    $index++;
//            }
//            else
//            {
//                $temp_type[$index]="image";
//                $temp_image_ids[$index]=$image_ids[$x];
//                $temp_descriptions[$index]=$descriptions[$x];
//                $temp_exts[$index]=$exts[$x];
//                $temp_user_ids[$index]=$user_ids[$x];
//                $temp_usernames[$index]=get_username($user_ids[$x]);
//                $temp_timestamps[$index]=$timestamps[$x];
//                $temp_likes[$index]=$likes[$x];
//                $temp_dislikes[$index]=$dislikes[$x];
//                $temp_num_likes[$index]=$num_likes[$x];
//                $temp_num_dislikes[$index]=$num_dislikes[$x];
//                $temp_num_favorites[$index]=$num_favorites[$x];
//                $temp_views[$index]=$views[$x];
//                $temp_album_belongs[$index]=$album_belongs[$x];
//                $temp_album_image_ids[$index]='';
//                $temp_album_exts[$index]='';
//                $index++;
//            }
//        }
//        
//        $album_belongs_names=array();
//        $album_belongs_thumbnails=array();
//        $album_belongs_thumbnails_exts=array();
//        $timestamps=array();
//        $has_liked=array();
//        $has_disliked=array();
//        for($x = 0; $x < sizeof($temp_album_belongs); $x++)
//        {
//            //gets timestamps
//            $timestamps[]=get_time_since($temp_timestamps[$x], $timezone);
//            
//            //gets album names for images
//            $query=mysql_query("SELECT album_name, image_ids, image_exts FROM albums WHERE album_id='$temp_album_belongs[$x]' LIMIT 1");
//            if($query&&mysql_num_rows($query)==1)
//            {
//                $array=mysql_fetch_row($query);
//                $album_belongs_names[]=$array[0];
//                $album_images=explode('|', $array[1]);
//                $album_image_exts=explode('|', $array[2]);
//                
//                //gets num album images
//                if($array[1]!='')
//                    $num_album_images[]=sizeof($album_images);
//                else
//                    $num_album_images[]=0;
//                
//                //gets thumbnails for album belongs
//                $album_belongs_thumbnails[$x]=array();
//                for($y = 0; $y < 5; $y++)
//                {
//                    if(isset($album_images[$y]))
//                    {
//                        $album_belongs_thumbnails[$x][]=$album_images[$y];
//                        $album_belongs_thumbnails_exts[$x][]=$album_image_exts[$y];
//                    }
//                    else
//                    {
//                        $album_belongs_thumbnails[$x][]='';
//                        $album_belongs_thumbnails_exts[$x][]='';
//                    }
//                }
//            }
//            
//            //gets has liked
//            if(array_search($_SESSION['id'], $temp_likes[$index])!==false)
//                $has_liked[]=true;
//            else
//                $has_liked[]=false;
//            
//            //gets has disliked
//            if(array_search($_SESSION['id'], $temp_dislikes[$index])!==false)
//                $has_disliked[]=true;
//            else
//                $has_disliked[]=false;
//        }
//
//                
//        
//         $JSON=array();
//         $JSON['type']=$temp_type;
//         $JSON['image_ids']=$temp_image_ids;
//         $JSON['image_exts']=$temp_exts;
//         $JSON['album_image_ids']=$temp_album_image_ids;
//         $JSON['album_exts']=$temp_album_exts;
//         $JSON['user_ids']=$temp_user_ids;
//         $JSON['usernames']=$temp_usernames;
//         $JSON['descriptions']=$temp_descriptions;
//         $JSON['num_views']=$temp_views;
//         $JSON['timestamps']=$timestamps;
//         $JSON['album_belongs']=$temp_album_belongs;
//         $JSON['album_belongs_names']=$album_belongs_names;
//         $JSON['album_belongs_thumbnails']=$album_belongs_thumbnails;
//         $JSON['album_belongs_thumbnails_exts']=$album_belongs_thumbnails_exts;
//         $JSON['num_likes']=$temp_num_likes;
//         $JSON['num_dislikes']=$temp_num_dislikes;
//         $JSON['has_liked']=$has_liked;
//         $JSON['has_disliked']=$has_disliked;
//         $JSON['num_album_images']=$num_album_images;
//         echo json_encode($JSON);
//         exit();
//         
//         
//         
//         
//      }
//      else
//      {
//          echo "Something went wrong. We are working on fixing it";
//          send_mail_error("home_query.php: (1:2): ", mysql_error());
//      }
//   }
//   else
//   {
//       echo "Something went wrong. We are working on fixing it";
//      send_mail_error("home_query.php: (1:1): ", mysql_error());
//   }
//}






//$num=(int)($_POST['num']);
////$num=1;
//
//if($num==1)
//{
//   $sort=(int)($_POST['sort']);
//   $page=(int)($_POST['page'])*30;
////    $sort=0;
////    $page=30;
//   
//   $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
//   if($query&&mysql_num_rows($query)==1)
//   {
//      $array=mysql_fetch_row($query);
//      $following=explode('|', $array[0]);
//      $following[]=$_SESSION['id'];
//      
//      //finds images belonging to everyone in following array
//      $sql_string="";
//      for($x = 0; $x < sizeof($following); $x++)
//      {
//          if($x==sizeof($following)-1)
//              $sql_string.="user_id=$following[$x]";
//          else
//                $sql_string.="user_id=$following[$x] OR ";
//      }
//      
//      //gets all image information from newest number $page
//      
//      if($sort==0)
//            $query=mysql_query("SELECT * FROM images WHERE ".$sql_string." ORDER BY timestamp DESC LIMIT ".$page);
//      else
//          $query=mysql_query("SELECT * FROM images WHERE ".$sql_string." ORDER BY (log(num_likes-num_dislikes)+((timestamp-1360994400)/45000)) DESC LIMIT ".$page);
//      
//      if($query&&mysql_num_rows($query)>=1)
//      {
//         $image_ids=array();
//         $descriptions=array();
//         $exts=array();
//         $user_ids=array();
//         $timestamps=array();
//         $likes=array();
//         $dislikes=array();
//         $num_likes=array();
//         $num_dislikes=array();
//         $comments=array();
//         $comment_likes=array();
//         $comment_dislikes=array();
//         $comment_user_ids=array();
//         $num_favorites=array();
//         $views=array();
//         $album_ids=array();
//         
//         $index=0;
//        while($array=mysql_fetch_array($query))
//        {
//            //only gets latest 30
//            if($index>=$page-30)
//            {
//                $image_ids[]=$array['image_id'];
//                $descriptions[]=$array['description'];
//                $exts[]=$array['ext'];
//                $user_ids[]=$array['user_id'];
//                $timestamps[]=$array['timestamp'];
//                $likes[]=explode('|', $array['likes']);
//                $dislikes[]=explode('|', $array['dislikes']);
//                $num_likes[]=explode('|', $array['num_likes']);
//                $num_dislikes[]=explode('|', $array['num_dislikes']);
//                $comments[]=explode('|^|*|', $array['comments']);
//                $comment_likes[]=explode('|', $array['comment_likes']);
//                $comment_dislikes[]=explode('|', $array['comment_dislikes']);
//                $comment_user_ids[]=explode('|', $array['comment_user_ids']);
//                $num_favorites[]=$array['num_favorites'];
//                $views[]=$array['views'];
//                $album_ids[]=$array['album_id'];
//            }
//            $index++;
//         }
//         
//         $has_liked=array();
//         $has_disliked=array();
//         $num_comment_likes=array();
//         $num_comment_dislikes=array();
//         $has_liked_comment=array();
//         $has_disliked_comment=array();
//         $usernames=array();
//         $existing_usernames=array();
//         $album_names=array();
//         $album_num_images=array();
//         for($x = 0; $x < sizeof($likes); $x++)
//         {
//             //gets usernames
//             $index=array_search($user_ids[$x], $existing_usernames);
//             if($index===false)
//             {
//                 $username=get_username($user_ids[$x]);
//                 $usernames[]=$username;
//                 $existing_usernames[]=$username;
//             }
//             else
//                 $usernames[]=$existing_usernames[$index];
//             
//             
//             //gets has liked
//             if(array_search($_SESSION['id'], $likes[$x])!==false)
//                 $has_liked[]=true;
//             else
//                 $has_liked[]=false;
//             
//             //gets has disliked
//             if(array_search($_SESSION['id'], $dislikes[$x])!==false)
//                 $has_disliked[]=true;
//             else
//                 $has_disliked[]=false;
//             
//             //gets comment num likes, num dislikes, has liked, and has disliked
//             for($y = 0; $y < sizeof($comment_likes[$x]); $y++)
//             {
//                 $comment_likes[$x][$y]=explode('^', $comment_likes[$x][$y]);
//                $comment_dislikes[$x][$y]=explode('^', $comment_dislikes[$x][$y]);
//             
//                //gets num comment likes
//                 if($comment_likes[$x][$y][0]!='')
//                     $num_comment_likes[$x][$y]=sizeof($comment_likes[$x][$y]);
//                 else
//                     $num_comment_likes[$x][$y]=0;
//                 
//                 //gets num comment dislikes
//                 if($comment_dislikes[$x][$y][0]!='')
//                     $num_comment_dislikes[$x][$y]=sizeof($comment_dislikes[$x][$y]);
//                 else
//                     $num_comment_dislikes[$x][$y]=0;
//                 
//                 //gets has liked
//                if(array_search($_SESSION['id'], $comment_likes[$x][$y])!==false)
//                    $has_liked_comment[$x][]=true;
//                else
//                    $has_liked_comment[$x][]=false;
//                
//                //gets has liked
//                if(array_search($_SESSION['id'], $comment_dislikes[$x][$y])!==false)
//                    $has_disliked_comment[$x][]=true;
//                else
//                    $has_disliked_comment[$x][]=false;
//             }
//             
//             //gets album info
//             $query=mysql_query("SELECT album_name, image_ids, image_exts FROM albums WHERE album_id='$album_ids[$x]' LIMIT 1");
//             if($query&&mysql_num_rows($query)==1)
//             {
//                 $array=mysql_fetch_row($query);
//                 $album_names[]=$array[0];
//                 $image_ids=explode('|', $array[1]);
//                 $image_exts=explode('|', $array[2]);
//                 
//                 if($array[2]!='')
//                    $album_num_images[]=sizeof($image_exts);
//                 else
//                     $album_num_images[]=0;
//             }
//         }
//         
//         $JSON=array();
//         $JSON['type']=$type;
//         $JSON['image_ids']=$image_ids;
//         $JSON['image_exts']=$exts;
//         $JSON['user_ids']=$user_ids;
//         $JSON['usernames']=$usernames;
//         $JSON['timestamps']=$timestamps;
//         $JSON['num_likes']=$num_likes;
//         $JSON['num_dislikes']=$num_dislikes;
//         $JSON['has_liked']=$has_liked;
//         $JSON['has_disliked']=$has_disliked;
//         $JSON['comment_num_likes']=$num_comment_likes;
//         $JSON['comment_num_dislikes']=$num_comment_dislikes;
//         $JSON['comment_has_liked']=$has_liked_comment;
//         $JSON['comment_has_disliked']=$has_disliked_comment;
//         $JSON['comment_user_ids']=$comment_user_ids;
//         $JSON['num_favorites']=$num_favorites;
//         $JSON['num_views']=$views;
//         $JSON['descriptions']=$descriptions;
//         $JSON['album_names']=$album_names;
//         $JSON['album_ids']=$album_ids;
//         $JSON['album_num_images']=$album_num_images;
//         echo json_encode($JSON);
//         exit();
//      }
//      else
//      {
//          echo "Something went wrong. We are working on fixing it";
//          nd_mail_error("home_query.php: (1:2): ", mysql_error());
//      }
//   }
//   else
//   {
//       echo "Something went wrong. We are working on fixing it";
//          send_mail_error("home_query.php: (1:1): ", mysql_error());
//   }
//}

?>