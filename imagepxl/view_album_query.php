<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$num=(int)($_POST['num']);

//resets image list
if($num==1)
{
    $album_id=clean_string($_POST['album_id']);
    $page=(int)($_POST['page']);
    
    $query=mysql_query("SELECT image_ids, image_exts FROM albums WHERE album_id='$album_id' LIMIT 1");
    if($query&&mysql_num_rows($query)==1)
    {
        $array=mysql_fetch_row($query);
        $image_ids=explode('|', $array[0]);
        $image_exts=explode('|', $array[1]);
        
        //gets start location of loop
        $start_index=$page*11;
        
        
        $showing_all=false;
        $new_images=array();
        $new_image_exts=array();
        for($x = $start_index; $x < ($start_index+11); $x++)
        {
            if(isset($image_ids[$x]))
            {
                $new_images[]=$image_ids[$x];
                $new_image_exts[]=$image_exts[$x];
            }
            else
            {
                $new_images[]="";
                $new_image_exts[]="";
                $showing_all=true;
            }
        }
        
        $next_page_image=$image_ids[$start_index+11];
        $prev_page_image=$image_ids[$start_index-1];
        
        $JSON=array();
        $JSON['new_image_list']=$new_images;
        $JSON['new_image_ext_list']=$new_image_exts;
        $JSON['showing_all']=$showing_all;
        $JSON['next_page_image']=$next_page_image;
        $JSON['prev_page_image']=$prev_page_image;
        echo json_encode($JSON);
        exit();
    }
}