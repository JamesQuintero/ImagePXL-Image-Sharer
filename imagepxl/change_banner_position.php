<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$top=(int)($_POST['top']);

//if banner isn't out of bounds
if($top<=0)
{
    mysql_query("UPDATE customization SET banner_top='$top' WHERE user_id=$_SESSION[id]");
}