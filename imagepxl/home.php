<?php
include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');


$query=mysql_query("SELECT username, num_followers, image_views, profile_views FROM user_data WHERE user_id=$_SESSION[id] LIMIT 1");
if($query&&mysql_num_rows($query)==1)
{
    $array=mysql_fetch_row($query);
    $username=$array[0];
    $num_followers=number_format($array[1]);
    $image_views=number_format($array[2]);
    $profile_views=number_format($array[3]);
}
?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
   <head>
      <title>Home</title>
      <?php include('code_header.php'); ?>
      <script type="text/javascript">
         function change_colors()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
         }
         function display_images(page, user_id)
         {
            $.post('home_query.php',
            {
               num:1,
               page:page,
               timezone: get_timezone(),
               user_id: user_id
            }, function(output)
            {
               var image_ids=output.image_ids;
               var image_exts=output.image_exts;
               var user_ids=output.user_ids;
               var usernames=output.usernames;
               var timestamps=output.timestamps;
               var num_likes=output.num_likes;
               var num_dislikes=output.num_dislikes;
               var has_liked=output.has_liked;
               var has_disliked=output.has_disliked;
               var num_views=output.num_views;
               var descriptions=output.descriptions;
               var type=output.type;
               var album_ids=output.album_ids;
               var album_names=output.album_names;
               var num_album_images=output.num_album_images;
               var profile_pictures=output.profile_pictures;
               var thumbnails=output.thumbnails;
               var nsfw=output.nsfw;
               
               if(image_ids.length>0)
               {
                    var html="";
                    for(var x = 0; x < image_ids.length; x++)
                    {
                        if(image_ids[x]!=undefined)
                        {
                            //gets album thumbnails
                             var album_images="";
                             var top=0;
                             var left=0;
                             var outside_width=150;
                             for(var y = 0; y < 5; y++)
                             {
                                 if(image_ids[x][y]!=undefined&&image_ids[x][y]!='')
                                 {
                                     var description="<table style='height:100%;'><tbody><tr><td style='vertical-align:top;'><span class='text_color' style='font-size:11px;'>"+descriptions[x][y]+"</span></td></tr><tr><td style='vertical-align:bottom;'><span class='text_color' style='font-size:11px;'>"+timestamps[x][y]+"</span></td></tr></tbody></table>";

                                     if(album_ids[x]!='')
                                         var link="http://imagepxl.com/album/"+album_ids[x]+"="+image_ids[x][y];
                                     else
                                         var link="http://imagepxl.com/"+image_ids[x][y];

                                     //gets interior functions
                                     var left_function="<div id='like_unit_"+image_ids[x][y]+"' class='left_function function_interior username ' style='border-left:none;'></div>";
                                     var middle_function="<div id='points_unit_"+image_ids[x][y]+"' class='middle_function_disabled function_interior_disabled ' ></div>";
                                     var right_function="<div id='dislike_unit_"+image_ids[x][y]+"' class='right_function function_interior username' style='border-right:none;'></div>";
                                     
                                     //if image isn't nsfw
                                     if(nsfw[x][y]=='false')
                                        var image_functions="<table class='functions_table' ><tbody><tr><td class='left_functions_unit' style='display:inline-block;'>"+left_function+"</td><td class='right_functions_unit' >"+right_function+"</td><td class='middle_functions_unit' style='width:100%;text-align:center;'>"+middle_function+"</td></tr></tbody></table>";
                                    else
                                        var image_functions="";



                                    album_images="<td style='vertical-align:top;height:100%;display:inline-table;' ><table style='height:100%;'><tbody><tr><td style='vertical-align:top;'><div class='image_body'><a class='link' href='"+link+"' ><img class='album_thumbnail_image' style='position:relative;' src='"+thumbnails[x][y]+"' /></a><div class='interior_image_functions'>"+image_functions+"</div></div></td></tr><tr><td style='width:130px;vertical-align:top;height:100%;'>"+description+"</td></tr></tbody></table></td>"+album_images;
                                    top+=5;
                                    left+=5;
                                    outside_width+=5;
                                 }
                                 else
                                 {
                                     //adds 'no image' thumbnail
                                     if(y==0)
                                        album_images="<td style='vertical-align:top;' ><img class='no_image' style='width:150px;' src='http://i.imagepxl.com/site/no_image.jpg' /></td>"+album_images;
                                     else
                                         album_images+="";
                                 }
                             }

                             if(image_ids[x].length==1)
                                 var plural="image";
                             else
                                 var plural="images";




                             //gets album info
                             var name="<a class='link title_color' href='http://imagepxl.com/user/"+usernames[x]+"' ><span class='title_color' style='font-size:12px;' >"+usernames[x]+"</span></a>";

                             if(album_ids[x]!='')
                                 var album_name="<span class='text_color' style='font-size:12px;' > uploaded "+(image_ids[x].length)+" "+plural+" to </span><a class='link title_color' href='http://imagepxl.com/gallery/"+album_ids[x]+"' ><span class='username title_color' style='font-size:12px;' >"+album_names[x]+"</span></a><span class='text_color' style='font-size:12px;' > album. ("+num_album_images[x]+" total)</span>";
                             else
                                 var album_name="<span class='text_color' style='font-size:12px;' > uploaded "+(image_ids[x].length)+" "+plural+"</span>";

                             var album_thumbnails="<table><tbody><tr>"+album_images+"</tr></tbody></table>";
                             var inside_table="<tr><td style='vertical-align:top;'>"+album_thumbnails+"</td></tr>";
                             var top_sentence=name+album_name;
                             var table="<table style='border-spacing:0px;width:100%;'><tbody><tr>"+top_sentence+"</tr>"+inside_table+"</tbody></table>";



                            var profile_picture="<a class='link' href='http://imagepxl.com/user/"+usernames[x]+"'><img class='small_profile_picture' src='"+profile_pictures[x]+"'/></a>";
                            html+="<tr><td style='vertical-align:top;width:75px;border-bottom:1px solid gray;padding-top:10px;padding-left:10px;'>"+profile_picture+"</td><td style='border-bottom:1px solid gray;padding-top:10px;padding-left:10px;padding-bottom:10px;'>"+table+"</td></tr>";
                        }
                    }
                    $('#home_page_'+page).html("<table style='width:100%;border-spacing:0px;'><tbody id='home_content_tbody_"+page+"'>"+html+"</tbody></table>");


                    for(var x = 0; x < image_ids.length; x++)
                     {
                         if(image_ids[x]!=undefined)
                         {
                            for(var y = 0; y < image_ids[x].length; y++)
                            {
                                if(nsfw[x][y]=='false')
                                    set_interior_functions(image_ids[x][y], num_likes[x][y], num_dislikes[x][y], has_liked[x][y], has_disliked[x][y]);
                            }
                         }
                     }
                }
                else
                    $('#home_page_'+page).html("<p class='text_color'>You don't seem to be following anyone. Find people <a class='link title_color' href='http://imagepxl.com/search'>here!</a></p>");
               
               $('#load_gif').hide();
               change_colors();
            }, "json");
         }

         
         $(document).ready(function(){
            display_images(1, -1);
            //display_following();
            change_colors();
            
            <?php include('required_jquery.php'); ?>
         });
      </script>
      <script type="text/javascript">
        <?php include('required_google_analytics.js'); ?>
      </script>
   </head>
   <body>
        <?php include('facebook_html.php'); ?>  
      <?php include('header.php'); ?>
      <div class="content">
         <table style="width:100%;padding:15px;">
             <tr>
                 <td colspan="2" style="padding-bottom:15px;border-bottom:1px solid gray;">
                     <table style="width:100%">
                         <tbody>
                             <tr>
                                 <td style="width:50px;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img class="small_profile_picture" style="width:50px;height:50px;" src="<?php echo get_profile_picture($_SESSION['id']); ?>"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                 </td>
                                 <td style="vertical-align:top;" >
                                     <p class="title_color"><?php echo $username; ?><p>
                                     <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p class="text_color">Followers: </p>
                                                </td>
                                                <td style="border-right:1px solid gray;padding-right:10px;">
                                                    <p class="text_color"><?php echo $num_followers; ?></p>
                                                </td>
                                                <td style="padding-left:10px;">
                                                    <p class="text_color">Image views: </p>
                                                </td>
                                                <td style="padding-right:10px;">
                                                    <p class="text_color"><?php echo $image_views; ?></p>
                                                </td>
<!--                                                <td style="padding-left:10px;">
                                                    <p class="text_color">Profile views: </p>
                                                </td>
                                                <td>
                                                    <p class="text_color"><?php echo $profile_views; ?></p>
                                                </td>-->
                                            </tr>
                                        </tbody>
                                    </table>
                                 </td>
                                 <td style="text-align:right;">
                                     <a class="link" href="http://imagepxl.com/upload"><input class="button red_button" type="button" value="Upload" style="font-size:14px;"/></a>
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                     
                 </td>
             </tr>
            <tr>
               <td id="home_content_unit">
                   <img class="load_gif" id="load_gif" src="http://i.imagepxl.com/site/load.gif"/>
                   <div id="home_page_1">
                       
                   </div>
                   <div id="home_page_2"></div>
                   <div id="home_page_3"></div>
                   <div id="home_page_4"></div>
                   <div id="home_page_5"></div>
                   <div id="home_page_6"></div>
                   <div id="home_page_7"></div>
                   <div id="home_page_8"></div>
                   <div id="home_page_9"></div>
                   <div id="home_page_10"></div>
                   <div id="home_page_11"></div>
                   <div id="home_page_12"></div>
                   <div id="home_page_13"></div>
                   <div id="home_page_14"></div>
                   <div id="home_page_15"></div>
               </td>
            </tr>
         </table>
      </div>
      <?php include('footer.php'); ?>
   </body>
    <script type="text/javascript">
            function facebook_post()
            {
                FB.ui(
                  {
                    method: 'feed',
                    name: 'ImagePXL',
                    link: 'http://imagePXL.com',
                    picture: 'http://imagepxl.com/favicon.ico',
                    caption: 'Follow me on ImagePXL!',
                    description: 'ImagePXL is a an awesome image sharing site. Follow others and gain a mass following yourself!'
                  },
                  function(response) {
                    
                  }
                );
            }

            function facebook_login()
            {
                FB.login(function(response) {
                    if (response.authResponse)
                    {
                        $.post('facebook_methods.php',
                        {
                            num:3
                        }, function(output)
                        {});

                        window.location.replace(window.location);

                    } else {
                    // cancelled
                    }
                });
            }
        </script>
</html>