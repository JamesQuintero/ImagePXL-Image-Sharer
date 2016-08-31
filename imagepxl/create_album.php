<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');


$album_name=clean_string($_POST['album_name']);

if($album_name!='')
{
    if(strlen($album_name)<=30)
    {
        $album_id=get_new_image_id();
        $query=mysql_query("INSERT INTO albums SET album_id='$album_id', album_name='$album_name', user_id=$_SESSION[id], timestamp='".get_date()."', updated='".get_date()."', image_ids='', image_exts='' ");
        if($query)
        {
            $query=mysql_query("SELECT albums FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
            if($query&&mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $albums=explode('|', $array[0]);

                if($array[0]!='')
                    $albums[]=$album_id;
                else
                    $albums[0]=$album_id;

                $albums=implode('|', $albums);
                $query=mysql_query("UPDATE user_data SET albums='$albums' WHERE user_id=$_SESSION[id]");
                if($query)
                    echo "success";
                else
                {
                    echo "Something went wrong. We are working on fixing it";
                    send_mail_error("create_album.php: (3): ", mysql_error());
                }
            }
            else
            {
                echo "Something went wrong. We are working on fixing it";
                send_mail_error("create_album.php: (2): ", mysql_error());
            }
        }
        else
        {
            echo "Something went wrong. We are working on fixing it";
            send_mail_error("create_album.php: (1): ", mysql_error());
        }
    }
    else
        echo "Album name must be under 30 characters";
}
else
    echo "Album name can't be empty";


?>