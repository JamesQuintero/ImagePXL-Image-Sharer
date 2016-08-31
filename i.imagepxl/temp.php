<?php
//counts view
$myFile = "./views/mXkXLyU.txt";
$temp_content=file_get_contents($myFile);
$contents=explode('|', $contents);
print_r($contents);

//if new file
if($contents[0]=="")
    $contents[0]=time();


//if haven't viewed before
if(array_search($_SERVER['REMOTE_ADDR'], $contents)===false)
    $contents[]=$_SERVER[REMOTE_ADDR];
$contents=implode("|", $contents);

echo $contents;
?>