<?php
sleep(2);
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

if(isset($_SESSION['id']))
{
    header("Location: http://www.imagepxl.com");
    exit();
}


$username=clean_string($_POST['username']);
$password=clean_string($_POST['password']);


if(isset($_POST['username'])&&isset($_POST['password']))
{
    if($username!='' && $password!='')
    {
        if(strlen($username)<=40)
        {
            if(strlen($password)<=255)
            {   
                //uses blowfish to hash the password for verification
                //salt will be truncated if over 22 characters
                //    $password=crypt($password, '$2a$07$27cad37e8a5fc13618b5ce35ad2b4d5b97400d63$');
                $temp_salt="0428ab59c4a50892f6bea0564ccf62a9f99d78b3";
                $password=crypt($password, '$6$rounds=5000$'.$temp_salt.'$');

                //check previous logins
                $valid=false;
                $query=mysql_query("SELECT timestamps FROM login_attempts WHERE username='".$username."'");
                if($query)
                {
                    if(mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $timestamps=explode('|', $array[0]);

                        $time_since=get_date()-$timestamps[0];
                        if($time_since<900&&sizeof($timestamps)>=5)
                            echo "Please wait ".(15-format_number($time_since/60))." minutes before trying again";
                        else
                        {
                            $valid=true;
                            $temp_timestamps=array();

                            for($x = 0; $x < sizeof($timestamps); $x++)
                            {
                                $temp_time=get_date()-$timestamps[$x];

                                if($temp_time>900)
                                    $temp_timestamps[]=$timestamps[$x];
                            }

                            $timestamps=implode('|', $temp_timestamps);
                            $query=mysql_query("UPDATE login_attempts SET timestamps='$timestamps' WHERE username='".$username."'");
                        }
                    }
                    else
                        $valid=true;
                }


                if($valid)
                {
                    $query=mysql_query("SELECT id, account_id, username FROM users WHERE username='$username' AND password='$password' LIMIT 1");
                    if($query&&mysql_num_rows($query)==1)
                    {
                        $array=mysql_fetch_row($query);
                        $ID=$array[0];
                        $account_id=$array[1];

                        if(!user_id_terminated($ID))
                        {
                            //sets the user to be logged in for a month
                            setcookie('acc_id', $account_id, strtotime('+90 days'), null, null, false, true);
                            $_SESSION['id']=$ID;
                            $_SESSION['username']=$array[2];

                            //deletes previous failed logins
                            $query=mysql_query("DELETE FROM login_attempts WHERE username='".$username."'");

                            //records login
                            //record_login();
                            $query=mysql_query("UPDATE user_data SET last_sign_in='".get_date()."' WHERE user_id=$_SESSION[id]");
                        }
                        else
                           echo "Your account has been terminated";
                    }

                    //if login failed
                    else
                    {
                        $query=mysql_query("SELECT timestamps FROM login_attempts WHERE username='".$username."'");
                        if($query)
                        {
                            //if no previous failed login attempts
                            if(mysql_num_rows($query)==0)
                                $query=mysql_query("INSERT INTO login_attempts SET username='".$username."', timestamps='".get_date()."'");
                            else
                            {
                                $array=mysql_fetch_row($query);
                                $timestamps=explode('|', $array[0]);

                                //adds current attempt
                                $timestamps[]=get_date();

                                $timestamps=implode('|', $timestamps);
                                $query=mysql_query("UPDATE login_attempts SET timestamps='$timestamps' WHERE username='".$username."'");
                            }
                        }

                        echo "Username or password are incorrect";
                    }
                }
            }
            else
                echo "Password is too long";
        }
        else
            echo "Username is too long";
    }
    else
        echo "One or more fields are empty";
}
?>
