<?php
@include('init.php');
include('universal_functions.php');
$allowed="all";
include('security_checks.php');

$value=clean_string(str_replace('"', '', str_replace("'", "", $_GET['search'])));
$content=strtolower(clean_string($_GET['content']));
$type=strtolower(clean_string($_GET['type']));
$sort=strtolower(clean_string($_GET['sort']));

//cleanses parameters
if($content!='images'&&$content!='users'&&$content!='albums')
    $content="images";

if($type!="description"&&$type!='username'&&$type!="real_name")
    $type="description";

if($sort!='1')
    $sort=0;
else
    $sort=1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
      <title>Search imagePXL</title>
      <?php include('code_header.php'); ?>
      <script type="text/javascript">
         function change_color()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
         }
         function display_options()
         {
             var type=$('#type_select_box').val();
             if(type=='images')
             {
                 var html="<option value='description' <?php if($type=='description') echo "selected='selected'"; ?>>Description</option>";
                 $('#param_select_box').html(html);
                 $('#sort_by').show();
             }
             else if(type=='users')
             {
                 var html="<option value='description' <?php if($type=='description') echo "selected='selected'"; ?>>Description</option>";
                 html+="<option value='username' <?php if($type=='username') echo "selected='selected'"; ?>>Username</option>";
                 html+="<option value='real_name' <?php if($type=='real_name') echo "selected='selected'"; ?>>Real Name</option>";
                 $('#param_select_box').html(html);
                 $('#sort_by').show();
             }
             else if(type=='albums')
             {
                 var html="<option value='name' <?php if($type=='name') echo "selected='selected'"; ?>>Name</option>";
                 $('#param_select_box').html(html);
                 $('#sort_by').hide();
             }
         }
        function display_results(page)
        {
            if(page<1)
                page=1;
            
            var type=$('#type_select_box').val();
            $.post('search_query.php',
            {
                num:1,
                type: type,
                param_type: $('#param_select_box').val(),
                page: page,
                param: $('#search_input').val(),
                timezone: get_timezone(),
                sort: $('#sort_by').val()
            }, function(output)
            {
                
                if(type=='images')
                {
                    var image_ids=output.image_ids;
                    var descriptions=output.descriptions;
                    var image_exts=output.exts;
                    var user_ids=output.user_ids;
                    var usernames=output.usernames;
                    var timestamps=output.timestamps;
                    var album_ids=output.album_ids;
                    var has_liked=output.has_liked;
                    var has_disliked=output.has_disliked;
                    var num_likes=output.num_likes;
                    var num_dislikes=output.num_dislikes;
                    var comment_ids=output.comment_ids;
                    var num_favorites=output.num_favorites;
                    var num_views=output.num_views;
                }
                else if(type=='users')
                {
                    var user_ids=output.user_ids;
                    var usernames=output.usernames;
                    var num_followers=output.num_followers;
                    var is_following=output.is_following;
                    var names=output.names;
                    var num_images=output.num_images;
                    var image_views=output.image_views;
                    var descriptions=output.descriptions;
                    var profile_pictures=output.profile_pictures;
                }
                else if(type=='albums')
                {
                    var album_ids=output.album_ids;
                    var album_names=output.album_names;
                    var user_ids=output.user_ids;
                    var usernames=output.usernames;
                    var timestamps=output.timestamps;
                    var updated=output.updated;
                    var num_images=output.num_images;
                    var album_thumbnails=output.album_thumbnails;
                    var album_thumbnail_exts=output.album_thumbnail_exts;
                }
                
                if(user_ids.length!=0)
                {
                    var index=0;
                    var html="";
                    for(var x = 0; x < (user_ids.length/3); x++)
                    {
                        var table="";
                        for(var y = 0; y < 3; y++)
                        {
                            if(user_ids[index]!=undefined)
                            {
                                //displays images
                                if(type=='images')
                                {
                                    var description_html="<p class='text_color' style='font-size:12px;'>"+descriptions[index]+"</p>";
                                    var name_html="<a class='link title_color' href='http://imagepxl.com/user/"+usernames[index]+"'><span class='title_color username'>"+usernames[index]+"</span></a>";
                                    var num_views_html="<p class='text_color' style='font-size:12px;'>"+num_views[index]+" views</p>";

                                    var image_info="<table ><tbody><tr><td>"+name_html+"</td></tr><tr><td>"+description_html+"</td></tr><tr><td>"+num_views_html+"</td></tr></tbody></table>";

                                    var left_function="<div id='like_unit_"+image_ids[index]+"' class='left_function_disabled'></div>";
                                    var middle_function="<div id='points_unit_"+image_ids[index]+"' class='middle_function_disabled' ></div>";
                                    var right_function="<div id='dislike_unit_"+image_ids[index]+"' class='middle_function_disabled'></div>";
                                    var favorite_function="<div id='favorite_unit_"+image_ids[index]+"' class='right_function_disabled'></div>";

                                    var image_functions="<table class='functions_table' style='width:100%;'><tbody><tr><td class='left_functions_unit' style='display:inline-block' >"+left_function+"</td><td class='middle_functions_unit' style='display:inline-block' >"+right_function+"</td><td class='middle_functions_unit' style='display:inline-block' >"+middle_function+"</td><td class='right_functions_unit' style='display:inline-block' >"+favorite_function+"</td></tr></tbody></table>";
                                    var timestamp="<p class='text_color' style='font-size:12px;' >"+timestamps[index]+"</p>";
                                    var image_info="<table style='height:100%;' class='image_info_table' ><tbody><tr><td style='vertical-align:top;'>"+image_info+"</td></tr><tr><td style='vertical-align:bottom'>"+timestamp+image_functions+"</td></tr></tbody></table>";

                                    var image="<a class='link' href='http://imagepxl.com/"+image_ids[index]+"' ><img class='image_preview' src='http://i.imagepxl.com/"+usernames[index]+"/thumbs/"+image_ids[index]+"."+image_exts[index]+"' /></a>";
                                    table+="<td style='width:33%;'><table style='width:100%;'><tbody><tr><td style='width:150px;'>"+image+"</td><td style='display:inline-table;height:100%;width:100%;' >"+image_info+"</td></tr></tbody></table></td>";
                                }

                                //displays users
                                else if(type=='users')
                                {
                                    var description_html="<p class='text_color' style='font-size:12px;'>"+descriptions[index]+"</p>";
                                    var name_html="<a class='link title_color' href='http://imagepxl.com/user/"+usernames[index]+"'><span class='title_color username'>"+usernames[index]+"</span></a>";
                                    
                                    var num_followers_html="<p class='text_color' style='font-size:12px;'>"+num_followers[index]+" followers</p>";
                                    var num_views_html="<p class='text_color' style='font-size:12px;'>"+image_views[index]+" image views</p>";

                                    var image_info="<table ><tbody><tr><td>"+name_html+"</td></tr><tr><td>"+description_html+"</td></tr><tr><td>"+num_followers_html+"</td></tr><tr><td>"+num_views_html+"</td></tr></tbody></table>";


                                    var image_info="<table style='height:100%;' class='image_info_table' ><tbody><tr><td style='vertical-align:top;'>"+image_info+"</td></tr></tbody></table>";

                                    var image="<a class='link' href='http://imagepxl.com/user/"+usernames[index]+"' ><img class='image_preview' src='"+profile_pictures[index]+"' /></a>";
                                    table+="<td style='width:33%;'><table style='width:100%;'><tbody><tr><td style='width:150px;'>"+image+"</td><td style='display:inline-table;height:100%;width:100%;' >"+image_info+"</td></tr></tbody></table></td>";
                                }
                                
                                //displays albums
                                else if(type=='albums')
                                {
                                    var album_name="<a class='link title_color' href='http://imagepxl.com/album/"+album_ids[index]+"'><p class='title_color'>"+album_names[index]+"</p></a>";
                                    var name_html="<span class='text_color' style='font-size:12px;'>by </span><a class='link title_color' href='http://imagepxl.com/user/"+usernames[index]+"'><span class='title_color username' style='font-size:12px;'>"+usernames[index]+"</span></a>";
                                    var num_images_html="<p class='text_color' style='font-size:12px;'>"+num_images[index]+" images</p>";
                                    var image_info="<table ><tbody><tr><td>"+album_name+"</td></tr><tr><td>"+name_html+"</td></tr><tr><td>"+num_images_html+"</td></tr></tbody></table>";
                                    var image_info="<table style='height:100%;' class='image_info_table' ><tbody><tr><td style='vertical-align:top;'>"+image_info+"</td></tr></tbody></table>";
                                    
                                    
                                    //gets album thumbnails
                                    var album_images="";
                                    var top=0;
                                    var left=0;
                                    var outside_width=100;
                                    for(var z = 0; z < 5; z++)
                                    {
                                        if(album_thumbnails[index][z]!=undefined&&album_thumbnails[index][z]!='')
                                        {
                                           album_images+="<img class='album_thumbnail_image' style='top:"+top+"px;left:"+left+"px;width:100px;' src='http://i.imagepxl.com/"+usernames[index]+"/thumbs/"+album_thumbnails[index][z]+"."+album_thumbnail_exts[index][z]+"'/>";
                                           top+=5;
                                           left+=5;
                                           outside_width+=5;
                                        }
                                        else
                                        {
                                            //adds 'no image' thumbnail
                                            if(z==0)
                                               album_images+="<img class='no_image' style='width:100px;' src='http://i.imagepxl.com/site/no_image.jpg' />";
                                            else
                                                album_images+="";
                                        }
                                    }
                                    
                                    table+="<td style='width:33%;'><table style='width:100%;'><tbody><tr><td style='width:150px;'> <div class='album_thumbnail_outside' id='album_thumbnail_outside_"+x+"' style='width:"+outside_width+"px;height:"+outside_width+"px;'>"+album_images+"</div></td><td style='display:inline-table;height:100%;width:100%;' >"+image_info+"</td></tr></tbody></table></td>";
                                }
                                
                            }
                            
                            index++;
                        }
                        
                        html+="<tr>"+table+"</tr>";
                    }
                    $('#search_results_table_body').html(html);

                    if(type=='images')
                    {
                        for(var x = 0; x < image_ids.length; x++)
                            set_functions(image_ids[x], num_likes[x], num_dislikes[x], has_liked[x], has_disliked[x]);
                    }
                }
                else
                    $('#search_results_table_body').html("<tr><td><p class='text_color'>No results</p></td></tr>");
                
                change_color();
            }, "json");
        }
        
        function search()
        {
            var search=$('#search_input').val();
            var content=$('#type_select_box').val();
            var type=$('#param_select_box').val();
            var sort=$('#sort_by').val();
            window.location.replace("http://imagepxl.com/search.php?search="+search+"&&content="+content+"&&type="+type+"&&sort="+sort);
        }
         
         $(document).ready(function(){
         display_options();
         display_results(1);
         initialize_search();
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
      <?php if(isset($_SESSION['id'])) include('header.php'); else include('index_header.php'); ?>
      <div class="content" style="margin-top:20px;padding:10px;">
          
          <table style="width:100%;">
              <tbody>
                  <tr>
                      <td>
                          <table id="search_menu_table">
                              <tbody>
                                  <tr>
                                      <td id="type_unit">
                                          <select id="type_select_box" onChange="display_options();">
                                              <option value="images" <?php if($content=='images') echo "selected='selected'"; ?>>Images</option>
                                              <option value="users" <?php if($content=='users') echo "selected='selected'"; ?>>Users</option>
                                              <option value="albums" <?php if($content=='albums') echo "selected='selected'"; ?>>Albums</option>
                                          </select>
                                      </td>
                                      <td id="param1_unit">
                                          <select id="param_select_box" >
                                              
                                          </select>
                                      </td>
                                      <td id="sort_by_unit">
                                          <select id="sort_by" >
                                              <option value="0" <?php if($sort==0) echo "selected='selected'"; ?>>Popularity</option>
                                              <option value="1" <?php if($sort==1) echo "selected='selected'"; ?>>Most Recent</option>
                                          </select>
                                      </td>
                                      <td>
                                          <input class="input_box" id="search_input" placeholder="Search..." maxlength="500" value="<?php echo $value; ?>" />
                                      </td>
                                      <td>
                                          <input class="button red_button" id="search_button" value="Search" onClick="search();" type="button"/>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <table id="search_results_table">
                              <tbody id="search_results_table_body">
                                  
                              </tbody>
                          </table>
                      </td>
                  </tr>
              </tbody>
          </table>
         
      </div>
      <?php include('footer.php'); ?>
   </body>
    <script type="text/javascript">
       function initialize_search()
        {
            $('#search_input').unbind('keypress').unbind('keydown').unbind('keyup');
            $('#search_input').keyup(function(e)
            {
                var key = (e.keyCode ? e.keyCode : e.which);

                //right arrow
                if(key == '13')
                {
                    search();
                }
            });
        }
    </script>
</html>