<?php
//ob_start();
//ini_set('session.cookie_httponly', true);
//session_start();
//session_cache_limiter();


$host="AMAZON_RDS"; //localhost
$username="DATABASE_USERNAME"; //database username
$password="RDS_PASSWORD"; //password for user to database
$db_name="DATABASE_NAME"; //name of database

//opens connection to mysql server

$dbc= mysql_connect("$host","$username", "$password" );
if(!$dbc)
{
   $from='no-reply@redlay.com';

   $array=array();
   $array['key']=ACCESS_KEY;
   $array['secret']=SECRET_KEY;
   $amazonSes = new AmazonSES($array);
   $amazonSes->verify_email_address($from);

   $amazonSes->send_email($from,
       array('ToAddresses' => array('EMAIL_ADDRESS')),
       array(
           'Subject.Data' => "Redlay down!",
           'Body.Text.Data' => mysql_error(),
       )
   );

    die("We are sorry, but it looks like imagepxl.com is down right now. ");
}

//select database
$db_selected = mysql_select_db("$db_name", $dbc);
if(!$db_selected)
{
   $from='no-reply@redlay.com';

   $array=array();
   $array['key']=ACCESS_KEY;
   $array['secret']=SECRET_KEY;
   $amazonSes = new AmazonSES($array);
   $amazonSes->verify_email_address($from);

   $amazonSes->send_email($from,
       array('ToAddresses' => array('EMAIL_ADDRESS')),
       array(
           'Subject.Data' => "Redlay down!",
           'Body.Text.Data' => mysql_error(),
       )
   );

    die("We are sorry, but it looks like imagepxl.com is down right now. ");
}