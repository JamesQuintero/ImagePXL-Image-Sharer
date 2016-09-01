<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

$theme=clean_string($_POST['theme']);

if($theme=='normal'||$theme=='dark'||$theme=='red_black'||$theme=='yellow'||$theme=='green'||$theme=='blue'||$theme=='custom')
{
    //gets custom stuff
    if($theme=='custom')
    {
        $background_color=clean_string($_POST['background_color']);
        $foreground_color=clean_string($_POST['foreground_color']);
        $main_text_color=clean_string($_POST['main_text_color']);
        $text_color=clean_string($_POST['text_color']);
        
        //protects against extra long strings
        //11 is the max length "255|255|255" can be
        if(strlen($background_color)<=11&&strlen($foreground_color)<=11&&strlen($main_text_color)<=11&&strlen($text_color)<=11)
            mysql_query("UPDATE customization SET theme='custom', background_color='$background_color', foreground_color='$foreground_color', main_text_color='$main_text_color', text_color='$text_color' WHERE user_id=$_SESSION[id]");
        
        $JSON=array();
        $JSON['background_color']=$background_color;
        $JSON['foreground_color']=$foreground_color;
        $JSON['main_text_color']=$main_text_color;
        $JSON['text_color']=$text_color;
        echo json_encode($JSON);
        exit();
    }
    else
    {
        mysql_query("UPDATE customization SET theme='$theme' WHERE user_id=$_SESSION[id]");
        
        $colors=get_theme_colors($theme);
        $JSON=array();
        $JSON['background_color']=$colors[0];
        $JSON['foreground_color']=$colors[1];
        $JSON['main_text_color']=$colors[2];
        $JSON['text_color']=$colors[3];
        echo json_encode($JSON);
        exit();
    }
}