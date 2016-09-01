<?php
include('init.php');
include('universal_functions.php');
$allowed="all";
include("security_checks.php");

?>

<!DOCTYPE html>
<html>
   <head>
      <title>User Agreement</title>
      <?php include('code_header.php'); ?>
      <script type="text/javascript">
         function change_color()
         {
            $('.title_color').css('color', 'rgb(220,20,0)');
            $('.text_color').css('color', 'rgb(30,30,30)');
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
        <div style="padding:30px;">
            <p class="text_color" style="padding-bottom:10px;">Last revised September 9, 2013</p>
            <p class="title_color" style="font-weight:bold;font-size:16px;">User Agreement</p>
            <p class="text_color">The following User Agreement ("agreement") governs the use of imagepxl.com ("site" or "we") as provided by ImagePXL. By accessing any page on imagepxl.com, you agree to these terms.</p>
            <p class="text_color">ImagePXL reserves the right to terminate and/or suspend access to the site without notice. </p>
            <p class="text_color" style="padding-bottom:10px;">ImagePXL is not responsible, in any way, for any illegal activities you perform on or by using the site. </p>
            <p class="title_color" style="font-weight:bold;">Usage: </p>
            <p class="text_color">You are responsible for all usage or activity on your account including, but not limited to, use of the account by any person, with or without authorization, or who has access to any computer on which your account resides or is accessible.</p>
            <p class="text_color">You must be 13 years or older to create an account. You must provide an email, username, and a password when registering. </p>
            <p class="text_color">You agree to not upload any images that may be copyrighted by someone other than you. You also agree to not upload gore, nude images, advertising, solicitations, "hate speech" (i.e. demeaning race, gender, age, religious or sexual orientation, etc.), or material that is threatening, harassing, defamatory, or that encourages illegality. Don’t hotlink to such content, or to file-sharing sites. </p>
            <p class="text_color" style="padding-bottom:10px;">You agree to not interfere, attack, disrupt the site's hardware, servers, or users in any way.</p>
            <p class="title_color" style="font-weight:bold;">Deletions:</>
            <p class="text_color" style="padding-bottom:10px;">ImagePXL reserves the right to delete any material provided for display or placed on the site without notice.</p>
            <p class="title_color" style="font-weight:bold;">Additional rules:</p>
            <p class="text_color">ImagePXL reserves the right to post additional rules of usage that apply to specific parts of the site. Your continued use of imagepxl.com constitutes your agreement to comply with these additional rules. Rules will be active at time of posting.</p>
        </div>
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>