<?php
include('init.php');
include('universal_functions.php');

$path=".";
    $directory=opendir($path);
    $num=0;
    $lines=0;
    while($file=readdir($directory))
    {
        $file_name=explode('.', $file);
        if(substr($file, 0, 1)!="."&&$file!='php.ini'&&$file_name[1]!='png'&&$file_name[1]!='jpg'&&$file_name[1]!='gif'&&$file_name[1]!='ico')
        {
            $file_contents=file_get_contents($file);
            $num_lines=sizeof(file($file));
            $files[$num]=$path."/".$file.": ".$num_lines." lines";
            $contents+=$num_lines;
            $lines+=strlen($file_contents);
            $num++;
        }
    }
    closedir($directory);


    

//    $path="./mobile";
//    $directory=opendir($path);
//    while($file=readdir($directory))
//    {
//        $file_name=explode('.', $file);
//        if(substr($file, 0, 1)!="."&&$file_name[1]!='png'&&$file_name[1]!='jpg'&&$file_name[1]!='gif')
//        {
//            $file_contents=file_get_contents($path."/".$file);
//            $num_lines=sizeof(file($path."/".$file));
//            $files[$num]=$path."/".$file.": ".$num_lines." lines";
//            $contents+=$num_lines;
//            $lines+=strlen($file_contents);
//            $num++;
//        }
//    }
//    closedir($directory);


    print_r($files);
    echo number_format($contents)." total lines \n";
    echo number_format($lines)." total characters";
?>