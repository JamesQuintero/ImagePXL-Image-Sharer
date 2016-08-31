<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$album_id=clean_string($_GET['album_id']);


$data=get_album_data($album_id);
$user_id=$data['user_id'];
$album_name=$data['album_name'];
$image_ids=$data['image_ids'];
$image_exts=$data['image_exts'];

$user_data=get_user_data($user_id);
$username=$user_data['username'];
$num_followers=$user_data['num_followers'];



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
       <meta name="description" content="<?php echo urlencode($album_name); ?>" />
      <title><?php echo $album_name; ?> - ImagePXL</title>
      <?php include('code_header.php'); ?>
      <link rel="image_src" href="http://i.imagepxl.com/<?php echo $username; ?>/<?php echo end($image_ids); ?>.<?php echo end($image_exts); ?>"/>
      <script type="text/javascript">
         function change_colors()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
         }
         
         image_ids=new Array();
         image_exts=new Array();
         descriptions=new Array();
         timestamps=new Array();
         num_likes=new Array();
         num_dislikes=new Array();
         num_favorited=new Array();
         views=new Array();
         nsfw=new Array();
         has_liked=new Array();
         has_disliked=new Array();
         has_favorited=new Array();
         total_num_images=0;
         num_images=5;
         loading=true;
         index=0;
         
         function get_images()
         {
            $.post('../view_gallery_query.php',
            {
                num:1,
                album_id: '<?php echo $album_id; ?>',
                timezone: get_timezone()
            }, function(output)
            {
                image_ids=output.all_image_ids;
                image_exts=output.all_image_exts;
                descriptions=output.descriptions;
                timestamps=output.timestamps;
                num_likes=output.num_likes;
                num_dislikes=output.num_dislikes;
                num_favorites=output.num_favorites;
                views=output.views;
                nsfw=output.nsfw;
                has_liked=output.has_liked;
                has_disliked=output.has_disliked;
                has_favorited=output.has_favorited;
                total_num_images=output.total_num_images;
                
                //gets the necessary html ready
                var html="";
                for(var x = 0; x < total_num_images; x++)
                {
                    if(x%2==0)
                        var background="style='background-color:whitesmoke'";
                    else
                        var background="";

                    html+="<tr "+background+" id='images_row_"+x+"' ></tr>";
                }
                $('#images_tbody').html(html);

                display_images();
            }, "json");
         }
         function display_images()
         {
                
                var temp_index=index;
                for(var x = index; x < num_images; x++)
                {
                    console.log("X: "+x+" | Images: "+num_images);
                    var image="<img class='main_image' id='main_image_"+index+"' src='http://i.imagepxl.com/<?php echo $username ?>/"+image_ids[x]+"."+image_exts[x]+"' style='cursor:pointer;' />";
                    var description="<div id='description_body' style='padding-bottom:10px;'><p class='text_color'>"+descriptions[x]+"</p></div>";
                    var timestamp="<p class='text_color'>"+timestamps[x]+"</p>";
                    
                    var image_unit="<div id='image_body_"+index+"' style='width:650px;'>"+image+"</div>";
                    
                    var like_function_unit="<td class='left_functions_unit' style='display:inline-block;' ><div id='like_unit_"+image_ids[x]+"' class='left_function_disabled'></div></td>";
                    var dislike_function_unit="<td class='middle_functions_unit' style='display:inline-block;' ><div id='dislike_unit_"+image_ids[x]+"' class='middle_function_disabled'></div></td>";
                    var point_function_unit="<td class='middle_functions_unit' style='display:inline-block;' ><div id='points_unit_"+image_ids[x]+"' class='middle_function_disabled' ></div></td>";
                    var favorite_function_unit="<td class='right_functions_unit' style='display:inline-block;' ><div id='favorite_unit_"+image_ids[x]+"' class='right_function_disabled' ></div></td>";
                    
                    var functions_table="<table class='functions_table' style='width:100%;'><tbody><tr>"+like_function_unit+dislike_function_unit+point_function_unit+favorite_function_unit+"</tr></tbody></table>";
                    if(<?php if(isset($_SESSION['id'])&&$user_id==$_SESSION['id']) echo "false"; else echo "true"; ?>==true)
                        var copy_button="<input class='button red_button' id='copy_button_"+index+"' type='button' value='Copy' style='float:right;' />";
                    else
                        var copy_button="";
                        
                    var functions_unit="<td style='padding-top:10px;padding-bottom:10px;border-bottom:1px solid gray;padding-left:10px;'><table><tbody><tr><td>"+functions_table+"</td><td>"+copy_button+"</td></tr></tbody></table></td>";
                    
                    var view_html="<p class='text_color' >"+views[x]+" views</p>";
                    var owner_functions="<table class='functions_table' id='owner_functions_table_"+index+"' style='float:right;'><tbody><tr><td class='left_function_unit' id='left_option_unit_"+index+"' style='display:inline-block;float:left;text-align:left'><div id='left_option_body_"+index+"' class='left_function username' ></div></td><td class='right_function_unit' id='right_option_unit_"+index+"' style='display:inline-block;float:left;'><div id='right_option_body_"+index+"' class='right_function username button red_button'></div></td></tr></tbody></table>";
                    var info_unit="<td style='border-bottom:1px solid gray;'><table style='font-size:14px;padding:10px;' ><tbody><tr><td id='upload_date_unit'>"+timestamp+"</td></tr><tr><td id='views_unit'>"+view_html+"</td></tr><tr><td>"+owner_functions+"</td></tr></tbody></table></td>";
                    
                    var share_text="<span class='text_color'>Share: </span>";
                    var share_reddit="<a href='#' id='share_reddit_"+index+"' ><img src='http://i.imagepxl.com/site/share_icons/reddit.png' alt='Submit to reddit' border='0' style='height:32px;' /></a>";
                    var share_facebook="<a href='#' id='share_facebook_"+index+"'><img src='http://i.imagepxl.com/site/share_icons/facebook.png' alt='Share on Facebook' border='0' /></a>";
                    var share_twitter="<a href='#' id='share_twitter_"+index+"' ><img src='http://i.imagepxl.com/site/share_icons/twitter.png' alt='Tweet to twitter' border='0' /></a>";
                    var share_table="<table style='padding:10px;'><tbody><tr><td>"+share_text+"</td><td>"+share_reddit+"</td><td>"+share_facebook+"</td><td>"+share_twitter+"</td></tr></tbody></table>";
                    
                    
                    var share_unit="<td style='border-bottom:1px solid gray;' id='share_unit_"+index+"'>"+share_table+"</td>";
                    var view_image_page_html="<td style='border-bottom:1px solid gray;padding:15px;' ><a class='link title_color' href='http://imagepxl.com/"+image_ids[x]+"'><p class='title_color' >View image page</p></a><a class='link title_color' href='http://imagepxl.com/album/<?php echo $album_id; ?>="+image_ids[x]+"'><p class='title_color'>View in album</p></a><p class='text_color'>View gallery</p></td>";
                    var image_info_unit="<table style='width:100%;' ><tbody><tr>"+functions_unit+"</tr><tr>"+info_unit+"</tr><tr>"+share_unit+"</tr><tr>"+view_image_page_html+"</tr></tbody></table>";
                    var table="<table style='width:100%;padding:15px;border-spacing:0px;padding-bottom:10px;margin-bottom:10px;'><tbody><tr><td style='width:650px;vertical-align:top;'>"+description+"</td></tr><tr><td style='vertical-align:top' ><table><tbody><tr><td style='width:650px;border-right:1px solid gray;padding-right:10px;' >"+image_unit+"</td><td style='vertical-align:top;width:100%;'>"+image_info_unit+"</td></tr></tbody></table></td></tr></tbody></table>";
                    $('#images_row_'+index).html("<td style='border-top:1px solid gray'>"+table+"</td>");
                    index++;
                }
                index=temp_index;
                

                //adds functionality to stuff
                for(var x = index; x < num_images; x++)
                {
                    $('#main_image_'+index).attr('onClick', "display_full_image('http://i.imagepxl.com/<?php echo $username ?>/"+image_ids[x]+"."+image_exts[x]+"');");
                    $('#copy_button_'+index).attr('onClick', "display_copy_image('"+image_ids[x]+"');");
                    $('#share_reddit_'+index).attr('onClick', "window.open('http://reddit.com/submit?url=' + encodeURIComponent('http://imagepxl.com/"+image_ids[x]+"')); return false");
                    $('#share_facebook_'+index).attr('onClick', "window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://imagepxl.com/"+image_ids[x]+"'), 'facebook-share-dialog', 'width=626,height=436'); return false;");
                    $('#share_twitter_'+index).attr('onClick', "window.open('https://twitter.com/intent/tweet?original_referer='+encodeURIComponent('http://imagepxl.com/"+image_ids[x]+"')+'&url='+encodeURIComponent('http://imagepxl.com/"+image_ids[x]+"')+'&via=imagePXL');");
                    
                    
                    set_functions(image_ids[x], num_likes[x], num_dislikes[x], has_liked[x], has_disliked[x], has_favorited[x]);
                    
                    if(<?php if(isset($_SESSION['id'])&&$user_id==$_SESSION['id']) echo "true"; else echo "false"; ?>==true)
                    {
                        $('#left_option_body_'+index).html("<span class='function_text text_color'>Make profile pic</span>").attr('onClick', "set_profile_picture('"+image_ids[x]+"');");
                        $('#right_option_body_'+index).html("<span class='function_text'>Delete</span>").attr('onClick', "delete_image('"+image_ids[x]+"');");
                    }
                    else
                        $('#owner_functions_table_'+index).hide();
                    
                    index++;
                }
                 loading=false;
         }
         function display_album_form(image_id)
         {
             $.post('../view_image_query.php',
            {
                num:4,
                image_id: image_id
            }, function(output)
            {
                var album_ids=output.album_ids;
                var album_names=output.album_names;
                var current_album_id=output.current_album_id;
                
                var html="<option value=''>--None--</option>";
                for(var x = 0; x < album_ids.length; x++)
                {
                    if(album_ids[x]==current_album_id)
                        var selected="selected='selected'";
                    else
                        var selected="";
                    
                    html+="<option value='"+album_ids[x]+"' "+selected+">"+album_names[x]+"</option>";
                }
                
                var select_box="<select id='album_select' >"+html+"</select>";
                $('#album_unit').html("<span class='text_color'>Album - </span>"+select_box);
                $('#album_select').attr('onChange', "save_album('"+image_id+"', '"+image_exts[current_index]+"');");
            }, "json");
         }
         function display_description_form(num, description)
         {
             var textarea="<textarea class='input_box view_image_textarea' id='description_textarea_"+num+"' style='width:600px;'  placeholder='Description...'>"+description+"</textarea>";
            var save_button="<input class='button red_button' id='image_description_button_"+num+"' value='Save' type='button' onClick='save_description();' />";
            $('#description_body_'+num).html("<table><tbody><tr><td>"+textarea+"</td><td style='vertical-align:top;' >"+save_button+"</td></tr></tbody></table>");
//            $('#image_description_button_'+num).attr('onClick', "save_description('"+all_image_ids[num]+"');");
            $('#image_description_button_'+num).attr('onClick', "console.log('"+all_image_ids[num]+"');");
         }
         function save_description(image_id)
         {
             $.post('../view_image_query.php',
            {
                num:3,
                description: $('#description_textarea').val(),
                image_id: image_id
            }, function(output)
            {
                if(output=='success')
                    display_error("Description changed", 'good_errors');
                else
                    display_error(output, 'bad_errors');
            });
         }
         function delete_image(image_id)
         {
             $.post('../delete_image.php',
            {
                image_id: image_id
            }, function(output)
            {
                if(output=='success')
                {
                    display_error("Image deleted", 'good_errors');
                    setTimeout(function(){window.location.replace("http://imagepxl.com/user/<?php echo $username; ?>")}, 3000);
                }
                else
                    display_error(output, 'bad_errors');
            });
         }
         function set_profile_picture(image_id)
         {
             $.post('../set_profile_picture.php',
            {
                image_id: image_id
            }, function(output)
            {
                if(output=="Profile picture changed")
                    display_error(output, "good_errors");
                else
                    display_error(output, "bad_errors");
            });
         }
         
         
         
         $(document).ready(function(){
             get_images();
            
            //if not logged in
            if(<?php if(isset($_SESSION['id'])) echo "true"; else echo "false"; ?>==false)
                $('#comment_form_table').hide();
             
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
        ?>
      <div class="content">
          <table style="width:100%;">
              <tbody>
                  <tr>
                      <td>
                          <a class="link title_color" href="http://imagepxl.com/album/<?php echo $album_id; ?>" ><span class="title_color" style="font-size:16px;padding-left:15px;"><?php echo $album_name; ?></span></a><span class="text_color" style="font-size:16px;"> (<?php echo sizeof($image_ids); ?> images)</span>
                      </td>
                      <td >
                          <table style="float:right;padding:10px;">
                              <tbody>
                                  <tr>
                                      <td>
                                          <a class="link" href="http://imagepxl.com/user/<?php echo $username; ?>"><div class="profile_picture_small" style="height:35px;width:35px;background:url('<?php echo get_profile_picture($user_id); ?>');background-size:35px 35px;"></div></a>
                                      </td>
                                      <td>
                                          <table style="height:100%;">
                                              <tbody>
                                                  <tr>
                                                      <td>
                                                          <a class="link title_color" href="http://imagepxl.com/user/<?php echo $username; ?>"><p class="title_color username" ><?php echo $username; ?></p></a>
                                                      </td>
                                                  </tr>
                                                  <tr>
                                                      <td>
                                                          <p class="text_color" style="font-size:12px;"><?php echo $num_followers; ?> followers</p>
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
          
          
          
          <table style="width:100%;">
              <tbody>
                  <tr>
                      <td id="album_images_unit">
                          <table style="width:100%;padding-top:0px;">
                              <tbody id="images_tbody">
                                  
                              </tbody>
                          </table>
                      </td>
                  </tr>
<!--                  <tr>
                      <td style="border-top:1px solid black;padding:15px;" >
                          <table style="width:100%;padding:15px;">
                              <tbody>
                                  <tr>
                                      <td>
                                          <table id="comment_form_table" style="margin-top:10px;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <textarea class="input_box view_image_textarea" id="comment_input_<?php echo $image_id; ?>" placeholder="Comment..."></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:right;">
                                                        <input type="button" class="button red_button" value="Comment" id="comment_button" onClick="comment('<?php echo $image_id; ?>');" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <img class="load_gif" src="http://i.imagepxl.com/site/load.gif" id="comment_load_gif" style="display:none"/>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <table style="width:100%;margin-top:10px;padding-top:10px;" id="comment_table">
                                                <tbody id="comment_table_body">

                                                </tbody>
                                            </table>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </td>
                  </tr>-->
              </tbody>
          </table>
          <script type="text/javascript">
                $(window).scroll(function()
                {
                    //if hits the bottom of the page
                    if(($(window).scrollTop() >= $(document).height() - $(window).height() - $(window).height())&&loading==false)
                    {
                        loading=true;
                        num_images++;
                        $('#images_row_'+index).html("<td><img class='load_gif' src='http://i.imagepxl.com/site/load.gif'/></td>");
                        display_images();
                    }
                });
            
        </script>
         
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>