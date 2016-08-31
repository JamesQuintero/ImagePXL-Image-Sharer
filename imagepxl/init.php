<?php
//changes imagepxl.com to www.imagepxl.com
if(strstr($_SERVER['SERVER_NAME'], "imagepxl.com")==true)
{
    if(strstr($_SERVER['SERVER_NAME'], "www")==true)
    {
         header("Location: http://imagepxl.com".$_SERVER['REQUEST_URI']);
         exit();
    }
}
else
    exit();


ob_start();
ini_set('session.cookie_httponly', true);
session_start();
session_cache_limiter();


$host="mydbinstance.cnwycvvueub2.us-east-1.rds.amazonaws.com"; //localhost
$username="root"; //database username
$password="L88014819F"; //password for user to database
$db_name="imagepxlDB"; //name of database

//$host="localhost"; //localhost
//$username="root"; //database username
//$password=""; //password for user to database
//$db_name="image_sharer"; //name of database

//opens connection to mysql server

$dbc= mysql_connect("$host","$username", "$password" );
if(!$dbc)
{
//    $from='no-reply@redlay.com';
//
//    $array=array();
//    $array['key']=ACCES_KEY;
//    $array['secret']=SECRET_KEY;
//    $amazonSes = new AmazonSES($array);
//    $amazonSes->verify_email_address($from);
//
//    $amazonSes->send_email($from,
//        array('ToAddresses' => array('redlaycom@gmail.com')),
//        array(
//            'Subject.Data' => "Redlay down!",
//            'Body.Text.Data' => mysql_error(),
//        )
//    );

    die("We are sorry, but it looks like redlay.com is down right now. ");
}

//select database
$db_selected = mysql_select_db("$db_name", $dbc);
if(!$db_selected)
{
//    $from='no-reply@redlay.com';
//
//    $array=array();
//    $array['key']=ACCES_KEY;
//    $array['secret']=SECRET_KEY;
//    $amazonSes = new AmazonSES($array);
//    $amazonSes->verify_email_address($from);
//
//    $amazonSes->send_email($from,
//        array('ToAddresses' => array('redlaycom@gmail.com')),
//        array(
//            'Subject.Data' => "Redlay down!",
//            'Body.Text.Data' => mysql_error(),
//        )
//    );

    die("We are sorry, but it looks like redlay.com is down right now. ");
}


//if(isset($_SESSION['id']))
//{
//    $query=mysql_query("SELECT theme FROM themes WHERE user_id=$_SESSION[id] LIMIT 1");
//    if($query&&mysql_num_rows($query)==1)
//    {
//        $array=mysql_fetch_row($query);
//        $theme=$array[0];
//
//        if($theme=="custom")
//        {
//            $query=mysql_query("SELECT redlay_gold FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
//            if($query&&mysql_num_rows($query)==1)
//            {
//                $array=mysql_fetch_row($query);
//                
//                if($array[0]=='')
//                    $theme="white";
//            }
//        }
//        $redlay_theme=$theme;
//    }
//    else
//        $redlay_theme="white";
//}
//else 
//    $redlay_theme="white";
?>
