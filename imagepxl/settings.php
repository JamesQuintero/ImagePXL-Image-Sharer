<?php
include('init.php');
include('universal_functions.php');
$allowed="users";
include('security_checks.php');



$data=get_user_data($_SESSION['id'], '');

$ID=$data['user_id'];
$username=$data['username'];
$num_followers=$data['num_followers'];
$description=$data['description'];
$real_name=$data['name'];
$following=$data['following'];
$favorites=$data['favorites'];
$image_views=$data['image_views'];
$profile_views=$data['profile_views'];
$num_images=$data['num_images'];
$last_sign_in=$data['last_sign_in'];
$date_joined=$data['date_joined'];
?>

<!DOCTYPE html>
<html>
   <head>
      <title>Settings</title>
      <?php include('code_header.php'); ?>
        <script type="text/javascript">
            function change_colors()
            {
                <?php
                    $colors=get_user_colors($ID);
                    $colors=explode('|', $colors);
                ?>
                $('.title_color').css('color', '<?php echo $colors[0]; ?>');
                $('.text_color').css('color', '<?php echo $colors[1]; ?>');
            }
            function menu_toggle(num)
            {
                display(num);
                if(num==1)
                {
                    $('#menu_item_1').css({'font-weight': 'bold'});
                    $('#menu_item_2').css({'font-weight': 'normal'});
                }   
                else if(num==2)
                {
                    $('#menu_item_1').css({'font-weight': 'normal'});
                    $('#menu_item_2').css({'font-weight': 'bold'});
                }
            }
            function display(num)
            {
                //displays general
                if(num==1)
                {
                    $('#general_table').show();
                    $('#customize_table').hide();
                }    
                else if(num==2)
                {
                    $('#general_table').hide();
                    $('#customize_table').show();
                }
            }
            function change_real_name()
            {
                $.post('settings_query.php',
                {
                    num:1,
                    real_name: $('#real_name_input').val()
                }, function(output)
                {
                    if(output=='Change successful')
                        display_error(output, 'good_errors');
                    else
                        display_error(output, 'bad_errors');
                });
            }
            function change_description()
            {
                $.post('settings_query.php', 
                {
                    num:3,
                    description: $('#description_input').val()
                }, function(output)
                {
                    if(output=='Change successful')
                        display_error(output, 'good_errors');
                    else
                        display_error(output, 'bad_errors');
                });
            }
            function delete_account()
            {
                $.post('settings_query.php',
                {
                    num:2,
                    password: $('#delete_account_password').val()
                }, function(output)
                {
                    if(output=='Account deleted')
                    {
                        display_error(output, 'good_errors');
                        setTimeout(function(){window.location.replace("http://imagepxl.com");},3000);
                    }
                    else
                        display_error(output, 'bad_errors');
                });
            }


            $(document).ready(function(){
                menu_toggle(1);
                initialize_settings();
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
            include('header.php');
        ?>
      <div class="content">
         <div style="padding:15px;">
             <table style="width:100%;">
                 <tbody>
                     <tr>
                         <td style="vertical-align:top;border-right:1px solid gray;padding-right:15px;width:150px;">
                             <table id="settings_menu_table">
                                 <tbody>
                                     <tr>
                                         <td>
                                             <table>
                                                 <tbody>
                                                     <tr>
                                                         <td>
                                                             <img class="icon" src="http://i.imagepxl.com/site/icons/general_settings.png"/>
                                                         </td>
                                                         <td>
                                                             <p class="title_color username" id="menu_item_1" onClick="menu_toggle(1);">General</p>
                                                         </td>
                                                     </tr>
                                                 </tbody>
                                             </table>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td>
                                             <table>
                                                 <tbody>
                                                     <tr>
                                                         <td>
                                                             <img class="icon" src="http://i.imagepxl.com/site/icons/customize_settings.png"/>
                                                         </td>
                                                         <td>
                                                             <p class="title_color username" id="menu_item_2" onClick="menu_toggle(2);">Customize</p>
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
                             <table id="general_table" style="width:100%;padding-left:15px;">
                                <tbody>
                                   <tr>
                                       <td>
                                           <p class="text_color" >Real Name: </p>
                                       </td>
                                       <td style="float:right;">
                                           <input class="input_box" type="text" placeholder="EX: Joe Parker" id="real_name_input" maxlength="255" value="<?php echo $real_name; ?>" />
                                       </td>
                                   </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr class="break"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text_color">Profile description: </p>
                                        </td>
                                        <td style="float:right">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <textarea class="input_box" placeholder="Description..." id="description_input" maxlength="255"><?php echo $description; ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:right;">
                                                            <input class="button red_button" type="button" value="Change" onClick="change_description();"/>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr class="break"/>
                                        </td>
                                    </tr>
                                    <tr >
                                        <td>
                                           <p class="text_color" >Delete account: </p>
                                       </td>
                                       <td style="float:right;">
                                           <p class="text_color">
                                               <span class="text_color">Password</span>
                                               <input type="password" placeholder="password..." id="delete_account_password" class="input_box"/>
                                           </p>
                                           <input class="button red_button" type="button" id="delete_account_button" value="Delete" onClick="delete_account();" style="float:right;"/>
                                       </td>
                                    </tr>
                                </tbody>
                             </table>
                             <table id="customize_table" style="width:100%;padding-left:15px;">
                                 <tbody>
                                     
                                 </tbody>
                             </table>
                         </td>
                     </tr>
                 </tbody>
             </table>
            
         </div>
      </div>
      <?php include('footer.php'); ?>
   </body>
    <script type="text/javascript">
       function initialize_settings()
        {
            $('#real_name_input').unbind('keypress').unbind('keydown').unbind('keyup');
            $('#real_name_input').keyup(function(e)
            {
                var key = (e.keyCode ? e.keyCode : e.which);

                //right arrow
                if(key == '13')
                    change_real_name();
            });
        }
    </script>
</html>