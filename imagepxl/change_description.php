<?php
//include stuff

include('universal_functions.php');

$description=clean_string($_POST['description']);

if(strlen($description)<=200)
{
   $query=mysql_query("UPDATE user_data SET description='$description' WHERE user_id=$_SESSION[id]");
   if(!$query)
   {
      //error code
   }
}
?>