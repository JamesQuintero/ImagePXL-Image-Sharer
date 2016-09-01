<?php
@include('init.php');
include('universal_functions.php');

//pauses code to let user get logged out
//usleep(1000000);
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id(true);

if(isset($_COOKIE['acc_id']))
{
    setcookie('acc_id', '0', (time()-(1)), null, null, false, true);
    $last_page=$_SERVER['HTTP_REFERER'];
    header("Location: ".$last_page);
}
else
    header("Location: http://imagepxl.com");