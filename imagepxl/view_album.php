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


if(isset($_GET['image_id']))
{
    $image_id=clean_string($_GET['image_id']);
    
    //gets start location of loop
    $start_index=$page*11;


    $index=array_search($image_id, $image_ids);
    if($index!==false)
    {
        $current_index=$index%11;
        $current_page=$index/11;

        $ext=$image_exts[$index];
    }
    else
    {
        header("Location: http://imagepxl.com/album/".$album_id);
        exit();
    }
}
else
{
//    add_view($image_ids[0]);
    $image_id=$image_ids[0];
    $current_index=0;
    $current_page=0;
    $ext=$image_exts[0];
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
       <meta name="description" content="<?php echo urlencode($album_name); ?>" />
      <title><?php echo $album_name; ?> - ImagePXL</title>
      <?php include('code_header.php'); ?>
      <link rel="image_src" href="http://i.imagepxl.com/<?php echo $username; ?>/<?php echo $image_id; ?>.<?php echo $ext; ?>"/>
      <script type="text/javascript">
         function change_colors()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
         }
         
         images=new Array();
         image_exts=new Array();
         current_index=<?php echo $current_index; ?>;
         current_page=<?php echo $current_page; ?>;
         last_page=false;
         timezone=get_timezone();
         next_page_image="";
         prev_page_image="";
         
         
         function fill_image_list()
         {
             if(current_page<0)
                 current_page=0;
             $.post('../view_album_query.php',
             {
                 num:1,
                 album_id: '<?php echo $album_id; ?>',
                 page: current_page
             }, function(output)
             {
                 images=output.new_image_list;
                 image_exts=output.new_image_ext_list;
                 last_page=output.showing_all;
                 next_page_image=output.next_page_image;
                 prev_page_image=output.prev_page_image;
                 
                     
                    //gives functionality to arrows
                    if(current_page<=0)
                        $('#arrow_left').attr({'class': 'arrow_body_disabled', 'onClick': ''});
                    else
                        $('#arrow_left').attr({'onClick': "window.location.replace('http://imagepxl.com/album/<?php echo $album_id; ?>="+prev_page_image+"');", 'class': 'arrow_body'}).show();
//                        $('#arrow_left').attr({'onClick': "left_page()", 'class': 'arrow_body'}).show();

                    if(last_page)
                        $('#arrow_right').attr({'class': 'arrow_body_disabled', 'onClick': ''});
                    else
                        $('#arrow_right').attr({'onClick': "window.location.replace('http://imagepxl.com/album/<?php echo $album_id; ?>="+next_page_image+"');", 'class': 'arrow_body'}).show();
//                        $('#arrow_right').attr({'onClick': "right_page()", 'class': 'arrow_body'}).show();


                    for(var x = 0; x < images.length; x++)
                    {
                        if(images[x]!='')
                        {
                           $('#image_list_unit_'+x).html("<div class='album_image_outside'><a class='link' href='http://imagepxl.com/album/<?php echo $album_id ?>="+images[x]+"' ><img class='small_image' style='width:75px;' id='album_image_"+x+"' src='http://i.imagepxl.com/<?php echo $username; ?>/thumbs/"+images[x]+"."+image_exts[x]+"' /></a></div>");
//                           $('#album_image_'+x).attr('onClick', "change_image("+x+");");
                        }
                        else
                           $('#image_list_unit_'+x).html("");
                    }

                    change_image(current_index);
                    
                 
             }, "json");
         }
         
         function left_page()
         {
             current_index=10;
             current_page--;
             fill_image_list();
         }
         function right_page()
         {
             current_index=0;
             current_page++;
             fill_image_list();
         }
         
         function change_image(index)
         {
             current_index=index;
             for(var x = 0; x < 11; x++)
             {
                 if($('#album_image_'+x).length)
                     $('#album_image_'+x).css('border', '1px solid black');
             }
             
             
             $('#album_image_'+index).css('border', '3px solid gold');
             
//             $('#image_body').html("<img class='main_image' src='http://i.imagepxl.com/<?php echo $username; ?>/"+images[index]+"."+image_exts[index]+"' />");
             
             display_image_information(images[index]);
         }
         function display_image_information(image_id)
         {
            $.post('../view_image_query.php',
            {
                num:1,
                user_id: <?php echo $user_id; ?>,
                image_id: image_id,
                timezone: timezone
            }, function(output)
            {
                var description=output.description;
                var upload_date=output.upload_date;
                var num_likes=output.num_likes;
                var num_dislikes=output.num_dislikes;
                var has_liked=output.has_liked;
                var has_disliked=output.has_disliked;
                var comments=output.comments;
                var num_comment_likes=output.num_comment_likes;
                var num_comment_dislikes=output.num_comment_dislikes;
                var has_liked_comments=output.has_liked_comments;
                var has_disliked_comments=output.has_disliked_comments;
                var comment_user_ids=output.comment_user_ids;
                var comment_ids=output.comment_ids;
                var comment_usernames=output.comment_usernames;
                var comment_timestamps=output.comment_timestamps;
                var num_favorites=output.num_favorites;
                var views=output.views;
                var points=output.points;
                var album_id=output.album_id;
                var album_name=output.album_name;
                var comment_profile_pictures=output.comment_profile_pictures;
                var has_favorited=output.has_favorited;
                
                //if user is viewing their own image
                if(<?php if($_SESSION['id']==$user_id) echo "true"; else echo "false"; ?>==true)
                {
                    display_description_form(description);
                    display_album_form(image_id);
                }
                else
                {
                    $('#album_unit').html("<span class='text_color'>Album - "+album_name+"</span>");
                    $('#description').html(description);
                }
                
                $('#upload_date_unit').html(upload_date);
                $('#views_unit').html(views+" views");
                $('#favorites_unit').html(num_favorites+" favorites");
                
                
                if(comment_ids[0]!=''&&comment_ids[0]!=undefined)
                    display_comments('<?php echo $image_id; ?>', comment_ids, comments, comment_user_ids, comment_usernames, num_comment_likes, num_comment_dislikes, has_liked_comments, has_disliked_comments, comment_timestamps, comment_profile_pictures);
                else
                    $('#comment_table_body').html("<tr><td><p class='text_color'>There are no comments</p></td></tr>");
                
                set_functions('<?php echo $image_id; ?>', num_likes, num_dislikes, has_liked, has_disliked, has_favorited);
                
                change_colors();
            }, "json");
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
         function display_description_form(description)
         {
             var textarea="<textarea class='input_box view_image_textarea' id='description_textarea' style='width:600px;'  placeholder='Description...'>"+description+"</textarea>";
            var save_button="<input class='button red_button' id='image_description_button' value='Save' type='button' onClick='save_description();' />";
            $('#description_body').html("<table><tbody><tr><td>"+textarea+"</td><td style='vertical-align:top;' >"+save_button+"</td></tr></tbody></table>");
            $('#image_description_button').attr('onClick', "save_description('<?php echo $image_id; ?>');");
         }
         function save_album(image_id, image_ext)
         {
             $.post('../view_image_query.php',
            {
                num:5,
                image_id: image_id,
                image_ext: image_ext,
                album_id: $('#album_select').val()
            }, function(output)
            {
                if(output=='success')
                    display_error("Image added to album", 'good_errors');
                else
                    display_error(output, 'bad_errors');
            });
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
         function display_comments(image_id, comment_ids, comments, comment_user_ids, comment_usernames, num_comment_likes, num_comment_dislikes, has_liked_comments, has_disliked_comments, timestamps, comment_profile_pictures)
         {
             var html="";
             for(var x = 0; x < comment_ids.length; x++)
             {
                 var name="<a class='link title_color' href='http://imagepxl.com/user/"+comment_usernames[x]+"'><span class='title_color' style='font-size:12px;'>"+comment_usernames[x]+"</span></a>";
                 var comment="<p class='text_color' style='font-size:12px;'>"+(convert_image(comments[x], image_id, comment_ids[x]))+"</p>";
                 var timestamp="<span class='text_color' style='font-size:12px;margin-left:10px;'>("+timestamps[x]+")</span>";
                
                if(<?php if(isset($_SESSION['id'])) echo "true"; else echo "false"; ?>==true&&comment_user_ids[x]==<?php if(isset($_SESSION['id'])) echo $_SESSION['id']; else echo "0"; ?>)
                    var delete_text="<span id='comment_delete_"+comment_ids[x]+"' class='username' style='color:rgb(220,20,0);font-size:12px;margin-left:10px;border:1px solid gray;padding:2px;border-radius:3px;background-color:whitesmoke;' >Delete</span>";
                else
                    var delete_text="";
                
                var bottom_menu="<table style='border-spacing:0px;'><tbody><tr><td style='border-right:1px solid gray;' id='comment_like_unit_"+image_id+"_"+comment_ids[x]+"' ></td><td id='comment_dislike_unit_"+image_id+"_"+comment_ids[x]+"' ></td><td style='padding-left:5px;padding-right:5px;' id='comment_points_unit_"+image_id+"_"+comment_ids[x]+"' ></td><td>"+delete_text+"</td></tr></tbody></table>";
                 var comment_right_unit="<table style='border-spacing:0px;'><tbody><tr><td>"+name+timestamp+"</td></tr><tr><td >"+comment+"</td></tr><tr><td >"+bottom_menu+"</td></tr></tbody></table>";
                 var comment_left_unit="<a class='link' href='http://imagepxl.com/user/"+comment_usernames[x]+"' ><img class='comment_profile_picture' src='"+comment_profile_pictures[x]+"'/></a>";
                 var comment_table="<div id='comment_body_"+comment_ids[x]+"' class='comment_body' ><table><tbody><tr><td>"+comment_left_unit+"</td><td>"+comment_right_unit+"</td></tr></tbody></table></div>";
                 html="<tr><td>"+comment_table+"</td></tr>"+html;
             }
             
             $('#comment_table_body').html(html);
             
             for(var x = 0; x < comment_ids.length; x++)
             {
                 $('#comment_delete_'+comment_ids[x]).attr('onClick', "delete_comment('<?php echo $image_id; ?>', "+comment_ids[x]+");");
                 set_comment_functions(image_id, comment_ids[x], num_comment_likes[x], num_comment_dislikes[x], has_liked_comments[x], has_disliked_comments[x]);
                 var image=$('#convert_image_'+image_id+'_'+comment_ids[x]);
                 image.attr('onClick', "display_comment_image('"+(image.html())+"');");
             }
         }
         function add_view(image_id)
         {
             
         }
         
         
         
         $(document).ready(function(){
             fill_image_list();
             initialize_arrows();
             
             //displays image options
            if(<?php if($_SESSION['id']==$user_id) echo "true"; else echo "false"; ?>==true)
            {
                $('#left_option_body').html("<span class='function_text text_color'>Make profile pic</span>").attr('onClick', "set_profile_picture('<?php echo $image_id; ?>');");
                $('#right_option_body').html("<span class='function_text'>Delete</span>").attr('onClick', "delete_image('<?php echo $image_id; ?>');");
                $('#copy_button_unit').html('');
            }
            else
            {
                $('#left_option_unit').html('');
                $('#right_option_unit').html('');
            }
            
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
          <a class="link title_color" href="http://imagepxl.com/gallery/<?php echo $album_id; ?>" ><p class="title_color" style="font-size:16px;padding-top:15px;padding-left:15px;"><?php echo $album_name; ?></p></a>
          <table style="width:100%;">
              <tbody>
                  <tr>
                      <td>
                          <table id="images_list_table" style="border-spacing:0px;">
                              <tbody>
                                  <tr>
                                      <td class="arrow_body" id="arrow_left" style="border-right:none;border-top-left-radius:50px;border-bottom-left-radius:50px;">
                                          <table class="album_arrow" >
                                              <tbody>
                                                  <tr>
                                                      <td>
                                                          <p class="text_color" style="font-size:20px;"><</p>
                                                      </td>
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </td>
                                      <td style="border:1px solid gray;background-color:whitesmoke;">
                                          <table style="border-spacing:0px;">
                                              <tbody>
                                                  <tr>
                                                        <td id="image_list_unit_0" class="image_list_unit"></td>
                                                        <td id="image_list_unit_1" class="image_list_unit"></td>
                                                        <td id="image_list_unit_2" class="image_list_unit"></td>
                                                        <td id="image_list_unit_3" class="image_list_unit"></td>
                                                        <td id="image_list_unit_4" class="image_list_unit"></td>
                                                        <td id="image_list_unit_5" class="image_list_unit"></td>
                                                        <td id="image_list_unit_6" class="image_list_unit"></td>
                                                        <td id="image_list_unit_7" class="image_list_unit"></td>
                                                        <td id="image_list_unit_8" class="image_list_unit"></td>
                                                        <td id="image_list_unit_9" class="image_list_unit"></td>
                                                        <td id="image_list_unit_10" class="image_list_unit"></td>
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </td>
                                      <td class="arrow_body" id="arrow_right" style="border-left:none;border-top-right-radius:50px;border-bottom-right-radius:50px;">
                                          <table class="album_arrow" >
                                              <tbody>
                                                  <tr>
                                                      <td>
                                                          <p class="text_color" style="font-size:20px;">></p>
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
                      <table style="width:100%;border-spacing:0px;padding-bottom:10px;border-bottom:1px solid black;margin-bottom:10px;">
                          <tbody>
                              <tr>
                                  <td style="border-right:1px solid gray;width:650px;vertical-align:top;">
                                      <div id="description_body" style="padding-left:15px;padding-bottom:10px;"><p class="text_color" id="description" style="font-size:16px;"></p></div>
                                        <div id="image_body" >
                                            <img class='main_image' src='http://i.imagepxl.com/<?php echo $username; ?>/<?php echo $image_id; ?>.<?php echo $ext; ?>' onClick="display_full_image('http://i.imagepxl.com/<?php echo $username; ?>/<?php echo $image_id; ?>.<?php echo $ext; ?>');" style="cursor:pointer;"/>
                                        </div>
                                  </td>
                                  <td style="vertical-align:top;">
                                      <table style="width:100%;">
                                          <tbody>
                                              <tr>
                                                      <td style="border-bottom:1px solid gray;padding-bottom:10px;padding-left:10px;">
                                                          <table style="border-spacing:0px;width:100%;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="vertical-align:top;width:50px;height:50px;">
                                                                        <a class="link" href="http://imagepxl.com/user/<?php echo $username; ?>" ><div class="profile_picture_small" style="height:50px;width:50px;background:url('<?php echo get_profile_picture($user_id); ?>');background-size:50px 50px;" ></div></a>
                                                                    </td>
                                                                    <td style="vertical-align:top;">
                                                                        <table style="border-spacing:0px;height:100%;width:100%;display:inline-table;">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td style="vertical-align:top;" colspan="2">
                                                                                        <a class="link" href="http://imagepxl.com/user/<?php echo $username; ?>"><span class="title_color username"><?php echo $username; ?></span></a>

                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <p class="text_color" id="num_followers_html"><?php echo $num_followers ?> followers</p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <input class="button red_button" type="button" value="Follow" onClick="follow(<?php echo $user_id; ?>);"/>
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
                                                    <td style="padding-top:10px;padding-bottom:10px;border-bottom:1px solid gray;padding-left:10px;">
                                                          <table>
                                                              <tbody>
                                                                  <tr>
                                                                        <td>
                                                                         <table class="functions_table" style="width:100%;">
                                                                             <tbody>
                                                                                 <tr>
                                                                                     <td class="left_functions_unit" style="display:inline-block;" >
                                                                                         <div id="like_unit_<?php echo $image_id; ?>" class="left_function_disabled">

                                                                                         </div>
                                                                                     </td>
                                                                                     <td class="middle_functions_unit" style="display:inline-block;" >
                                                                                         <div id="dislike_unit_<?php echo $image_id; ?>" class="middle_function_disabled">

                                                                                         </div>
                                                                                     </td>
                                                                                     <td class="middle_functions_unit" style="display:inline-block;" >
                                                                                         <div id="points_unit_<?php echo $image_id; ?>" class="middle_function_disabled" >

                                                                                         </div>
                                                                                     </td>
                                                                                     <td class="right_functions_unit" style="display:inline-block;" >
                                                                                         <div id="favorite_unit_<?php echo $image_id; ?>" class="right_function_disabled" >

                                                                                         </div>
                                                                                     </td>
                                                                                 </tr>
                                                                             </tbody>
                                                                         </table>
                                                                       </td>
                                                                       <td id="copy_button_unit">
                                                                           <input class="button red_button" id="copy_button" type="button" value="Copy" onClick="display_copy_image('<?php echo $image_id; ?>');" style="float:right;"/>
                                                                       </td>
                                                                  </tr>
                                                              </tbody>
                                                          </table>
                                                    </td>
                                              </tr>
                                              <tr>
                                                  <td style="border-bottom:1px solid gray;">
                                                        <table style="font-size:14px;padding:10px;">
                                                          <tbody>
                                                              <tr>
                                                                  <td id="views_unit">

                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                  <td id="favorites_unit">

                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                  <td id="upload_date_unit">

                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                  <td id="album_unit">

                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                  <td>
                                                                      <table class="functions_table" style="float:right;">
                                                                          <tbody>
                                                                              <tr>
                                                                                  <td class="left_function_unit" id="left_option_unit" style="display:inline-block;float:left;text-align:left">
                                                                                      <div id="left_option_body" class="left_function username" >

                                                                                      </div>
                                                                                  </td>
                                                                                  <td class="right_function_unit" id="right_option_unit" style="display:inline-block;float:left;">
                                                                                      <div id="right_option_body" class="right_function username button red_button">

                                                                                      </div>
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
                                                  <td style="border-bottom:1px solid gray;">
                                                      <?php include('share_html.php'); ?>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="border-bottom:1px solid gray;padding:10px;">
                                                      <a class="link title_color" href="http://imagepxl.com/<?php echo $image_id; ?>"><p class="title_color">View image page</p></a>
                                                      <p class="text_color">View in album</p>
                                                      <a class="link title_color" href="http://imagepxl.com/gallery/<?php echo $album_id; ?>"><p class="title_color">View gallery</p></a>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                      
                  </tr>
                  <tr>
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
                  </tr>
              </tbody>
          </table>
          <script type="text/javascript">
           function initialize_arrows()
            {
                $('html').unbind('keypress').unbind('keydown').unbind('keyup');
                $('html').keyup(function(e)
                {
                    var key = (e.keyCode ? e.keyCode : e.which);
                    
                    //right arrow
                    if(key == '39')
                    {
                        if($("input,textarea").is(":focus")==false)
                        { 
                            var temp_index=current_index+1;

                            //if reached end and isn't last page
                            if(temp_index>=images.length&&last_page==false)
                            {
    //                            current_index=0;
    //                            current_page++;
    //                            fill_image_list();
                                    window.location.replace("http://imagepxl.com/album/<?php echo $album_id ?>="+next_page_image);
                            }
                            else
                            {
                                if(images[temp_index]!=undefined&&images[temp_index]!='')
                                    window.location.replace("http://imagepxl.com/album/<?php echo $album_id ?>="+images[temp_index]);
    //                                change_image(temp_index);
                            }
                        }
                    }
                    
                    //left arrow
                    else if(key== '37')
                    {
                        if($("input,textarea").is(":focus")==false)
                        { 
                            //if going left page
                            if(current_index-1<0)
                            {
                                //if not on page 0
                                if(current_page>0)
                                {
                                    //puts current index to last image
    //                                current_index=10;
    //                                current_page--;
    //                                fill_image_list();
                                        window.location.replace("http://imagepxl.com/album/<?php echo $album_id ?>="+prev_page_image);
                                }
                            }
                            else
                                window.location.replace("http://imagepxl.com/album/<?php echo $album_id ?>="+images[current_index-1]);
    //                            change_image(current_index-1);
                        }
                    }
                    
                    //up arrow
                    if(key == '38')
                    {
                        if($("input,textarea").is(":focus")==false)
                        { 
                            like(images[current_index], 'regular');
                        }
                    }
                });
            }
        </script>
         
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>