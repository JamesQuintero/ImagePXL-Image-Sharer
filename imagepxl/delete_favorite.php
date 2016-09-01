<?php
@include("init.php");
include('universal_functions.php');
$allowed="users";
include("security_checks.php");

$image_id=clean_string($_POST['image_id']);

$query=mysql_query("SELECT favorites FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
if($query&&mysql_num_rows($query)==1)
{
   $array=mysql_fetch_row($query);
   $favorites=explode('|', $array[0]);
   
   $index=array_search($image_id, $favorites);
   
   unset($favorites[$index]);
   $favorites = array_values($favorites);
   
   $favorites=implode('|', $favorites);
   
   $query=mysql_query("UPDATE user_data SET favorites='$favorites' WHERE user_id=$_SESSION[id]");
   if(!$query)
   {
      //error code
      log_error("delete_favorite.php: (2): ", mysql_error());
   }
}
else
{
   //error code
   log_error("delete_favorite.php: (2): ", mysql_error());
}