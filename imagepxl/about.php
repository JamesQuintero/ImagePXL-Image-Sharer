<?php
include('init.php');
include('universal_functions.php');
?>

<!DOCTYPE html>
<html>
   <head>
      <title>About ImagePXL</title>
      <?php include('code_header.php'); ?>
      <script type="text/javascript">
         function change_color()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(100,100,100)');
         }
        
         
         $(document).ready(function(){
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
      <div class="content" >
            <table>
                <tbody>
                    <tr>
                        <td colspan="2" style="padding:30px;padding-bottom:0px;">
                            <p class="title_color title" >About</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left:30px;padding-right:30px;">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding:10px;padding-left:0px;" >
                                            <img class="about_image" src="http://i.imagepxl.com/site/about/upload_images.png" style="border:none;"/>
                                        </td>
                                        <td style="padding:10px;" >
                                            <p class="text_color">Upload an unlimited amount of High Definition images. You can also do this without needing to create an account.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px;padding-top:20px;padding-left:30px;text-align:center;border-top:1px solid gray;background-color:whitesmoke" colspan="2">
                            <img class="about_image" src="http://i.imagepxl.com/site/about/profile_customization.png" style="max-width:900px;width:900px;border:none;"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border-bottom:1px solid gray;padding:10px;padding-bottom:30px;padding-right:30px;background-color:whitesmoke" colspan="2">
                            <p class="text_color">Customize your profile to represent you. You can change the theme, and upload a banner. More features coming soon!</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px;padding-left:30px;border-bottom:1px solid gray;" >
                            <img class="about_image" src="http://i.imagepxl.com/site/about/follow_others2.png"/>
                        </td>
                        <td style="padding:10px;padding-right:30px;border-bottom:1px solid gray;" >
                            <p class="text_color">Follow your favorite uploaders. Their most recently uploads will be displayed in your home feed.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;text-align:center;padding-top:20px;background-color:whitesmoke" colspan="2">
                            <img class="about_image" src="http://i.imagepxl.com/site/about/search_images.png" style="border:none;"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;border-bottom:1px solid gray;padding-bottom:20px;text-align:center;background-color:whitesmoke" colspan="2" >
                            <p class="text_color">Easily search for specific images, users, or albums. You can search for users by their description, username, or real name.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px;padding-top:20px;padding-bottom:20px;padding-left:30px;border-bottom:1px solid gray;background-color:whitesmoke;" >
                            <img class="about_image" src="http://i.imagepxl.com/site/about/viral_images.png" style="border:none;"/>
                        </td>
                        <td style="padding:10px;padding-right:30px;border-bottom:1px solid gray;background-color:whitesmoke" >
                            <p class="text_color">View the most viral images. You will be able to browse the top 100 most popular images or the 100 most followed people!</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;padding-left:0px;padding-top:20px;text-align:center;" colspan="2">
                            <img class="about_image" src="http://i.imagepxl.com/site/about/social_media_large.png" style="border:none;width:250px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;padding-bottom:20px;text-align:center;" colspan="2">
                            <p class="text_color">Quickly and easily share your image to the most popular social networks. This includes Reddit, Twitter, and Facebook. More options coming soon!</p>
                        </td>
                    </tr>
                </tbody>
            </table>
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>