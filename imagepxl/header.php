<div id="window_background" ></div>
<div id="header">
   <div id="header_body">
       <table style="width:100%;height:100%;">
           <tbody>
               <tr>
                   <td>
                       <a class="link" href="http://imagepxl.com"><img class="icon_picture" src="http://i.imagepxl.com/site/imagepxl.png" id="logged_in_icon"/></a>
                   </td>
                   <td>
                       <input class="input_box search_input_top" id="search_input_top" placeholder="Search for images or users..." maxlength="500" />
                   </td>
                   <td>
                       <table>
                           <tbody>
                               <tr>
                                   <td>
                                       <table class="left_header_button" style="width: auto;" onClick="window.location.replace('http://imagepxl.com/alerts');">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="http://i.imagepxl.com/site/icons/comment_icon.png" class="icon" id="comment_icon">
                                                    </td>
                                                    <td id="new_comment_header_unit">

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                   </td>
                                   <td>
                                       <a class="link title_color" href="http://imagepxl.com/upload">
                                           <div class="right_header_button" style="height: 24px;margin-left: -3px;">
                                                <p style="font-size:12px;margin:5px;" class="title_color">Upload</p>
                                            </div>
                                       </a>
                                   </td>
                               </tr>
                           </tbody>
                       </table>
                        
                            
                        
                   </td>
                   <td>
                       <table id="header_table">
                            <tbody>
                               <tr>
                                  <td class="left_header_button"  onClick="window.location.replace('http://imagepxl.com/home');" >
                                      <table>
                                          <tbody>
                                              <tr>
                                                  <td>
                                                      <img class="icon" src="http://i.imagepxl.com/site/icons/home_icon.png" />
                                                  </td>
                                                  <td>
                                                      <span class="header_button_text">Home</span>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </td>
                                  <td class="header_button" style="border-right:1px solid black" onClick="window.location.replace('http://imagepxl.com/user/<?php echo get_username($_SESSION['id']); ?>');" >
                                      <table>
                                          <tbody>
                                              <tr>
                                                  <td>
                                                      <img class="icon" src="http://i.imagepxl.com/site/icons/profile_icon.png" />
                                                  </td>
                                                  <td>
                                                      <p class="header_button_text">Profile</p>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </td>
                                  <td class="header_button" style="border-right:1px solid black" onClick="window.location.replace('http://imagepxl.com/viral');" >
                                      <table>
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
                                  <td class="header_button" onClick="window.location.replace('http://imagepxl.com/settings');" >
                                      <table>
                                          <tbody>
                                              <tr>
                                                  <td>
                                                      <img class="icon" src="http://i.imagepxl.com/site/icons/settings_icon.png" />
                                                  </td>
                                                  <td>
                                                      <p class="header_button_text">Settings</p>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </td>
                                  <td class="right_header_button"  onClick="window.location.replace('http://imagepxl.com/logout.php');" >
                                      <table>
                                          <tbody>
                                              <tr>
                                                  <td>
                                                      <img class="icon" src="http://i.imagepxl.com/site/icons/logout_icon.png" />
                                                  </td>
                                                  <td>
                                                      <p class="header_button_text">Logout</p>
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
        var url="./alerts_query.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../alerts_query.php"
                }});
            
        $.post(url, {
            num:2
        }, function(output)
        {
            var new_comments=output.new_comments;
            
            if(new_comments>0)
            {
                $('#comment_icon').attr('src', "http://i.imagepxl.com/site/icons/comment_icon2.png");
                $('#new_comment_header_unit').html("<span class='text_color' style='font-size:11px;'>("+new_comments+")</span>");
            }
            
        }, "json");
    });
    
//    function reset_message_title()
//    {
//        document.title="Messages";
//    }
</script>