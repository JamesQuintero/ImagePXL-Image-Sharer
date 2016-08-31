<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$num=(int)($_POST['num']);

//gets image information
if($num==1)
{
    $image_id=clean_string($_POST['image_id']);
    $timezone=(int)($_POST['timezone']);
    
    if($image_id!='')
    {
        
        $query=mysql_query("SELECT * FROM images WHERE image_id='$image_id' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_array($query);
            $description=$array['description'];
            $timestamp=$array['timestamp'];
            $num_likes=$array['num_likes'];
            $num_dislikes=$array['num_dislikes'];
            $comment_ids=explode('|', $array['comment_ids']);
            $comments=explode('|^|*|', $array['comments']);
            $comment_likes=explode('|', $array['comment_likes']);
            $comment_dislikes=explode('|', $array['comment_dislikes']);
            $comment_user_ids=explode('|', $array['comment_user_ids']);
            $comment_timestamps=explode('|', $array['comment_timestamps']);
            $num_favorites=$array['num_favorites'];
            $views=convert_number($array['views']);
            $album_id=$array['album_id'];

            //gets the album name
            $album_name=get_album_name($album_id);

            $upload_date=get_time_since($timestamp, 0);
            if(isset($_SESSION['id']))
            {
                //gets current user's likes and disliked list of images
                $query=mysql_query("SELECT likes, dislikes, favorites FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_Row($query);
                    $likes=explode('|', $array[0]);
                    $dislikes=explode('|', $array[1]);
                    $favorites=explode('|', $array[2]);
                }

                //gets if has liked
                if(array_search($image_id, $likes)!==false)
                    $has_liked=true;
                else
                    $has_liked=false;

                //gets if has disliked
                if(array_search($image_id, $dislikes)!==false)
                    $has_disliked=true;
                else
                    $has_disliked=false;
                
                //gets has favorited
                if(array_search($image_id, $favorites)!==false)
                    $has_favorited=true;
                else
                    $has_favorited=false;
            }
            else
            {
                $has_liked=false;
                $has_disliked=false;
                $has_favorited=false;
            }


            $num_comment_likes=array();
            $num_comment_dislikes=array();
            $comment_usernames=array();
            $comment_profile_pictures=array();
            for($x = 0; $x < sizeof($comment_likes); $x++)
            {
                //explodes comment likes and dislikes
                $comment_likes[$x]=explode('^', $comment_likes[$x]);
                $comment_dislikes[$x]=explode('^', $comment_dislikes[$x]);

                $comment_timestamps[$x]=get_time_since($comment_timestamps[$x], $timezone);

                $comment_profile_pictures[$x]=get_profile_picture($comment_user_ids[$x]);

                //gets num comment likes
                if($comment_likes[$x][0]=='')
                    $num_comment_likes[]=0;
                else
                    $num_comment_likes[]=sizeof($comment_likes[$x]);

                //gets num comment dislikes
                if($comment_dislikes[$x][0]=='')
                    $num_comment_dislikes[]=0;
                else
                    $num_comment_dislikes[]=sizeof($comment_dislikes[$x]);

                if(isset($_SESSION['id']))
                {
                    //gets if has liked comment
                    if(array_search($_SESSION['id'], $comment_likes[$x])!==false)
                        $has_liked_comments[]=true;
                    else
                        $has_liked_comments[]=false;

                    //gets if has disliked comment
                    if(array_search($_SESSION['id'], $comment_dislikes[$x])!==false)
                        $has_disliked_comments[]=true;
                    else
                        $has_disliked_comments[]=false;
                }
                else
                {
                    $has_liked_comments[]=false;
                    $has_disliked_comments[]=false;
                }

                //gets comment usernames
                $comment_usernames[$x]=get_username($comment_user_ids[$x]);

            }


            $JSON=array();
            $JSON['description']=$description;
            $JSON['upload_date']=$upload_date;
            $JSON['num_likes']=$num_likes;
            $JSON['num_dislikes']=$num_dislikes;
            $JSON['has_liked']=$has_liked;
            $JSON['has_disliked']=$has_disliked;
            $JSON['comments']=$comments;
            $JSON['comment_usernames']=$comment_usernames;
            $JSON['num_comment_likes']=$num_comment_likes;
            $JSON['num_comment_dislikes']=$num_comment_dislikes;
            $JSON['has_liked_comments']=$has_liked_comments;
            $JSON['has_disliked_comments']=$has_disliked_comments;
            $JSON['comment_user_ids']=$comment_user_ids;
            $JSON['comment_ids']=$comment_ids;
            $JSON['comment_timestamps']=$comment_timestamps;
            $JSON['num_favorites']=$num_favorites;
            $JSON['views']=$views;
            $JSON['album_id']=$album_id;
            $JSON['album_name']=$album_name;
            $JSON['comment_profile_pictures']=$comment_profile_pictures;
            $JSON['has_favorited']=$has_favorited;
            echo json_encode($JSON);
            exit();
        }
        else if(!$query)
            send_mail_error("view_image_query.php: (1:1): ", mysql_error());
            
    }
}

//gets user's other images
else if($num==2)
{
    $user_id=(int)($_POST['user_id']);
    $image_id=clean_string($_POST['image_id']);

    if(is_id($user_id))
    {
        if($image_id!='')
        {
            $query=mysql_query("SELECT images_uploaded_order FROM user_data WHERE user_id=$user_id LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $images_uploaded_order=explode('|', $array[0]);

                $images=array();
                $exts=array();
                $descriptions=array();


                while(sizeof($images)<10&&sizeof($images_uploaded_order)>0)
                {
                    $random=(int)rand(0, sizeof($images_uploaded_order)-1);

                    if($images_uploaded_order[$random]!=$image_id)
                    {
                        $images[]=$images_uploaded_order[$random];

                        //deleted index from array
                        unset($images_uploaded_order[$random]);
                        $images_uploaded_order = array_values($images_uploaded_order);
                    }
                }

                for($x = 0; $x < sizeof($images); $x++)
                {
                    $query=mysql_query("SELECT ext, description FROM images WHERE image_id='$images[$x]' LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $ext=$array[0];
                        $description=$array[1];

                        $exts[]=$ext;
                        $descriptions[]=str_replace("'", "\'", $description);
                    }
                    else if(!$query)
                        send_mail_error("view_image_query.php: (2:2): ", mysql_error());
                }

                $JSON=array();
                $JSON['images']=$images;
                $JSON['exts']=$exts;
                $JSON['descriptions']=$descriptions;
                echo json_encode($JSON);
                exit();

            }
            else if(!$query)
                send_mail_error("view_image_query.php: (2:1): ", mysql_error());
        }
    }
}

//saves description
else if($num==3)
{
    $description=clean_string($_POST['description']);
    $image_id=clean_string($_POST['image_id']);
    
    if(strlen($description)<=500)
    {
        if($image_id!='')
        {
            $query=mysql_query("UPDATE images SET description='$description' WHERE image_id='$image_id' AND user_id=$_SESSION[id]");
            if($query)
                echo "success";
            else
            {
                echo "Something went wrong. We are working on fixing it";
                send_mail_error("view_image_query.php: (3:1): ", mysql_error());
            }
        }
        else
            echo "Invalid image id";
    }
    else
        echo "Description needs to be under 500 characters";
}

//gets user's album data
else if($num==4)
{
    $image_id=clean_string($_POST['image_id']);
    
    if($image_id!='')
    {
        $query=mysql_query("SELECT albums FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $albums=explode('|', $array[0]);

            //gets the names of the albums
            $album_names=array();
            for($x = 0; $x < sizeof($albums); $x++)
            {
                $query=mysql_query("SELECT album_name FROM albums WHERE album_id='$albums[$x]' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $album_names[]=$array[0];
                }
                else if(!$query)
                    send_mail_error("view_image_query.php: (4:2): ", mysql_error());
            }

            $temp_album_names=$album_names;
            sort($album_names, SORT_STRING);

            //creates temporary data of arrays for future use
            $temp_albums=array();
            $new_album_names=array();
            for($x = 0; $x < sizeof($albums); $x++)
            {
               $number=array_search($album_names[$x], $temp_album_names);
               $temp_album_names[$number]='';


                $temp_albums[$x]=$albums[$number];
            }


            //gets current album
            $query=mysql_query("SELECT album_id FROM images WHERE image_id='$image_id' LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $current_album_id=$array[0];
            }
            else if(!$query)
                send_mail_error("view_image_query.php: (4:3): ", mysql_error());

            $JSON=array();
            $JSON['album_ids']=$temp_albums;
            $JSON['album_names']=$album_names;
            $JSON['current_album_id']=$current_album_id;
            echo json_encode($JSON);
            exit();
        }
        else if(!$query)
            send_mail_error("view_image_query.php: (4:1): ", mysql_error());
    }
}

//saves image's new album
else if($num==5)
{
    $album_id=clean_string($_POST['album_id']);
    $image_id=clean_string($_POST['image_id']);
    $image_ext=clean_string($_POST['image_ext']);
    
    
    if($image_id!='')
    {
        if($image_ext!=''&&($image_ext=='jpg'||$image_ext=='png'||$image_ext=='gif'))
        {
            //gets old album_id
            $query=mysql_query("SELECT album_id FROM images WHERE image_id='$image_id' AND user_id=$_SESSION[id] LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $old_album_id=$array[0];

                if($old_album_id!='')
                {
                    //deletes image from list of images belonging to old album id
                    $query=mysql_query("SELECT image_ids, image_exts FROM albums WHERE album_id='$old_album_id' LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $image_ids=explode('|', $array[0]);
                        $image_exts=explode('|', $array[1]);

                        $temp_image_ids=array();
                        $temp_image_exts=array();
                        for($x = 0; $x < sizeof($image_ids); $x++)
                        {
                            if($image_ids[$x]!=$image_id)
                            {
                                $temp_image_ids[]=$image_ids[$x];
                                $temp_image_exts[]=$image_exts[$x];
                            }
                        }

                        $image_ids=implode('|', $temp_image_ids);
                        $image_exts=implode('|', $temp_image_exts);
                        $query=mysql_query("UPDATE albums SET image_ids='$image_ids', image_exts='$image_exts' WHERE album_id='$old_album_id'");
                        if(!$query)
                        {
                            echo "Something went wrong. We are working on fixing it";
                            send_mail_error("view_image_query.php: (5:3): ", mysql_error());
                        }
                    }
                    else if(!$query)
                    {
                        echo "Something went wrong. We are working on fixing it";
                        send_mail_error("view_image_query.php: (5:2): ", mysql_error());
                    }
                }
            }
            else if(!$query)
            {
                echo "Something went wrong. We are working on fixing it";
                send_mail_error("view_image_query.php: (5:1): ", mysql_error());
            }






            if($album_id=='')
            {
                $query=mysql_query("UPDATE images SET album_id='' WHERE image_id='$image_id' AND user_id=$_SESSION[id]");
                if($query)
                    echo "success";
                else
                {
                    echo "Something went wrong. We are working on fixing it";
                    send_mail_error("view_image_query.php: (5:4): ", mysql_error());
                }
            }

            if($album_id!='')
            {
                $query=mysql_query("SELECT image_ids, image_exts FROM albums WHERE album_id='$album_id' AND user_id=$_SESSION[id] LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $image_ids=explode('|', $array[0]);
                    $image_exts=explode('|', $array[1]);

                    if($array[0]=='')
                    {
                        $image_ids[0]=$image_id;
                        $image_exts[0]=$image_ext;
                    }
                    else
                    {
                        $image_ids[]=$image_id;
                        $image_exts[]=$image_ext;
                    }

                    $image_ids=implode('|', $image_ids);
                    $image_exts=implode('|', $image_exts);

                    $query=mysql_query("UPDATE albums SET image_ids='$image_ids', image_exts='$image_exts' WHERE album_id='$album_id'");
                    if($query)
                    {
                        $query=mysql_query("UPDATE images SET album_id='$album_id' WHERE image_id='$image_id'");
                        if($query)
                            echo "success";
                        else
                        {
                            echo "Something went wrong. We are working on fixing it";
                            send_mail_error("view_image_query.php: (5:7): ", mysql_error());
                        }
                    }
                    else
                    {
                        echo "Something went wrong. We are working on fixing it";
                        send_mail_error("view_image_query.php: (5:6): ", mysql_error());
                    }
                }
                else
                    send_mail_error("view_image_query.php: (5:5): ", "got here");
            }
        }
        else
            echo "Invalid image extension";
    }
    else
        echo "Empty image id";
}

//gets user's images in album fashion
else if($num==6)
{
    $image_id=clean_string($_POST['image_id']);
    $page=(int)($_POST['page']);
    $user_id=(int)($_POST['user_id']);
    
    if($image_id!='')
    {
        if(is_id($user_id))
        {
            //if loading image for first time
            if($page==-1)
            {
                $query=mysql_query("SELECT images_uploaded_order, username FROM user_data WHERE user_id=$user_id LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $images_uploaded_order=explode('|', $array[0]);
                    $username=$array[1];

                    $index=array_search($image_id, $images_uploaded_order);

                    //if image exists
                    if($index!==false)
                    {
                        $page=(int)($index/11);

                        $start=$index%11;
                        $end=$index-$start+11;

                        $current_images=array();
                        $showing_all=false;
                        for($x = ($index-$start); $x < $end; $x++)
                        {
                            if(isset($images_uploaded_order[$x]))
                                $current_images[]=$images_uploaded_order[$x];
                            else if($x>0)
                                $showing_all=true;
                        }

                        $prev_page_image=$images_uploaded_order[$index-$start-1];
                        $next_page_image=$images_uploaded_order[$end];

                        $current_index=$index%11;
                    }
                }
            }
            else
            {
                $query=mysql_query("SELECT images_uploaded_order, username FROM user_data WHERE user_id=$user_id LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $images_uploaded_order=explode('|', $array[0]);
                    $username=$array[1];

                    $start=$page*11;
                    $end=$start+11;

                    $current_images=array();
                    $showing_all=false;
                    for($x = $start; $x < $end; $x++)
                    {
                        if(isset($images_uploaded_order[$x]))
                            $current_images[]=$images_uploaded_order[$x];
                        else if($x>0)
                            $showing_all=true;
                    }

                    $prev_page_image=$images_uploaded_order[$start-1];
                    $next_page_image=$images_uploaded_order[$end];

                    $current_index=0;
                }
            }

            //gets image exts
            $image_exts=array();
            $thumbnails=array();
            for($x = 0; $x < sizeof($current_images); $x++)
            {
                $query=mysql_query("SELECT ext, nsfw FROM images WHERE image_id='$current_images[$x]' LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $ext=$array[0];
                    $image_exts[]=$ext;
                    $nsfw=$array[1];
                    
                    //gets thumbnails
                    if($nsfw=='false')
                        $thumbnails[]="http://i.imagepxl.com/$username/thumbs/$current_images[$x].$ext";
                    else
                        $thumbnails[]="http://i.imagepxl.com/site/nsfw.png";
                }
                else
                {
                    $image_exts[]='';
                    $thumbnails[]="http://i.imagepxl.com/site/no_image.jpg";
                }
            }
        }
    }
    
    $JSON=array();
    $JSON['new_image_list']=$current_images;
    $JSON['new_image_ext_list']=$image_exts;
    $JSON['showing_all']=$showing_all;
    $JSON['new_page']=$page;
    $JSON['current_index']=$current_index;
    $JSON['next_page_image']=$next_page_image;
    $JSON['prev_page_image']=$prev_page_image;
    $JSON['thumbnails']=$thumbnails;
    echo json_encode($JSON);
    exit();
}
?>