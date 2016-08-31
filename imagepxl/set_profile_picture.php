<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$image_id=clean_string($_POST['image_id']);

$query=mysql_query("SELECT user_id, ext FROM images WHERE image_id='$image_id' LIMIT 1");
if($query&&mysql_num_rows($query)==1)
{
    $array=mysql_fetch_row($query);
    $user_id=$array[0];
    $ext=$array[1];
    
    //if current user's own image
    if($_SESSION['id']==$user_id)
    {
        $query=mysql_query("UPDATE customization SET profile_pic_id='$image_id', profile_pic_ext='$ext' WHERE user_id=$_SESSION[id]");
        if($query)
            echo "Profile picture changed";
        else
        {
            echo "Something went wrong. We are working on fixing it";
            send_mail_error("set_profile_picture.php: (2): ", mysql_error());
        }
    }
    else
    {
        echo "What are you doing...";
    }
}
else
{
    echo "Something went wrong. We are working on fixing it";
    send_mail_error("set_profile_picture.php: (1): ", mysql_error());
}
?>