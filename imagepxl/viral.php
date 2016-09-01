<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');


?>

<!DOCTYPE html>
<html>
   <head>
       <meta name="description" content=" View the 100 most viral images on imagePXL" />
      <title>Most Viral Images</title>
      <?php include('code_header.php'); ?>
      <script type="text/javascript">
        function change_colors()
        {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
        }

        function menu_toggle(content_type, type)
        {
            //gives functionality to bottom menu
            $('#all_time_menu_unit').attr('onClick', "display_top_100(1, 'all_time', '"+content_type+"');");
            $('#new_menu_unit').attr('onClick', "display_top_100(1, 'new', '"+content_type+"');");
            
            $('#images_menu').css({'text-decoration': 'none', 'font-weight': 'normal'});
            $('#users_menu').css({'text-decoration': 'none', 'font-weight': 'normal'});
            
                
            if(content_type=='images')
            {
                //shows rising if images
                $('#rising_menu_unit').show().attr('onClick', "display_top_100(1, 'rising', '"+content_type+"');");;
                
                //resets style bottom menu
                $('#rising_top_100').css({'text-decoration': 'none', 'font-weight': 'normal'});
                $('#all_time_top_100').css({'text-decoration': 'none', 'font-weight': 'normal'});
                $('#new_top_100').css({'text-decoration': 'none', 'font-weight': 'normal'});
                
                $('#images_menu').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                

                if(type=='rising')
                    $('#rising_top_100').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                else if(type=='all_time')
                    $('#all_time_top_100').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                else if(type=='new')
                    $('#new_top_100').css({'text-decoration': 'underline', 'font-weight': 'bold'});
            }
            else if(content_type=='users')
            {
                //hides rising if users
                $('#rising_menu_unit').hide().attr('onClick', '');
                
                //resets style bottom menu
                $('#all_time_top_100').css({'text-decoration': 'none', 'font-weight': 'normal'});
                $('#new_top_100').css({'text-decoration': 'none', 'font-weight': 'normal'});
                
                $('#users_menu').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                

                if(type=='all_time')
                    $('#all_time_top_100').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                else if(type=='new')
                    $('#new_top_100').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                
                
            }
        }

        function display_top_100(page, type, content_type)
        {
            menu_toggle(content_type, type);
            $('#see_more_'+(page-1)).hide();
            $.post('top_query.php',
            {
                type:type,
                page: page,
                content_type: content_type,
                timezone: get_timezone()
            }, function(output)
            {
                console.log(type);
                console.log(content_type);
                if(content_type=='images')
                {
                    
                    var image_ids=output.image_ids;
                    var descriptions=output.descriptions;
                    var exts=output.exts;
                    var user_ids=output.user_ids;
                    var usernames=output.usernames;
                    var timestamps=output.timestamps;
                    var has_liked=output.has_liked;
                    var has_disliked=output.has_disliked;
                    var num_likes=output.num_likes;
                    var num_dislikes=output.num_dislikes;
                    var has_favorited=output.has_favorited;
                    var num_views=output.views;
                    var num_followers=output.num_followers;
                    var is_following=output.is_following;
                    var num_favorites=output.num_favorites;
                    var thumbnails=output.thumbnails;
                    
                    var album_ids=output.album_ids;
                    var album_names=output.album_names;
                    var num_album_images=output.num_album_images;
                    var album_image_thumbnails=output.album_image_thumbnails;
                    var album_image_ids=output.album_image_ids;
                    
                    console.log(image_ids.length);

                    var html="";
                    for(var x = 0; x < image_ids.length; x++)
                    {
                        var description_html="<p class='text_color' style='font-size:12px;'>"+descriptions[x]+"</p>";
                        
                        if(usernames[x]!='')
                            var name_html="<a class='link title_color' href='http://imagepxl.com/user/"+usernames[x]+"'><span class='title_color username'>"+usernames[x]+"</span></a><span class='text_color' style='font-size:12px;'> ("+num_followers[x]+" followers)</span>";
                        else
                            var name_html="";

                        var favorite_html="";
                        var view_html="<span class='text_color' style='font-size:12px;'>"+num_views[x]+" views</span>";
                        var image_info="<table ><tbody><tr><td>"+name_html+"</td></tr><tr><td>"+description_html+"</td></tr><tr><td>"+view_html+"</td></tr><tr><td>"+favorite_html+"</td></tr></tbody></table>";
                        
                        var timestamp="<p class='text_color' style='font-size:12px;'>"+timestamps[x]+"</p>";


                        var left_function="<div id='like_unit_"+image_ids[x]+"' class='left_function_disabled'></div>";
                        var middle_function="<div id='points_unit_"+image_ids[x]+"' class='middle_function_disabled' ></div>";
                        var right_function="<div id='dislike_unit_"+image_ids[x]+"' class='middle_function_disabled'></div>";
                        var favorite_function="<div id='favorite_unit_"+image_ids[x]+"' class='right_function_disabled'></div>";
                        
                        var image_functions="<table class='functions_table' style='width:100%;'><tbody><tr><td class='left_functions_unit' style='display:inline-block' >"+left_function+"</td><td class='middle_functions_unit' style='display:inline-block' >"+right_function+"</td><td class='middle_functions_unit' style='display:inline-block' >"+middle_function+"</td><td class='right_functions_unit' style='display:inline-block' >"+favorite_function+"</td></tr></tbody></table>";
                        var image_info="<table style='height:100%;'><tbody><tr><td style='vertical-align:top;'>"+image_info+"</td></tr><tr><td style='vertical-align:bottom'>"+timestamp+image_functions+"</td></tr></tbody></table>";

                        if(album_ids[x]!='')
                        {
                            var album_preview1="<a class='link' href='http://imagepxl.com/album/"+album_ids[x]+"="+album_image_ids[x][0]+"' ><img class='image_preview' src='"+album_image_thumbnails[x][0]+"' style='width:75px;' /></a>";
                            var album_preview2="<a class='link' href='http://imagepxl.com/album/"+album_ids[x]+"="+album_image_ids[x][1]+"' ><img class='image_preview' src='"+album_image_thumbnails[x][1]+"' style='width:75px;' /></a>";
                            var album_preview3="<a class='link' href='http://imagepxl.com/album/"+album_ids[x]+"="+album_image_ids[x][2]+"' ><img class='image_preview' src='"+album_image_thumbnails[x][2]+"' style='width:75px;' /></a>";
                            var album_preview4="<a class='link' href='http://imagepxl.com/album/"+album_ids[x]+"="+album_image_ids[x][3]+"' ><img class='image_preview' src='"+album_image_thumbnails[x][3]+"' style='width:75px;' /></a>";
                            var album_preview5="<a class='link' href='http://imagepxl.com/album/"+album_ids[x]+"="+album_image_ids[x][4]+"' ><img class='image_preview' src='"+album_image_thumbnails[x][4]+"' style='width:75px;' /></a>";
                            var album_info="<table><tbody><tr> <td>"+album_preview1+"</td><td>"+album_preview2+"</td><td>"+album_preview3+"</td><td>"+album_preview4+"</td><td>"+album_preview5+"</td> </tr></tbody></table>";
                            
                            var album_name="<a class='link title_color' href='http://imagepxl.com/gallery/"+album_ids[x]+"' ><span class='title_color'>"+album_names[x]+"</span></a><span class='text_color'> ("+num_album_images[x]+" images)</span>";
                            var album_table="<table style='float:right;padding-bottom:10px;'><tbody><tr><td>"+album_name+"</td></tr><tr><td>"+album_info+"</td></tr></tbody></table>";
                        }
                        else
                            var album_table="";

                        html+="<tr><td style='border-bottom:1px solid gray;' ><table style='width:100%;' ><tbody><tr> <td style='width:150px;padding:10px;padding-right:5px;' ><a class='link' href='http://imagepxl.com/"+image_ids[x]+"' ><img class='image_preview' src='"+thumbnails[x]+"' /></a></td><td style='vertical-align:top;height:100%;display:inline-table;padding:10px;padding-left:5px;width:100%;' >"+image_info+"</td><td style='vertical-align:bottom;'> "+album_table+" </td></tr></tbody></table></td></tr>";
                    }

                    if(page<4)
                        html+="<tr><td colspan='3'><input type='button' class='button blue_button' value='See more' id='see_more_"+page+"'/></td></tr>";

                    $('#top_100_tbody_'+page).html(html);

                    $('#see_more_'+page).attr('onClick', "display_top_100("+page+", '"+type+"', '"+content_type+"');");

                    for(var x = 0; x < image_ids.length; x++)
                        set_functions(image_ids[x], num_likes[x], num_dislikes[x], has_liked[x], has_disliked[x], has_favorited[x]);
                }
                else if(content_type=='users')
                {
                    var user_ids=output.user_ids;
                    var usernames=output.usernames;
                    var num_followers=output.num_followers;
                    var descriptions=output.descriptions;
                    var real_names=output.real_names;
                    var image_views=output.image_views;
                    var profile_views=output.profile_pictures;
                    var num_images=output.num_images;
                    var date_joined=output.date_joined;
                    var profile_pictures=output.profile_pictures;
                    var is_following=output.is_following;
                    
                
                    var html="";
                    for(var x = 0; x < user_ids.length; x++)
                    {
                        var description_html="<p class='text_color'>"+descriptions[x]+"</p>";
                        var name_html="<a class='link title_color' href='http://imagepxl.com/user/"+usernames[x]+"'><span class='title_color username'>"+usernames[x]+"</span></a>";

                        if(<?php if(isset($_SESSION['id'])) echo "true"; else echo "false"; ?>==true&&is_following[x]==false&&<?php if(isset($_SESSION['id'])) echo $_SESSION['id']; else echo "0" ?>!=user_ids[x])
                            var follow_button="<input class='button green_button' type='button' value='Follow' onClick='follow("+user_ids[x]+");'/>";
                        else 
                            var follow_button="<p class='text_color' style='font-size:12px;'>"+num_followers[x]+" followers</p>";

                        var user_info="<table ><tbody><tr><td>"+name_html+"</td></tr><tr><td>"+description_html+"</td></tr><tr><td>"+follow_button+"</td></tr></tbody></table>";
                        var user_info="<table style='height:100%;'><tbody><tr><td style='vertical-align:top;'>"+user_info+"</td></tr></tbody></table>";
                        html+="<tr><td style='width:20px;vertical-align:top;'><span class='text_color'>"+((page-1)*25+x+1)+".</span></td><td style='width:150px;'><a class='link' href='http://imagepxl.com/user/"+usernames[x]+"' ><img class='small_profile_picture' src='"+profile_pictures[x]+"' /></a></td><td style='vertical-align:top;height:100%;display:inline-table;'>"+user_info+"</td></tr><tr><td colspan='3'><hr style='margin:0px;padding:0px;'/></td></tr>";
                    }
                    
                    if(page<4&&user_ids.length==25)
                        html+="<tr><td colspan='3'><input type='button' class='button blue_button' value='See more' id='see_more_"+page+"'/></td></tr>";

                    $('#top_100_tbody_'+page).html(html);

                    $('#see_more_'+page).attr('onClick', "display_top_100("+page+", '"+type+"', '"+content_type+"');");
                    
                }
                
                $('#top_load_gif').hide();
                change_colors();
            }, "json");
        }

        $(document).ready(function(){
             display_top_100(1, 'rising', 'images');
             change_colors();
             <?php include('required_jquery.php'); ?>
        });
      </script>
   </head>
   <body>
      <?php 
      if(isset($_SESSION['id']))
        include('header.php');
      else
        include('index_header.php');
      ?>
      <div class="content">
         <table style="width:100%;padding:15px;">
            <tbody>
               <tr>
                  <td colspan="2">
                     <p class="title_color" style="font-size:20px;">Viral</p>
                  </td>
               </tr>
               <tr>
                    <td style="vertical-align:top;">
                        <table id="menu_table" style="border-spacing:0px;">
                            <tbody>
                                <tr id="other_menu_row" >
                                    <td onClick="display_top_100(1, 'rising', 'images');">
                                        <div class="left_function">
                                            <p class="function_text text_color" id="images_menu" style="padding:5px;">Images</p>
                                        </div>
                                    </td>
                                    <td onClick="display_top_100(1, 'all_time', 'users');">
                                        <div class="right_function">
                                            <p class="function_text text_color" id="users_menu" style="padding:5px;">Users</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="all_time_menu_unit" >
                                        <div class="left_function">
                                            <p class="function_text text_color" id="all_time_top_100" style="padding:5px;">All time</p>
                                        </div>
                                    </td>
                                    <td id="rising_menu_unit" >
                                        <div class="right_function">
                                            <p class="function_text text_color" id="rising_top_100" style="padding:5px;">Rising</p>
                                        </div>
                                    </td>
                                    <td id="new_menu_unit" >
                                        <div class="right_function">
                                            <p class="function_text text_color" id="new_top_100" style="padding:5px;">New</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
               </tr>
                <tr>
                  <td style="text-align:left;">
                      <img class="load_gif" src="http://i.imagepxl.com/site/load.gif" id="top_load_gif"/>
                     <table style="width:100%;float:left;">
                        <tbody id="top_100_tbody_1">
                           
                        </tbody>
                     </table>
                      <table style="width:100%;float:left;">
                        <tbody id="top_100_tbody_2">
                           
                        </tbody>
                     </table>
                      <table style="width:100%;float:left;">
                        <tbody id="top_100_tbody_3">
                           
                        </tbody>
                     </table>
                      <table style="width:100%;float:left;">
                        <tbody id="top_100_tbody_4">
                           
                        </tbody>
                     </table>
                      <table style="width:100%;float:left;">
                        <tbody id="top_100_tbody_5">
                           
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>