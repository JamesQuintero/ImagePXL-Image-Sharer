<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$album_id=clean_string($_POST['album_id']);

$query=mysql_query("SELECT albums FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
if($query&&mysql_num_rows($query)==1)
{
    $array=mysql_fetch_row($query);
    $albums=explode('|', $array[0]);
    
    $index=array_search($album_id, $albums);
    
    //if album belongs to current user
    if($index!==false)
    {
        //delete album frmo array
        unset($albums[$index]);
        $albums = array_values($albums);

        $albums=implode('|', $albums);

        $query=mysql_query("UPDATE user_data SET albums='$albums' WHERE user_id=$_SESSION[id]");
        if($query)
        {
            $query=mysql_query("UPDATE images SET album_id='' WHERE album_id='$album_id'");
            if($query)
            {
                $query=mysql_query("DELETE FROM albums WHERE album_id='$album_id' AND user_id=$_SESSION[id]");
                if($query)
                    echo "success";
                else
                {
                    echo "Something went wrong. We are working on fixing it";
                    log_error("delete_album.php: (4): ", mysql_error());
                }
            }
            else
            {
                echo "Something went wrong. We are working on fixing it";
                log_error("delete_album.php: (3): ", mysql_error());
            }
        }
        else
        {
            echo "Something went wrong. We are working on fixing it";
            log_error("delete_album.php: (2): ", mysql_error());
        }
    }
    else
        echo "Album can't be deleted";
}
else
{
    echo "Something went wrong. We are working on fixing it";
    log_error("delete_album.php: (1): ", mysql_error());
}