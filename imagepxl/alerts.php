<?php
@include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $_SESSION['username']; ?> alerts</title>
        <?php include('code_header.php'); ?>
        <script type="text/javascript">
            function change_color()
            {
                $('.title_color').css('color', 'rgb(220,20,0)');
                $('.text_color').css('color', 'rgb(30,30,30)');
            }
            
            function display_alerts(page)
            {
                $.post('alerts_query.php',
                {
                    num:1,
                    page: page,
                    timezone: get_timezone()
                }, function(output)
                {
                    var image_ids=output.image_ids;
                    var image_exts=output.image_exts;
                    var comment_ids=output.comment_ids;
                    var comments=output.comments;
                    var profile_pictures=output.profile_pictures;
                    var usernames=output.usernames;
                    var image_usernames=output.image_usernames;
                    var num_likes=output.num_likes;
                    var num_dislikes=output.num_dislikes;
                    var has_liked=output.has_liked;
                    var has_disliked=output.has_disliked;
                    var comment_timestamps=output.comment_timestamps;
                    var comment_user_ids=output.comment_user_ids;
                    var descriptions=output.descriptions;
                    
                    var html="";
                    for(var x = 0; x < image_ids.length; x++)
                    {
                        var image_preview="<a class='link' href='http://imagepxl.com/"+image_ids[x]+"' ><img class='comment_profile_picture' src='http://i.imagepxl.com/"+image_usernames[x]+"/thumbs/"+image_ids[x]+"."+image_exts[x]+"' /></a>";
//                        var image_description="<span class='text_color' style='font-size:12px;padding:5px;'>"+descriptions[x]+"</span>";
                        
                        
                        
                        var name="<a class='link title_color' href='http://imagepxl.com/user/"+usernames[x]+"'><span class='title_color' style='font-size:12px;'>"+usernames[x]+"</span></a>";
                        var comment="<p class='text_color' style='font-size:12px;'>"+(convert_image(comments[x], image_ids[x], comment_ids[x]))+"</p>";
                        var timestamp="<span class='text_color' style='font-size:12px;margin-left:10px;'>("+comment_timestamps[x]+")</span>";

                       if(<?php if(isset($_SESSION['id'])) echo "true"; else echo "false"; ?>==true&&comment_user_ids[x]==<?php if(isset($_SESSION['id'])) echo $_SESSION['id']; else echo "0"; ?>)
                           var delete_text="<span id='comment_delete_"+comment_ids[x]+"' class='username' style='color:rgb(220,20,0);font-size:12px;margin-left:10px;border:1px solid gray;padding:2px;border-radius:3px;background-color:whitesmoke;' >Delete</span>";
                       else
                           var delete_text="";

                       var bottom_menu="<table style='border-spacing:0px;'><tbody><tr><td style='border-right:1px solid gray;' id='comment_like_unit_"+image_ids[x]+"_"+comment_ids[x]+"' ></td><td id='comment_dislike_unit_"+image_ids[x]+"_"+comment_ids[x]+"' ></td><td style='padding-left:5px;padding-right:5px;' id='comment_points_unit_"+image_ids[x]+"_"+comment_ids[x]+"' ></td><td>"+delete_text+"</td></tr></tbody></table>";
                        var comment_right_unit="<table style='border-spacing:0px;'><tbody><tr><td>"+name+timestamp+"</td></tr><tr><td >"+comment+"</td></tr><tr><td >"+bottom_menu+"</td></tr></tbody></table>";
                        var comment_left_unit="<a class='link' href='http://imagepxl.com/user/"+usernames[x]+"' ><img class='comment_profile_picture' src='"+profile_pictures[x]+"'/></a>";
                        var comment_table="<div id='comment_body_"+comment_ids[x]+"' class='comment_body_alert' ><table><tbody><tr><td>"+comment_left_unit+"</td><td>"+comment_right_unit+"</td></tr></tbody></table></div>";
                        
                        
                        var left_table="<table><tbody><tr><td>"+image_preview+"</td></tr></tbody></table>";
                        var table="<table style='width:100%;' class='comment_body' ><tbody><tr><td style='width:50px;'>"+left_table+"</td><td >"+comment_table+"</td></tr></tbody></table>";
                        
                        html="<tr><td >"+table+"</td></tr>"+html;
                    }
                    $('#new_comments_tbody').html(html);
                    
                    for(var x = 0; x < comment_ids.length; x++)
                    {
//                        $('#comment_body_'+comment_ids[x]).attr('onClick', "window.location.replace('http://imagepxl.com/"+image_ids[x]+"');");
                        
                        set_comment_functions(image_ids[x], comment_ids[x], num_likes[x], num_dislikes[x], has_liked[x], has_disliked[x]);
                        var image=$('#convert_image_'+image_ids[x]+'_'+comment_ids[x]);
                        image.attr('onClick', "display_comment_image('"+(image.html())+"');");
                    }
                    
                    change_color();
                }, "json");
            }


            $(document).ready(function(){
                change_color();
                display_alerts(1);
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
        <div class="content" style="margin-top:0px;">
            <div style="padding:30px;" >
                <table style="width:100%;">
                    <tbody id="new_comments_tbody">

                    </tbody>
                </table>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>