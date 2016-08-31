<?php
//include stuff

include('universal_functions.php');

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
   }
}
else
{
   //error code
}
?>