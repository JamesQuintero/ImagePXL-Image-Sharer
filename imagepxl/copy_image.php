<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

//gets all the necessary AWS schtuff
if (!class_exists('S3'))
    require_once('S3.php');
if (!defined('awsAccessKey'))
    define('awsAccessKey', ACCES_KEY);
if (!defined('awsSecretKey'))
    define('awsSecretKey', SECRET_KEY);
$s3 = new S3(awsAccessKey, awsSecretKey);


$image_id=clean_string($_POST['image_id']);
$description=clean_string($_POST['description']);
$album=clean_string($_POST['album']);

if($image_id!='')
{
    if(strlen($description)<=500)
    {
        $query=mysql_query("SELECT ext, user_id FROM images WHERE image_id='$image_id' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $ext=$array[0];
            $user_id=$array[1];

            //can't copy your own image
            if($user_id!=$_SESSION['id'])
            {
                //gets new image id
                $name=get_new_image_id();

                //path of current hosted image
                if($user_id!=0)
                {
                    $username=get_username($user_id);
                    
                    $prev=$username."/$image_id.".$ext;
                    $prev_thumb=$username."/thumbs/$image_id.".$ext;
                }
                else
                {
                    $username='';
                    
                    $prev="unassociated/$image_id.".$ext;
                    $prev_thumb="unassociated/thumbs/$image_id.".$ext;
                }

                //gets new file path
                $new=$_SESSION['username']."/$name.".$ext;
                $new_thumb=$_SESSION['username']."/thumbs/$name.".$ext;

                //copies it to new path
                $s3->copyObject("imagepxl.images", $prev, "imagepxl.images", $new, S3::ACL_PUBLIC_READ);
                $s3->copyObject("imagepxl.images", $prev_thumb, "imagepxl.images", $new_thumb, S3::ACL_PUBLIC_READ);

                //if album isn't valid
                if(mysql_num_rows(mysql_query("SELECT * FROM albums WHERE album_id='$album' AND user_id=$_SESSION[id] LIMIT 1"))==0)
                    $album='';
                
               
                $query=mysql_query("INSERT INTO images SET image_id='$name', description='$description', ext='$ext', user_id=$_SESSION[id], timestamp='".get_date()."', album_id='$album', comment_ids='', comments='', comment_likes='', comment_dislikes='', comment_user_ids='', num_favorites=0, nsfw='false'");
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
                            $images_uploaded_order[]=$name;
                        else
                            $images_uploaded_order[0]=$name;

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
                                        $image_ids[0]=$name;
                                        $image_exts[0]=$ext;
                                    }
                                    else
                                    {
                                        $image_ids[]=$name;
                                        $image_exts[]=$ext;
                                    }

                                    $image_ids=implode('|', $image_ids);
                                    $image_exts=implode('|', $image_exts);

                                    $query=mysql_query("UPDATE albums SET image_ids='$image_ids', image_exts='$image_exts', updated='".get_date()."' WHERE album_id='$album'");
                                    if(!$query)
                                        send_mail_error("copy_image.php: (6): ", mysql_error());
                                }
                                else
                                    send_mail_error("copy_image.php: (5): ", mysql_error());
                            }
                        }
                        else
                            send_mail_error("copy_image.php: (4): ", mysql_error());
                    }
                    else
                        send_mail_error("copy_image.php: (3): ", mysql_error());
                }
                else
                    send_mail_error("copy_image.php: (2): ", mysql_error());
            }
            else
                echo "You can't copy your own images";
        }
        else
        {
            echo "Something went wrong. We are working on fixing it";
            send_mail_error("copy_image.php: (1): ", mysql_error());
        }
    }
    else
        echo "Description is too long";
}
else
    echo "Empty image id"
?>