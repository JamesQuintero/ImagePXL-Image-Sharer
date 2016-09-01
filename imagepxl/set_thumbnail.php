<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');


include("requiredS3.php");


$image_id=clean_string($_POST['image_id']);
$top=(int)($_POST['top']);
$left=(int)($_POST['left']);
$preview_width=(int)($_POST['width']);
$preview_height=(int)($_POST['height']);

$username=get_username($_SESSION['id']);

$query=mysql_query("SELECT ext, user_id FROM images WHERE image_id='$image_id' LIMIT 1");
if($query&&mysql_num_rows($query)==1)
{
    $array=mysql_fetch_row($query);
    $ext=$array[0];
    $user_id=$array[1];
    
    //if current user is changing thumbnail of their own photo
    if($_SESSION['id']==$user_id)
    {
        $file="http://i.imagepxl.com/$username/$image_id.".$ext;

        //gets image dimensions
        list($width, $height)=getimagesize($file);

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

        $left=($width/$preview_width)*$left;
        $top=($height/$preview_height)*$top;

        //if image is a jpg
        if($ext=='jpg')
        {
            $new_path=$username."/thumbs/$image_id.jpg";
            $value=md5(uniqid(rand()));
            $temp_path="/tmp/$value.jpg";

            //copies photo to temperary path
            copy($file, $temp_path);

            //creates thumbnail from temp photo path
            $img=imagecreatefromjpeg($temp_path);
            $thumb=imagecreatetruecolor(150, 150);
            imagecopyresampled($thumb, $img, 0, 0, $left, $top, $new_thumb_width, $new_thumb_height, $width, $height);
            imagejpeg($thumb, $temp_path, 80);

            //updates newly created thumbnail
            $s3->putObjectFile($temp_path, "bucket_name", $new_path, S3::ACL_PUBLIC_READ);

            //deletes temp photo
            imagedestroy($thumb);
            unlink($temp_path);
            echo "Thumbnail set";
        }

        //if image is a png
        else if($ext=='png')
        {
            $thumb_path=$username."/thumbs/$image_id.png";
            $value=md5(uniqid(rand()));
            $temp_path="/tmp/$value.png";

            //copies photo to temperary path
            copy($file, $temp_path);

            //uploads thumb nail
            $img=imagecreatefrompng($temp_path);
            $thumb=imagecreatetruecolor(150, 150);
            imagecopyresampled($thumb, $img, 0, 0, $left, $top, $new_thumb_width, $new_thumb_height, $width, $height);
            imagepng($thumb, $temp_path, 9);

            //updates newly created thumbnail
            $s3->putObjectFile($temp_path, "bucket_name", $new_path, S3::ACL_PUBLIC_READ);

            //deletes temp photo
            imagedestroy($thumb);
            unlink($temp_path);
            echo "Thumbnail set";
        }
        else if($ext=='gif')
        {
            $thumb_path=$username."/thumbs/$image_id.gif";
            $value=md5(uniqid(rand()));
            $temp_path="/tmp/$value.gif";

            //copies photo to temperary path
            copy($file, $temp_path);

            //uploads thumb nail
            $img=imagecreatefromgif($temp_path);
            $thumb=imagecreatetruecolor(150, 150);
            imagecopyresampled($thumb, $img, 0, 0, $left, $top, $new_thumb_width, $new_thumb_height, $width, $height);
            imagegif($thumb, $temp_path);

            //updates newly created thumbnail
            $s3->putObjectFile($temp_path, "bucket_name", $new_path, S3::ACL_PUBLIC_READ);

            //deletes temp photo
            imagedestroy($thumb);
            unlink($temp_path);
            echo "Thumbnail set";
        }
        else
        {
            echo "Something went wrong. We are working on fixing it";
            log_error("set_thumbnail.php: (1): ", mysql_error());
        }
    }
}