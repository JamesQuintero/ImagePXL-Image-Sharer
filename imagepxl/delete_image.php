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

//creates S3 item with schtuff
$s3 = new S3(awsAccessKey, awsSecretKey);

$image_id=clean_string($_POST['image_id']);

if(isset($_SESSION['id']))
{
    if($image_id!='')
    {
        $query=mysql_query("SELECT ext, album_id, user_id FROM images WHERE image_id='$image_id' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $ext=$array[0];
            $album_id=$array[1];
            $user_id=$array[2];
            
            //if current user's image
            if($_SESSION['id']==$user_id)
            {
                $query=mysql_query("DELETE FROM images WHERE image_id='$image_id'");
                if($query)
                {
                    $query=mysql_query("SELECT image_ids, image_exts FROM albums WHERE album_id='$album_id' LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $image_ids=explode('|', $array[0]);
                        $image_exts=explode('|', $array[1]);

                        $new_image_ids=array();
                        $new_image_exts=array();
                        for($x = 0; $x < sizeof($image_ids); $x++)
                        {
                            if($image_ids[$x]!=$image_id)
                            {
                                $new_image_ids[]=$image_ids[$x];
                                $new_image_exts[]=$image_exts[$x];
                            }
                        }

                        $image_ids=implode('|', $new_image_ids);
                        $image_exts=implode('|', $new_image_exts);
                        $query=mysql_query("UPDATE albums SET image_ids='$image_ids', image_exts='$image_exts' WHERE album_id='$album_id'");
                        if(!$query)
                        {
                            echo "Something went wrong. We are working on fixing it";
                            send_mail_error("delete_image.php: (4): ", mysql_error());
                        }

                    }
                    else if(!$query)
                    {
                        echo "Something went wrong. We are working on fixing it";
                        send_mail_error("delete_image.php: (3): ", mysql_error());
                    }

                    $query=mysql_query("SELECT username, num_images, images_uploaded_order FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $username=$array[0];
                        $num_images=$array[1];
                        $images_uploaded_order=explode('|', $array[2]);

                        //substracts num images uploaded
                        $num_images--;

                        //removes image from list of images
                        $temp_images=array();
                        for($x = 0; $x < sizeof($images_uploaded_order); $x++)
                        {
                            if(strpos($images_uploaded_order[$x], $image_id)===false)
                                $temp_images[]=$images_uploaded_order[$x];
                        }
                        if(isset($temp_images[0]))
                            $temp_images=implode('|', $temp_images);
                        else
                            $temp_images='';

                        $query=mysql_query("UPDATE user_data SET num_images=$num_images, images_uploaded_order='$temp_images' WHERE user_id=$_SESSION[id]");
                        if(!$query)
                        {
                            echo "Something went wrong. We are working on fixing it";
                            send_mail_error("delete_image.php: (6): ", mysql_error());
                        }

                        $path=$username."/$image_id.$ext";
                        $thumb_path=$username."/thumbs/$image_id.$ext";

                        $s3->deleteObject("imagepxl.images", $path);
                        $s3->deleteObject("imagepxl.images", $thumb_path);

                        echo "success";

                    }
                    else if(!$query)
                    {
                        echo "Something went wrong. We are working on fixing it";
                        send_mail_error("delete_image.php: (5): ", mysql_error());
                    }
                }
                else
                {
                    echo "Something went wrong. We are working on fixing it";
                    send_mail_error("delete_image.php: (2): ", mysql_error());
                }
            }
        }
        else if(!$query)
        {
            echo "Something went wrong. We are working on fixing it";
            send_mail_error("delete_image.php: (1): ", mysql_error());
        }
    }
    else
        echo "Picture does not exist";
}
?>