function login()
{
    $('#login_load').show();
    
    var url="./login.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../login.php"
            }});
    
   
    $.post(url,
    {
        username: $('#login_username').val(),
        password: $('#login_password').val()
    },
    function (output)
    {
        $('#login_load').hide();
        if(output=='')
            window.location.replace(window.location);
        else
           display_error(output, 'bad_errors');
    });
}
function alert_login()
{
    $('#login_load').show();
    
    var url="./login.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../login.php"
            }});
    
    $.post(url,
    {
        username: $('#alert_login_username').val(),
        password: $('#alert_login_password').val()
    },
    function (output)
    {
        $('#login_load').hide();
        if(output=='')
            window.location.replace(window.location);
        else
           display_error(output, 'bad_errors');
    });
}
function get_timezone()
{
    var date = new Date()
    var timezone = date.getTimezoneOffset();
    return timezone;
}
function display_error(error, type)
{
    $('#errors').css('opacity', 0).html(error).attr('class', type).show();
    $('#errors').stop().animate({
        opacity :1
    }, 500, function()
    {
        setTimeout(function()
        {
            $('#errors').stop().animate({
                opacity:0
            }, 500, function()
            {
                $('#errors').html('').hide();
            });
        }, 5000);
    });
}
//id is id of element and string is message
function display_title(id, string)
{
    $(id).mousemove(function(e)
    {
        if($('.tool_tip').css('opacity')<'0.8')
        {
            $('.tool_tip').html("<p class='tool_tip_text'>"+string+"</p>").css({'left': (e.pageX-50), 'top': (e.pageY+20)});
            $('.tool_tip').stop().animate({
                opacity: 1
            }, 100, function()
            {});
        }
        else
            $('.tool_tip').css({'left': (e.pageX-50), 'top': (e.pageY+20)});
    });
}
function name_over(string)
{
    $(string).css({'text-decoration': 'underline'});
}
function name_out(string)
{
    $(string).css('text-decoration', 'none');
}
function text_format(text)
{
    var final_text=text;
    
    //continue until everything is converted
    while(1==1)
    {
        if(final_text.toLowerCase().indexOf('[b](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[b](');
            
            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+4;
            var temp=final_text.substring(start);
            
            
            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');
                
                
                var body_text=final_text.substring(start, end);
                
                var front=final_text.substring(0,prev_index);
                var middle="<span style='font-weight:bold;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='font-weight:bold;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[i](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[i](');
            
            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+4;
            var temp=final_text.substring(start);
            
            
            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');
                
                
                var body_text=final_text.substring(start, end);
                
                var front=final_text.substring(0,prev_index);
                var middle="<span style='font-style:italic;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='font-style:italic;'>"+body_text+"</span>";
            }
        }
        
        else if(final_text.toLowerCase().indexOf('[u](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[u](');
            
            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+4;
            var temp=final_text.substring(start);
            
            
            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');
                
                
                var body_text=final_text.substring(start, end);
                
                var front=final_text.substring(0,prev_index);
                var middle="<span style='text-decoration:underline;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='text-decoration:underline;'>"+body_text+"</span>";
            }
        }
            
        else if(final_text.toLowerCase().indexOf('[s](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[s](');
            
            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+4;
            var temp=final_text.substring(start);
            
            
            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');
                
                
                var body_text=final_text.substring(start, end);
                
                var front=final_text.substring(0,prev_index);
                var middle="<span style='font-size:75%;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='font-size:75%;'>"+body_text+"</span>";
            }
        }
        
        else if(final_text.toLowerCase().indexOf('[box](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[box](');
            
            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+6;
            var temp=final_text.substring(start);
            
            
            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');
                
                
                var body_text=final_text.substring(start, end);
                
                var front=final_text.substring(0,prev_index);
                var middle="<span style='border:1px solid black;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);
                
                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='border:1px solid black;'>"+body_text+"</span>";
            }
        }
        
        else if(final_text.toLowerCase().indexOf('[red](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[red](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+6;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:red;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:red;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[orange](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[orange](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+9;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:orange;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:orange;'>"+body_text+"</span>";
            }
        }   
        else if(final_text.toLowerCase().indexOf('[yellow](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[yellow](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+9;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:yellow;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:yellow;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[green](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[green](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+8;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:green;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:green;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[blue](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[blue](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+7;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:blue;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:blue;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[purple](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[purple](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+9;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:purple;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:purple;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[pink](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[pink](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+7;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:pink;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:pink;'>"+body_text+"</span>";
            }
        }
        else if(final_text.toLowerCase().indexOf('[brown](')!=-1)
        {
            var prev_index=final_text.toLowerCase().indexOf('[brown](');

            //gets "and this is going to be bold) whatever comes after"
            var start=prev_index+8;
            var temp=final_text.substring(start);


            if(temp.indexOf(')')!=-1)
            {
                //gets ")" of "and this is going to be bold)"
                var end=start+temp.indexOf(')');


                var body_text=final_text.substring(start, end);

                var front=final_text.substring(0,prev_index);
                var middle="<span style='color:brown;'>"+body_text+"</span>";
                var back=final_text.substring(end+1);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+middle+back;
            }
            else
            {
                //gets "and this is going to be bold"
                var front=final_text.substring(0,prev_index);
                var body_text=final_text.substring(start);

                //removes "[b](and this is going to be bold)" and replaces it with <span style='font-weight:bold;'>and this is going to be bold</span>
                final_text=front+"<span style='color:brown;'>"+body_text+"</span>";
            }
        }
        else
            break;
    }
    
    while(final_text.indexOf('3:)')!=-1)
        final_text=final_text.replace("3:)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/7.png)'></span>");
    while(final_text.indexOf('(devil)')!=-1)
        final_text=final_text.replace("(devil)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/7.png)'></span>");
    while(final_text.indexOf(':D')!=-1)
        final_text=final_text.replace(":D", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/1.png)'></span>");
    while(final_text.indexOf(':-D')!=-1)
        final_text=final_text.replace(":-D", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/1.png)'></span>");
    while(final_text.indexOf(':)')!=-1)
        final_text=final_text.replace(":)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/2.png)'></span>");
    while(final_text.indexOf(':-)')!=-1)
        final_text=final_text.replace(":-)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/2.png)'></span>");
    while(final_text.indexOf('(:')!=-1)
        final_text=final_text.replace("(:", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/2.png)'></span>");
    while(final_text.indexOf('(-:')!=-1)
        final_text=final_text.replace("(-:", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/2.png)'></span>");
    while(final_text.indexOf('-_-')!=-1)
        final_text=final_text.replace("-_-", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/3.png)'></span>");
    while(final_text.indexOf('(-_-)')!=-1)
        final_text=final_text.replace("(-_-)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/3.png)'></span>");
    while(final_text.indexOf('*_*')!=-1)
        final_text=final_text.replace("*_*", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('*.*')!=-1)
        final_text=final_text.replace("*.*", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('O_O')!=-1)
        final_text=final_text.replace("O_O", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('O.O')!=-1)
        final_text=final_text.replace("O.O", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('0.0')!=-1)
        final_text=final_text.replace("0.0", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('0_0')!=-1)
        final_text=final_text.replace("0_0", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('(*_*)')!=-1)
        final_text=final_text.replace("(*_*)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('(*.*)')!=-1)
        final_text=final_text.replace("(*.*)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('(O_O)')!=-1)
        final_text=final_text.replace("(O_O)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('(O.O)')!=-1)
        final_text=final_text.replace("(O.O)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('(0.0)')!=-1)
        final_text=final_text.replace("(0.0)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf('(0_0)')!=-1)
        final_text=final_text.replace("(0_0)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/4.png)'></span>");
    while(final_text.indexOf(":'(")!=-1)
        final_text=final_text.replace(":'(", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/5.png)'></span>");
    while(final_text.indexOf(")':")!=-1)
        final_text=final_text.replace(")':", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/5.png)'></span>"); 
    while(final_text.indexOf('(cool)')!=-1)
        final_text=final_text.replace("(cool)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/6.png)'></span>");
    while(final_text.indexOf('XD')!=-1)
        final_text=final_text.replace("XD", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/8.png)'></span>");
    while(final_text.indexOf('&lt;3')!=-1)
        final_text=final_text.replace("&lt;3", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/9.png)'></span>");
    while(final_text.indexOf('<3')!=-1)
        final_text=final_text.replace("<3", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/9.png)'></span>");
    while(final_text.indexOf(":P")!=-1)
        final_text=final_text.replace(":P", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/10.png)'></span>");
    while(final_text.indexOf(':-P')!=-1)
        final_text=final_text.replace(":-P", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/10.png)'></span>");
    while(final_text.indexOf('d:')!=-1)
        final_text=final_text.replace("d:", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/10.png)'></span>");
    while(final_text.indexOf('d-:')!=-1)
        final_text=final_text.replace("d-:", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/10.png)'></span>");
    while(final_text.indexOf('(swear)')!=-1)
        final_text=final_text.replace("(swear)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/11.png)'></span>");
    while(final_text.indexOf('&gt;:(')!=-1)
        final_text=final_text.replace("&gt;:(", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/12.png)'></span>");
    while(final_text.indexOf('&gt;:|')!=-1)
        final_text=final_text.replace("&gt;:|", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/12.png)'></span>");
    while(final_text.indexOf('>:(')!=-1)
        final_text=final_text.replace(">:(", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/12.png)'></span>");
    while(final_text.indexOf('>:|')!=-1)
        final_text=final_text.replace(">:|", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/12.png)'></span>");
    while(final_text.indexOf(';)')!=-1)
        final_text=final_text.replace(";)", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/13.png)'></span>");
    while(final_text.indexOf('(;')!=-1)
        final_text=final_text.replace("(;", "<span class='emoticon' style='background-image: url(http://pics.redlay.com/pictures/emoticons/13.png)'></span>");
    
    
    return final_text;
}
function convert_image(text, image_id, comment_id)
{
    //text EX: http://i.imgur.com/4v8spSg.jpg
    var final_text=text;
    
    //if there's a link
    if(final_text.toLowerCase().indexOf('http://imagepxl.com/')!=-1||final_text.toLowerCase().indexOf('http://i.imagepxl.com/')!=-1)
    {
            //gets index of beginning occurance
            var first=final_text.toLowerCase().indexOf('http://imagepxl.com/');
            var second=final_text.toLowerCase().indexOf('http://i.imagepxl.com/');
            
            if(first!=-1&&second==-1)
                var prev_index=first;
            else if(first==-1&&second!=-1)
                var prev_index=second;
            else
            {
                if(first<second)
                    var prev_index=first;
                else
                    var prev_index=second;
            }

            //gets "http://i.imgur.com/4v8spSg.jpg ..."
            var temp=final_text.substring(prev_index);
            temp=temp.replace("#", '');
            
            //gets the end of the url
            if(temp.indexOf(' ')!=-1||temp.indexOf("<br />")!=-1||temp.indexOf(". ")!=-1)
            {
                //gets " " of "http://i.imgur.com/4v8spSg.jpg ..."
                var space=temp.indexOf(' ');
                var line=temp.indexOf('<br />');
                //gets the second period
                var period=temp.indexOf('. ');
                
                var end=temp.indexOf(' ')+prev_index;
                
                if(space!=-1&&line==-1&&period==-1)
                    var end=space+prev_index;
                else if(space==-1&&line!=-1&&period==-1)
                    var end=line+prev_index;
                else if(space==-1&&line==-1&&period!=-1)
                    var end=period+prev_index;
                else
                {
                    if(space<line&&space<period)
                        var end=space+prev_index;
                    else if(line<space&&line<period)
                        var end=line+prev_index;
                    else
                        var end=period+prev_index;
                }

                //gets the full url: "http://i.imgur.com/4v8spSg.jpg"
                var body_text=final_text.substring(prev_index, end);
                
                //gets the stuff before the image
                var front=final_text.substring(0,prev_index);

                //gets the image
                var middle="<span class='title_color username' style='font-size:12px;' id='convert_image_"+image_id+"_"+comment_id+"' >"+body_text+"</span>";

                //gets the stuff after the image
                var back=final_text.substring(end);

                //removes "http://i.imgur.com/4v8spSg.jpg" and replaces it with "<img src='http://i.imgur.com/4v8spSg.jpg' />"
                final_text=front+back+middle;
                
            }
            else
            {
                //gets the full url: "http://i.imgur.com/4v8spSg.jpg"
                var body_text=final_text.substring(prev_index);
                
                //gets the stuff before the image
                var front=final_text.substring(0,prev_index);

                //gets the image
                var middle="<span class='title_color username' style='font-size:12px;' id='convert_image_"+image_id+"_"+comment_id+"' >"+body_text+"</span>";


                //removes "http://i.imgur.com/4v8spSg.jpg" and replaces it with "<img src='http://i.imgur.com/4v8spSg.jpg' />"
                final_text=front+middle;
            }
    }
    return final_text;
//return text;
}
function display_dim()
{
    $('#dim').css({'opacity': '0'}).show();
    $('#dim').animate({opacity:.3}, 350, function(){});
}
function show_alert_box()
{
    setTimeout(function()
    {
        var Document_width=($(window).width())/2;
        
        $('.alert_box').css({'margin-top': (-1*($('.alert_box').height()/2))});
        $('.alert_box').css('display', 'block').animate({opacity: 1}, 350, function(){}).draggable();
        $('.alert_box').css('left', Document_width-($('.alert_box').width()/2));
    }, 200);
}
function close_alert_box()
{
    $('.alert_box').animate({
        opacity: 0
    }, 500, function()
    {
        $('.alert_box_inside').html('');
        $('.alert_box').hide();
    }).remoteAttr('width', 'height');
    $('#dim').animate({opacity:0}, 350, function(){
        $('#dim').hide();
    });
    
}
function display_comment_image(image_link)
{
    var load="<img class='load_gif' id='comment_image_load_gif' />";
    display_alert(load);
    
    var url="./process_comment_image.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../process_comment_image.php"
            }});
    
    
    $.post(url,
    {
        image_link: image_link
    }, function(output)
    {
        var link=output.image_link;
        var src=output.image_src;
        
        var image="<a class='link' href='"+link+"'><img class='display_comment_image' src='"+src+"' onloadend='show_alert_box();' /></a>";
        display_alert(image)
    }, "json");
}
function display_full_image(image_link)
{
    var load="<img class='load_gif' id='comment_image_load_gif' />";
    display_alert(load);
    var image="<img class='display_full_image' src='"+image_link+"' onClick='close_alert_box();' onloadend='show_alert_box();' />";
    display_alert(image);
    $('.display_full_image').css({'max-height': $(window).height()+"px", 'max-width': $(window).width()+'px'});
    setTimeout(function(){
        show_alert_box();
    }, 1000);
}
function display_alert(body)
{
    display_dim();
    $('.alert_box_inside').html(body);
    show_alert_box();
}
function convert_time(seconds)
{
    if(seconds<2678400)
    {
        if(seconds>=3600)
        {
            if(seconds>604800)
                //old number format
                var new_time=format_number((seconds/86400))+" days ago";
            else if(seconds>=86400&&seconds<604800)
            {
                //old number format
                var num_days=format_number((seconds/86400));
                var num_hours=format_number(((seconds%86400)/3600));

                if(num_days!=1)
                    var days=num_days+" days";
                else
                    var days="1 day";

                if(num_hours!=0)
                {
                    if(num_hours!=1)
                        var hours=num_hours+" hours ago";
                    else
                        var hours="1 hour ago";
                }
                else
                {
                    //old number format
                    var minutes=format_number(((seconds%3600)/60));
                    hours=minutes+" minutes ago";
                }

                var new_time=days+" "+hours;
            }
            else if(seconds>=7200)
            {
                //old number format
                var minutes=format_number((seconds%3600)/60);
                if(minutes!=0)
                    var new_time=format_number((seconds/3600))+" hours "+minutes+" minutes ago";
                else
                    var new_time=format_number((seconds/3600))+" hours ago";
            }
            else if(seconds>=3660&&seconds<7200)
            {
                //old number format
                var minutes=format_number((seconds%3600)/60);
                if(minutes!=0)
                    var new_time="1 hour and "+minutes+" minutes ago";
                else
                    var new_item="1 hour ago";
            }
            else
                var new_time="1 hour ago";
        }
        else
        {
            if(seconds>=120)
            {
                //old number format
                var new_time=format_number((seconds/60))+" minutes ago";
            }
            else if(seconds>=60&&seconds<120)
                var new_time="1 minute ago";
            else if(seconds<60)
                var new_time=seconds+" seconds ago";
        }
    } 
    
    return new_time;
}
function count_time(seconds, id, prev_time)
{
    if($(id).length)
    {
        if(prev_time==undefined)
            prev_time="";
        
        seconds=parseInt(seconds);
        if(seconds<0)
            seconds=0;

        if(seconds<2678400)
        {
            var new_time=convert_time(seconds);

            //displays new time
            if(new_time!=prev_time)
            {
                if(prev_time==''||$(id).html()==prev_time)
                    $(id).html(new_time);
            }

            seconds++;

            //creates recursion
            if($(id).length)
            {
                setTimeout(function(){
                    count_time(seconds, id, new_time);
                }, 1000);
            }
        }
    }
}
function format_number(number)
{
    var temp=number;
    
    while(number>=1000)
        number-=1000;
    
    while(number>=100)
        number-=100;
    
    while(number>=10)
        number-=10;
    
    while(number>=1)
        number-=1;
    
    //gets overflow and subtracts to round
    return temp-number;   
}
function follow(user_id)
{
    //if user is logged in
    if($('#logged_in_icon').length)
    {
        $.post('follow',
        {
            num:1,
            user_id:user_id
        }, function(output)
        {
            if(output!="success")
                display_error(output, 'bad_errors');
            else
                window.location.replace(window.location);
        });
    }
    else
        display_login();
        
}
function unfollow(user_id)
{
    $.post('follow',
    {
        num:2,
        user_id:user_id
    }, function(output)
    {
        if(output!='success')
            display_error(output, 'bad_errors');
        else
            window.location.replace(window.location);
    });
}
function image_over(element)
{
    $(element).css({'position': 'relative', 'top': '0px', 'z-index': '2', 'left': '0px'});
    $(element).stop().animate(
    {
        width:'160px', 
        top: '-5px',
        left:'-5px'
    }, 100, function(){});
}
function image_out(element)
{
    $(element).stop().animate(
    {
        top: '0px',
        width:'150px',
        left:'0px'
    }, 100, function(){
        $(element).css({'position': 'relative', 'top':'', 'z-index': '1','left':''});
    });
}
function display_login()
{
    var login_title="<p class='title_color' >Login</p>";
    var login_username_text="<span class='text_color' >Username: </span>";
    var login_username="<input class='input_box' type='text' maxlength='255' placeholder='Username...' id='alert_login_username' />";
    var login_password_text="<span class='text_color'>Password: </span>";
    var login_password="<input class='input_box' type='password' maxlength='255' placeholder='Password...' id='alert_login_password' />";
    var login_button="<input class='button red_button' type='button' value='Login' onClick='alert_login();' />";
    var login_load_gif="<img class='load_gif' src='http://i.imagepxl.com/site/load.gif' id='login_load' style='display:none;' />";
    var login_table="<table style='padding-right:15px;'><tbody><tr><td colspan='2' style='text-align:center' >"+login_title+"</td></tr><tr><td>"+login_username_text+"</td><td>"+login_username+"</td></tr><tr><td>"+login_password_text+"</td><td>"+login_password+"</td></tr><tr><td style='text-align:center;' colspan='2' >"+login_button+"</td></tr><tr><td colspan='2'>"+login_load_gif+"</td></tr></tbody></table>";
    
    var join_title="<p class='title_color' >Join</p>";
    var join_username_text="<span class='text_color' >Username: </span>";
    var join_username="<input class='input_box' type='text' maxlength='40' placeholder='Username...' id='register_username' />";
    var join_email_text="<span class='text_color' >Email: </span>";
    var join_email="<input class='input_box' type='email' maxlength='255' placeholder='Email...' id='register_email' />";
    var join_password_text="<span class='text_color' >Password: </span>";
    var join_password="<input class='input_box' type='password' maxlength='255' placeholder='Password...' id='register_password' />";
    var join_agreement_text="<p style='font-size:12px;margin-top:10px;' class='text_color'>By joining, you agree to the </p><a class='link title_color' href='http://imagepxl.com/user_agreement' ><p class='username title_color' style='font-size:12px;' >User Agreement</p></a>";
    var join_button="<input class='button red_button' type='button' value='Join' onClick='register();' />";
    var join_load_gif="<img class='load_gif' src='http://i.imagepxl.com/site/load.gif' id='register_load' style='display:none;' />";
    var join_table="<table style='padding-left:15px;'><tbody><tr><td colspan='2' style='text-align:center;' >"+join_title+"</td></tr><tr><td>"+join_username_text+"</td><td>"+join_username+"</td></tr><tr><td>"+join_email_text+"</td><td>"+join_email+"</td></tr><tr><td>"+join_password_text+"</td><td>"+join_password+"</td></tr><tr><td colspan='2' style='text-align:center' >"+join_agreement_text+"</td></tr><tr><td style='text-align:center;' colspan='2'>"+join_button+"</td></tr><tr><td colspan='2'>"+join_load_gif+"</td></tr></tbody></table>";
    
    var body="<table style='padding:15px;' ><tbody><tr><td style='border-right:1px solid gray;vertical-align:top;' >"+login_table+"</td><td>"+join_table+"</td></tr></tbody></table>";
    display_alert(body);
    $('.alert_box_inside').css('background-color', 'white');
}

function set_functions(image_id, num_likes, num_dislikes, has_liked, has_disliked, has_favorited)
{
    if(has_liked)
        $('#like_unit_'+image_id).html("<img class='function_icon up_arrow' src='http://i.imagepxl.com/site/icons/up_arrow.png'/>").attr('onClick', "unlike('"+image_id+"', 'regular');");
    else
        $('#like_unit_'+image_id).html("<img class='function_icon up_arrow' src='http://i.imagepxl.com/site/icons/white_arrow.png'/>").attr('onClick', "like('"+image_id+"', 'regular');");

    if(has_disliked)
        $('#dislike_unit_'+image_id).html("<img class='function_icon down_arrow' src='http://i.imagepxl.com/site/icons/down_arrow.png'/>").attr('onClick', "undislike('"+image_id+"', 'regular');");
    else
        $('#dislike_unit_'+image_id).html("<img class='function_icon down_arrow' src='http://i.imagepxl.com/site/icons/white_arrow.png'/>").attr('onClick', "dislike('"+image_id+"', 'regular');");
    
    if(has_favorited)
        $('#favorite_unit_'+image_id).html("<img class='function_icon' src='http://i.imagepxl.com/site/icons/favorite_icon.png'/>").attr('onClick', "unfavorite('"+image_id+"');");
    else
        $('#favorite_unit_'+image_id).html("<img class='function_icon' src='http://i.imagepxl.com/site/icons/favorite_icon_white.png'/>").attr('onClick', "favorite('"+image_id+"');");
    
    $('#points_unit_'+image_id).html("<span class='function_text ' style='cursor:default;' >"+(num_likes-num_dislikes)+" points</span>");
}
function set_interior_functions(image_id, num_likes, num_dislikes, has_liked, has_disliked)
{
    if(has_liked)
        $('#like_unit_'+image_id).html("<img class='function_icon up_arrow' src='http://i.imagepxl.com/site/icons/up_arrow.png'/>").attr('onClick', "unlike('"+image_id+"', 'profile');");
    else
        $('#like_unit_'+image_id).html("<img class='function_icon up_arrow' src='http://i.imagepxl.com/site/icons/white_arrow.png'/>").attr('onClick', "like('"+image_id+"', 'profile');");

    if(has_disliked)
        $('#dislike_unit_'+image_id).html("<img class='function_icon down_arrow' src='http://i.imagepxl.com/site/icons/down_arrow.png'/>").attr('onClick', "undislike('"+image_id+"', 'profile');");
    else
        $('#dislike_unit_'+image_id).html("<img class='function_icon down_arrow' src='http://i.imagepxl.com/site/icons/white_arrow.png'/>").attr('onClick', "dislike('"+image_id+"', 'profile');");

        $('#points_unit_'+image_id).html("<span class='function_text inside_function_text ' style='cursor:default;text-align:center;' >"+(num_likes-num_dislikes)+" points</span>");
}
function set_comment_functions(image_id, comment_id, num_likes, num_dislikes, has_liked, has_disliked)
{
    if(has_liked)
        $('#comment_like_unit_'+image_id+'_'+comment_id).html("<img class='function_icon up_arrow' src='http://i.imagepxl.com/site/icons/up_arrow.png'/>").attr('onClick', "unlike_comment('"+image_id+"', "+comment_id+");");
    else
        $('#comment_like_unit_'+image_id+'_'+comment_id).html("<img class='function_icon up_arrow' src='http://i.imagepxl.com/site/icons/white_arrow.png'/>").attr('onClick', "like_comment('"+image_id+"', "+comment_id+");");

    if(has_disliked)
        $('#comment_dislike_unit_'+image_id+'_'+comment_id).html("<img class='function_icon down_arrow' src='http://i.imagepxl.com/site/icons/down_arrow.png'/>").attr('onClick', "undislike_comment('"+image_id+"', "+comment_id+");");
    else
        $('#comment_dislike_unit_'+image_id+'_'+comment_id).html("<img class='function_icon down_arrow' src='http://i.imagepxl.com/site/icons/white_arrow.png'/>").attr('onClick', "dislike_comment('"+image_id+"', "+comment_id+");");
    
    $('#comment_points_unit_'+image_id+'_'+comment_id).html("<span class='function_text text_color' style='cursor:default;' >"+(num_likes-num_dislikes)+" points</span>");
}
function like(image_id, link_type)
{
    //if user is logged in
    if($('#logged_in_icon').length)
    {
        var url="./like_image.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../like_image.php"
                }});

        $.post(url,
        {
            image_id: image_id
        }, function(output)
        {
            var errors=output.errors;
            if(errors!='')
                display_error(errors, 'bad_errors');
            else
            {
                var num_likes=output.num_likes;
                var num_dislikes=output.num_dislikes;

                if(link_type=='profile')
                    set_interior_functions(image_id, num_likes, num_dislikes, true, false);
                else
                    set_functions(image_id, num_likes, num_dislikes, true, false);
            }
        }, "json");
    }
    else
        display_login();
}
function dislike(image_id, link_type)
{
    if($('#logged_in_icon').length)
    {
        var url="./dislike_image.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../dislike_image.php"
                }});

        $.post(url,
        {
            image_id: image_id
        }, function(output)
        {
            var errors=output.errors;
            if(errors!='')
                display_error(errors, 'bad_errors');
            else
            {
                var num_dislikes=output.num_dislikes;
                var num_likes=output.num_likes;

                if(link_type=='profile')
                    set_interior_functions(image_id, num_likes, num_dislikes, false, true);
                else
                    set_functions(image_id, num_likes, num_dislikes, false, true);

            }
        }, "json");
    }
    else
        display_login();
}

function unlike(image_id, link_type, text_type)
{
    var url="./unlike_image.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../unlike_image.php"
            }});
    
    $.post(url,
    {
        image_id: image_id
    }, function(output)
    {
        var errors=output.errors;
        if(errors!='')
            display_error(errors, 'bad_errors');
        else
        {
            var num_likes=output.num_likes;
            var num_dislikes=output.num_dislikes;
            
            if(link_type=='profile')
                set_interior_functions(image_id, num_likes, num_dislikes, false, false);
            else
                set_functions(image_id, num_likes, num_dislikes, false, false);
        }
    }, "json");
}
function undislike(image_id, link_type, text_type)
{
    var url="./undislike_image.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../undislike_image.php"
            }});
    
    $.post(url,
    {
        image_id: image_id
    }, function(output)
    {
        var errors=output.errors;
        if(errors!='')
            display_error(errors, 'bad_errors');
        else
        {
            var num_dislikes=output.num_dislikes;
            var num_likes=output.num_likes;
            
            
            if(link_type=='profile')
                set_interior_functions(image_id, num_likes, num_dislikes, false, false);
            else
                set_functions(image_id, num_likes, num_dislikes, false, false);
        }
    }, "json");
}

function like_comment(image_id, comment_id)
{
    if($('#logged_in_icon').length)
    {
        var url="./like_comment.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../like_comment.php"
                }});

        $.post(url,
        {
            image_id: image_id,
            comment_id: comment_id
        }, function(output)
        {
            var errors=output.errors;

            if(errors!='')
                display_error(errors, 'bad_errors');
            else
            {
                var num_likes=output.num_likes;
                var num_dislikes=output.num_dislikes;

                set_comment_functions(image_id, comment_id, num_likes, num_dislikes, true, false);
            }    
        }, "json");
    }
    else
        display_login();
}
function dislike_comment(image_id, comment_id)
{
    if($('#logged_in_icon').length)
    {
        var url="./dislike_comment.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../dislike_comment.php"
                }});

        $.post(url,
        {
            image_id: image_id,
            comment_id: comment_id
        }, function(output)
        {
            var errors=output.errors;

            if(errors!='')
                display_error(errors, 'bad_errors');
            else
            {
                var num_likes=output.num_likes;
                var num_dislikes=output.num_dislikes;

                set_comment_functions(image_id, comment_id, num_likes, num_dislikes, false, true);
            }    
        }, "json");
    }
    else
        display_login();
}
function unlike_comment(image_id, comment_id)
{
    var url="./unlike_comment.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../unlike_comment.php"
            }});
    
    $.post(url,
    {
        image_id: image_id,
        comment_id: comment_id
    }, function(output)
    {
        var errors=output.errors;
        
        if(errors!='')
            display_error(errors, 'bad_errors');
        else
        {
            var num_likes=output.num_likes;
            var num_dislikes=output.num_dislikes;
            
            set_comment_functions(image_id, comment_id, num_likes, num_dislikes, false, false);
        }    
    }, "json");
}
function undislike_comment(image_id, comment_id)
{
    var url="./undislike_comment.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../undislike_comment.php"
            }});
    
    $.post(url,
    {
        image_id: image_id,
        comment_id: comment_id
    }, function(output)
    {
        var errors=output.errors;
        
        if(errors!='')
            display_error(errors, 'bad_errors');
        else
        {
            var num_likes=output.num_likes;
            var num_dislikes=output.num_dislikes;
            
            set_comment_functions(image_id, comment_id, num_likes, num_dislikes, false, false);
        }    
    }, "json");
}
function delete_comment(image_id, comment_id)
{
    var url="./delete_comment.php";
    $.ajax({url: url, async: false, error: function() {
                  url="../delete_comment.php"
            }});
        
    $.post(url, 
    {
        image_id: image_id,
        comment_id: comment_id
    }, function(output)
    {
        if(output=='success')
            window.location.replace(window.location);
        else
            display_error(output, 'bad_errors');
    });
}
function comment(image_id)
{
    if($('#logged_in_icon').length)
    {
        var comment=$('#comment_input_'+image_id).val();
        $('#comment_input_'+image_id).val('');

    //    comment = comment.replace(/(\n)/gm, "");
        if(comment!='')
        {
            $('#comment_load_gif').show();

            var url="./comment.php";
            //checks if viewing from album
            $.ajax({url: url, async: false, error: function() {
                          url="../comment.php"
                    }});

            $.post(url,
            {
                image_id: image_id,
                comment: comment
            }, function(output)
            {
                $('#comment_load_gif').hide();
                if(output=="")
                    window.location.replace(window.location);
                else
                    display_error(output, 'bad_errors');
            });
        }
        else
            display_error("Comment seems to be empty", 'bad_errors');
    }
    else
        display_login();
}

function initialize_thumbnail_selection(image_id, width, height)
{
    //if image's width is bigger than its height
    if(width>=height)
    {
        if(width/height>=1.5)
        {
            var ratio=width/450;
            var new_width=format_number(width/ratio);
            var new_height=format_number(height/ratio);
            
            $('.draggable_thumbnail_selector').height(new_height);
            $('.draggable_thumbnail_selector').width(new_height);
        }
        else
        {
           var ratio=height/300;
            var new_width=format_number(width/ratio);;
            var new_height=format_number(height/ratio);
            
            $('.draggable_thumbnail_selector').height(300);
            $('.draggable_thumbnail_selector').width(300);
        }


        $('#thumbnail_image_preview').css('max-height', '150px');
    }

    //if image's height is bigger than its width
    else 
    {
           var ratio=height/300;
            var new_width=format_number(width/ratio);;
            var new_height=format_number(height/ratio);
            
            $('.draggable_thumbnail_selector').height(300);
            $('.draggable_thumbnail_selector').width(300);
            

        $('#thumbnail_image_preview').css('max-width', '150px');
    }


    $('.draggable_thumbnail_selector').draggable(
    {
        containment: '#picture_preview_body',
        drag: function()
        {
            if($('#upload_photo_preview_image').width()>$('#upload_photo_preview_image').height())
            {
                var width=$('#upload_photo_preview_image').width();
                var small_width=$('#thumbnail_image_preview').width();


                var position=$('.draggable_thumbnail_selector').position();
                var ratio=width/small_width;
                $('#thumbnail_image_preview').css('left', Math.round(position.left/(ratio))*-1);
            }
            else
            {
                var height=$('#upload_photo_preview_image').height();
                var small_height=$('#thumbnail_image_preview').height();


                var position=$('.draggable_thumbnail_selector').position();
                var ratio=height/small_height;
                $('#thumbnail_image_preview').css('top', Math.round(position.top/(ratio))*-1);
            }    

        }
    });

    $('#thumbnail_info').html("<p >Set the thumbnail for this picture</p><table><tbody><tr><td><input class='button red_button' type='button' onClick=set_picture_thumbnail('"+image_id+"'); value='Set'/></td><td><input class='button gray_button' type='button' value='Cancel' onClick='close_thumbnail_selection();'/></td></tr></tbody></table>");
}
function set_picture_thumbnail(image_id)
{
    $('#upload_image_gif').show();
    var left=parseFloat($('.draggable_thumbnail_selector').css('left'));
    var top=parseFloat($('.draggable_thumbnail_selector').css('top'));

    var width=parseFloat($('#upload_photo_preview_image').width());
    var height=parseFloat($('#upload_photo_preview_image').height());
    
    $.post('set_thumbnail.php',
    {
        image_id: image_id,
        top:top,
        left:left,
        width:width,
        height:height
    }, function(output)
    {
        $('#upload_image_gif').hide();
        if(output=='Thumbnail set')
            display_error(output, 'good_errors');
        else
            display_error(output, 'bad_errors');
        close_thumbnail_selection();
    });
}
function close_thumbnail_selection()
{
    $('#upload_photo_preview').animate({
        height:0
    }, 1000,function()
    {
        $('#upload_photo_preview').html('').css('height', '').hide();
    });
    
    $('#image_description_upload').show();
}
//id is id of element and string is message
function display_title(id, string)
{
    $(id).mousemove(function(e)
    {
        if($('.tool_tip').css('opacity')<'0.8')
        {
            $('.tool_tip').html("<p class='tool_tip_text'>"+string+"</p>").css({'left': (e.pageX-50), 'top': (e.pageY+20)});
            $('.tool_tip').stop().animate({
                opacity: 1
            }, 100, function()
            {});
        }
        else
            $('.tool_tip').css({'left': (e.pageX-50), 'top': (e.pageY+20)});
    });
}
function hide_title(id)
{
    $('.tool_tip').stop().animate({
        opacity: '0'
    }, 100, function()
    {
        $('.tool_tip').html('').css({'left': '0px', 'top': '0px', 'opacity': '0'});
    });
}
function search()
{
    var search="http://imagepxl.com/search.php?search=" + encodeURIComponent($('#search_input_top').val());
   window.location.replace(search);
}
function favorite(image_id)
{
    if($('#logged_in_icon').length)
    {
        var url="./favorite.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../favorite.php"
                }});

        $.post(url,
        {
            image_id: image_id
        }, function(output)
        {
            if(output=='')
                $('#favorite_unit_'+image_id).html("<img class='function_icon' src='http://i.imagepxl.com/site/icons/favorite_icon.png'/>").attr('onClick', "unfavorite('"+image_id+"');");
            else
                display_error(output, 'bad_errors');

        });
    }
    else
        display_login();
}
function unfavorite(image_id)
{
    var url="./unfavorite.php";
    //checks if viewing from album
    $.ajax({url: url, async: false, error: function() {
                  url="../unfavorite.php"
            }});
    
    $.post(url,
    {
        image_id: image_id
    }, function(output)
    {
        if(output=='')
            $('#favorite_unit_'+image_id).html("<img class='function_icon' src='http://i.imagepxl.com/site/icons/favorite_icon_white.png'/>").attr('onClick', "favorite('"+image_id+"');");
        else
            display_error(output, 'bad_errors');
        
    });
}
function display_copy_image(image_id)
{
    if($('#logged_in_icon').length)
    {
        var url="./view_image_query.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../view_image_query.php"
                }});

        //gets album html
        $.post(url,
        {
            num:4,
            image_id: image_id
        }, function(output)
        {
            var album_ids=output.album_ids;
            var album_names=output.album_names;
            var current_album_id=output.current_album_id;

            var html="<option value=''>--None--</option>";
            for(var x = 0; x < album_ids.length; x++)
            {
                if(album_ids[x]==current_album_id)
                    var selected="selected='selected'";
                else
                    var selected="";

                html+="<option value='"+album_ids[x]+"' "+selected+">"+album_names[x]+"</option>";
            }

            var select_box="<select id='copy_image_album_select' >"+html+"</select>";

            var button="<input class='button red_button' type='button' value='Copy' id='copy_image_button' style='float:right;'/>";
            var description="<textarea class='input_box view_image_textarea' id='copy_image_description' placeholder='Description...' maxlength='500'></textarea>";
            var table="<table style='padding:20px;background-color:rgba(255,255,255,.8);'><tbody><tr><td colspan='2' >"+description+"</td></tr><tr><td>"+select_box+"</td><td style='text-align:right;'>"+button+"</td></tr></tbody></table>";

            display_alert(table);
            $('#copy_image_button').attr('onClick', "copy_image('"+image_id+"');");
        }, "json");
    }
    else
        display_login();
}
function copy_image(image_id)
{
    if($('#logged_in_icon').length)
    {
        var url="./copy_image.php";
        //checks if viewing from album
        $.ajax({url: url, async: false, error: function() {
                      url="../copy_image.php"
                }});

        $.post(url,
        {
            image_id: image_id,
            description: $('#copy_image_description').val(),
            album: $('#copy_image_album_select').val()
        }, function(output)
        {
            if(output=='')
                display_error("Image copied!", 'good_errors');
            else
                display_error(output, 'bad_errors');
        });
    }
    else
        display_login();
}
function remove_banner()
{
    
}
function theme_over(element)
{
    $(element).stop().animate({
        marginTop:'-10px'
    }, 150, function(){
        
    });
}
function theme_out(element)
{
    $(element).stop().animate({
        marginTop:'0px'
    }, 150, function(){
        
    });
}
function upload_url()
{
    $('#upload_image_url_gif').show();

    var description=$('#image_description_url').val();
    var url=$('#image_input').val();
    var album=$('#album_select_url').val();

    $('#image_description_url').val('');
    $('#image_input').val('');
    $('#album_select_url').val('');

    $.post('upload_image_url.php',
    {
        url:url,
        description:description,
        album: album
    }, function(output)
    {
        var errors=output.errors;
        var image_id=output.image_id;

        if($('#logged_in_icon').length==0)
            window.location.replace('http://imagepxl.com/'+image_id);

        $('#upload_image_url_gif').hide();
        load_recent_pictures();
        load_albums_list();

        if(errors=="")
            display_error("Image uploaded successfully", 'good_errors');
        else
            display_error(errors, "bad_errors");


    }, "json");
}
function display_progress_bar()
{
    $('#percent_loaded').show();
    $('#progress_bar').show();
    $( "#progress_bar" ).progressbar({
        value: 0
    });
    $('#percent_loaded').html("0%");
    $('.ui-progressbar-value').css({'background': 'rgb(220,20,0)', 'border-radius': '0px' });
}
function display_computer_upload()
{
    
    var file_input="<input type='file' id='image' name='image[]' multiple='multiple' />";
    var description="<textarea id='image_description_upload' name='description' class='input_box textarea' placeholder='Describe the image...' maxlength='500' style='width:400px;' ></textarea>";
    var nsfw="<span class='text_color' style='font-size:12px;'>NSFW?</span><input type='checkbox' id='nsfw' />";
    var submit_button="<input type='submit' class='button red_button' value='Upload' id='submit' style='float:right;' /><img class='load_gif' id='upload_image_gif' src='http://i.imagepxl.com/site/load.gif' style='display: none;' />";
                              
    var table="<table style='width:100%;' ><tbody><tr><td id='file_input_row' colspan='3' >"+file_input+"</td></tr><tr><td colspan='3' >"+description+"</td></tr><tr><td></td><td>"+nsfw+"</td><td>"+submit_button+"</td></tr></tbody></table>";
    var progress_bar="<div><div id='progress_bar' style='display:none' ></div><p class='text_color' id='percent_loaded'></p></div>";
    var body="<div id='computer_upload' ><form method='post' action='upload_image.php' enctype='multipart/form-data' >"+table+"</form></div>"+progress_bar;
    display_alert(body);
    $('.alert_box_inside').css({'background-color': 'white', 'padding': '15px'});
    
    document.getElementById('submit').addEventListener('click', handleUpload);
}
function display_url_upload()
{
    var file_input="<input type='text' class='input_box' placeholder='http://example.com/image.jpg' id='image_input' style='width:300px;' />";
    var description="<textarea id='image_description_url' class='input_box textarea' placeholder='Describe the image...' maxlength='500' style='width:400px;' ></textarea>";
    var nsfw="<span class='text_color' style='font-size:12px;' >NSFW?</span><input type='checkbox' id='nsfw' />";
    var submit_button="<input type='button' class='button red_button' value='Upload' style='float:right;' onClick='upload_url();' /><img class='load_gif' id='upload_image_url_gif' src='http://i.imagepxl.com/site/load.gif' style='display: none;' />";
    
    
    var table="<table><tbody><tr><td id='file_input_row' colspan='3' >"+file_input+"</td></tr><tr><td colspan='3' >"+description+"</td></tr><tr><td></td><td>"+nsfw+"</td><td>"+submit_button+"</td></tr></tbody></table>";
    var body="<div id='url_upload' >"+table+"</div>";
    display_alert(body);
    $('.alert_box_inside').css('background-color', 'white');
}