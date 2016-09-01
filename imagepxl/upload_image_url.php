<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');


include("requiredS3.php");


$description=clean_string($_POST['description']);
$username=get_username($_SESSION['id']);
$album=clean_string($_POST['album']);
$url=clean_string($_POST['url']);
$nsfw=clean_string($_POST['nsfw']);

if($nsfw!="false"&&$nsfw!="true")
    $nsfw="false";

//fixes URL
$temp=explode('/', $url);
$end=end($temp);
$end=explode('?', $end);
$end=$end[0];
$temp[sizeof($temp)-1]=$end;

$url=implode('/', $temp);

$errors="";

//checks if there actually is a photo selected
if(file_exists_server($url))
{
    //checks if description is right length
    if(strlen($description)<=500)
    {
            //gets image extention:
            $type=strtolower(end(explode('.', $url)));

            $allowed=array('jpeg' ,'jpg', 'png', 'gif');
            if(in_array($type, $allowed))
            {
                //gets image dimensions
                list($width, $height)=getimagesize($url);

                //gets width and height of 
                if($width>$height)
                {
                    $new_thumb_height=150;
                    $new_thumb_width=$width/($height/150);
                }
                else if($height>$width)
                {
                    $new_thumb_width=150;
                    $new_thumb_height=$height/($width/150);
                }
                else
                {
                    $new_thumb_width=150;
                    $new_thumb_height=150;
                }

                //creates new width and new height
                if($width>2000||$height>2000)
                {
                    $new_width=$width-($width/100)*10;
                    $new_height=$height-($height/100)*10;
                }
                else
                {
                    $new_width=$width;
                    $new_height=$height;
                }

                $new_image_id=get_new_image_id();

                //if image is a jpg
                if($type=='jpeg'||$type=='jpg')
                {
                    $value=md5(uniqid(rand()));
                    $tmp_path="/tmp/$value.jpg";

                    //gets locations
                    if(isset($_SESSION['id']))
                    {
                        $path=$username."/$new_image_id.jpg";
                        $thumb_path=$username."/thumbs/$new_image_id.jpg";
                    }
                    else
                    {
                        $path="unassociated/$new_image_id.jpg";
                        $thumb_path="unassociated/thumbs/$new_image_id.jpg";
                    }

                    if(copy($url, $tmp_path))
                    {
                        //uploads main image
                        $img=imagecreatefromjpeg($tmp_path);

                        //gets true color stuff
                        $true_color=imagecreatetruecolor($new_width, $new_height);
                        $thumb=imagecreatetruecolor(150, 150);

                        //copies original to true color
                        imagecopyresampled($true_color, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                        //uploads true color
                        imagejpeg($thumb, $tmp_path."thumb.jpg", 80);
                        imagejpeg($true_color, $tmp_path, 80);

                        //uploads new version and thumbnail to S3
                        $s3->putObjectFile($tmp_path, "bucket_name", $path, S3::ACL_PUBLIC_READ);
                        $s3->putObjectFile($tmp_path."thumb.jpg", "bucket_name", $thumb_path, S3::ACL_PUBLIC_READ);

                        $type="jpg";

                        imagedestroy($thumb);
                        imagedestroy($true_color);
                    }

                    //deletes the temp images
                    unlink($tmp_path);
                    unlink($tmp_path."thumb.jpg");
                }

                //if image is a png
                else if($type=='png')
                {
                    $value=md5(uniqid(rand()));
                    $tmp_path="/tmp/$value.jpg";

                    //gets locations
                    $path=$username."/$new_image_id.png";
                    $thumb_path=$username."/thumbs/$new_image_id.png";

                    if(copy($url, $tmp_path))
                    {

                        //gets true color stuff
                        $true_color=imagecreatetruecolor($new_width, $new_height);
                        $thumb=imagecreatetruecolor(150, 150);

                        //sets transparency stuff
                        imagealphablending($true_color, false);
                        imagesavealpha($true_color, true);  

                        //sets transparency stuff
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);  

                        //uploads main image
                        $img=imagecreatefrompng($tmp_path);
                        imagealphablending($img, true);

                        //copies original to true color
                        imagecopyresampled($true_color, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                        //uploads true color
                        imagepng($thumb, $tmp_path."thumb.png", 9);
                        imagepng($true_color, $tmp_path, 9);

                        //uploads new version and thumbnail to S3
                        $s3->putObjectFile($tmp_path, "bucket_name", $path, S3::ACL_PUBLIC_READ);
                        $s3->putObjectFile($tmp_path."thumb.png", "bucket_name", $thumb_path, S3::ACL_PUBLIC_READ);

                        imagedestroy($thumb);
                        imagedestroy($true_color);

                        //deletes the temp images
                        unlink($tmp_path);
                        unlink($tmp_path."thumb.png");
                    }
                }

                //if image is a gif
                else if($type=='gif')
                {
                    $value=md5(uniqid(rand()));
                    $tmp_path="/tmp/$value.jpg";

                    if(copy($url, $tmp_path))
                    {
                        $animated=is_animated($tmp_path);

                        //if GIF isn't animated
                        if($animated==false)
                        {
                            //gets locations
                            $path=$username."/$new_image_id.gif";
                            $thumb_path=$username."/thumbs/$new_image_id.gif";

                            //uploads main image
                            $img=imagecreatefromgif($tmp_path);

                            //gets true color stuff
                            $true_color=imagecreatetruecolor($new_width, $new_height);
                            $thumb=imagecreatetruecolor(150, 150);

                            //copies original to true color
                            imagecopyresampled($true_color, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                            //uploads true color
                            imagegif($thumb, $tmp_path."thumb.gif");
                            imagegif($true_color, $tmp_path);

                            //uploads true color and thumb to S3
                            $s3->putObjectFile($tmp_path, "bucket_name", $path, S3::ACL_PUBLIC_READ);
                            $s3->putObjectFile($tmp_path."thumb.gif", "bucket_name", $thumb_path, S3::ACL_PUBLIC_READ);

                            imagedestroy($thumb);
                            imagedestroy($true_color);

                            //deletes the temp images
                            unlink($tmp_path);
                            unlink($tmp_path."thumb.gif");
                        }
                        else
                        {
                            //gets locations
                            $path=$username."/$new_image_id.gif";
                            $thumb_path=$username."/thumbs/$new_image_id.gif";

                            //uploads main image
                            $img=imagecreatefromgif($tmp_path);

                            //gets true color stuff
                            $thumb=imagecreatetruecolor(150, 150);

                            //copies original to true color
                            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                            //uploads true color
                            imagegif($thumb, $tmp_path."thumb.gif");

                            //uploads true color and thumb to S3
                            $s3->putObjectFile($tmp_path, "bucket_name", $path, S3::ACL_PUBLIC_READ);
                            $s3->putObjectFile($tmp_path."thumb.gif", "bucket_name", $thumb_path, S3::ACL_PUBLIC_READ);

                            imagedestroy($thumb);

                            //deletes the temp images
                            unlink($tmp_path);
                            unlink($tmp_path."thumb.gif");
                        }
                    }
                }

                if(isset($_SESSION['id']))
                {
                    //checks if album is current user's
                    $query=mysql_query("SELECT albums FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $albums=explode('|', $array[0]);

                        $index=array_search($album, $albums);
                    }

                    //if it's not, set it to ''
                    if($index===false)
                        $album='';
                }
                else
                    $album='';

                if(isset($_SESSION['id']))
                {
                    $query=mysql_query("INSERT INTO images SET image_id='$new_image_id', description='$description', ext='$type', width=$new_width, height=$new_height, user_id=$_SESSION[id], timestamp='".get_date()."', album_id='$album', comment_ids='', comments='', comment_likes='', comment_dislikes='', comment_user_ids='', num_favorites=0, nsfw='$nsfw'");
                    if($query)
                    {
                        $query=mysql_query("SELECT num_images, images_uploaded_order FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                        if($query&&mysql_num_rows($query)==1)
                        {
                            $array=mysql_fetch_row($query);
                            $num_images=$array[0];
                            $images_uploaded_order=explode('|', $array[1]);
                            $num_images++;
                            if($array[1]!='')
                                $images_uploaded_order[]=$new_image_id;
                            else
                                $images_uploaded_order[0]=$new_image_id;

                            $images_uploaded_order=implode('|', $images_uploaded_order);
                            $query=mysql_query("UPDATE user_data SET num_images=$num_images, images_uploaded_order='$images_uploaded_order' WHERE user_id=$_SESSION[id]");
                            if($query)
                            {
                                if($album!='')
                                {
                                    $query=mysql_query("SELECT image_ids, image_exts FROM albums WHERE album_id='$album' LIMIT 1");
                                    if($query&&mysql_num_rows($query)==1)
                                    {
                                        $array=mysql_fetch_row($query);
                                        $image_ids=explode('|', $array[0]);
                                        $image_exts=explode('|', $array[1]);

                                        if($array[0]=='')
                                        {
                                            $image_ids[0]=$new_image_id;
                                            $image_exts[0]=$type;
                                        }
                                        else
                                        {
                                            $image_ids[]=$new_image_id;
                                            $image_exts[]=$type;
                                        }

                                        $image_ids=implode('|', $image_ids);
                                        $image_exts=implode('|', $image_exts);

                                        $query=mysql_query("UPDATE albums SET image_ids='$image_ids', image_exts='$image_exts', updated='".get_date()."' WHERE album_id='$album'");
                                        if(!$query)
                                        {
                                            $errors="Something went wrong. We are working on fixing it";
                                            log_error("upload_image_url.php: (5): ", mysql_error());
                                        }
                                    }
                                    else
                                    {
                                        $errors="Something went wrong. We are working on fixing it";
                                        log_error("upload_image_url.php: (4): ", mysql_error());
                                    }
                                }
                            }
                            else
                            {
                                $errors="Something went wrong. We are working on fixing it";
                                log_error("upload_image_url.php: (3): ", mysql_error());
                            }
                        }
                        else
                        {
                            $errors="Something went wrong. We are working on fixing it";
                            log_error("upload_image_url.php: (2): ", mysql_error());
                        }
                    }
                    else
                    {
                        $errors="Something went wrong. We are working on fixing it";
                        log_error("upload_image_url.php: (1): ", mysql_error());
                    }
                }
                
                //if user isn't logged in
                else
                {
                    $query=mysql_query("INSERT INTO images SET image_id='$new_image_id', description='$description', ext='$type', width=$new_width, height=$new_height, ip_address='$_SERVER[REMOTE_ADDR]', timestamp='".get_date()."', album_id='$album', comment_ids='', comments='', comment_likes='', comment_dislikes='', comment_user_ids='', num_favorites=0, nsfw='$nsfw'");
                    if(!$query)
                    {
                        $errors="Something went wrong. We are working on fixing it";
                        log_error("upload_image_url.php: (6): ", mysql_error());
                    }
                }
            }
            else
                $errors="Sorry, this file extension is not allowed";
    }
    else
        $errors="Description is too long";
}
else
    $errors="Image doesn't exist";
    
$JSON=array();
$JSON['image_id']=$new_image_id;
$JSON['errors']=$errors;
echo json_encode($JSON);
exit();