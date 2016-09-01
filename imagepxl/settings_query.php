<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$num=(int)($_POST['num']);

//changes real name
if($num==1)
{
    $real_name=clean_string($_POST['real_name']);
    
    if(strlen($real_name)<255)
    {
        $query=mysql_query("UPDATE user_data SET real_name='$real_name' WHERE user_id=$_SESSION[id]");
        if($query)
            echo "Change successful";
        else
        {
            echo "Something went wrong. We are working on fixing it";
            log_error("settings_query.php: (1:1): ", mysql_error());
        }
    }
}

//deletes account
else if($num==2)
{
    if(isset($_SESSION['id']))
    {
        include("requiredS3.php");
        
        $password=clean_string($_POST['password']);

        $temp_salt=$_SESSION['username']."0564ccf62a9f99d78b3";
        $password=crypt($password, '$6$rounds=5000$'.$temp_salt.'$');
        
        $error=false;

        //if password is correct
        $query=mysql_query("SELECT id FROM users WHERE password='$password' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $query=mysql_query("DELETE FROM customization WHERE user_id=$_SESSION[id]");
            if(!$query)
            {
                $error=true;
                log_error("settings_query.php: (2:1): ", mysql_error());
            }
            
            $query=mysql_query("DELETE FROM albums WHERE user_id=$_SESSION[id]");
            if(!$query)
            {
                $error=true;
                log_error("settings_query.php: (2:2): ", mysql_error());
            }
            
            $query=mysql_query("SELECT image_id, ext FROM images WHERE user_id=$_SESSION[id]");
            if($query)
            {
                $image_ids=array();
                $image_exts=array();
                
                while($array=mysql_fetch_row($query))
                {
                    $image_ids[]=$array[0];
                    $image_exts[]=$array[1];
                }
                
                for($x = 0; $x < sizeof($image_ids); $x++)
                {
                    $s3->deleteObject("bucket_name", $_SESSION['username']."/$image_ids[$x].$image_exts[$x]");
                    $s3->deleteObject("bucket_name", $_SESSION['username']."/thumbs/$image_ids[$x].$image_exts[$x]");
                }
            }
            else
            {
                $error=true;
                log_error("settings_query.php: (2:3): ", mysql_error());
            }
            
            $query=mysql_query("DELETE FROM images WHERE user_id=$_SESSION[id]");
            if(!$query)
            {
                $error=true;
                log_error("settings_query.php: (2:4): ", mysql_error());
            }
            
            $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
            if($query)
            {
                $array=mysql_fetch_row($query);
                $following=explode('|', $array[0]);
                
                for($x = 0; $x < sizeof($following); $x++)
                {
                    $query=mysql_query("SELECT num_followers FROM user_data WHERE user_id=$following[$x] LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $num_followers=(int)($array[0]);
                        
                        $num_followers--;
                        
                        $query=mysql_query("UPDATE user_data SET num_followers=$num_followers WHERE user_id=$following[$x]");
                    }
                }
            }
            
            $query=mysql_query("DELETE FROM user_data WHERE user_id=$_SESSION[id]");
            if(!$query)
            {
                $error=true;
                log_error("settings_query.php: (2:5): ", mysql_error());
            }
            
            
            $query=mysql_query("UPDATE users SET closed=1 WHERE id=$_SESSION[id]");
            if(!$query)
            {
                $error=true;
                log_error("settings_query.php: (2:6): ", mysql_error());
            }
            
            //logs user out
            session_unset();
            session_destroy();
            session_write_close();
            setcookie(session_name(),'',0,'/');
            session_regenerate_id(true);

            //deletes cookie
            if(isset($_COOKIE['acc_id']))
                setcookie('acc_id', '0', (time()-(1)), null, null, false, true);
            
            if($error==false)
                echo "Account deleted";
            else
                echo "Something went wrong. We are working on fixing it";
        }
        else
            echo "Incorrect password";
    }
}

//changes description
else if($num==3)
{
    $description=clean_string($_POST['description']);
    
    if(strlen($description)<=255)
    {
        $query=mysql_query("UPDATE user_data SET description='$description' WHERE user_id=$_SESSION[id]");
        if($query)
            echo "Change successful";
        else
        {
            echo "Something went wrong. We are working on fixing it";
            log_error("settings_query.php: (3:1): ", mysql_error());
        }
    }
}