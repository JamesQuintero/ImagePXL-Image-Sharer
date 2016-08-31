<?php
include('init.php');

//redirects to mobile version if user is using phone
//include('mobile_device_detect.php');

if(!isset($_SESSION['id']))
{
    if(isset($_COOKIE['acc_id']))
    {
        $query=mysql_query("SELECT id FROM users WHERE account_id='$_COOKIE[acc_id]' LIMIT 1");
        if($query&&mysql_num_rows($query)==1)
        {
            $array=mysql_fetch_row($query);

            $_SESSION['id']=$array[0];
            header("Location: http://imagepxl.com/home");
            exit();
        }
    }
}
else
{
    header("Location: http://imagepxl.com/home");
    exit();
}

include('universal_functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
      <meta name="description" content="ImagePXL is an image site where you get to share awesome images over a large audience" />
        <meta name="keywords" content="image, image sharer, images, photo, picture, sharer, upload, uploader" />
        <title>ImagePXL - Share images over a large audience</title>
        <script type="text/javascript">
            startTime = (new Date).getTime();
        </script>
        <?php include('required_header.php'); ?>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
      <?php include('code_header.php'); ?>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script type="text/javascript" src="./image_upload.js"></script>
      <script type="text/javascript">
         function change_color()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
         }
        function register()
        {
            $('#register_load').show();
            $.post('register.php',
            {
                username: $('#register_username').val(),
                password: $('#register_password').val(),
                email: $('#register_email').val()
            }, function (output)
            {
                console.log(output);
                $('#register_load').hide();
                if(output=='')
                    window.location.replace(window.location);
                else
                    display_error(output, 'bad_errors');
            });
        }
        
        function image_fades(index)
        {
            
            //goes on forever
            var index2=0;
            
            if(index!=3)
                index2=index+1;
            else
                index2=0;

            setTimeout(function(){
                $('#index_image_'+index).animate({
                    opacity: 0
                }, 1000, function(){});

                $('#index_image_'+index2).animate({
                    opacity: 1
                }, 1000, function(){});
                setTimeout(function(){
                    image_fades(index2);;
                }, 1000);
            }, 5000);
        }
        
        function display_top_images(page)
        {
            $.post('top_query.php',
            {
                type:'rising',
                page: page,
                content_type: 'images',
                timezone: get_timezone()
            }, function(output)
            {
                var image_ids=output.image_ids;
                var exts=output.exts;
                var usernames=output.usernames;
                var descriptions=output.descriptions;
                var num_views=output.views;
                var num_likes=output.num_likes;
                var num_dislikes=output.num_dislikes;
                var thumbnails=output.thumbnails;

                var index=page*25-25;
                for(var x = 0; x < image_ids.length; x++)
                {
                    var image="<a class='link' href='http://imagepxl.com/"+image_ids[x]+"' ><img id='index_image_preview_"+index+"' class='image_preview' src='"+thumbnails[x]+"' style='width:155px;' /></a>";
                    var points_unit="<span class='function_text inside_function_text ' style='cursor:default;text-align:center;' >"+(num_likes[x]-num_dislikes[x])+" points</span>";
                    var views_unit="<span class='function_text inside_function_text ' style='cursor:default;text-align:center;' >"+num_views[x]+" views</span>";
                    var image_functions="<table class='functions_table' style='width:100%;'><tbody><tr><td class='left_functions_unit' ><div id='points_unit_"+index+"' class='left_function_disabled function_interior_disabled  ' style='border-left:none;'>"+points_unit+"</div></td><td class='middle_functions_unit' style='text-align:center;'><div id='views_unit_"+index+"' class='right_function_disabled function_interior_disabled ' style='border-right:none;'>"+views_unit+"</div></td></tr></tbody></table>";
                    var image_body="<div class='image_body'>"+image+"<div class='interior_image_functions'>"+image_functions+"</div></div>"
                    
//                    $('#index_unit_'+x).html("<table><tbody><tr><td><a class='link' href='http://imagepxl.com/"+image_ids[x]+"' ><img class='image_preview' id='index_image_preview_"+x+"' src='http://i.imagepxl.com/"+usernames[x]+"/thumbs/"+image_ids[x]+"."+exts[x]+"' /></a></td></tr></tbody></table>");
                    $('#index_unit_'+index).html(image_body).css({'opacity': '0', 'width': '155px', 'height': '155px'});
                    
                    if(descriptions[x]!='')
                        $("#index_image_preview_"+index).attr({'onmouseover': "display_title(this, '"+descriptions[x]+"');", 'onmouseout': "hide_title(this);"});
                    else
                        $("#index_image_preview_"+index).attr({'onmouseover': "display_title(this, '<i>No Caption</i>');", 'onmouseout': "hide_title(this);"});
                   
                   index++;
                }
                
                if(page<4)
                       $('#index_see_more').attr('onClick', "display_top_images("+(page+1)+")");
                   else
                       $('#index_see_more').hide();
                   
                animate(page*25-25)
                
                change_color();
            }, "json");
        }
        
        function animate_recursion(num)
        {
            $('#index_unit_'+num).animate({
                opacity:1
            }, 75, function(){
                num++;
                if($('#index_unit_'+(num+1)).length)
                    animate_recursion(num);
            });
        }
        
        function animate(num)
        {
            setTimeout(function(){
                animate_recursion(num);
            }, 500);
        }
        
         
         $(document).ready(function(){
         
         initialize_input_boxes();
            for(var x = 1; x < 4; x++)
                $('#index_image_'+x).css('opacity', 0);
            display_top_images(1);
//             image_fades(0);
            change_color();
            
            <?php
                include('required_jquery.php');
            ?>
         });
      </script>
      <script type="text/javascript">
        <?php include('required_google_analytics.js'); ?>
      </script>
   </head>
   <body>
        <div class="tool_tip"></div>
        <div id="alert_box_body">
            <div class="alert_box box">
                <div class="alert_box_inside">

                </div>
            </div>
        </div>
        <div id="errors" ></div>
        <div id="dim" onClick="close_alert_box();"></div>
        
       <div style="text-align:center;">
           <img src="http://i.imagepxl.com/site/imagePXL_index.png"/>
           <p class="text_color" style="margin-top:-20px;margin-bottom:15px;">ImagePXL "image pixel" is an image site where you get to share awesome images over a large audience</p>
       </div>
      <div class="content" style="margin-top:0px;">
          
          <div style="padding:15px;">
<!--                          <div class="index_image_body">
                              <img class="index_image" id="index_image_0" src="http://i.imagepxl.com/site/index_images/backflip.png"/>
                              <img class="index_image" id="index_image_1" src="http://i.imagepxl.com/site/index_images/image2.jpg"/>
                              <img class="index_image" id="index_image_2" src="http://i.imagepxl.com/site/index_images/image3.jpg"/>
                              <img class="index_image" id="index_image_3" src="http://i.imagepxl.com/site/index_images/image4.jpg"/>
                          </div>-->
                            <a class="link title_color" href="http://imagepxl.com/viral">
                                <span class="title_color" style="margin-bottom:5px;">(Most popular)</span>
                            </a>
                          <table style="padding-top:0px;">
                              <tbody>
                                  <tr>
                                      <td id="index_unit_0" ></td>
                                      <td id="index_unit_1" ></td>
                                      <td id="index_unit_2" ></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_3" ></td>
                                      <td id="index_unit_4" ></td>
                                      <td id="index_unit_5" ></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_6" ></td>
                                      <td id="index_unit_7" ></td>
                                      <td id="index_unit_8" ></td>
                                      <td id="index_unit_9" ></td>
                                      <td id="index_unit_10" ></td>
                                      <td id="index_unit_11" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_12" ></td>
                                      <td id="index_unit_13" ></td>
                                      <td id="index_unit_14" ></td>
                                      <td id="index_unit_15" ></td>
                                      <td id="index_unit_16" ></td>
                                      <td id="index_unit_17" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_18" ></td>
                                      <td id="index_unit_19" ></td>
                                      <td id="index_unit_20" ></td>
                                      <td id="index_unit_21" ></td>
                                      <td id="index_unit_22" ></td>
                                      <td id="index_unit_23" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_24" ></td>
                                      <td id="index_unit_25" ></td>
                                      <td id="index_unit_26" ></td>
                                      <td id="index_unit_27" ></td>
                                      <td id="index_unit_28" ></td>
                                      <td id="index_unit_29" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_30" ></td>
                                      <td id="index_unit_31" ></td>
                                      <td id="index_unit_32" ></td>
                                      <td id="index_unit_33" ></td>
                                      <td id="index_unit_34" ></td>
                                      <td id="index_unit_35" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_36" ></td>
                                      <td id="index_unit_37" ></td>
                                      <td id="index_unit_38" ></td>
                                      <td id="index_unit_39" ></td>
                                      <td id="index_unit_40" ></td>
                                      <td id="index_unit_41" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_42" ></td>
                                      <td id="index_unit_43" ></td>
                                      <td id="index_unit_44" ></td>
                                      <td id="index_unit_45" ></td>
                                      <td id="index_unit_46" ></td>
                                      <td id="index_unit_47" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_48" ></td>
                                      <td id="index_unit_49" ></td>
                                      <td id="index_unit_50" ></td>
                                      <td id="index_unit_51" ></td>
                                      <td id="index_unit_52" ></td>
                                      <td id="index_unit_53" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_54" ></td>
                                      <td id="index_unit_55" ></td>
                                      <td id="index_unit_56" ></td>
                                      <td id="index_unit_57" ></td>
                                      <td id="index_unit_58" ></td>
                                      <td id="index_unit_59" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_60" ></td>
                                      <td id="index_unit_61" ></td>
                                      <td id="index_unit_62" ></td>
                                      <td id="index_unit_63" ></td>
                                      <td id="index_unit_64" ></td>
                                      <td id="index_unit_65" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_66" ></td>
                                      <td id="index_unit_67" ></td>
                                      <td id="index_unit_68" ></td>
                                      <td id="index_unit_69" ></td>
                                      <td id="index_unit_70" ></td>
                                      <td id="index_unit_71" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_72" ></td>
                                      <td id="index_unit_73" ></td>
                                      <td id="index_unit_74" ></td>
                                      <td id="index_unit_75" ></td>
                                      <td id="index_unit_76" ></td>
                                      <td id="index_unit_77" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_78" ></td>
                                      <td id="index_unit_79" ></td>
                                      <td id="index_unit_80" ></td>
                                      <td id="index_unit_81" ></td>
                                      <td id="index_unit_82" ></td>
                                      <td id="index_unit_83" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_84" ></td>
                                      <td id="index_unit_85" ></td>
                                      <td id="index_unit_86" ></td>
                                      <td id="index_unit_87" ></td>
                                      <td id="index_unit_88" ></td>
                                      <td id="index_unit_89" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_90" ></td>
                                      <td id="index_unit_91" ></td>
                                      <td id="index_unit_92" ></td>
                                      <td id="index_unit_93" ></td>
                                      <td id="index_unit_94" ></td>
                                      <td id="index_unit_95" ></td>
                                  </tr>
                                  <tr>
                                      <td id="index_unit_96" ></td>
                                      <td id="index_unit_97" ></td>
                                      <td id="index_unit_98" ></td>
                                      <td id="index_unit_99" ></td>
                                      <td id="index_unit_100" ></td>
                                      <td id="index_unit_101" ></td>
                                  </tr>
                                  
                                  
                                  <tr>
                                      <td colspan="3">
                                          <input class="button blue_button" type="button" value="See more" style="width:100%;font-size:16px;" id="index_see_more"/>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>

          </div>
          <div style="position:absolute;top:0px;right:0px;">
                      
                          <table style="position:relative;margin:0 auto;padding:15px;">
                            <tbody>
                                <tr>
                                    <td style="vertical-align:top;">
                                        <table style="margin-right:15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align:center" colspan="2">
                                                        <p class="title_color">Login</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="text_color">Username: </span>
                                                    </td>
                                                    <td>
                                                        <input class="input_box index_input" type="text" maxlength="255" placeholder="Username..." id="login_username"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="text_color">Password: </span>
                                                    </td>
                                                    <td>
                                                        <input class="input_box index_input" type="password" maxlength="255" placeholder="Password..." id="login_password" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center;">
                                                        <input class="button red_button" type="button" value="Login" onClick="login();"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <img class="load_gif" src="http://i.imagepxl.com/site/load.gif" id="login_load" style="display:none;"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" style="text-align:center">
                                                        <p class="title_color" >Join</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                       <p class="text_color">Username: </p>
                                                    </td>
                                                    <td>
                                                       <input class="input_box index_input" type="text" maxlength="40" placeholder="Username..." id="register_username"/>
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                       <p class="text_color">Email: </p>
                                                    </td>
                                                    <td>
                                                       <input class="input_box index_input" type="email" maxlength="255" placeholder="Email..." id="register_email"/>
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                       <p class="text_color">Password: </p>
                                                    </td>
                                                    <td>
                                                       <input class="input_box index_input" type="password" maxlength="255" placeholder="Password..." id="register_password" />
                                                    </td>
                                                 </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center;">
                                                        <p style="font-size:12px;margin-top:10px;" class="text_color">By joining, you agree to the </p><p onClick="window.open('http://imagepxl.com/user_agreement');" class="username title_color" style="font-size:12px;">User Agreement</p>
                                                    </td>
                                                </tr>
                                                  <tr>
                                                      <td colspan="2" style="text-align:center;">
                                                          <input class="button red_button" type="button" value="Join" onClick="register();" />
                                                      </td>
                                                  </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <img class="load_gif" src="http://i.imagepxl.com/site/load.gif" id="register_load" style="display:none;"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table id="upload_type_menu" style="margin-top:20px;width:400px;background-color:white;">
                                            <tbody>
                                                <tr>
                                                    <td class="upload_type_menu_item" style="width:50%;border-right:1px solid gray;" onClick="display_computer_upload();" >
                                                        <div class="upload_type_menu_body">
                                                            <p class="text_color" style="font-size:12px;">Upload from your computer</p>
                                                            <img src="http://i.imagepxl.com/site/image_upload_computer.png" style="height:50px;padding-top:10px;" />
                                                        </div>
                                                    </td>
                                                    <td class="upload_type_menu_item" style="width:50%;" onClick="display_url_upload();" >
                                                        <div class="upload_type_menu_body">
                                                            <p class="text_color" style="font-size:12px;">Upload from a URL</p>
                                                            <img src="http://i.imagepxl.com/site/image_upload_url.png" style="height:50px;padding-top:10px;" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                         </table>
          </div>
            <script type="text/javascript">
              function initialize_input_boxes()
               {
                   $('#login_password').unbind('keypress').unbind('keydown').unbind('keyup');
                   $('#login_password').keyup(function(e)
                   {
                       var key = (e.keyCode ? e.keyCode : e.which);

                       //right arrow
                       if(key == '13')
                       {
                           login();
                       }

                   });
               }
           </script>
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>