<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');


$num=(int)($_POST['num']);

//follow
if($num==1)
{
    $user_id=(int)($_POST['user_id']);
    
    if(is_id($user_id))
    {
        $query=mysql_query("SELECT num_followers FROM user_data WHERE user_id=$user_id LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $num_followers=$array[0];
            
            $num_followers++;
            
            $query=mysql_query("UPDATE user_data SET num_followers=$num_followers WHERE user_id=$user_id");
            if($query)
            {
                $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $following=explode('|', $array[0]);
                    
                    if($array[0]!='')
                        $following[]=$user_id;
                    else
                        $following[0]=$user_id;
                    
                    $following=implode('|', $following);
                    
                    $query=mysql_query("UPDATE user_data SET following='$following' WHERE user_id=$_SESSION[id]");
                    if($query)
                    {
                        echo "success";
                    }
                    else
                    {
                        echo "Something went wrong. We are working on fixing it";
                        log_error("follow.php: (4): ", mysql_error());
                    }
                }
                else
                {
                    echo "Something went wrong. We are working on fixing it";
                    log_error("follow.php: (3): ", mysql_error());
                }
            }
            else
            {
                echo "Something went wrong. We are working on fixing it";
                log_error("follow.php: (2): ", mysql_error());
            }
        }
        else
        {
            echo "Something went wrong. We are working on fixing it";
            log_error("follow.php: (1): ", mysql_error());
        }
    }
    else
        echo "Invalid user ID";
}

//unfollow
else if($num==2)
{
    $user_id=(int)($_POST['user_id']);
    
    if(is_id($user_id))
    {
        $query=mysql_query("SELECT num_followers FROM user_data WHERE user_id=$user_id LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $num_followers=$array[0];
            
            $num_followers--;
            
            $query=mysql_query("UPDATE user_data SET num_followers=$num_followers WHERE user_id=$user_id");
            if($query)
            {
                $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $following=explode('|', $array[0]);
                    
                    $index=array_search($user_id, $following);
                    
                    //deleted index from array
                    unset($following[$index]);
                    $following = array_values($following);
                    
                    $following=implode('|', $following);
                    
                    $query=mysql_query("UPDATE user_data SET following='$following' WHERE user_id=$_SESSION[id]");
                    if($query)
                    {
                        echo "success";
                    }
                    else
                    {
                        echo "Something went wrong. We are working on fixing it";
                        log_error("follow.php: (8): ", mysql_error());
                    }
                }
                else
                {
                    echo "Something went wrong. We are working on fixing it";
                    log_error("follow.php: (7): ", mysql_error());
                }
            }
            else
            {
                echo "Something went wrong. We are working on fixing it";
                log_error("follow.php: (6): ", mysql_error());
            }
        }
        else
        {
            echo "Something went wrong. We are working on fixing it";
            log_error("follow.php: (5): ", mysql_error());
        }
    }
    else
        echo "Invalid user ID";
}