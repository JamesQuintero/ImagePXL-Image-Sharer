<?php
include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$username=clean_string($_GET['username']);
$data=get_user_data(0, $username);


$ID=$data['user_id'];
$username=$data['username'];
$num_followers=$data['num_followers'];
$description=$data['description'];
$name=$data['name'];
$following=$data['following'];
$favorites=$data['favorites'];
$image_views=number_format($data['image_views']);
$profile_views=$data['profile_views'];
$num_images=$data['num_images'];
$last_sign_in=$data['last_sign_in'];
$date_joined=$data['date_joined'];

// add_profile_view($ID);
?>

<!DOCTYPE html>
<html>
   <head>
       <meta name="description" content="<?php echo $username; ?>" />
      <title><?php echo $username; ?></title>
      <?php include('code_header.php'); ?>
      <script type="text/javascript">
         function change_colors()
         {
            <?php
               $colors=get_user_colors($ID);
            ?>
            $('html').css('background-color', "<?php echo $colors[0]; ?>");
            $('#profile_body, #profile_customization_div').css('background-color', "<?php echo $colors[1] ?>");
            $('.title_color').css('color', '<?php echo $colors[2]; ?>');
            $('.text_color').css('color', '<?php echo $colors[3]; ?>');
         }
         function login()
        {
            $.post('../login.php',
            {
                email: $('#login_email').val(),
                password: $('#login_password').val()
            },
            function (output)
            {
                if(output=='')
                    window.location.replace(window.location);
                else
                   display_error(output, 'bad_errors');
            });
        }
         function display_top_row()
         {
            var profile_image='<?php echo get_profile_picture($ID); ?>';
            $('#profile_pic_unit').html("<img class='profile_picture' src='"+profile_image+"'/>");
         }
         function profile_menu(content_type, sort)
         {
             //sets to default
            $('#sort0, #sort1, #sort2, #sort3, #sort4').css({'text-decoration': 'none', 'font-weight': 'normal'});
            
            if(content_type=='images')
            {
                $('#images_menu').show();
                $('#sort0').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                
                if(sort==0)
                {
                    $('#sort2').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                    $('#content_title').html("Images (Recent)");
                }
                 else
                 {
                    $('#sort3').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                    $('#content_title').html("Images (Popular)");
                 }
                 
                 display_images(1, content_type, sort);
            }
            else if(content_type=='albums')
            {
                $('#images_menu').hide();
                $('#sort1').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                $('#content_title').html("Albums");
                
                display_albums();
            }
            else if(content_type=='favorites')
            {
                $('#images_menu').hide();
                $('#sort4').css({'text-decoration': 'underline', 'font-weight': 'bold'});
                $('#content_title').html("Favorites");
                
                display_favorites(1);
            }
         }
         function display_images(page, content_type, sort)
         {
             $('#profile_load_gif').show();
            $.post('../profile_query.php',
            {
               num:1,
               user_id: <?php echo $ID; ?>,
               page: page,
               display:sort,
               content_type: content_type
            }, function(output)
            {
               var images=output.images;
               var exts=output.exts;
               var num_likes=output.num_likes;
               var num_dislikes=output.num_dislikes;
               var has_liked=output.has_liked;
               var has_disliked=output.has_disliked
               var num_comments=output.num_comments;
               var timestamps=output.timestamps;
               var descriptions=output.descriptions;
               var displaying_all=output.displaying_all;
               var total_images=output.total_images;
               var nsfw=output.nsfw;
               var thumbnails=output.thumbnails;
               
               
               if(page==1)
               {
                   $('#images').html("");
                   var size=total_images/36+1;
                   for(var x = 1; x <= size; x++)
                   {
                       $('#images').html($('#images').html()+"<div id='images_page_"+x+"' style='width:100%;'></div>");
                   }
               }
               
               
               if(images.length>=1)
               {
                    if(images.length<6)
                       var num_rows=1;
                    else
                       var num_rows=images.length/6;

                    //puts in HTML
                    var index=0;
                    var row_html="";
                    for(var x = 0; x < num_rows; x++)
                    {
                       row_html+="<tr id='image_row_"+page+"_"+x+"' class='image_row'>";
                       for(var y = 0; y < 6; y++)
                       {
                          row_html+="<td id='image_unit_"+page+"_"+index+"' class='image_unit'></td>";
                          index++;
                       }
                       row_html+="</tr>";
                    }
                    $('#images_page_'+page).html("<table style='margin:0 auto;'><tbody>"+row_html+"</tbody></table>");
                    
                    //puts in images
                    var index=0;
                    for(var x = 0; x < images.length; x++)
                    {
                       var image="<a href='http://imagepxl.com/"+images[index]+"'><img id='image_"+page+"_"+index+"' class='image_preview' src='"+thumbnails[x]+"' style='width:155px;' /></a>";
                       if(nsfw[x]=='false')
                            var image_functions="<table class='functions_table' ><tbody><tr><td class='left_functions_unit' style='display:inline-block;float:left;text-align:left;'><div id='like_unit_"+images[index]+"' class='left_function function_interior username ' style='border-left:none;'></div></td><td class='middle_functions_unit' ><div id='dislike_unit_"+images[index]+"' class='middle_function function_interior username' ></div></td><td class='middle_functions_unit' style='width:100%;text-align:center;'><div id='points_unit_"+images[index]+"' class='right_function_disabled function_interior_disabled ' style='border-right:none;'></div></td></tr></tbody></table>";
                        else
                            var image_functions="";
                       var image_body="<div class='image_body'>"+image+"<div class='interior_image_functions'>"+image_functions+"</div></div>";
                       $('#image_unit_'+page+'_'+x).html(image_body);
                       
                       if(descriptions[index]=='')
                           descriptions[index]="<i>No Caption</i>";
                       $('#image_'+page+'_'+index).attr({'onmouseover': "display_title(this, '"+descriptions[index]+"');", 'onmouseout': "hide_title(this);"});
                       
                       set_interior_functions(images[index], num_likes[index], num_dislikes[index], has_liked[index], has_disliked[index]);
                       
                       index++;
                    }
                    
                    if(displaying_all==false)
                        $('#see_more_button').attr("onClick", "display_images("+(page+1)+", '"+content_type+"', '"+sort+"');").show();
                    else
                        $('#see_more_button').hide();
               }
               else
               {
                  $('#profile_images_unit').html("<p class='text_color'><?php if($_SESSION['id']==$ID) echo "You have "; else echo $username." has "; ?>not uploaded any images yet</p>");
               }
               
               $('#profile_load_gif').hide();
               change_colors();
            }, "json");
         }
         function display_albums()
         {
             $('#see_more_button').hide();
             $('#profile_load_gif').show();
             $.post('../profile_query.php',
             {
                 num:2,
                 user_id: <?php echo $ID; ?>
             }, function(output)
             {
                 var album_ids=output.album_ids;
                 var image_ids=output.image_ids;
                 var image_exts=output.image_exts;
                 var album_names=output.album_names;
                 var num_images=output.num_images;
                 
                 
                 
                 
                  //puts in HTML
                var row_html="";
                for(var x = 0; x < album_ids.length; x++)
                {
                   row_html+="<tr id='album_row_"+x+"' class='image_row'>";
                   row_html+="<td id='album_unit_"+x+"' ></td>";
                   row_html+="</tr>";
                }
                $('#images').html("<table><tbody>"+row_html+"</tbody></table>");
                 
                 
                 for(var x = 0; x < album_ids.length; x++)
                 {
                     var album_info="<a class='link title_color' href='http://imagepxl.com/gallery/"+album_ids[x]+"' ><span class='title_color'>"+album_names[x]+"</span></a><span class='text_color' > ("+num_images[x]+" images)</span>";
                     
                     
                     var album_thumbnails="";
                     for(var y = 0; y < 7; y++)
                     {
                         if(image_ids[x][y]!=undefined&&image_ids[x][y]!='')
                            var image="<img class='album_thumbnail_image' src='http://i.imagepxl.com/<?php echo $username; ?>/thumbs/"+image_ids[x][y]+"."+image_exts[x][y]+"'/>";
                         else
                            var image="";
                        var image_link="<a class='link' id='album_thumbnail_link_"+x+"_"+y+"' href='http://imagepxl.com/album/"+album_ids[x]+"="+image_ids[x][y]+"' >"+image+"</a>";
                         
                         album_thumbnails+="<td id='album_thumbnail_"+x+"_"+y+"'>"+image_link+"</td>";
                     }
                     
                     var table="<table><tbody><tr>"+album_thumbnails+"</tr></tbody></table>";
                     $('#album_unit_'+x).html("<table><tbody><tr><td>"+album_info+"</td></tr><tr><td>"+table+"</td></tr></tbody></table>");
                 }
                 $('#profile_load_gif').hide();
                 change_colors();
             }, "json");
         }
         function display_favorites(page)
         {
             $('#see_more_button').hide();
             $('#profile_load_gif').show();
             $.post('../profile_query.php',
            {
               num:3,
               user_id: <?php echo $ID; ?>,
               page: page
            }, function(output)
            {
               var images=output.images;
               var exts=output.exts;
               var num_likes=output.num_likes;
               var num_dislikes=output.num_dislikes;
               var has_liked=output.has_liked;
               var has_disliked=output.has_disliked
               var num_comments=output.num_comments;
               var timestamps=output.timestamps;
               var descriptions=output.descriptions;
               var displaying_all=output.displaying_all;
               var total_images=output.total_images;
               
               
               if(page==1)
               {
                   var size=total_images/36+1;
                   $('#images').html("");
                   for(var x = 1; x <= size; x++)
                   {
                       $('#images').html($('#images').html()+"<div id='images_page_"+x+"' style='width:100%;'></div>");
                   }
               }
               
               
               if(images.length>=1)
               {
                    if(images.length<6)
                       var num_rows=1;
                    else
                       var num_rows=images.length/6;

                    //puts in HTML
                    var index=0;
                    var row_html="";
                    for(var x = 0; x < num_rows; x++)
                    {
                       row_html+="<tr id='image_row_"+page+"_"+x+"' class='image_row'>";
                       for(var y = 0; y < 6; y++)
                       {
                          row_html+="<td id='image_unit_"+page+"_"+index+"' class='image_unit'></td>";
                          index++;
                       }
                       row_html+="</tr>";
                    }
                    $('#images_page_'+page).html("<table style='margin:0 auto;'><tbody>"+row_html+"</tbody></table>");
                    
                    //puts in images
                    var index=0;
                    for(var x = 0; x < images.length; x++)
                    {
                    
                       var image="<a href='http://imagepxl.com/"+images[index]+"'><img id='image_"+page+"_"+index+"' class='image_preview' src='http://i.imagepxl.com/<?php echo $username; ?>/thumbs/"+images[index]+"."+exts[index]+"'/></a>";
                       var image_functions="<table class='functions_table' ><tbody><tr><td class='left_functions_unit' style='display:inline-block;float:left;text-align:left;'><div id='like_unit_"+images[index]+"' class='left_function function_interior username ' style='border-left:none;'></div></td><td class='middle_functions_unit' ><div id='dislike_unit_"+images[index]+"' class='middle_function function_interior username' ></div></td><td class='middle_functions_unit' style='width:100%;text-align:center;'><div id='points_unit_"+images[index]+"' class='right_function_disabled function_interior_disabled ' style='border-right:none;'></div></td></tr></tbody></table>";
                       var image_body="<div class='image_body'>"+image+"<div class='interior_image_functions'>"+image_functions+"</div></div>"
                       $('#image_unit_'+page+'_'+x).html(image_body);
                       
                       if(descriptions[index]=='')
                           descriptions[index]="<i>No Caption</i>";
                       $('#image_'+page+'_'+index).attr({'onmouseover': "display_title(this, '"+descriptions[index]+"');", 'onmouseout': "hide_title(this);"});
                       
                       set_interior_functions(images[index],num_likes[index], num_dislikes[index], has_liked[index], has_disliked[index]);
                       
                       index++;
                    }
                    
                    if(displaying_all==false)
                        $('#see_more_button').attr("onClick", "display_favorites("+(page+1)+");");
                    else
                        $('#see_more_button').hide();
               }
               else
               {
                  $('#profile_images_unit').html("<p class='text_color'><?php if($_SESSION['id']==$ID) echo "You have "; else echo $username." has "; ?>not favorited anything yet</p>");
               }
               
               $('#profile_load_gif').hide();
               change_colors();
            }, "json");
         }
         function delete_album(album_id)
         {
             $.post('../delete_album.php',
            {
                album_id: album_id
            }, function(output)
            {
                if(output=='success')
                    display_error("Album deleted", 'good_errors');
                else
                    display_error(output, 'bad_errors');
            });
         }
         function follow(user_id)
        {
            $.post('../follow',
            {
                num:1,
                user_id:user_id
            }, function(output)
            {
                if(output!="success")
                    display_error(output, 'bad_errors');
                else
                    window.location.replace(window.location);
            });
        }
        function unfollow(user_id)
        {
            $.post('../follow',
            {
                num:2,
                user_id:user_id
            }, function(output)
            {
                if(output!='success')
                    display_error(output, 'bad_errors');
                else
                    window.location.replace(window.location);
            });
        }

        function initialize_banner()
        {
            $( "#banner" ).draggable({
                axis: "y",
                stop:function(event, ui){
                    var position=$('#banner').position();
                    var top=position.top;
                    var height=$('#banner').height();

                    //can't have empty space at top
                    if(top>0)
                    {
                        $('#banner').css('top', '0px');
                        top=0;
                    }

                    //can't have empty space at bottom
                    if(top+height<200)
                    {
                        top=(height-200)*-1;
                        $('#banner').css('top', top+"px");
                    }

                    change_banner_position(top);
                }
            });
        }
        function change_banner_position(top)
        {
            $.post('../change_banner_position.php',
            {
                top: top
            }, function(output)
            {});
        }
        function change_theme(theme)
        {
            $.post('../change_theme.php',
            {
                theme: theme
            }, function(output)
            {
                var background_color=output.background_color;
                var foreground_color=output.foreground_color;
                var main_text_color=output.main_text_color;
                var text_color=output.text_color;

                $('html').css('background-color', "rgb("+background_color+")");
                $('#profile_body, #profile_customization_div').css('background-color', "rgb("+foreground_color+")");
                $('.title_color').css('color', "rgb("+main_text_color+")");
                $('.text_color').css('color', "rgb("+text_color+")");


            }, "json");
        }
         
         $(document).ready(function(){
            if($('#upload_button').length)
                $('#upload_button').attr('onClick', "window.open('http://imagepxl.com/upload', '_blank');");
         
            <?php if(isset($_SESSION['id'])&&$ID==$_SESSION['id']) echo "initialize_banner();"; ?>
            display_top_row();
            profile_menu('images', 0);
            change_colors();
            
            <?php include('required_jquery.php'); ?>
         });
      </script>
      <script type="text/javascript">
        <?php include('required_google_analytics.js'); ?>
      </script>
   </head>
   <body>
      <?php 
        if(isset($_SESSION['id']))
            include('header.php');
        else
            include('index_header.php');
        
        $banner_data=get_banner_data($ID);
        $has_banner=$banner_data['has_banner'];
        $banner_top=$banner_data['banner_top'];
        ?>
       <?php if($ID==$_SESSION['id']) include('customize_html.php'); ?>
      <div class="content">
         <div id="profile_body">
             
             <?php if((isset($_SESSION['id'])&&$ID==$_SESSION['id'])||$has_banner) echo "<div id='profile_banner_div' style='border-bottom:1px solid gray;' ><div id='banner_container_because_F_javascript'>"; ?>
                    <?php if(isset($_SESSION['id'])&&$ID==$_SESSION['id']) include('banner_html.php'); ?>
                    <?php if($has_banner) echo "<img id='banner' src='http://i.imagepxl.com/$username/banner.jpg' style='top:".$banner_top."px' />"; ?>
             <?php if((isset($_SESSION['id'])&&$ID==$_SESSION['id'])||$has_banner) echo "</div></div>"; ?>
                    
            <table id="profile_table" style="width:100%;padding:15px;border-spacing:0px;">
               <tbody>
                  <tr id="top_row">
                     <td id="profile_pic_unit" style="vertical-align:top;"></td>
                     <td style="vertical-align:top;height:100%;display:inline-table;width:100%;">
                         <table style="height:100%;width:100%;">
                             <tbody>
                                 <tr>
                                     <td style="vertical-align:top;">
                                         <table>
                                             <tbody>
                                                 <tr>
                                                    <td style="border-spacing:0px;vertical-align:top;" colspan="2">
                                                       <p class="name title_color" style="font-weight:bold;"><?php echo $username; ?></p>
                                                       <p class="description text_color" ><?php echo $description; ?></p>
                                                    </td>
                                                 </tr>
                                                  <tr>
                                                       <td >
                                                           <?php if($_SESSION['id']!=$ID&&!user_following($ID)) 
                                                                  echo "<input class='button red_button' style='font-size:14px;' value='Follow' type='button' onclick='follow(".$ID.");' >"; 
                                                              else if($_SESSION['id']!=$ID) 
                                                                  echo "<input class='button gray_button' style='font-size:14px;' value='Unfollow' type='button' onclick='unfollow(".$ID.");' >";
                                                              ?>
                                                       </td>
                                                   </tr>
                                             </tbody>
                                         </table>
                                     </td>
                                     <td style="vertical-align:top;">
                                         <table style="height:100%;display:inline-table;width:100%;">
                                            <tbody>
                                               <tr>
                                                  <td style="border-spacing:0px;vertical-align:bottom;">
                                                      <table style="border-spacing:0px;width:100%;">
                                                          <tbody>
                                                              <tr>
                                                                  <td style="vertical-align:bottom;">
                                                                      <table style="float:right;">
                                                                          <tbody>
                                                                              <tr>
                                                                                 <td>
                                                                                     <p class="text_color" >Followers: </p>
                                                                                 </td>
                                                                                 <td>
                                                                                     <span class="profile_stat_numbers text_color"><?php echo $num_followers ?></span>
                                                                                 </td>
                                                                             </tr>
                                                                             <tr>
                                                                                 <td>
                                                                                     <p class="text_color" >Image views: </p>
                                                                                 </td>
                                                                                 <td>
                                                                                     <span class="profile_stat_numbers text_color"><?php echo $image_views ?></span>
                                                                                 </td>
                                                                             </tr>
                                                                              <tr>
                                                                                 <td>
                                                                                     <p class="text_color" >Profile views: </p>
                                                                                 </td>
                                                                                 <td>
                                                                                     <span class="profile_stat_numbers text_color"><?php echo $profile_views; ?></span>
                                                                                 </td>
                                                                             </tr>
                                                                             <tr>
                                                                                 <td>
                                                                                     <p class="text_color" >Images: </p>
                                                                                 </td>
                                                                                 <td>
                                                                                     <span class="profile_stat_numbers text_color"><?php echo $num_images; ?></span>
                                                                                 </td>
                                                                             </tr>
                                                                              <tr>
                                                                                  <td>
                                                                                      <p class="text_color">Last sign in: </p>
                                                                                  </td>
                                                                                  <td>
                                                                                      <span class="profile_stat_numbers text_color"><?php echo get_time_since((int)($last_sign_in),0); ?></span>
                                                                                  </td>
                                                                              </tr>
                                                                              <tr>
                                                                                  <td>
                                                                                      <p class="text_color">Date joined: </p>
                                                                                  </td>
                                                                                  <td>
                                                                                      <span class="profile_stat_numbers text_color"><?php echo get_time_since((int)($date_joined), 0); ?></span>
                                                                                  </td>
                                                                              </tr>
                                                                          </tbody>
                                                                      </table>
                                                                  </td>
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                     </td>
                                 </tr>
                             </tbody>
                         </table>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="2">
                        <table style="border-spacing:0px;">
                           <tbody>
                               <tr>
                                   <td>
                                       <table class="left_function profile_menu_div" style="border-bottom-left-radius:0px;"onClick="profile_menu('images', 0);">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table style="margin:0 auto;" >
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <img class="icon" src="http://i.imagepxl.com/site/icons/image_icon.png"/>
                                                                    </td>
                                                                    <td>
                                                                        <span class="function_text profile_menu_text" id="sort0" style="cursor:pointer;" >Images</span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                   </td>
                                   <td>
                                       <table class="middle_function profile_menu_div"  onClick="profile_menu('albums', 0);">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table style="margin:0 auto;" >
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <img class="icon" src="http://i.imagepxl.com/site/icons/album_icon.png" />
                                                                    </td>
                                                                    <td>
                                                                        <span class="function_text profile_menu_text" id="sort1" style="cursor:pointer;" >Albums</span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                   </td>
                                   <td>
                                       <table class="right_function profile_menu_div"  onClick="profile_menu('favorites', 0);">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table style="margin:0 auto;" >
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <img class="icon" src="http://i.imagepxl.com/site/icons/favorite_icon.png"/>
                                                                    </td>
                                                                    <td>
                                                                        <span class="function_text profile_menu_text" id="sort4" style="cursor:pointer;" >Favorites</span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                   </td>
                               </tr>
                              <tr id="images_menu">
                                 <td >
                                     <table class="left_function profile_menu_div" style="border-top-left-radius:0px;margin-top:-3px;" onClick="profile_menu('images', 0);">
                                         <tbody>
                                             <tr>
                                                 <td>
                                                     <span class="function_text profile_menu_text" id="sort2" style="cursor:pointer;" >Recent</span>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 </td>
                                 <td>
                                     <table class="right_function profile_menu_div" style="border-top-right-radius:0px;margin-top:-3px;" onClick="profile_menu('images', 1);">
                                         <tbody>
                                             <tr>
                                                 <td>
                                                     <span class="function_text profile_menu_text" id="sort3" style="cursor:pointer;" >Most Popular</span>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 </td>
                                  <td></td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td id="profile_images_unit" colspan="2">
                         <p class="text_color" id="content_title"></p>
                         <img class="load_gif" src="http://i.imagepxl.com/site/load.gif" id="profile_load_gif" />
                         <div id="images">
                             
                         </div>
                         <div style="text-align:center;">
                            <input class="button blue_button" type="button" value="See More" id="see_more_button" style="margin-top:10px;"/>
                         </div>
                     </td>
                  </tr>
               </tbody>
            </table>
             <iframe name="banner_upload_iframe" style="display:none" ></iframe>
         </div>
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>