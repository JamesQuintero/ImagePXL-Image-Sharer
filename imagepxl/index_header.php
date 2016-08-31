<div id="window_background" ></div>
<div id="header">
   <div id="header_body">
       <table style="width:100%;height:100%;">
           <tbody>
               <tr>
                   <td>
                       <a class="link" href="http://imagepxl.com"><img class="icon_picture" src="http://i.imagepxl.com/site/imagepxl.png" /></a>
                   </td>
                    <td>
                        <table class="header_table" style="float:right;">
                            <tbody>
                                <tr>
                                    <td class="left_header_button" onClick="window.location.replace('http://imagepxl.com/viral');" >
                                        <table style="margin:0 auto;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img class="icon" src="http://i.imagepxl.com/site/icons/viral_icon.png" />
                                                    </td>
                                                    <td>
                                                        <p class="header_button_text">Viral</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                     </td>
                                     <td class="header_button" onClick="display_login();" >
                                        <table style="margin:0 auto;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p class="header_button_text">Login</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                     </td>
                                     <td class="right_header_button" onClick="display_login();" >
                                        <table style="margin:0 auto;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p class="header_button_text">Register</p>
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
   </div>
</div>
<div class="tool_tip"></div>
<div id="alert_box_body">
    <div class="alert_box box">
        <div class="alert_box_inside">
            
        </div>
    </div>
</div>
<div id="errors" ></div>
<div id="dim" onClick="close_alert_box();"></div>
<script type="text/javascript">
    
    //does message stuff
    $(window).ready(function()
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
    });
    
</script>