<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$image_link=clean_string($_POST['image_link']);

if(isset($_POST['image_link']))
{
    //if regular link
    if(strpos($image_link, 'http://imagepxl.com/')!==false)
    {
        $split=explode('/', $image_link);
        $image_id=end($split);
        $query=mysql_query("SELECT user_id, ext FROM images WHERE image_id='$image_id' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $user_id=$array[0];
            $ext=$array[1];

            $username=get_username($user_id);
        }

        $new_image_id="http://i.imagepxl.com/$username/$image_id.$ext";

        $JSON=array();
        $JSON['image_link']=$image_link;
        $JSON['image_src']=$new_image_id;
        echo json_encode($JSON);
        exit();
    }

    //if direct link
    else if(strpos($image_link, 'http://i.imagepxl.com')!==false)
    {
        $image_link=str_replace("/thumbs/", "/", $image_link);
        $split=explode('/', $image_link);
        $username=$split[sizeof($split)-2];

        $image_id=end($split);
        $image_id_split=explode('.', $image_id);
        $image_id=$image_id_split[0];

        $new_image_link="http://imagepxl.com/".$image_id;

        $JSON=array();
        $JSON['image_link']=$new_image_link;
        $JSON['image_src']=$image_link;
        echo json_encode($JSON);
        exit();
    }
    else
    {
        $JSON=array();
        $JSON['image_link']="";
        $JSON['image_src']="http://i.imagepxl.com/site/no_image.jpg";
        echo json_encode($JSON);
        exit();
    }
}