<?php
include('init.php');

include('universal_functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
      <title>DMCA</title>
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
      <style>
          p{margin-bottom:15px;}
          li{font-size:14px;}
      </style>
      <script type="text/javascript">
        <?php include('required_google_analytics.js'); ?>
      </script>
   </head>
   <body>
      <?php if(isset($_SESSION['id'])) include('header.php'); else include('index_header.php'); ?>
      <div class="content" style="margin-top:0px;">
        <div style="padding:30px;">
            <p class="text_color" style="padding-bottom:10px;">Last revised September 27, 2013</p>
            <p class="title_color" style="font-weight:bold;font-size:16px;">Intellectual Property</p>
            <p class="text_color">By uploading a file or other content or by making a comment, you represent and warrant to us that (1) doing so does not violate or infringe anyone else’s rights; and (2) you created the file or other content you are uploading, or otherwise have sufficient intellectual property rights to upload the material consistent with these terms. With regard to any file or content you upload to the public portions of our site, you grant ImagePXL a non-exclusive, royalty- free, perpetual, irrevocable worldwide license (with sublicense and assignment rights) to use, to display online and in any present or future media, to create derivative works of, to allow downloads of, and/or distribute any such file or content. To the extent that you delete a such file or content from the public portions of our site, the license you grant to ImagePXL pursuant to the preceding sentence will automatically terminate, but will not be revoked with respect to any file or content ImagePXL has already copied and sublicensed or designated for sublicense. Also, of course, anything you post to a public portion of our site may be used by the public pursuant to the following paragraph even after you delete it.</p> 
            <p class="text_color">By downloading a file or other content from the ImagePXL site, you agree that you will not use such file or other content except for personal, non-commercial purposes, and you may not claim any rights to such file or other content, except to the extent otherwise specifically provided in writing.</p>
            <p class="text_color">NOTICES OF CLAIMED COPYRIGHT INFRINGEMENT</p>
            <p class="text_color">If you see anything on our site that you believe infringes your copyright rights, you may notify our Digital Millennium Copyright Act ("DMCA") agent by sending the following information:</p>
            <ul>
                <li>Identification of the copyrighted work or works claimed to have been infringed;</li>
                <li>Identification of the material on our servers that is claimed to be infringing and that is to be
                removed, including the URL or other information to enable us to locate the material;</li>
                <li>A statement that you have a good faith belief that use of the material in the manner
                complained of is not authorized by you as copyright owner, or by your agent, or by law;</li>
                <li>A statement that the information in your notice is accurate, and under penalty of perjury, that
                you are the owner (or authorized to act on behalf of the owner) of the exclusive copyright
                right that is allegedly being infringed.</li>
                <li>Your physical or electronic signature, or of someone authorized to act on your behalf;</li>
                <li>Instructions on how we may contact you: preferably email, but also address and phone</li>
            </ul>       

            <p class="text_color">Our agent to receive such notifications of claimed infringement is James Quintero.</p>
            <p class="text_color" style="margin-bottom:0px;" >Email: dmca@imagepxl.com</p>
            <p class="text_color">Mailing Address: Please email for request of the mailing address.</p>
            <p class="text_color">Use the same procedure for any claimed trademark violations or other infringements. If we receive a DMCA notice and remove something you posted anonymously, we will have no way of notifying you, so you will have to contact us if you think that may have happened. Keep in mind that we reserve the right to remove any content at any time whether or not it infringes or violates any of our policies.</p>
        </div>
      </div>
      <?php include('footer.php'); ?>
   </body>
</html>