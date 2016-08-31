<?php
//$allowed
//user = only allows user access
//all = allows all access
//ini_set('display_errors', 'on');
//$allowed="all";

if(isset($_COOKIE['acc_id']))
{
    //if user is completely logged in
    if(isset($_SESSION['id']))
    {
        //if the user's account is terminated
        if(user_id_terminated($_SESSION['id']))
        {
            header("Location: http://www.imagepxl.com/account_terminated.php");
            exit();
        }
        if(!isset($_SESSION['username']))
            $_SESSION['username']=get_username($_SESSION['id']);
    }
    
    //sets user's session id
    else
    {
        //gets the user id and sets it
        $query=mysql_query("SELECT id FROM users WHERE account_id='$_COOKIE[acc_id]' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $_SESSION['id']=$array[0];
            
            $_SESSION['username']=get_username($array[0]);
        }

        //deletes cookie if acc_id isn't valid or doesn't exist
        else
            setcookie('acc_id', '', (time()-(1)), null, null, false, true);
    }
}

//sets user's cookie acc_ids
else if(isset($_SESSION['id']))
{
    $query=mysql_query("SELECT account_id FROM users WHERE id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $account_id=$array[0];

        //sets the user to be logged in for 3 months
        setcookie('acc_id', $account_id, strtotime('+90 days'), null, null, false, true);
    }
}

//redirects if user isn't logged in and only users can access
else if($allowed=="users")
{
    header("Location: http://www.imagepxl.com");
    exit();
}