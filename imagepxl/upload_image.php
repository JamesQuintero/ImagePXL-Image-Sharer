<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');


$image_ids_uploaded=array();
if(!empty($_FILES['image']))
{
    //gets all the necessary AWS schtuff
    if (!class_exists('S3'))
        require_once('S3.php');
    if (!defined('awsAccessKey'))
        define('awsAccessKey', ACCES_KEY);
    if (!defined('awsSecretKey'))
        define('awsSecretKey', SECRET_KEY);

    //creates S3 item with schtuff
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $description=clean_string($_POST['description']);
    
    if(isset($_SESSION['id']))
        $username=get_username($_SESSION['id']);
    else
        $username="unassociated";
    
    $album=clean_string($_POST['album']);
    $nsfw=clean_string($_POST['nsfw']);
    
    if($nsfw!="false"&&$nsfw!="true")
        $nsfw="false";
    
    if(strlen($description)<=500)
    {
        for($x = 0; $x < sizeof($_FILES['image']['name']); $x++)
        {
            //checks if there actually is a photo selected
            if($_FILES['image']['size'][$x]!=0)
            {
                //if the file is less than or equal to 20MB
                if($_FILES['image']['size'][$x]<=10240000)
                {
                    //gets image extention:
                    $type=strtolower(end(explode('.', $_FILES['image']['name'][$x])));

                    $allowed=array('jpeg' ,'jpg', 'png', 'gif');
                    if(in_array($type, $allowed))
                    {
                        //gets image dimensions
                        list($width, $height)=getimagesize($_FILES['image']['tmp_name'][$x]);

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
                            //gets locations
                            $path=$username."/$new_image_id.jpg";
                            $thumb_path=$username."/thumbs/$new_image_id.jpg";

                            //uploads main image
                            $img=imagecreatefromjpeg($_FILES['image']['tmp_name'][$x]);

                            //gets true color stuff
                            $true_color=imagecreatetruecolor($new_width, $new_height);
                            $thumb=imagecreatetruecolor(150, 150);

                            //copies original to true color
                            imagecopyresampled($true_color, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                            //uploads true color
                            imagejpeg($true_color, $_FILES['image']['tmp_name'][$x], 70);
                            imagejpeg($thumb, $_FILES['image']['tmp_name'][$x]."thumb.jpg", 70);

                            //uploads new version and thumbnail to S3
                            $s3->putObjectFile($_FILES['image']['tmp_name'][$x], "imagepxl.images", $path, S3::ACL_PUBLIC_READ);
                            $s3->putObjectFile($_FILES['image']['tmp_name'][$x]."thumb.jpg", "imagepxl.images", $thumb_path, S3::ACL_PUBLIC_READ);

                            $type="jpg";
                            
                            imagedestroy($true_color);
                            imagedestroy($thumb);

                            //deletes the temp images
                            unlink($_FILES['image']['tmp_name'][$x]);
                            unlink($_FILES['image']['tmp_name'][$x]."thumb.jpg");
                        }

                        //if image is a png
                        else if($type=='png')
                        {
                            //gets locations
                            $path=$username."/$new_image_id.png";
                            $thumb_path=$username."/thumbs/$new_image_id.png";

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
                            $img=imagecreatefrompng($_FILES['image']['tmp_name'][$x]);
                            imagealphablending($img, true);

                            //copies original to true color
                            imagecopyresampled($true_color, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                            //uploads true color
                            imagepng($true_color, $_FILES['image']['tmp_name'][$x], 9);
                            imagepng($thumb, $_FILES['image']['tmp_name'][$x]."thumb.png", 9);

                            //uploads new version and thumbnail to S3
                            $s3->putObjectFile($_FILES['image']['tmp_name'][$x], "imagepxl.images", $path, S3::ACL_PUBLIC_READ);
                            $s3->putObjectFile($_FILES['image']['tmp_name'][$x]."thumb.png", "imagepxl.images", $thumb_path, S3::ACL_PUBLIC_READ);
                            
                            imagedestroy($true_color);
                            imagedestroy($thumb);

                            //deletes the temp images
                            unlink($_FILES['image']['tmp_name'][$x]);
                            unlink($_FILES['image']['tmp_name'][$x]."thumb.png");
                        }

                        //if image is a gif
                        else if($type=='gif')
                        {

                            $animated=is_animated($_FILES['image']['tmp_name'][$x]);

                            //if GIF isn't animated
                            if($animated==false)
                            {
                                //gets locations
                                $path=$username."/$new_image_id.gif";
                                $thumb_path=$username."/thumbs/$new_image_id.gif";

                                //uploads main image
                                $img=imagecreatefromgif($_FILES['image']['tmp_name'][$x]);

                                //gets true color stuff
                                $true_color=imagecreatetruecolor($new_width, $new_height);
                                $thumb=imagecreatetruecolor(150, 150);

                                //copies original to true color
                                imagecopyresampled($true_color, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                                imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                                //uploads true color
                                imagegif($true_color, $_FILES['image']['tmp_name'][$x]);
                                imagegif($thumb, $_FILES['image']['tmp_name'][$x]."thumb.gif");

                                //uploads true color and thumb to S3
                                $s3->putObjectFile($_FILES['image']['tmp_name'][$x], "imagepxl.images", $path, S3::ACL_PUBLIC_READ);
                                $s3->putObjectFile($_FILES['image']['tmp_name'][$x]."thumb.gif", "imagepxl.images", $thumb_path, S3::ACL_PUBLIC_READ);
                                
                                imagedestroy($true_color);
                                imagedestroy($thumb);

                                //deletes the temp images
                                unlink($_FILES['image']['tmp_name'][$x]);
                                unlink($_FILES['image']['tmp_name'][$x]."thumb.gif");
                            }
                            else
                            {
                                
                                //gets locations
                                $path=$username."/$new_image_id.gif";
                                $thumb_path=$username."/thumbs/$new_image_id.gif";

                                //uploads main image
                                $img=imagecreatefromgif($_FILES['image']['tmp_name'][$x]);

                                //gets true color stuff
                                $thumb=imagecreatetruecolor(150, 150);

                                //copies original to true color
                                imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $width, $height);

                                //uploads true color
                                imagegif($thumb, $_FILES['image']['tmp_name'][$x]."thumb.gif");

                                //uploads true color and thumb to S3
                                $s3->putObjectFile($_FILES['image']['tmp_name'][$x], "imagepxl.images", $path, S3::ACL_PUBLIC_READ);
                                $s3->putObjectFile($_FILES['image']['tmp_name'][$x]."thumb.gif", "imagepxl.images", $thumb_path, S3::ACL_PUBLIC_READ);
                                
                                imagedestroy($thumb);

                                //deletes the temp images
                                unlink($_FILES['image']['tmp_name'][$x]);
                                unlink($_FILES['image']['tmp_name'][$x]."thumb.gif");
                            }
                        }

                        $image_ids_uploaded[]=$new_image_id.".".$type;
                        
                        //if user is logged in
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
                                                    $message="Something went wrong. We are working on fixing it";
                                                    send_mail_error("upload_image.php: (5): ", mysql_error());
                                                }
                                            }
                                            else
                                            {
                                                $message="Something went wrong. We are working on fixing it";
                                                send_mail_error("upload_image.php: (4): ", mysql_error());
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $message="Something went wrong. We are working on fixing it";
                                        send_mail_error("upload_image.php: (3): ", mysql_error());
                                    }
                                }
                                else
                                {
                                    $message="Something went wrong. We are working on fixing it";
                                    send_mail_error("upload_image.php: (2): ", mysql_error());
                                }
                            }
                            else
                            {
                                $message="Something went wrong. We are working on fixing it";
                                send_mail_error("upload_image.php: (1): ", mysql_error());
                            }
                        }
                        
                        //if non-logged in user is uploading the photo
                        else
                        {
                            $query=mysql_query("INSERT INTO images SET image_id='$new_image_id', description='$description', ext='$type', width=$new_width, height=$new_height, ip_address='$_SERVER[REMOTE_ADDR]', timestamp='".get_date()."', album_id='', comment_ids='', comments='', comment_likes='', comment_dislikes='', comment_user_ids='', num_favorites=0, nsfw='$nsfw'");
                            if(!$query)
                            {
                                $message="Something went wrong. We are working on fixing it";
                                send_mail_error("upload_image.php: (6): ", mysql_error());
                            }
                            else
                                $message=$new_image_id;
                        }
                    }
                    else
                        $message="Sorry, this file extension is not allowed";
                }
                else
                    $message="Sorry, image must be under 10MB";
            }
            else
                $message="No image selected: ".$_FILES['image']['size'][$x];
        }
    }
    else
        $message="Description is too long";
}
else
    $message="No images selected";

echo $message;
?>