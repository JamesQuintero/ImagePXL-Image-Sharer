<?php
sleep(3);
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

if(isset($_SESSION['id']))
{
    header("Location: http://imagepxl.com");
    exit();
}


//test username for validation
$username=clean_string($_POST['username']);
$email=clean_string($_POST['email']);
$password=clean_string($_POST['password']);


if(isset($_POST['username'])&&$username!='')
{
    if(isset($_POST['email'])&&$email!='')
    {
        if(isset($_POST['password'])&&$password!='')
        {
            if(strlen($username) <= 40)
            {
                if(strlen($password) <= 255)
                {
                    if((filter_var($email, FILTER_VALIDATE_EMAIL) == true)&& strlen($email) <255)
                    {
                        if(is_valid_email($email))
                        {
                            //checks if email is already in use
                            $query=mysql_query("SELECT id FROM users WHERE email='$email' LIMIT 1");
                            if(mysql_num_rows($query)==0)
                            {
                                $query=mysql_query("SELECT id FROM user_data WHERE username='$username' LIMIT 1");
                                if(mysql_num_rows($query)==0&&$username!='default'&&$username!='unassociated')
                                {
                                    $query=mysql_query("SELECT passkey FROM temp_users WHERE username='$username' LIMIT 1");
                                    if(mysql_num_rows($query)==0)
                                    {
                                        $query=mysql_query("SELECT passkey FROM temp_users WHERE email='$email' LIMIT 1");
                                        if(mysql_num_rows($query)==0)
                                        {
                                            //blowfish hashes password for database storage
                                            $temp_salt="0428ab59c4a50892f6bea0564ccf62a9f99d78b3";
                                            $password=crypt($password, '$6$rounds=5000$'.$temp_salt.'$');


                                            //gives completely unique and random account id
                                            $bool=false;
                                            while($bool==false)
                                            {
                                                //gets a random SHA512 hash of random hash for the account id
                                                $temp_hash=sha1(uniqid(rand()));
                                                $temp_salt=sha1(uniqid(rand()));
                                                $account_id=crypt($temp_hash, '$6$rounds=5000$'.$temp_salt.'$');
                                                $act_id=sha1($account_id);

                                                //checks if act_id already exists
                                                $query=mysql_query("SELECT id FROM users WHERE account_id='$act_id' LIMIT 1");
                                                if($query&&mysql_num_rows($query)==0)
                                                    $bool=true;
                                            }

                                            //inserts the user into the users table
                                            $query=mysql_query("INSERT INTO users SET password='$password', email='$email', username='$username', ip_addresses='$_SERVER[REMOTE_ADDR]', account_id='$act_id'");
                                            if(!$query)
                                            {
                                                echo "Error something has gone wrong when trying to create your account";
                                                send_mail_error("confirmation.php: (1): ",mysql_error());
                                            }

                                            //sets login cookie with value of the user's account id for a month
                                            setcookie('acc_id', $act_id, (time()+(86400*31)), null, null, false, true);
                                            $query=mysql_query("SELECT id FROM users WHERE email='$email'");
                                            $array=mysql_fetch_row($query);
                                            $_SESSION['id']=$array[0];
                                            $_SESSION['username']=$username;

                                            //inserts into user_data table
                                            $query=mysql_query("INSERT INTO user_data SET user_id=$_SESSION[id], username='$username', num_followers=0, description='', real_name='', following='', favorites='', image_views=0, profile_views=0, num_images=0, last_sign_in='".get_date()."', date_joined='".get_date()."'");
                                            if(!$query)
                                            {
                                                echo "Error something has gone wrong when trying to create your account";
                                                send_mail_error("confirmation.php: (3): ",mysql_error());
                                            }

                                            //increments the num_users count
                                            $query=mysql_query("SELECT num_users FROM data WHERE num=1");
                                            if($query)
                                            {
                                                $array=mysql_fetch_row($query);
                                                $num_users=$array[0];
                                                $num_users++;
                                                $query=mysql_query("UPDATE data SET num_users=$num_users WHERE num=1");
                                                if(!$query)
                                                {
                                                    echo "Error something has gone wrong when trying to create your account";
                                                    send_mail_error("confirmation.php: (4): ",mysql_error());
                                                }
                                            }
                                            else
                                            {
                                                echo "Error something has gone wrong when trying to create your account";
                                                send_mail_error("confirmation.php: (5): ",mysql_error());
                                            }

                                            //inserts into customization table
                                            $query=mysql_query("INSERT INTO customization SET user_id=$_SESSION[id], profile_pic_id='', theme='normal' ");
                                            if(!$query)
                                            {
                                                echo "Error something has gone wrong when trying to create your account";
                                                send_mail_error("confirmation.php: (6): ",mysql_error());
                                            }

                                            //creates random confirmation code
                                            $confirm_code=sha1(uniqid(rand()));
                                            $result=mysql_query("INSERT INTO temp_users SET passkey='$confirm_code', user_id='$_SESSION[id]', email='$email', email_resend='".get_date()."'");
                                            if($result)
                                            {
                                                if(sendAWSemail($email, 'Email Confirmation', 'Click on this link to confirm your email with the username '.$username.'!  http://www.imagepxl.com/confirmation.php?passkey='.$confirm_code)==false)
                                                {
                                                    echo "Error something has gone wrong when trying to create your account";
                                                    send_mail_error("confirmation.php: (7): ",mysql_error());
                                                }
                                            }
                                            else
                                            {
                                                echo "Error something has gone wrong when trying to create your account";
                                                send_mail_error("confirmation.php: (8): ",mysql_error());
                                            }

                                        }
                                        else
                                            echo "Email is reserved for different account creation";
                                    }
                                    else
                                        echo "Username is reserved by someone else";
                                }
                                else
                                    echo "Username is already in use";
                            }
                            else
                                echo "Email is already in use";
                        }
                        else
                            echo "Please use an actual email. We won't spam you";
                    }
                    else
                        echo "Email is invalid";
                }
                else
                    echo "Username is too long";
            }
            else
                echo "Password is too long";
        }
        else
            echo "Password field is empty";
    }
    else
        echo "Email field is empty";
}
else
    echo "Username field is empty";






////test username for validation
//$username=clean_string($_POST['username']);
//$email=clean_string($_POST['email']);
//$password=clean_string($_POST['password']);
//
//
//if(isset($_POST['username'])&&$username!='')
//{
//        if(isset($_POST['email'])&&$email!='')
//        {
//            if(isset($_POST['password'])&&$password!='')
//            {
//                if(strlen($username) < 40)
//                {
//                    if((filter_var($email, FILTER_VALIDATE_EMAIL) == true)&& strlen($email) <255)
//                    {
//                        if(is_valid_email($email))
//                        {
//                            //checks if email is already in use
//                            $query=mysql_query("SELECT id FROM users WHERE email='$email' LIMIT 1");
//                            if(mysql_num_rows($query)==0)
//                            {
//                                $query=mysql_query("SELECT id FROM user_data WHERE username='$username' LIMIT 1");
//                                if(mysql_num_rows($query)==0&&$username!='default')
//                                {
//                                    $query=mysql_query("SELECT passkey FROM temp_users WHERE username='$username' LIMIT 1");
//                                    if(mysql_num_rows($query)==0)
//                                    {
//                                        $query=mysql_query("SELECT passkey FROM temp_users WHERE email='$email' LIMIT 1");
//                                        if(mysql_num_rows($query)==0)
//                                        {
//                                                //creates random confirmation code
//                                                $confirm_code=sha1(uniqid(rand()));
//
//                                                //blowfish hashes password for database storage
//            //                                    $password=crypt($password, '$2a$07$27cad37e8a5fc13618b5ce35ad2b4d5b97400d63$');
//                                                $temp_salt="0428ab59c4a50892f6bea0564ccf62a9f99d78b3";
//                                                $password=crypt($password, '$6$rounds=5000$'.$temp_salt.'$');
//
//
//                                                $result=mysql_query("INSERT INTO temp_users SET passkey='$confirm_code', username='$username', password='$password', email='$email', timestamp='".get_date()."', email_resend='".get_date()."'");
//                                                if($result)
//                                                {
//                                                    if(sendAWSemail($email, 'Registration Confirmation', 'Click on this link to start using imagePXL!  http://www.imagepxl.com/confirmation.php?passkey='.$confirm_code))
//                                                        echo "Email has been sent! Email may be in spam folder";
//                                                    else
//                                                    {
//                                                        echo "Something went wrong";
//                                                        send_mail_error("Register_after.php - 1: ",mysql_error());
//                                                    }
//                                                }
//                                                else
//                                                {
//                                                    echo "Something went wrong";
//                                                    send_mail_error("Register_after.php - 2: ",mysql_error());
//                                                }
//                                        }
//                                        else
//                                            echo "Email is reserved for different account creation";
//                                    }
//                                    else
//                                        echo "Username is reserved by someone else";
//                                }
//                                else
//                                    echo "Username is already in use";
//                            }
//                            else
//                                echo "Email is already in use";
//                        }
//                        else
//                            echo "Please use an actual email. We won't spam you";
//                    }
//                    else
//                        echo "Email is invalid";
//                }
//                else
//                {
//                    echo "Something went wrong";
//                    send_mail_error("Register_after.php - 6: ",mysql_error());
//                }
//            }
//            else
//                echo "Password field is empty";
//        }
//        else
//            echo "Email field is empty";
//}
//else
//    echo "First name field is empty";
?>