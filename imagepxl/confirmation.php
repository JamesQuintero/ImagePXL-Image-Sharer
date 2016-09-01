<?php
@include('init.php');
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
            else
            {
                echo "Something went wrong";
                log_error("confirmation.php: (2): ", mysql_error());
            }
        }
        else
        {
            echo "Something went wrong";
            log_error("confirmation.php: (2): ", mysql_error());
        }
    }
    else
        echo "Confirmation code doesn't exist! The email was either just verified, or the confirmation code never existed at all.";
}
else
    echo "Invalid confirmation code";