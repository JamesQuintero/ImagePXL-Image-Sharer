<?php
@include('init.php');
//if(strstr($_SERVER['SERVER_NAME'], "www")==false)
//    include('cross_domain_headers.php');

include('universal_functions.php');


$passkey=clean_string($_GET['passkey']);


if(strlen($passkey)==40)
{
    $query=mysql_query("SELECT user_id, email FROM temp_users WHERE passkey='$passkey' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
            $array=mysql_fetch_row($query);

            $user_id=$array[0];
            $email=$array[1];
            
            $query=mysql_query("UPDATE users SET email_verified=1 WHERE id=$user_id");
            if($query)
            {
                $query=mysql_query("DELETE FROM temp_users WHERE passkey='$passkey'");
                if($query)
                    echo "Your email has been verified! Go to <a href='http://imagepxl.com'>imagePXL</a>";
            }
    }
    else
        echo "Confirmation code doesn't exist! The email was either just verified, or the confirmation code never existed at all.";
}
else
    echo "Invalid confirmation code";





//$passkey=clean_string($_GET['passkey']);
//
//////gets all the necessary AWS schtuff
//require 'aws-sdk-for-php-master/sdk.class.php';
//
//$array=array();
//$array['key']=ACCES_KEY;
//$array['secret']=SECRET_KEY;
//$s3=new AmazonS3($array);
//
////gets all the necessary AWS schtuff
//if (!class_exists('S3'))
//    require_once('S3.php');
//if (!defined('awsAccessKey'))
//    define('awsAccessKey', ACCES_KEY);
//if (!defined('awsSecretKey'))
//    define('awsSecretKey', SECRET_KEY);
//
////creates S3 item with schtuff
//$awsS3 = new S3(awsAccessKey, awsSecretKey);
//
//echo "Creating account...";
//
//if(strlen($passkey)==40)
//{
//    $query=mysql_query("SELECT username, password, email FROM temp_users WHERE passkey='$passkey' LIMIT 1");
//    if($query&&mysql_num_rows($query)==1)
//    {
//            $array=mysql_fetch_row($query);
//
//            $username=$array[0];
//            $password=$array[1];
//            $email=$array[2];
//            
//
//            $timestamp=get_date();
//
//            //gives completely unique and random account id
//            $bool=false;
//            while($bool==false)
//            {
//                //gets a random SHA512 hash of random hash for the account id
//                $temp_hash=sha1(uniqid(rand()));
//                $temp_salt=sha1(uniqid(rand()));
//                $account_id=crypt($temp_hash, '$6$rounds=5000$'.$temp_salt.'$');
//                $act_id=sha1($account_id);
//
//                //checks if act_id already exists
//                $query=mysql_query("SELECT id FROM users WHERE account_id='$act_id' LIMIT 1");
//                if($query&&mysql_num_rows($query)==0)
//                    $bool=true;
//            }
//
//            //inserts the user into the users table
//            $query=mysql_query("INSERT INTO users SET password='$password', email='$email', username='$username', ip_addresses='$_SERVER[REOMTE_ADDR]', account_id='$act_id'");
//            if(!$query)
//            {
//                echo "Error something has gone wrong when trying to create your account";
//                send_mail_error("confirmation.php: (1): ",mysql_error());
//            }
//            
//            //sets login cookie with value of the user's account id for a month
//            setcookie('acc_id', $act_id, (time()+(86400*31)), null, null, false, true);
//
//            $query=mysql_query("SELECT id FROM users WHERE email='$email'");
//            $array=mysql_fetch_row($query);
//            $_SESSION['id']=$array[0];
//
//            //remove from temporary table
//            $query=mysql_query("DELETE FROM temp_users WHERE passkey='$passkey'");
//            if(!$query)
//            {
//                echo "Error something has gone wrong when trying to create your account";
//                send_mail_error("confirmation.php: (2): ",mysql_error());
//            }
//            
//            $query=mysql_query("INSERT INTO user_data SET user_id=$_SESSION[id], username='$username', num_followers=0, description='', name='', following='', favorites='', image_views=0, profile_views=0, num_images=0, last_sign_in='$timestamp', date_joined='$timestamp'");
//            if(!$query)
//            {
//                echo "Error something has gone wrong when trying to create your account";
//                send_mail_error("confirmation.php: (3): ",mysql_error());
//            }
//            
//            $query=mysql_query("SELECT num_users FROM data WHERE num=1");
//            if($query)
//            {
//                $array=mysql_fetch_row($query);
//                $num_users=$array[0];
//                $num_users++;
//                $query=mysql_query("UPDATE data SET num_users=$num_users WHERE num=1");
//                if(!$query)
//                {
//                    echo "Error something has gone wrong when trying to create your account";
//                    send_mail_error("confirmation.php: (4): ",mysql_error());
//                }
//            }
//            else
//            {
//                echo "Error something has gone wrong when trying to create your account";
//                send_mail_error("confirmation.php: (5): ",mysql_error());
//            }
//            
//            $query=mysql_query("INSERT INTO customization SET user_id=$_SESSION[id], profile_pic_id='', main_color='220|20|0', secondary_color='30|30|30'");
//            if(!$query)
//            {
//                echo "Error something has gone wrong when trying to create your account";
//                send_mail_error("confirmation.php: (6): ",mysql_error());
//            }
//            
//            
//            
////            $responses=array();
////
////            $new=array();
////            $new['body']="";
////            $new['contentType']="text/plain";
////            $new['acl']=AmazonS3::ACL_PRIVATE;
////
////            $responses[]=$s3->create_object('imagepxl.users', $_SESSION[id]."/files/login.txt", $new);
////            $responses[]=$s3->create_object('imagepxl.users', $_SESSION[id]."/files/logout.txt", $new);
//// 
//// 
////            //default profile picture's current file location
////            $file="http://i.imagepxl.com/default/default_profile_pic.jpg";
////            $temp_path="/tmp/".md5(uniqid(rand())).".jpg";
////            copy($file, $temp_path);
////            
////            $profile_pic_name=get_new_image_id();
////
////            //uploads default profile picture from temporary location
////            $awsS3->putObjectFile($temp_path, "imagepxl.users", "users/$_SESSION[id]/photos/$profile_pic_name.jpg", S3::ACL_PUBLIC_READ);
////            $awsS3->putObjectFile($temp_path, "imagepxl.users", "users/$_SESSION[id]/thumbs/$profile_pic_name.jpg", S3::ACL_PUBLIC_READ);
////            unlink($temp_path);
//
//            header("Location: http://imagepxl.com/user/$username");
//            exit();     
//                    
//    }
//    else
//        echo "Confirmation code doesn't exist! The account was either just created or the confirmation code never existed at all.";
//}
//else
//    echo "Invalid confirmation code";
?>