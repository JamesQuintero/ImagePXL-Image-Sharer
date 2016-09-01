<?php
include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

?>

<!DOCTYPE html>
<html>
   <head>
      <title>Upload images - ImagePXL</title>
      <?php include('code_header.php'); ?>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      <meta name="description" content="Upload an unlimited amount of HD images to imagePXL." />
      <meta name="keywords" content="upload, uploader, image uploader, image sharer" />
      <script type="text/javascript" src="./image_upload.js"></script>
      <script type="text/javascript">
         function add_new_album()
         {
             $.post('create_album.php',
             {
                 album_name: $('#new_album_input').val()
             }, function(output)
             {
                 if(output=='success')
                     display_error("Album created", 'good_errors');
                 else
                     display_error(output, 'bad_errors');
             });
         }
         function load_recent_pictures()
         {
             $.post('profile_query.php',
            {
                num:1,
                user_id: <?php if(isset($_SESSION['id'])) echo $_SESSION['id']; else echo "0"; ?>,
                display:0,
                page: 1
            }, function(output)
            {
                var images=output.images;
                var exts=output.exts;
                
                var index=0;
                for(var x = 1; x <= images.length; x++)
                {
                    $('#uploaded_image_unit_'+x).html("<a href='http://imagepxl.com/"+images[index]+"' ><img class='small_image' src='http://i.imagepxl.com/<?php echo $_SESSION['username']; ?>/thumbs/"+images[index]+"."+exts[index]+"' /></a>");
                    index++;
                }
            }, "json");
         }
         
         function load_albums_list(num)
         {
             $.post('profile_query.php',
            {
                num:2,
                user_id: <?php if(isset($_SESSION['id'])) echo $_SESSION['id']; else echo "0"; ?>
            }, function(output)
            {
                var album_ids=output.album_ids;
                var album_names=output.album_names;
                var image_ids=output.image_ids;
                
                ///fills album list
                var html="";
                for(var x = 0; x < image_ids.length; x++)
                {
                    if(image_ids[x][0]=='')
                        var num_images=0;
                    else
                        var num_images=image_ids[x].length;
                    html+="<tr><td><span class='text_color username' id='album_name_"+x+"' >"+album_names[x]+"</span></td><td><span class='text_color'>("+(num_images)+" images)</span></td></tr>";
                }
                //adds new album form
                var html="<tr><td colspan='2'><input class='input_box' id='new_album_input' placeholder='New album...' maxlength='30' /></td></tr>"+html;
                $('#albums_tbody').html(html);
                initialize_album_form();
                
                ////fills album select form
                var html="";
                for(var x = 0; x < image_ids.length; x++)
                {
                    //sets onClick for album name
                    $('#album_name_'+x).attr('onClick', "display_album_images('"+album_ids[x]+"')");
                    
                    //displays number of images
                    if(image_ids[x][0]=='')
                        var num_images=0;
                    else
                        var num_images=image_ids[x].length;
                    html+="<option value='"+album_ids[x]+"' >"+album_names[x]+" ("+(num_images)+")</option>";
                }
                html="<option val='' >-- None --</option>"+html;
                
                if(num==0)
                    $('#album_select').html(html);
                else
                    $('#album_select_url').html(html);
                
            }, "json");
         }
         function display_computer_upload()
         {
             $('#url_upload').hide();
             $('#computer_upload').show();
             
             if($('#album_select').html()=='')
                load_albums_list(0);
         }
         function display_url_upload()
         {
             $('#computer_upload').hide();
             $('#url_upload').show();
             
             if($('#album_select_url').html()=='')
                load_albums_list(1);
         }
         function display_album_images(album_id)
         {
            $.post('profile_query.php',
            {
                num:2,
                user_id: <?php if(isset($_SESSION['id'])) echo $_SESSION['id']; else echo "0"; ?>
            }, function(output)
            {
                var album_ids=output.album_ids;
                var image_ids=output.image_ids;
                var image_exts=output.image_exts;
                
                var album_index=0;
                for(var x = 0; x < album_ids.length; x++)
                {
                    if(album_ids[x]==album_id)
                        album_index=x;
                }
                
                //resets image list
                for(var x = 1; x <= 15; x++)
                    $('#album_image_unit_'+x).html("");
                
                var index=0;
                for(var x = 1; x <= image_ids[album_index].length; x++)
                {
                    if(x<=15)
                    {
                        $('#album_image_unit_'+x).html("<img class='small_image' src='http://i.imagepxl.com/<?php echo $_SESSION['username']; ?>/thumbs/"+image_ids[album_index][index]+"."+image_exts[album_index][index]+"' />");
                        index++;
                    }
                }
                
            }, "json");
         }
         
         $(document).ready(function(){
             $('#computer_upload').hide();
             $('#url_upload').hide();
             <?php if(isset($_SESSION['id'])) echo "load_recent_pictures();load_albums_list();"; else echo "$('#album_images_unit').html('');$('#my_images_unit').html('');$('#my_albums_unit').html('');"; ?>
             $('#progress_bar').hide();
            
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
      <?php if(isset($_SESSION['id'])) include('header.php'); else include('index_header.php'); ?>
      <div class="content">
          <div style="width:100%;height:100%;padding:15px;">
              <p class="text_color" style="margin-bottom:15px;">Images supported: JPG, PNG, GIF. Max uploading at once is 20MB. No images above 10MB.</p>
              <table>
                  <tbody>
                      <tr>
                          <td style="vertical-align:top;width:400px;">
                              <table id="upload_type_menu" style="margin-bottom:20px;width:400px;">
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
                              <div id="computer_upload">
                                    <form method="post" action="upload_image.php" enctype="multipart/form-data" >
                                        <table style="width:100%;">
                                            <tbody>
                                                <tr>
                                                    <td id="file_input_row" colspan="3">
                                                        <input type="file" id="image" name="image[]" multiple="multiple"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea id="image_description_upload" name="description" class="input_box textarea" placeholder="Describe the image..." maxlength="500" style="width:400px;"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php if(isset($_SESSION['id'])) echo "<span class='text_color' style='font-size:12px;'>Album: </span><select id='album_select'></select>"; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text_color" style="font-size:12px;">NSFW?</span>
                                                        <input type="checkbox" id="nsfw"/>
                                                    </td>
                                                    <td>
                                                        <input type="submit" class="button red_button" value="Upload" id="submit" style="float:right;" />
                                                        <img class="load_gif" id="upload_image_gif" src="http://i.imagepxl.com/site/load.gif" style="display: none;" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                              </div>
                              <div id="url_upload">
                                  <table>
                                        <tbody>
                                            <tr>
                                                <td id="file_input_row" colspan="3">
                                                    <input type="text" class="input_box" placeholder="http://example.com/image.jpg" id="image_input" style="width:300px;"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <textarea id="image_description_url" class="input_box textarea" placeholder="Describe the image..." maxlength="500" style="width:400px;"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php if(isset($_SESSION['id'])) echo "<span class='text_color' style='font-size:12px;'>Album: </span><select id='album_select_url'></select>"; ?>
                                                </td>
                                                <td>
                                                    <span class="text_color" style="font-size:12px;">NSFW?</span>
                                                    <input type="checkbox" id="nsfw"/>
                                                </td>
                                                <td>
                                                    <input type="button" class="button red_button" value="Upload" style="float:right;" onClick="upload_url();"/>
                                                    <img class="load_gif" id="upload_image_url_gif" src="http://i.imagepxl.com/site/load.gif" style="display: none;" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                              </div>
                              <div>
                                    <div id="progress_bar"></div>
                                    <p class="text_color" id="percent_loaded"></p>
                              </div>
                          </td>
                          <td style="vertical-align:top;padding-left:15px;" id="my_albums_unit">
                              <p class="title_color" >My albums:</p>
                              <table style="position:relative;">
                                  <tbody id="albums_tbody">
                                      <?php if(!isset($_SESSION['id'])) echo "<tr><td><p>You must be logged in to create or view albums</p></td></tr>"; ?>
                                  </tbody>
                              </table>
                          </td>
                          <td style="vertical-align:top;" id="album_images_unit">
                                <p class="title_color" style="text-align:center;">Album images:</p>
                                <table style="position:relative;margin:0 auto;">
                                    <tbody>
                                        <tr>
                                            <td id="album_image_unit_1"></td>
                                            <td id="album_image_unit_2"></td>
                                            <td id="album_image_unit_3"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_4"></td>
                                            <td id="album_image_unit_5"></td>
                                            <td id="album_image_unit_6"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_7"></td>
                                            <td id="album_image_unit_8"></td>
                                            <td id="album_image_unit_9"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_10"></td>
                                            <td id="album_image_unit_11"></td>
                                            <td id="album_image_unit_12"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_13"></td>
                                            <td id="album_image_unit_14"></td>
                                            <td id="album_image_unit_15"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_16"></td>
                                            <td id="album_image_unit_17"></td>
                                            <td id="album_image_unit_18"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_19"></td>
                                            <td id="album_image_unit_20"></td>
                                            <td id="album_image_unit_21"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_22"></td>
                                            <td id="album_image_unit_23"></td>
                                            <td id="album_image_unit_24"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_25"></td>
                                            <td id="album_image_unit_26"></td>
                                            <td id="album_image_unit_27"></td>
                                        </tr>
                                        <tr>
                                            <td id="album_image_unit_28"></td>
                                            <td id="album_image_unit_29"></td>
                                            <td id="album_image_unit_30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                          </td>
                      </tr>
                      <tr>
                          <td colspan="3">
                              <table>
                                  <tbody>
                                      <tr>
                                          <td style="vertical-align:top;" id="my_images_unit">
                                                <p class="title_color" style="text-align:left;">My images:</p>
                                                <?php if(!isset($_SESSION['id'])) echo "<p>You must be logged in</p>"; ?>
                                                <table style="position:relative;margin:0 auto;">
                                                    <tbody>
                                                        <tr>
                                                            <td id="uploaded_image_unit_1"></td>
                                                            <td id="uploaded_image_unit_2"></td>
                                                            <td id="uploaded_image_unit_3"></td>
                                                            <td id="uploaded_image_unit_4"></td>
                                                            <td id="uploaded_image_unit_5"></td>
                                                            <td id="uploaded_image_unit_6"></td>
                                                            <td id="uploaded_image_unit_7"></td>
                                                            <td id="uploaded_image_unit_8"></td>
                                                            <td id="uploaded_image_unit_9"></td>
                                                            <td id="uploaded_image_unit_10"></td>
                                                            <td id="uploaded_image_unit_11"></td>
                                                            <td id="uploaded_image_unit_12"></td>
                                                        </tr>
                                                        <tr>
                                                            <td id="uploaded_image_unit_13"></td>
                                                            <td id="uploaded_image_unit_14"></td>
                                                            <td id="uploaded_image_unit_15"></td>
                                                            <td id="uploaded_image_unit_16"></td>
                                                            <td id="uploaded_image_unit_17"></td>
                                                            <td id="uploaded_image_unit_18"></td>
                                                            <td id="uploaded_image_unit_19"></td>
                                                            <td id="uploaded_image_unit_20"></td>
                                                            <td id="uploaded_image_unit_21"></td>
                                                            <td id="uploaded_image_unit_22"></td>
                                                            <td id="uploaded_image_unit_23"></td>
                                                            <td id="uploaded_image_unit_24"></td>
                                                        </tr>
                                                        <tr>
                                                            <td id="uploaded_image_unit_25"></td>
                                                            <td id="uploaded_image_unit_26"></td>
                                                            <td id="uploaded_image_unit_27"></td>
                                                            <td id="uploaded_image_unit_28"></td>
                                                            <td id="uploaded_image_unit_29"></td>
                                                            <td id="uploaded_image_unit_30"></td>
                                                            <td id="uploaded_image_unit_31"></td>
                                                            <td id="uploaded_image_unit_32"></td>
                                                            <td id="uploaded_image_unit_33"></td>
                                                            <td id="uploaded_image_unit_34"></td>
                                                            <td id="uploaded_image_unit_35"></td>
                                                            <td id="uploaded_image_unit_36"></td>
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

          </div>
      </div>
      <?php include('footer.php'); ?>
       <script type="text/javascript">
           function initialize_album_form()
            {
                $('#new_album_input').unbind('keypress').unbind('keydown').unbind('keyup');
                $('#new_album_input').keyup(function(e)
                {
                    var key = (e.keyCode ? e.keyCode : e.which);
                    if(key == '13')
                    {
                        add_new_album();
                        load_albums_list();
                        $(this).val('');
                    }
                });
            }
        </script>
   </body>
</html>