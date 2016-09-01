<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

include("requiredS3.php");


//checks if there actually is a photo selected
if($_FILES['image']['size']!=0)
{
    //if the file is less than or equal to 10MB
    if($_FILES['image']['size']<=5120000)
    {
        //gets image extention:
        $type=strtolower(end(explode('.', $_FILES['image']['name'])));

        $allowed=array('jpeg' ,'jpg', 'png', 'gif');
        if(in_array($type, $allowed))
        {
            //gets image dimensions
            list($width, $height)=getimagesize($_FILES['image']['tmp_name']);

            //if min width and height
            if($width>=400&&$height>=150)
            {
                $new_width=1000;
                $new_height=$height/($width/1000);

                
                mysql_query("UPDATE customize SET has_banner='true' WHERE user_id=$_SESSION[id]");

                $path=$_SESSION['username']."/banner.jpg";
                
                    
                if($type=='jpeg'||$type=='jpg')
                    $img=imagecreatefromjpeg($_FILES['image']['tmp_name']);
                else if($type=='png')
                    $img=imagecreatefrompng($_FILES['image']['tmp_name']);
                else if($type=='gif')
                    $img=imagecreatefromgif($_FILES['image']['tmp_name']);

                $thumb=imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($thumb, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($thumb, $_FILES['image']['tmp_name'], 80);

                $headers=array();
                $headers['Cache-Control']='max-age=0';
                $s3->putObjectFile($_FILES['image']['tmp_name'], "bucket_name", $path, S3::ACL_PUBLIC_READ, $headers);

                unlink($_FILES['image']['tmp_name']);

                $success='true';
            }
            else
            {
                $message="Photo's width needs to be at least 400px and height at least 150px";
                $success='false';
            }
        }
        else
        {
            $message="That file type isn't allowed";
            $success='false';
        }
    }
    else
    {
        $message="Image file is too big. 5MB is the max.";
        $success='false';
    }
}
else
{
    $message="No Image Selected!";
    $success='false';
}
?>

<script type="text/javascript">
    console.log("<?php echo $message; ?>");
    
    if(<?php echo $success ?>==true)
    {
        window.parent.document.getElementById('banner_container_because_F_javascript').innerHTML="<img id='banner' src='<?php echo "http://i.imagepxl.com/$_SESSION[username]/banner.jpg"; ?>' style='top:0px' />";
        parent.initialize_banner();
    }
    else
        parent.display_error("<?php echo $message; ?>", 'bad_errors');
</script>