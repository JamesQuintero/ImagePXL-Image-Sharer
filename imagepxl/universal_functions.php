<?php
header('Content-type: text/html; charset=utf-8');

function get_num_followers($user_id)
{
   if(is_id($user_id))
   {
      $query=mysql_query("SELECT num_followers FROM user_data WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
      {
         $array=mysql_fetch_row($query);
         $num_followers=(int)($array[0]);
         
         return $num_followers;
      }
      else
          return 0;
   }
}
function get_username($user_id)
{
   if(is_id($user_id))
   {
      $query=mysql_query("SELECT username FROM user_data WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
      {
         $array=mysql_fetch_row($query);
         $username=$array[0];
         
         return $username;
      }
      else
          return '';
   }
}

function get_name($user_id)
{
   if(is_id($user_id))
   {
      $query=mysql_query("SELECT name FROM user_data WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
      {
         $array=mysql_fetch_row($query);
         $name=$array[0];
         
         return $name;
      }
      else
          return '';
   }
}

function get_description($user_id)
{
   if(is_id($user_id))
   {
      $query=mysql_query("SELECT description FROM user_data WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
      {
         $array=mysql_fetch_row($query);
         $description=$array[0];
         
         return $description;
      }
   }
}
function get_user_data($user_id=0, $username='')
{
    //gets information by user_id
    if(($user_id!=0&&is_id($user_id)))
    {
        $query=mysql_query("SELECT username, num_followers, description, real_name, following, favorites, image_views, profile_views, num_images, last_sign_in, date_joined FROM user_data WHERE user_id=$user_id LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $arr=array();
            $arr['username']=$array[0];
            $arr['num_followers']=$array[1];
            $arr['description']=$array[2];
            $arr['name']=$array[3];
            $arr['following']=explode('|', $array[4]);
            $arr['favorites']=explode('|', $array[5]);
            $arr['image_views']=$array[6];
            $arr['profile_views']=$array[7];
            $arr['num_images']=$array[8];
            $arr['last_sign_in']=$array[9];
            $arr['date_joined']=$array[10];
            
            return $arr;
        }
    }
    
    //gets information by username
    else if($username!='')
    {
        $query=mysql_query("SELECT user_id, username, num_followers, description, real_name, following, favorites, image_views, profile_views, num_images, last_sign_in, date_joined FROM user_data WHERE username='$username' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $arr=array();
            $arr['user_id']=$array[0];
            $arr['username']=$array[1];
            $arr['num_followers']=$array[2];
            $arr['description']=$array[3];
            $arr['name']=$array[4];
            $arr['following']=explode('|', $array[5]);
            $arr['favorites']=explode('|', $array[6]);
            $arr['image_views']=$array[7];
            $arr['profile_views']=$array[8];
            $arr['num_images']=$array[9];
            $arr['last_sign_in']=$array[10];
            $arr['date_joined']=$array[11];
            
            return $arr;
        }
    }
}

function get_profile_picture($user_id)
{
   if(is_id($user_id))
   {
      $query=mysql_query("SELECT profile_pic_id, profile_pic_ext FROM customization WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
      {
         $array=mysql_fetch_row($query);
         $profile_pic_id=$array[0];
         $ext=$array[1];
         
         if($profile_pic_id=='')
            return "http://i.imagepxl.com/site/default_profile_pic.jpg";
         else
            return "http://i.imagepxl.com/".get_username($user_id)."/thumbs/$profile_pic_id.".$ext;
      }
   }
}
function get_user_id($username)
{
    $username=clean_stirng($username);
    if($username!='')
    {
        $query=mysql_query("SELECT user_id FROM user_data WHERE username='$username' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $user_id=$array[0];
            
            return $user_id;
        }
    }
}
function get_image_type($image_id)
{
//   $image_id=clean_string($image_id);
//   
//   $query=mysql_query("SELECT ext FROM images WHERE image_id='$image_id' LIMIT 1");
//   if($query&&mysql_num_rows($query)==1)
//   {
//      $array=mysql_fetch_row($query);
//      $ext=$array[0];
//      
//      return $ext;
//   }
   return "jpg";
}

function get_user_colors($user_id)
{
   if(is_id($user_id))
   {
      $query=mysql_query("SELECT theme, background_color, foreground_color, main_text_color, text_color FROM customization WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
      {
         $array=mysql_fetch_row($query);
         $theme=$array[0];
         
         if($theme=='custom')
         {
            $temp_color=explode('|', $array[1]);
            $temp_2_color=explode('|', $array[2]);
            $temp_3_color=explode('|', $array[3]);
            $temp_4_color=explode('|', $array[4]);

            $background_color="rgb(".$temp_color[0].",".$temp_color[1].",".$temp_color[2].")";
            $foreground_color="rgb(".$temp_2_color[0].",".$temp_2_color[1].",".$temp_2_color[2].")";
            $main_text_color="rgb(".$temp_3_color[0].",".$temp_3_color[1].",".$temp_3_color[2].")";
            $text_color="rgb(".$temp_4_color[0].",".$temp_4_color[1].",".$temp_4_color[2].")";
         }
         else
         {
             $colors=get_theme_colors($theme);
             
             $background_color=$colors[0];
             $foreground_color=$colors[1];
             $main_text_color=$colors[2];
             $text_color=$colors[3];
         }
      }
   }
   else
   {
       $background_color="rgb(245,245,245)";
       $foreground_color="rgb(255,255,255)";
       $main_text_color="rgb(220,20,0);";
       $text_color="rgb(30,30,30)";
   }
   
   $array=array();
    $array[0]=$background_color;
    $array[1]=$foreground_color;
    $array[2]=$main_text_color;
    $array[3]=$text_color;
    return $array;
}
function get_theme_colors($theme)
{
    //normal/default site theme
    if($theme=='normal')
    {
        $background_color="rgb(245,245,245)";
        $foreground_color="rgb(255,255,255)";
        $main_text_color="rgb(220,20,0);";
        $text_color="rgb(30,30,30)";
    }
    else if($theme=='dark')
    {
        $background_color="rgb(50,50,50)";
        $foreground_color="rgb(150,150,150)";
        $main_text_color="rgb(220,20,0)";
        $text_color="rgb(255,255,255)";
    }
    else if($theme=='blue')
    {
        $background_color="rgb(50,50,250)";
        $foreground_color="rgb(0,100,255)";
        $main_text_color="rgb(255,255,255)";
        $text_color="rgb(255,255,255)";
    }
    else if($theme=='green')
    {
        $background_color="rgb(0,180,0)";
        $foreground_color="rgb(50,255,50)";
        $main_text_color="rgb(220,20,0)";
        $text_color="rgb(30,30,30)";
    }
    else if($theme=='yellow')
    {
        $background_color="rgb(250,200,50)";
        $foreground_color="rgb(250,250,75)";
        $main_text_color="rgb(220,20,0)";
        $text_color="rgb(30,30,30)";
    }
    else if($theme=='red_black')
    {
        $background_color="rgb(30,30,30)";
        $foreground_color="rgb(220,20,0)";
        $main_text_color="rgb(255,255,255)";
        $text_color="rgb(255,255,255)";
    }
    
    
    
    $array=array();
    $array[0]=$background_color;
    $array[1]=$foreground_color;
    $array[2]=$main_text_color;
    $array[3]=$text_color;
    
    return $array;
}

function is_id($ID)
{
   if($ID!='')
    {
        $ID=preg_split('//', $ID, -1);
        $bool=true;
        for($x = 0; $x < sizeof($ID); $x++)
        {
            if(!($ID[$x]>0||$ID[$x]==0||$ID[$x]<0))
                $bool=false;
        }
        return $bool;
    }
    else
        return false;
}

function get_new_image_id()
{
   $array=array();
   $array[0]='a';
   $array[1]='b';
   $array[2]='c';
   $array[3]='d';
   $array[4]='e';
   $array[5]='f';
   $array[6]='g';
   $array[7]='h';
   $array[8]='i';
   $array[9]='j';
   $array[10]='k';
   $array[11]='l';
   $array[12]='m';
   $array[13]='n';
   $array[14]='o';
   $array[15]='p';
   $array[16]='q';
   $array[17]='r';
   $array[18]='s';
   $array[19]='t';
   $array[20]='u';
   $array[21]='v';
   $array[22]='w';
   $array[23]='x';
   $array[24]='y';
   $array[25]='z';
   $array[26]='A';
   $array[27]='B';
   $array[28]='C';
   $array[29]='D';
   $array[30]='E';
   $array[31]='F';
   $array[32]='G';
   $array[33]='H';
   $array[34]='I';
   $array[35]='J';
   $array[36]='K';
   $array[37]='L';
   $array[38]='M';
   $array[39]='N';
   $array[40]='O';
   $array[41]='P';
   $array[42]='Q';
   $array[43]='R';
   $array[44]='S';
   $array[45]='T';
   $array[46]='U';
   $array[47]='V';
   $array[48]='W';
   $array[49]='X';
   $array[50]='Y';
   $array[51]='Z';
   $array[52]='0';
   $array[53]='1';
   $array[54]='2';
   $array[55]='3';
   $array[56]='4';
   $array[57]='5';
   $array[58]='6';
   $array[59]='7';
   $array[60]='8';
   $array[61]='9';
   
   
   $exists=true;
   
   while($exists)
   {
      $name_array=array();
      for($x = 0; $x < 7; $x++)
      {
         $random=(int)(rand(0, sizeof($array)-1));
         $name_array[$x]=$array[$random];

         //deleted index from array
         unset($array[$x]);
         $array = array_values($array);
      }
      
      $image_id=implode('', $name_array);
      
      //checks whether image id already exists
      $query=mysql_query("SELECT user_id FROM images WHERE image_id='$image_id' LIMIT 1");
      if(mysql_num_rows($query)==0)
         $exists=false;
   }
   
   return $image_id;
   
}
function clean_string($string)
{
    return str_replace('|@|$|', '', str_replace('|%|&|', '', str_replace('|^|*|', '', trim(mysql_real_escape_string(htmlentities(stripslashes($string), ENT_COMPAT, 'UTF-8'))))));
}

function user_exists($user_id=0, $username='')
{
   if($user_id!=0&&is_id($user_id))
   {
      $query=mysql_query("SELECT email FROM users WHERE user_id=$user_id LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
         return true;
      else
         return false;
   }
   else
   {
       $query=mysql_query("SELECT user_id FROM user_data WHERE username=$username LIMIT 1");
      if($query&&mysql_num_rows($query)==1)
         return true;
      else
         return false;
   }
}
//checks if email isn't a temporary email
function is_valid_email($email)
{
    if(strstr($email, "mailinator")==true) return false;
    else if(strstr($email, "guerrillamail")==true) return false;
    else if(strstr($email, 'dispostable')==true)return false;
    else if(strstr($email, 'disposemail')==true)return false;
    else if(strstr($email, 'yopmail')==true)return false;
    else if(strstr($email, 'getairmail')==true)return false;
    else if(strstr($email, 'fakeinbox')==true)return false;
    else if(strstr($email, '10minutemail')==true)return false;
    else if(strstr($email, '20minutemail')==true)return false;
    else if(strstr($email, 'deadaddress')==true)return false;
    else if(strstr($email, 'emailsensei')==true)return false;
    else if(strstr($email, 'emailthe')==true)return false;
    else if(strstr($email, 'incognitomail')==true)return false;
    else if(strstr($email, 'koszmail')==true)return false;
    else if(strstr($email, 'mailcatch')==true)return false;
    else if(strstr($email, 'mailnesia')==true)return false;
    else if(strstr($email, 'mytrashmail')==true)return false;
    else if(strstr($email, 'noclickemail')==true)return false;
    else if(strstr($email, 'spamspot')==true)return false;
    else if(strstr($email, 'spamavert')==true)return false;
    else if(strstr($email, 'spamfree24')==true)return false;
    else if(strstr($email, 'tempemail')==true)return false;
    else if(strstr($email, 'trashmail')==true)return false;
    else if(strstr($email, 'easytrashmail')==true)return false;
    else if(strstr($email, 'easytrashemail')==true)return false;
    else if(strstr($email, 'jetable')==true)return false;
    else if(strstr($email, 'mailexpire')==true)return false;
    else if(strstr($email, 'emailexpire')==true)return false;
    else if(strstr($email, 'meltmail')==true)return false;
    else if(strstr($email, 'spambox')==true)return false;
    else if(strstr($email, 'tempomail')==true)return false;
    else if(strstr($email, 'tempoemail')==true)return false;
    else if(strstr($email, '33mail')==true)return false;
    else if(strstr($email, 'e4ward')==true)return false;
    else if(strstr($email, 'gishpuppy')==true)return false;
    else if(strstr($email, 'inboxalias')==true)return false;
    else if(strstr($email, 'mailnull')==true)return false;
    else if(strstr($email, 'spamex')==true)return false;
    else if(strstr($email, 'spamgourmet')==true)return false;
    else if(strstr($email, 'dudmail')==true)return false;
    else if(strstr($email, 'mintemail')==true)return false;
    else if(strstr($email, 'spambog')==true)return false;
    else if(strstr($email, 'flitzmail')==true)return false;
    else if(strstr($email, 'eyepaste')==true)return false;
    else if(strstr($email, '12minutemail')==true)return false;
    else if(strstr($email, 'onewaymail')==true)return false;
    else if(strstr($email, 'disposableinbox')==true)return false;
    else if(strstr($email, 'freemail')==true)return false;
    else if(strstr($email, 'koszmail')==true)return false;
    else if(strstr($email, '0wnd')==true)return false;
    else if(strstr($email, '2prong')==true)return false;
    else if(strstr($email, 'binkmail')==true)return false;
    else if(strstr($email, 'amilegit')==true)return false;
    else if(strstr($email, 'bobmail')==true)return false;
    else if(strstr($email, 'brefmail')==true)return false;
    else if(strstr($email, 'bumpymail')==true)return false;
    else if(strstr($email, 'dandikmail')==true)return false;
    else if(strstr($email, 'despam')==true)return false;
    else if(strstr($email, 'dodgeit')==true)return false;
    else if(strstr($email, 'dump-email')==true)return false;
    else if(strstr($email, 'email60')==true)return false;
    else if(strstr($email, 'emailias')==true)return false;
    else if(strstr($email, 'emailinfive')==true)return false;
    else if(strstr($email, 'emailmiser')==true)return false;
    else if(strstr($email, 'emailtemporario')==true)return false;
    else if(strstr($email, 'emailwarden')==true)return false;
    else if(strstr($email, 'ephemail')==true)return false;
    else if(strstr($email, 'explodemail')==true)return false;
    else if(strstr($email, 'fakeinbox')==true)return false;
    else if(strstr($email, 'fakeinformation')==true)return false;
    else if(strstr($email, 'filzmail')==true)return false;
    else if(strstr($email, 'fixmail')==true)return false;
    else if(strstr($email, 'get1mail')==true)return false;
    else if(strstr($email, 'getonemail')==true)return false;
    else if(strstr($email, 'haltospam')==true)return false;
    else if(strstr($email, 'ieatspam')==true)return false;
    else if(strstr($email, 'ihateyoualot')==true)return false;
    else if(strstr($email, 'imails')==true)return false;
    else if(strstr($email, 'inboxclean')==true)return false;
    else if(strstr($email, 'ipoo')==true)return false;
    else if(strstr($email, 'mail4trash')==true)return false;
    else if(strstr($email, 'mailbidon')==true)return false;
    else if(strstr($email, 'maileater')==true)return false;
    else if(strstr($email, 'mailexpire')==true)return false;
    else if(strstr($email, 'mailin8r')==true)return false;
    else if(strstr($email, 'mailinator2')==true)return false;
    else if(strstr($email, 'mailincubator')==true)return false;
    else if(strstr($email, 'mailme')==true)return false;
    else if(strstr($email, 'mailnull')==true)return false;
    else if(strstr($email, 'mailzilla')==true)return false;
    else if(strstr($email, 'meltmail')==true)return false;
    else if(strstr($email, 'nobulk')==true)return false;
    else if(strstr($email, 'nowaymail')==true)return false;
    else if(strstr($email, 'pookmail')==true)return false;
    else if(strstr($email, 'proxymail')==true)return false;
    else if(strstr($email, 'putthisinyourspamdatabase')==true)return false;
    else if(strstr($email, 'quickinbox')==true)return false;
    else if(strstr($email, 'safetymail')==true)return false;
    else if(strstr($email, 'snakemail')==true)return false;
    else if(strstr($email, 'sharklasers')==true)return false;
    else 
        return true;
}
function user_id_terminated($user_id)
{
    $query=mysql_query("SELECT closed FROM users WHERE id=$user_id LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $terminated=$array[0];
        if($terminated=='0')
            return false;
        else
            return true;
    }
}

function get_date()
{
    $date=time();
    return $date;
}
function get_regular_date($timestamp)
{
//    return date('F j, Y g:i:s A', $timestamp);
    return date('F j, Y', $timestamp);
}
function get_adjusted_date($timestamp, $timezone)
{
    return date('F j, Y g:i A', ($timestamp-($timezone*60)));
}
function get_current_time($timezone)
{
    $date=get_date();
    $new_timezone=($timezone*60);
    $total=$date-$new_timezone;
    
    $final=(time())-($timezone*60);
    return (int)($final);
}
function get_time_since_seconds($timestamp, $timezone)
{
    $time=$timestamp-($timezone*60);
    $new_time=(int)(get_current_time($timezone)-$time);
    if(((int)$new_time)<0)
        return 0;
    else
        return $new_time;
}
function get_time_since($timestamp, $timezone)
{
    $time=(int)($timestamp-($timezone*60));
    $new_time=get_current_time($timezone)-$time;
    
    if($new_time>=3600)
    {
        
        if($new_time>2678400)
        {
            $ago=get_adjusted_date($time, $timezone);
            
            $time=explode(' ', $ago);
            $ago=$time[0]." ".$time[1]." ".$time[2];
        }
        else if($new_time>604800&&$new_time<2678400)
            $ago=number_format((int)($new_time/86400),0)." days ago";
        else if($new_time>=86400&&$new_time<604800)
        {
            $num_days=number_format((int)($new_time/86400),0);
            $num_hours=number_format(((int)($new_time%86400)/3600));
            
            if($num_days!=1)
                $days=$num_days." days";
            else
                $days="1 day";
                
            if($num_hours!=0)
            {
                if($num_hours!=1)
                    $hours=$num_hours." hours ago";
                else
                    $hours="1 hour ago";
            }
            else
            {
                $minutes=number_format(((int)($new_time%3600)/60));
                $hours=$minutes." minutes ago";
            }
            
            $ago=$days." ".$hours;
        }

        else if($new_time>=7200)
            $ago=number_format((int)($new_time/3600),0)." hours ago";
        else
            $ago="1 hour ago";
    }
    else
    {
        if($new_time>=120)
            $ago=number_format((int)($new_time/60),0)." minutes ago";
        else if($new_time>=60&&$new_time<120)
            $ago="1 minute ago";
        else if($new_time<60)
            $ago=$new_time." seconds ago";
    }
    return $ago;
}
function sendAWSEmail($to, $subject, $message)
{
    require 'aws-sdk-for-php-master/sdk.class.php';
    
    $from=get_email_from();
    
    $array=array();
    $array['key']=ACCES_KEY;
    $array['secret']=SECRET_KEY;
    $amazonSes = new AmazonSES($array);
    $amazonSes->verify_email_address($from);

    $response = $amazonSes->send_email($from,
        array('ToAddresses' => array($to)),
        array(
            'Subject.Data' => $subject,
            'Body.Text.Data' => $message,
        )
    );

    if (!$response->isOK())
    {
        log_error("sendAWSEmail(): ", "Something went wrong when sending an email.");
        // handle error
        return false;
    }
    else
        return true;
}
function get_email_from()
{
    return "imagepxl@imagepxl.com";
}
function log_error($error, $second_error)
{
    require 'aws-sdk-for-php-master/sdk.class.php';
    
    $from=get_email_from();
        
    $array=array();
    $array['key']=ACCES_KEY;
    $array['secret']=SECRET_KEY;
    $amazonSes = new AmazonSES($array);
    $amazonSes->verify_email_address($from);

    $response = $amazonSes->send_email($from,
        array('ToAddresses' => array('ERROR_EMAIL')),
        array(
            'Subject.Data' => $error,
            'Body.Text.Data' => $second_error,
        )
    );

    if (!$response->isOK())
    {
        "Well I'm screwed";
    }
}
//checks if current user is following $user_id
function user_following($user_id)
{
    if(is_id($user_id))
    {
        $query=mysql_query("SELECT following FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $following=explode('|', $array[0]);
            
            $index=array_search($user_id, $following);
            if($index!==false)
                return true;
            else
                return false;
        }
    }
}
function get_image_data($image_id)
{
    $image_id=clean_string($image_id);
    $query=mysql_query("SELECT * FROM images WHERE image_id='$image_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_array($query);
        $data=array();
        $data['description']=$array['description'];
        $data['ext']=$array['ext'];
        $data['user_id']=$array['user_id'];
        $data['timestamp']=$array['timestamp'];
        $data['likes']=explode('|', $array['likes']);
        $data['dislikes']=explode('|', $array['dislikes']);
        $data['comment_ids']=explode('|', $array['comment_ids']);
        $data['comment_likes']=explode('|', $array['comment_likes']);
        $data['comment_dislikes']=explode('|', $array['comment_dislikes']);
        
        //explodes comment likes and dislikes
        for($x = 0; $x < sizeof($data['comment_likes']); $x++)
        {
            $data['comment_likes'][$x]=explode('^', $data['comment_likes'][$x]);
            $data['comment_dislikes'][$x]=explode('^', $data['comment_dislikes'][$x]);
        }
        
        $data['comment_user_ids']=explode('|', $array['comment_user_ids']);
        $data['num_favorites']=$array['num_favorites'];
        
        return $data;
    }
    else
        return array();
}
function get_album_data($album_id)
{
    $album_id=clean_string($album_id);
    $query=mysql_query("SELECT * FROM albums WHERE album_id='$album_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_array($query);
        $data['album_name']=$array['album_name'];
        $data['user_id']=$array['user_id'];
        $data['image_ids']=explode('|', $array['image_ids']);
        $data['image_exts']=explode('|', $array['image_exts']);
        
        
        return $data;
    }
    
}
function get_album_name($album_id)
{
    $album_id=clean_string($album_id);
    $query=mysql_query("SELECT album_name FROM albums WHERE album_id='$album_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        
        return $array[0];
    }
    else
        return '';
}
function convert_number($n)
{
    // first strip any formatting;
    $n = (0+str_replace(",","",$n));

    // is this a number?
    if(!is_numeric($n)) return false;

    // now filter it;

    return number_format($n);
}
function add_view($image_id)
{
    $valid=false;
    $image_id=clean_string($image_id);
    
    if(isset($_SESSION['id']))
    {
        $query=mysql_query("SELECT recently_viewed_images FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $recently_viewed_images=explode('|', $array[0]);


            $index=-1;
            $current=get_date();

            $temp_array=array();
            $changed=false;
            for($x = 0; $x < sizeof($recently_viewed_images); $x++)
            {
                $recently_viewed_images[$x]=explode('^', $recently_viewed_images[$x]);

                //adds to array if view isn't over an hour
                if($current-$recently_viewed_images[$x][1]<86400)
                {
                    $temp_array[]=$recently_viewed_images[$x];
                    if($recently_viewed_images[$x][0]==$image_id)
                        $index=$x;
                }
                else
                {
                    $changed=true;

                    if($recently_viewed_images[$x][0]==$image_id)
                    {
                        $recently_viewed_images[$x][1]=get_date();
                        $index=$x;
                        $valid=true;
                    }
                }
            }

            //if image already viewed
            if($index==-1)
            {
                $valid=true;
                $array=array();
                $array[0]=$image_id;
                $array[1]=get_date();
                $temp_array[]=$array;
                $changed=true;
            }

            if($changed)
            {
                for($x = 0; $x < sizeof($temp_array); $x++)
                    $temp_array[$x]=implode('^', $temp_array[$x]);
                $temp_array=implode('|', $temp_array);

                $query=mysql_query("UPDATE user_data SET recently_viewed_images='$temp_array' WHERE user_id=$_SESSION[id]");
            }
        }
    }
    else
    {
        $ip_address=$_SERVER['REMOTE_ADDR'];
        $query=mysql_query("SELECT viewed_images, timestamps FROM non_registered_views WHERE ip_address='$ip_address' LIMIT 1");
        if($query)
        {
            //if user has visited site before
            if(mysql_num_rows($query)==1)
            {
                $array=mysql_fetch_row($query);
                $viewed_images=explode('|', $array[0]);
                $timestamps=explode('|', $array[1]);
                
                
                $index=-1;
                $current_time=get_date();

                $temp_viewed_images=array();
                $temp_timestamps=array();
                $changed=false;
                for($x = 0; $x < sizeof($viewed_images); $x++)
                {

                    //adds to array if view isn't over an hour
                    if($current_time-$timestamps[$x]<86400)
                    {
                        $temp_viewed_images[]=$viewed_images[$x];
                        $temp_timestamps[]=$timestamps[$x];
                        
                        //gets index of image
                        if($viewed_images[$x]==$image_id)
                            $index=$x;
                    }
                    else
                    {
                        $changed=true;

                        if($viewed_images[$x]==$image_id)
                        {
                            $timestamps[$x]=get_date();
                            $index=$x;
                            $valid=true;
                        }
                    }
                }

                //if image already viewed
                if($index==-1)
                {
                    $valid=true;
                    $temp_timestamps[]=get_date();
                    $temp_viewed_images[]=$image_id;
                    $changed=true;
                }
                
                if($changed)
                {
                    
                    $viewed_images=implode('|', $temp_viewed_images);
                    $timestamps=implode('|', $temp_timestamps);

                    $query=mysql_query("UPDATE non_registered_views SET viewed_images='$viewed_images', timestamps='$timestamps' WHERE ip_address='$ip_address'");
                }
            }
            
            //if user is new
            else
            {
                $query=mysql_query("INSERT INTO non_registered_views SET ip_address='$ip_address', viewed_images='$image_id', timestamps='".get_date()."'");
                if($query)
                    $valid=true;
            }
        }
        else
            log_error("universal_functions.php: (add_view()): ", mysql_error());
    }
    
    
    //if view counts
    if($valid)
    {
        $query=mysql_query("SELECT views, user_id FROM images WHERE image_id='$image_id' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $views=$array[0];
            $user_id=$array[1];

            $views++;

            $query=mysql_query("UPDATE images SET views=$views WHERE image_id='$image_id'");
            if($query)
            {
                $query=mysql_query("SELECT image_views FROM user_data WHERE user_id=$user_id LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $image_views=$array[0];

                    $image_views++;

                    $query=mysql_query("UPDATE user_data SET image_views=$image_views WHERE user_id=$user_id");
                }
            }
        }
    }
}
function add_profile_view($user_id)
{
    $user_id=(int)($user_id);
    
    if(is_id($user_id))
    {
        $query=mysql_query("SELECT recently_viewed_profiles FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $recently_viewed_profiles=explode('|', $array[0]);

            $valid=false;
            $index=-1;
            $current=get_date();

            $temp_array=array();
            $changed=false;
            for($x = 0; $x < sizeof($recently_viewed_profiles); $x++)
            {
                $recently_viewed_profiles[$x]=explode('^', $recently_viewed_profiles[$x]);

                //adds to array if view isn't over an hour
                if($current-$recently_viewed_profiles[$x][1]<86400)
                {
                    $temp_array[]=$recently_viewed_profiles[$x];
                    if($recently_viewed_profiles[$x][0]==$user_id)
                        $index=$x;
                }
                else
                {
                    $changed=true;

                    if($recently_viewed_profiles[$x][0]==$user_id)
                    {
                        $recently_viewed_profiles[$x][1]=get_date();
                        $index=$x;
                        $valid=true;
                    }
                }
            }

            //if image already viewed
            if($index==-1)
            {
                $valid=true;
                $array=array();
                $array[0]=$user_id;
                $array[1]=get_date();
                $temp_array[]=$array;
                $changed=true;
            }

            if($changed)
            {
                for($x = 0; $x < sizeof($temp_array); $x++)
                    $temp_array[$x]=implode('^', $temp_array[$x]);
                $temp_array=implode('|', $temp_array);

                $query=mysql_query("UPDATE user_data SET recently_viewed_profiles='$temp_array' WHERE user_id=$_SESSION[id]");
            }

            //if view counts
            if($valid)
            {
                $query=mysql_query("SELECT profile_views FROM user_data WHERE user_id=$user_id LIMIT 1");
                if($query&&mysql_num_rows($query)==1)
                {
                    $array=mysql_fetch_row($query);
                    $views=$array[0];

                    $views++;

                    $query=mysql_query("UPDATE user_data SET profile_views=$views WHERE user_id=$user_id ");
                }
            }


        }
    }
}

//determines if gif is an animated gif
function is_animated($filename)
{
    $filecontents=file_get_contents($filename);

    $str_loc=0;
    $count=0;
    while ($count < 2) # There is no point in continuing after we find a 2nd frame
    {
        $where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
        if ($where1 === FALSE)
            break;
        else
        {
            $str_loc=$where1+1;
            $where2=strpos($filecontents,"\x00\x2C",$str_loc);
            if ($where2 === FALSE)
                break;
            else
            {
                if ($where1+8 == $where2)
                    $count++;

                $str_loc=$where2+1;
            }
        }
    }

    if ($count > 1)
        return true;
    else
        return false;
}

//checks if a file exists on a different server
//(used for EC2 and S3 communication)
function file_exists_server($file_url)
{
    $AgetHeaders = @get_headers($file_url);
    if (preg_match("|200|", $AgetHeaders[0])) 
        return true;
    else
        return false;
}

function get_banner_data($user_id)
{
    if(is_id($user_id))
    {
        $query=mysql_query("SELECT banner_top, has_banner FROM customization WHERE user_id=$user_id LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);
            $banner_top=$array[0];
            $has_banner=$array[1];
            
            
            $banner_data=array();
            if($has_banner=="true")
                $banner_data['has_banner']=true;
            else
                $banner_data['has_banner']=false;
            
            $banner_data['banner_top']=$banner_top;
            return $banner_data;
        }
    }
}
function get_current_theme()
{
    $query=mysql_query("SELECT theme FROM customization WHERE user_id=$_SESSION[id] LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        return $array[0];
    }
}